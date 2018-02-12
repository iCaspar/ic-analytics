<?php
/**
 * IcAnalyticsTest.php
 */

namespace ICaspar\Analytics\Tests\PhpUnit\Integration;

use ICaspar\Analytics\IcAnalytics;
use WP_UnitTestCase;

define( 'ICASPAR_ANALYTICS_PLUGIN_DIR', IC_ANALYTICS_DIR );

class IcAnalyticsTest extends WP_UnitTestCase {

	public function testSetPluginHooks(): void {
		$analytics = new IcAnalytics();

		$hooksAddTrackingCodeOptionToInit = has_action( 'admin_init', [ $analytics, 'addTrackingCodeOption' ] );
		$this->assertEquals( 10, $hooksAddTrackingCodeOptionToInit );

		$hooksDoCustomizeModsToCustomizeRegister = has_action( 'customize_register', [
			$analytics,
			'doCustomizerMods'
		] );
		$this->assertEquals( 10, $hooksDoCustomizeModsToCustomizeRegister );

		$hooksMaybeRenderGaScriptToWPHead = has_action( 'wp_head', [ $analytics, 'maybeRenderGaScript' ] );
		$this->assertFalse( $hooksMaybeRenderGaScriptToWPHead );

		update_option( 'ic-ga-tracking-id', 'UA-1234567-12' );

		$analytics2 = new IcAnalytics();

		$hooksMaybeRenderGaScriptToWPHead = has_action( 'wp_head', [ $analytics2, 'maybeRenderGaScript' ] );
		$this->assertEquals( 10, $hooksMaybeRenderGaScriptToWPHead );
	}

	public function testAddTrackingCodeOption(): void {
		global $wp_settings_fields;
		$analytics = new IcAnalytics();

		$analytics->addTrackingCodeOption();

		$trackingCodeOptionAdded = $wp_settings_fields['general']['default']['ic-ga-tracking-id'] == [
				'id'       => 'ic-ga-tracking-id',
				'title'    => 'Google Analytics Tracking ID',
				'callback' => [ $analytics, 'renderGaTrackingIdField' ],
				'args'     => [ 'label_for' => 'ic-ga-tracking-id' ],
			];

		$this->assertTrue( $trackingCodeOptionAdded );
	}

	public function testGaScriptOutputForSiteVisitor(): void {
		$analytics = new IcAnalytics();

		wp_set_current_user( 0 );

		ob_start();
		$analytics->maybeRenderGaScript();
		$output = ob_get_clean();

		$this->assertTrue( true == $output );
	}

	public function testGaScriptOutputForLoggedInNonAdmin(): void {
		$analytics = new IcAnalytics();

		$editorUserID = $this->factory->user->create( [
			'role' => 'editor',
		] );

		wp_set_current_user( $editorUserID );

		ob_start();
		$analytics->maybeRenderGaScript();
		$output = ob_get_clean();
		$this->assertTrue( true == $output );
	}

	public function testGaScriptNotOutputForAdmin(): void {
		$analytics = new IcAnalytics();

		$adminUserID = $this->factory->user->create( [
			'role' => 'administrator',
		] );

		wp_set_current_user( $adminUserID );

		ob_start();
		$analytics->maybeRenderGaScript();
		$output = ob_get_clean();
		$this->assertEquals( '', $output );
	}

	public function testGaScriptNotRenderedOnAdminPage(): void {
		$analytics = new IcAnalytics();

		$editorUserID = $this->factory->user->create( [
			'role' => 'editor',
		] );

		wp_set_current_user( $editorUserID );
		set_current_screen( 'edit-page' );

		ob_start();
		$analytics->maybeRenderGaScript();
		$output = ob_get_clean();
		$this->assertEquals( '', $output );
	}
}

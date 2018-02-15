<?php
/**
 * IcAnalyticsTest.php
 */

namespace ICaspar\Analytics\Tests\PhpUnit\Integration;

use ICaspar\Analytics\IcAnalytics;
use WP_UnitTestCase;

define( 'ICASPAR_ANALYTICS_PLUGIN_DIR', IC_ANALYTICS_DIR );

class IcAnalyticsTest extends WP_UnitTestCase {

	public function testAddTrackingCodeShouldBeHookedToAdminInit(): void {
		$analytics = new IcAnalytics();

		$addTrackingCodeOptionToInit = has_action( 'admin_init', [ $analytics, 'addTrackingCodeOption' ] );
		$this->assertEquals( 10, $addTrackingCodeOptionToInit );
	}

	public function testRenderGaScriptShouldNotBeHookedWhenTrackingIdNotDefined(): void {
		$analytics = new IcAnalytics();

		$renderGaScriptToWPHead = has_action( 'wp_head', [ $analytics, 'renderGaScript' ] );
		$this->assertFalse( $renderGaScriptToWPHead );
	}

	public function testRenderGaScriptShouldNotBeHookedWhenUserIsAdministrator(): void {
		update_option( 'ic-ga-tracking-id', 'UA-1234567-12' );
		wp_set_current_user( $this->factory->user->create( [ 'role' => 'administrator' ] ) );
		$analytics = new IcAnalytics();

		$renderGaScriptToWPHead = has_action( 'wp_head', [ $analytics, 'renderGaScript' ] );
		$this->assertFalse( $renderGaScriptToWPHead );
	}

	public function testRenderGaScriptShouldBeHookedWhenNonAdminUserAndTrackingID(): void {
		update_option( 'ic-ga-tracking-id', 'UA-1234567-12' );
		$analytics = new IcAnalytics();

		$renderGaScriptToWPHead = has_action( 'wp_head', [ $analytics, 'renderGaScript' ] );
		$this->assertEquals( 10, $renderGaScriptToWPHead );
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
		$analytics->renderGaScript();
		$output = ob_get_clean();

		$this->assertTrue( true == $output );
	}
}

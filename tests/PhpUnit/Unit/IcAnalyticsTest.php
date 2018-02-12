<?php
/**
 * IcAnalyticsTest.php
 */

namespace ICaspar\Analytics\Tests\PhpUnit\Unit;

use ICaspar\Analytics\IcAnalytics;
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;

class IcAnalyticsTest extends TestCase {
	use MockeryPHPUnitIntegration;

	public function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * @throws \PHPUnit\Framework\Exception
	 */
	public function test__construct(): void {
		Monkey\Functions\when( 'get_option' )->justReturn( 'UA-1234567-1' );
		$this->assertInstanceOf( 'ICaspar\Analytics\IcAnalytics', new IcAnalytics() );
	}

	public function testAddTrackingCodeOption(): void {
		Monkey\Functions\when( 'get_option' )->justReturn( 'UA-1234567-1' );
		Monkey\Functions\when( '__' )->justReturn( 'Google Analytics Tracking ID' );

		$analytics = new IcAnalytics();

		Monkey\Functions\expect( 'register_setting' )
			->once()
			->with( 'general', 'ic-ga-tracking-id', [ $analytics, 'validateTrackingCode' ] );

		Monkey\Functions\expect( 'add_settings_field' )
			->once()
			->with(
				'ic-ga-tracking-id',
				'Google Analytics Tracking ID',
				[ $analytics, 'renderGaTrackingIdField' ],
				'general',
				'default',
				[ 'label_for' => 'ic-ga-tracking-id' ] );

		$analytics->addTrackingCodeOption();
	}

	/**
	 * @throws \PHPUnit\Framework\Exception
	 */
	public function testRenderGaTrackingIdField(): void {
		Monkey\Functions\when( 'get_option' )->justReturn( 'UA-1234567-1' );
		Monkey\Functions\when( 'esc_attr' )->justReturn( 'UA-1234567-1' );

		$args           = [
			'label_for' => 'ic-ga-tracking-id',
		];
		$expectedOutput = '<input type="text" id="ic-ga-tracking-id" name="ic-ga-tracking-id" value="UA-1234567-1" placeholder="UA-XXXXXXX-ZZ" />';

		$this->expectOutputString( $expectedOutput );

		$analytics = new IcAnalytics();
		$analytics->renderGaTrackingIdField( $args );
	}

	public function testDoCustomizerMods(): void {
		Monkey\Functions\when( 'get_option' )->justReturn( 'UA-1234567-1' );
		Monkey\Functions\when( '__' )->justReturn();

		$analytics = new IcAnalytics();
		$customizer = \Mockery::mock('WP_Customize_Manager');

		$customizer->shouldReceive('add_setting')->once();
		$customizer->shouldReceive('add_control')->once();

		$analytics->doCustomizerMods( $customizer );
	}

	public function testValidateTrackingCode(): void {
		Monkey\Functions\when( 'get_option' )->justReturn( 'UA-1234567-1' );

		$validTrackingCode   = 'UA-1234567-12';
		$invalidTrackingCode = 'Sinister plot to destroy the world';
		$analytics           = new IcAnalytics();

		$good = $analytics->validateTrackingCode( $validTrackingCode );
		$this->assertEquals( 'UA-1234567-12', $good );

		$bad = $analytics->validateTrackingCode( $invalidTrackingCode );
		$this->assertEquals( '', $bad );
	}

	/**
	 * @throws \PHPUnit\Framework\Exception
	 */
	public function testMaybeRenderGaScript(): void {
		Monkey\Functions\when( 'get_option' )->justReturn( 'UA-1234567-1' );
		Monkey\Functions\when( 'current_user_can' )->justReturn( false );
		Monkey\Functions\when( 'is_admin' )->justReturn( false );
		Monkey\Functions\when( 'esc_attr' )->justReturn( 'UA-1234567-1' );
		define( 'ICASPAR_ANALYTICS_PLUGIN_DIR', IC_ANALYTICS_DIR );

		$analytics      = new IcAnalytics();
		$expectedOutput = "
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-1234567-1', 'auto');
    ga('send', 'pageview');

</script>";

		$this->expectOutputString( $expectedOutput );

		$analytics->maybeRenderGaScript();
	}
}

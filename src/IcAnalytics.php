<?php

namespace ICaspar\Analytics;

/**
 * iCaspar Analytics Class.
 *
 * @since 1.0.0
 *
 * @package ICaspar\Analytics
 */
class IcAnalytics {

	/**
	 * Holds the GA Tracking ID.
	 *
	 * @var string
	 */
	private $trackingId;

	/**
	 * IcAnalytics constructor.
	 */
	public function __construct() {
		$this->getGaTrackingId();
		$this->setPluginHooks();
	}

	/**
	 * Set hooks to tie plugin functions into WordPress.
	 * @return void
	 */
	private function setPluginHooks() {
		add_action( 'customize_register', [ $this, 'doCustomizerMods' ] );

		if ( $this->trackingId ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueueGaScript' ] );
		}

	}

	/**
	 * Get the GA Tracking ID from the DB Options table.
	 * @return void
	 */
	private function getGaTrackingId() {
		$this->trackingId = get_option( 'ic-ga-tracking-id' );
	}

	/**
	 * Callback to modify the WP Customizer.
	 *
	 * @param \WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function doCustomizerMods( \WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_setting( 'ic-ga-tracking-id', [
			'type'              => 'option',
			'capability'        => 'administrator',
			'sanitize_callback' => function ( $input ) {
				return preg_match( '\bUA-\d{6,10}-\d{1,4}\b', $input ) ? $input : '';
			},
			'default'           => '',
			'transport'         => 'postMessage'
		] );

		$wp_customize->add_control( 'ic-ga-tracking-id', [
			'type'        => 'text',
			'priority'    => 10,
			'section'     => 'title_tagline',
			'label'       => __( 'Google Analytics Tracking ID', 'icaspar' ),
			'input_attrs' => [
				'placeholder' => 'UA-XXXXXXX-ZZ'
			],
		] );
	}

	/**
	 * Add the Google Analytics script to the WordPress script queue.
	 * @return void
	 */
	public function enqueueGaScript() {
		if ( ! current_user_can( 'administrator' ) || is_admin() ) {
			wp_enqueue_script( 'ic-ga', ICASPAR_ANALYTICS_ASSETS_URL . 'min/analytics-min.js', [], ICASPAR_ANALYTICS_VERSION );
			wp_localize_script( 'ic-ga', 'icGaTrackId', [ 'id' => $this->trackingId ] );
		}
	}
}
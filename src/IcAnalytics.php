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
		add_action( 'admin_init', [ $this, 'addTrackingCodeOption' ] );
		add_action( 'customize_register', [ $this, 'doCustomizerMods' ] );

		if ( $this->trackingId ) {
			add_action( 'wp_head', [ $this, 'maybeRenderGaScript' ] );
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
	 * Callback to add the GA Tracking Code Option on the WP Dashboard's General Settings page.
	 * @return void
	 */
	public function addTrackingCodeOption() {
		register_setting( 'general', 'ic-ga-tracking-id', [ $this, 'validateTrackingCode' ] );
		add_settings_field(
			'ic-ga-tracking-id',
			__( 'Google Analytics Tracking ID', 'icaspar' ),
			[ $this, 'renderGaTrackingIdField' ],
			'general',
			'default',
			[ 'label_for' => 'ic-ga-tracking-id' ]
		);
	}

	/**
	 * Callback to display the GA Tracking ID field on the WP Dashboard's General Settings page.
	 *
	 * @param array $args Arguments passed from add_settings_field.
	 *
	 * @return void
	 */
	public function renderGaTrackingIdField( $args ) {
		$value = $this->trackingId ?: '';
		echo '<input type="text" id="' . $args['label_for'] . '" name="' . $args['label_for'] . '" value="' . esc_attr( $value ) . '" placeholder="UA-XXXXXXX-ZZ" />';
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
			'sanitize_callback' => [ $this, 'validateTrackingCode' ],
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
	 * Validate a GA Tracking Code input.
	 *
	 * @param string $input User-entered GA Tracking Code
	 *
	 * @return string Valid tracking code or empty string.
	 */
	public function validateTrackingCode( $input ) {
		return preg_match( '/\bUA-\d{6,10}-\d{1,4}\b/', $input ) ? $input : '';
	}

	/**
	 * Callback to add the Google Analytics script to the WordPress script queue.
	 *
	 * @deprecated 1.1.0 Use maybeRenderGaScript()
	 *
	 * @return void
	 */
	public function enqueueGaScript() {
		if ( ! current_user_can( 'administrator' ) || is_admin() ) {
			wp_enqueue_script( 'ic-ga', ICASPAR_ANALYTICS_ASSETS_URL . 'min/analytics-min.js', [], ICASPAR_ANALYTICS_VERSION );
			wp_localize_script( 'ic-ga', 'icGaTrackId', [ 'id' => $this->trackingId ] );
		}
	}

	/**
	 * Echo out the GA Script code, if the user isn't an admin.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function maybeRenderGaScript() {
		if ( ! current_user_can( 'administrator' ) || is_admin() ) {
			include ICASPAR_ANALYTICS_PLUGIN_DIR . 'views/ga-script.php';
		}
	}
}
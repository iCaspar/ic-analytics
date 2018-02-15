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
	 * Get the GA Tracking ID from the DB Options table.
	 * @return void
	 */
	private function getGaTrackingId() {
		$this->trackingId = get_option( 'ic-ga-tracking-id' );
	}

	/**
	 * Set hooks to tie plugin functions into WordPress.
	 * @return void
	 */
	private function setPluginHooks() {
		add_action( 'admin_init', [ $this, 'addTrackingCodeOption' ] );

		if ( $this->trackingId && ! current_user_can( 'administrator' ) ) {
			add_action( 'wp_head', [ $this, 'renderGaScript' ] );
		}
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
	 * Echo out the GA Script code, if the user isn't an admin.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function renderGaScript() {
		include ICASPAR_ANALYTICS_PLUGIN_DIR . 'views/ga-script.php';
	}
}
<?php
/**
 * Gestion des actions des commandes.
 *
 * Ajoutes une page "Orders" dans le menu de WordPress.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Classes
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

/**
 * Action of Order module.
 */
class My_Account_Action {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( My_Account_Shortcode::g(), 'init_shortcode' ) );
		add_action( 'init', array( My_Account_Class::g(), 'init_endpoint' ) );

		add_action( 'wps_before_customer_login_form', array( My_Account_Class::g(), 'before_login_form' ) );

		add_action( 'wps_before_checkout_form', array( My_Account_Class::g(), 'checkout_form_login' ) );

		add_action( 'admin_post_wps_login', array( $this, 'handle_login' ) );
		add_action( 'admin_post_nopriv_wps_login', array( $this, 'handle_login' ) );

		add_action( 'wps_account_navigation', array( My_Account_Class::g(), 'display_navigation' ) );
		add_action( 'wps_account_orders', array( My_Account_Class::g(), 'display_orders' ) );
		add_action( 'wps_account_proposals', array( My_Account_Class::g(), 'display_proposals' ) );

		add_action( 'wp_ajax_load_modal_resume_order', array( My_Account_Class::g(), 'load_modal_resume_order' ) );
	}

	public function handle_login() {
		$page = ! empty( $_POST['page'] ) ? sanitize_text_field( $_POST['page'] ) : 'my-account';

		if ( empty( $_POST['username'] ) || empty( $_POST['password'] ) ) {
			wp_redirect( site_url( $page ) );
			exit;
		}

		$user = wp_signon( array(
			'user_login'    => $_POST['username'],
			'user_password' => $_POST['password'],
		), is_ssl() );

		if ( is_wp_error( $user ) ) {
			set_transient( 'login_error_' . $_COOKIE['PHPSESSID'], __( 'Votre identifiant ou votre mot de passe est incorrect.', 'wpshop' ), 30 );

			wp_redirect( $page );
		} else {
			wp_redirect( $page );
			exit;
		}
	}

	public function load_modal_resume_order() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$order = Orders_Class::g()->get( array( 'id' => $id ), true );

		ob_start();
		\eoxia\View_Util::exec( 'wpshop', 'my-account', 'frontend/order-modal', array(
			'order' => $order,
		) );
		wp_send_json_success( array(
			'view'          => ob_get_clean(),
			'buttons_view' => '<div class="wpeo-button button-main modal-close"><span>' . __( 'Close', 'wpshop' ) . '</span></div>'
		) );
	}
}

new My_Account_Action();

<?php
/**
 * Gestion API.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2019 Eoxia <dev@eoxia.com>.
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
 * API Action Class.
 */
class API_Action {

	/**
	 * Constructeur
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_filter( 'eo_model_check_cap', array( $this, 'check_cap' ), 1, 2 );

		add_action( 'rest_api_init', array( $this, 'callback_rest_api_init' ) );
		add_action( 'init', array( $this, 'init_endpoint' ) );

		add_action( 'show_user_profile', array( $this, 'callback_edit_user_profile' ) );
		add_action( 'edit_user_profile', array( $this, 'callback_edit_user_profile' ) );

		add_action( 'wp_ajax_generate_api_key', array( $this, 'generate_api_key' ) );
	}

	/**
	 * Vérifie que l'utilisateur à les droits.
	 *
	 * @since 2.0.0
	 *
	 * @param  boolean         $cap     True ou false.
	 * @param  WP_REST_Request $request Les données de la requête.
	 *
	 * @return boolean                  True ou false.
	 */
	public function check_cap( $cap, $request ) {
		$headers = $request->get_headers();

		if ( empty( $headers['wpapikey'] ) ) {
			return false;
		}

		$wp_api_key = $headers['wpapikey'];

		$user = API::g()->get_user_by_token( $wp_api_key[0] );

		if ( empty( $user ) ) {
			return false;
		}

		wp_set_current_user( $user->ID );

		return true;
	}

	/**
	 * Ajoutes la route pour PayPal.
	 *
	 * @since 2.0.0
	 */
	public function callback_rest_api_init() {
		register_rest_route( 'wpshop/v2', '/statut', array(
			'methods'             => array( 'GET' ),
			'callback'            => array( $this, 'check_statut' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/wps_gateway_paypal', array(
			'methods'  => array( 'GET', 'POST' ),
			'callback' => array( $this, 'callback_wps_gateway_paypal' ),
		) );

		register_rest_route( 'wpshop/v2', '/wps_gateway_stripe', array(
			'methods'  => array( 'GET', 'POST' ),
			'callback' => array( $this, 'callback_wps_gateway_stripe' ),
		) );

		register_rest_route( 'wpshop/v2', '/product/search', array(
			'methods'  => array( 'GET' ),
			'callback' => array( $this, 'callback_search' ),
		) );

		register_rest_route( 'wpshop/v2', '/order/(?P<id>[\d]+)/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_add_line_to_order' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/propal/(?P<id>[\d]+)/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_add_line_to_propal' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/invoice/(?P<id>[\d]+)/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_add_line_to_invoice' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/order/(?P<id>[\d]+)/update/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_update_line_to_order' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/propal/(?P<id>[\d]+)/update/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_update_line_to_propal' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/invoice/(?P<id>[\d]+)/update/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_update_line_to_invoice' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/order/(?P<id>[\d]+)/delete/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_delete_line_to_order' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/propal/(?P<id>[\d]+)/delete/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_delete_line_to_propal' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/invoice/(?P<id>[\d]+)/delete/line', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_delete_line_to_invoice' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/create/order', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_create_order' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/create/propal', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_create_propal' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/create/invoice', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_create_invoice' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/create/payment', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_create_payment' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );

		register_rest_route( 'wpshop/v2', '/sync', array(
			'methods' => array( 'POST' ),
			'callback' => array( $this, 'callback_wps_sync_from_dolibarr' ),
			'permission_callback' => function( $request ) {
				return \eoxia\Rest_Class::g()->check_cap( 'get', $request );
			},
		) );
	}

	/**
	 * Ajoutes la route si oauth2 n'est pas activé.
	 *
	 * @since 2.0.0
	 */
	public function init_endpoint() {
		if ( ! DEFINED( 'WPOAUTH_VERSION' ) ) {
			add_rewrite_endpoint( 'oauth/authorize', EP_ALL );

			if ( ! get_option( 'plugin_permalinks_flushed' ) ) {
				flush_rewrite_rules( false );
				update_option( 'plugin_permalinks_flushed', 1 );
			}
		}
	}

	/**
	 * Permet de vérifier que l'application externe soit bien connecté.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_REST_Request $request Les données de la requête.
	 *
	 * @return WP_REST_Response          La réponse au format JSON.
	 */
	public function check_statut( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_REST_Response( false );
		}

		return new \WP_REST_Response( true );
	}

	/**
	 * Gestion de la route Paypal.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Request $request L'objet contenant les informations de la
	 * requête.
	 */
	public function callback_wps_gateway_paypal( $request ) {
		$data = $request->get_body_params();

		// translators: Paypal Gateway data: {json_data}.
		\eoxia\LOG_Util::log( sprintf( 'Paypal Gateway data: %s', json_encode( $data ) ), 'wpshop2' );

		$txn_id = get_post_meta( $data['custom'], 'payment_txn_id', true );

		if ( $txn_id !== $data['txn_id'] ) {
			update_post_meta( $data['custom'], 'payment_data', $data );
			update_post_meta( $data['custom'], 'payment_txn_id', $data['txn_id'] );
			update_post_meta( $data['custom'], 'payment_method', 'paypal' );

			do_action( 'wps_gateway_paypal', $data );
		}
	}

	/**
	 * Gestion de la route Stripe.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Request $request L'objet contenant les informations de la
	 * requête.
	 */
	public function callback_wps_gateway_stripe( $request ) {
		$param = json_decode( $request->get_body(), true );

		// translators: Stripe Gateway data: {json_data}.
		\eoxia\LOG_Util::log( sprintf( 'Stripe Gateway data: %s', json_encode( $param ) ), 'wpshop2' );

		$order = Doli_Order::g()->get( array(
			'meta_key'     => '_external_data',
			'meta_compare' => 'LIKE',
			'meta_value'   => $param['data']['object']['id'],
		), true );

		if ( ! empty( $order ) ) {
			update_post_meta( $order->data['id'], 'payment_data', $param );
			update_post_meta( $order->data['id'], 'payment_txn_id', $param['data']['object']['id'] );
			update_post_meta( $order->data['id'], 'payment_method', 'stripe' );

			$param['custom'] = $order->data['id'];

			do_action( 'wps_gateway_stripe', $param );
		}
	}

	/**
	 * Recherche un produit depuis l'API.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_REST_Request $request Les données de la requête.
	 *
	 * @return WP_REST_Response         Les produits trouvés.
	 */
	public function callback_search( $request ) {
		$param    = $request->get_params();
		$products = Product::g()->get( array( 's' => $param['s'] ) );

		$response_products = array();

		if ( ! empty( $products ) ) {
			foreach ( $products as $product ) {
				$response_products[] = $product->data;
			}
		}

		$response = new \WP_REST_Response( $response_products );
		return $response;
	}


	/**
	 * Ajoute les champs spécifiques à note de frais dans le compte utilisateur.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_User $user L'objet contenant la définition
	 * complète de l'utilisateur.
	 */
	public function callback_edit_user_profile( $user ) {
		$token = get_user_meta( $user->ID, '_wpshop_api_key', true );

		\eoxia\View_Util::exec( 'wpshop', 'api', 'field-api', array(
			'id'    => $user->ID,
			'token' => $token,
		) );
	}

	/**
	 * Génère une clé API pour un utilisateur
	 *
	 * @since 2.0.0
	 */
	public function generate_api_key() {
		check_ajax_referer( 'generate_api_key' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$token = API::g()->generate_token();
		update_user_meta( $id, '_wpshop_api_key', $token );

		ob_start();
		\eoxia\View_Util::exec( 'wpshop', 'api', 'field-api', array(
			'id'    => $id,
			'token' => $token,
		) );

		wp_send_json_success( array(
			'namespace'        => 'wpshop',
			'module'           => 'API',
			'callback_success' => 'generatedAPIKey',
			'view'             => ob_get_clean(),
		) );
	}

	 /**
 	 * Gestion de la route pour synchroniser un objet depuis dolibarr.
 	 *
 	 * @since 2.0.0
 	 *
 	 * @param  WP_Request $request L'objet contenant les informations de la
 	 * requête.
 	 */
 	public function callback_wps_sync_from_dolibarr( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		if ( empty( $param['wp_id'] ) || empty( $param['doli_id'] ) ) {
			$response->set_status( 400 );
			$response->set_data( array( 'status_code' => 404 ) );
			return $response;
		}

		Doli_Sync::g()->associate_and_synchronize( 'dolibarr', $param['wp_id'], $param['doli_id'] );

		return $response;
	}

	public function callback_add_line_to_order( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$product = Product::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $param['fk_product'],
		), true );

		if ( empty( $product ) ) {
			$product   = Product::g()->create( array( 'title' => 'tmp' ) );
			$sync_data = Doli_Sync::g()->associate_and_synchronize( 'dolibarr', $product->data['id'], $param['fk_product'] );

			$param['libelle'] = $sync_data['wp_object']->data['title'];
		}

		$order = Doli_Order::g()->get( array( 'id' => $param['id'] ), true );

		Doli_Order::g()->add_line( $order, $param );

		return $response;
	}

	public function callback_update_line_to_order( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$product = Product::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $param['fk_product'],
		), true );

		if ( empty( $product ) ) {
			$product   = Product::g()->create( array( 'title' => 'tmp' ) );
			$sync_data = Doli_Sync::g()->associate_and_synchronize( 'dolibarr', $product->data['id'], $param['fk_product'] );

			$param['libelle'] = $sync_data['wp_object']->data['title'];
		}

		$order = Doli_Order::g()->get( array( 'id' => $param['id'] ), true );

		Doli_Order::g()->update_line( $order, $param );
		return $response;
	}

	public function callback_delete_line_to_order( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$order = Doli_Order::g()->get( array( 'id' => $param['id'] ), true );

		Doli_Order::g()->delete_line( $order, $param['rowid'] );
		return $response;
	}

	public function callback_create_order( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$third_party_id = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $param['socid'] );

		if ( empty( $third_party_id ) ) {
			$wp_entry = Third_Party::g()->get( array( 'schema' => true ), true );

			$doli_entry = Request_Util::get( 'thirdparties/' . $param['socid'] );
			$third_party = Doli_Third_Parties::g()->doli_to_wp( $doli_entry, $wp_entry );
			$third_party_id = $third_party->data['id'];
		}

		$data = array(
			'external_id'       => $param['external_id'],
			'title'             => $param['title'],
			'total_ht'          => $param['total_ht'],
			'total_ttc'         => $param['total_ttc'],
			'date_commande'     => date( 'Y-m-d H:i:s', $param['date_commande'] ),
			'date_creation'     => date( 'Y-m-d H:i:s', $param['date_creation'] ),
			'parent_id'         => $third_party_id,
			'status'            => 'draft',
			'date_last_synchro' => current_time( 'mysql' ),
		);

		$order = Doli_Order::g()->create( $data );
		return $order;
	}

	public function callback_create_propal( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$third_party_id = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $param['socid'] );

		if ( empty( $third_party_id ) ) {
			$wp_entry = Third_Party::g()->get( array( 'schema' => true ), true );

			$doli_entry = Request_Util::get( 'thirdparties/' . $param['socid'] );
			$third_party = Doli_Third_Parties::g()->doli_to_wp( $doli_entry, $wp_entry );
			$third_party_id = $third_party->data['id'];
		}

		$data = array(
			'external_id'       => $param['external_id'],
			'title'             => $param['title'],
			'total_ht'          => $param['total_ht'],
			'total_ttc'         => $param['total_ttc'],
			'datec'             => date( 'Y-m-d H:i:s', $param['datec'] ),
			'parent_id'         => $third_party_id,
			'status'            => 'draft',
			'date_last_synchro' => $param['date_last_synchro'],
		);

		$propal = Proposals::g()->create( $data );
		return $propal;
	}

	public function callback_add_line_to_propal( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$product = Product::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $param['fk_product'],
		), true );

		if ( empty( $product ) ) {
			$product   = Product::g()->create( array( 'title' => 'tmp' ) );
			$sync_data = Doli_Sync::g()->associate_and_synchronize( 'dolibarr', $product->data['id'], $param['fk_product'] );

			$param['libelle'] = $sync_data['wp_object']->data['title'];
		}

		$propal = Proposals::g()->get( array( 'id' => $param['id'] ), true );

		Proposals::g()->add_line( $propal, $param );

		return $response;
	}

	public function callback_update_line_to_propal( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$product = Product::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $param['fk_product'],
		), true );

		if ( empty( $product ) ) {
			$product   = Product::g()->create( array( 'title' => 'tmp' ) );
			$sync_data = Doli_Sync::g()->associate_and_synchronize( 'dolibarr', $product->data['id'], $param['fk_product'] );

			$param['libelle'] = $sync_data['wp_object']->data['title'];
		}

		$propal = Proposals::g()->get( array( 'id' => $param['id'] ), true );

		Proposals::g()->update_line( $propal, $param );
		return $response;
	}

	public function callback_delete_line_to_propal( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$propal = Proposals::g()->get( array( 'id' => $param['id'] ), true );

		Proposals::g()->delete_line( $propal, $param['rowid'] );
		return $response;
	}

	public function callback_create_invoice( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$third_party_id = Doli_Third_Parties::g()->get_wp_id_by_doli_id( $param['socid'] );

		if ( empty( $third_party_id ) ) {
			$wp_entry = Third_Party::g()->get( array( 'schema' => true ), true );

			$doli_entry = Request_Util::get( 'thirdparties/' . $param['socid'] );
			$third_party = Doli_Third_Parties::g()->doli_to_wp( $doli_entry, $wp_entry );
			$third_party_id = $third_party->data['id'];
		}

		$data = array(
			'external_id'       => $param['external_id'],
			'title'             => $param['title'],
			'total_ht'          => $param['total_ht'],
			'total_ttc'         => $param['total_ttc'],
			'third_party_id'    => $third_party_id,
			'status'            => 'draft',
			'date_last_synchro' => current_time( 'mysql' ),
		);
		if ( ! empty( $param['linked_object']['commande'] ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.

			$order_id            = Doli_Order::g()->get_wp_id_by_doli_id( $param['linked_object']['commande'] );
			$data['post_parent'] = $order_id;

			$order             = Doli_Order::g()->get( array( 'id' => $order_id ), true );
			$data['author_id'] = $order->data['author_id'];
		}

		$propal = Doli_Invoice::g()->create( $data );
		return $propal;
	}

	public function callback_add_line_to_invoice( $request ) {
		$response = new \WP_REST_Response();
 		$param    = $request->get_params();

		$product = Product::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $param['fk_product'],
		), true );

		if ( empty( $product ) ) {
			$product   = Product::g()->create( array( 'title' => 'tmp' ) );
			$sync_data = Doli_Sync::g()->associate_and_synchronize( 'dolibarr', $product->data['id'], $param['fk_product'] );

			$param['libelle'] = $sync_data['wp_object']->data['title'];
		}

		$propal = Doli_Invoice::g()->get( array( 'id' => $param['id'] ), true );

		Doli_Invoice::g()->add_line( $propal, $param );

		return $response;
	}

	public function callback_update_line_to_invoice( $request ) {
		$response = new \WP_REST_Response();
		$param    = $request->get_params();

		$product = Product::g()->get( array(
			'meta_key'   => '_external_id',
			'meta_value' => (int) $param['fk_product'],
		), true );

		if ( empty( $product ) ) {
			$product   = Product::g()->create( array( 'title' => 'tmp' ) );
			$sync_data = Doli_Sync::g()->associate_and_synchronize( 'dolibarr', $product->data['id'], $param['fk_product'] );

			$param['libelle'] = $sync_data['wp_object']->data['title'];
		}

		$invoice = Doli_Invoice::g()->get( array( 'id' => $param['id'] ), true );

		Doli_Invoice::g()->update_line( $invoice, $param );
		return $response;
	}

	public function callback_delete_line_to_invoice( $request ) {
		$response = new \WP_REST_Response();
		$param    = $request->get_params();

		$invoice = Doli_Invoice::g()->get( array( 'id' => $param['id'] ), true );

		Doli_Invoice::g()->delete_line( $invoice, $param['rowid'] );
		return $response;
	}

	public function callback_create_payment( $request ) {
		$response = new \WP_REST_Response();
		$param    = $request->get_params();

		$invoice_id = Doli_Invoice::g()->get_wp_id_by_doli_id( $param['parent_id'] );

		$data = array(
			'external_id'  => $param['external_id'],
			'title'        => $param['title'],
			'amount'       => $param['amount'],
			'date'         => $param['date'],
			'status'       => 'publish',
			'last_sync'    => $param['last_sync'],
			'parent_id'    => $invoice_id,
			'payment_type' => ! empty( $param['paiementcode'] ) ? Doli_Payment::g()->convert_to_wp( $param['paiementcode'] ) : '-',
		);

		$propal = Doli_Payment::g()->create( $data );

		return $propal;
	}
}

new API_Action();

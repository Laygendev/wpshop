<?php
/**
 * Les fonctions principales des tiers.
 *
 * Le controlleur du modèle Third_Party_Model.
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
 * Third Party class.
 */
class Third_Party extends \eoxia\Post_Class {

	/**
	 * Model name @see ../model/*.model.php.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $model_name = '\wpshop\Third_Party_Model';

	/**
	 * Post type
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $type = 'wps-third-party';

	/**
	 * La clé principale du modèle
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $meta_key = 'third-party';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $base = 'third-party';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '';

	/**
	 * Récupères la liste des produits et appel la vue "list" du module "Product".
	 *
	 * @since 2.0.0
	 */
	public function display() {
		$current_page = isset( $_GET['current_page'] ) ? $_GET['current_page'] : 1;

		$s = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

		$third_parties_ids = Third_Party::g()->search( $s, array(
			'orderby'        => 'ID',
			'offset'         => ( $current_page - 1 ) * 25,
			'posts_per_page' => 25,
		) );

		$third_parties = array();

		if ( ! empty( $third_parties_ids ) ) {
			$third_parties = $this->get( array(
				'post__in' => $third_parties_ids,
			) );
		}

		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );

		\eoxia\View_Util::exec( 'wpshop', 'third-parties', 'list', array(
			'third_parties' => $third_parties,
			'doli_url'      => $dolibarr_option['dolibarr_url'],
		) );
	}

	/**
	 * Affiches les trois dernières actions commerciales du tier.
	 *
	 * @since 2.0.0
	 *
	 * @param  Third_Party $third_party Les données du tier.
	 */
	public function display_commercial( $third_party ) {
		$dolibarr_option = get_option( 'wps_dolibarr', Settings::g()->default_settings );
		$dolibarr_active = Settings::g()->dolibarr_is_active() ? true : false;

		// Move to doli module.
		$order   = array();
		$invoice = array();

		$propal = Proposals::g()->get( array(
			'post_parent'    => $third_party['id'],
			'posts_per_page' => 1,
		), true );

		if ( $dolibarr_active ) {
			$order = Doli_Order::g()->get( array(
				'post_parent'    => $third_party['id'],
				'posts_per_page' => 1,
			), true );

			$invoice = Doli_Invoice::g()->get( array(
				'meta_key'       => '_third_party_id',
				'meta_value'     => $third_party['id'],
				'posts_per_page' => 1,
			), true );
		}

		\eoxia\View_Util::exec( 'wpshop', 'third-parties', 'commercial', array(
			'doli_url'    => $dolibarr_option['dolibarr_url'],
			'order'       => $order,
			'propal'      => $propal,
			'invoice'     => $invoice,
			'doli_active' => $dolibarr_active,
		) );
	}

	public function search( $s = '', $default_args = array(), $count = false ) {
		$args = array(
			'post_type'      => 'wps-third-party',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		$args = wp_parse_args( $args, $default_args );

		if ( ! empty( $s ) ) {
			// Search in contact.
			$users_id = get_users( array(
				'search' => '*' . $s . '*',
				'fields' => 'ID',
			) );

			$users_id = array_merge( $users_id, get_users( array(
				'meta_key'     => '_phone',
				'meta_value'   => $s,
				'meta_compare' => 'LIKE',
				'fields'       => 'ID',
			) ) );

			$third_parties_id = array_map( function( $id ) {
				return get_user_meta( $id, '_third_party_id', true );
			}, $users_id );

			$third_parties_id_from_contact = array_filter( $third_parties_id, 'is_numeric' );

			$third_parties_id = array_merge( $third_parties_id_from_contact, get_posts( array(
				's'              => $s,
				'fields'         => 'ids',
				'post_type'      => 'wps-third-party',
				'posts_per_page' => -1,
			) ) );

			array_unique( $third_parties_id );

			if ( empty( $third_parties_id ) ) {
				if ( $count ) {
					return 0;
				} else {
					return array();
				}
			} else {
				$args['post__in'] = $third_parties_id;

				if ( $count ) {
					return count( get_posts( $args ) );
				} else {
					return $third_parties_id;
				}
			}
		}

		if ( $count ) {
			return count( get_posts( $args ) );
		} else {
			return get_posts( $args );
		}

		return $result;
	}
}

Third_Party::g();

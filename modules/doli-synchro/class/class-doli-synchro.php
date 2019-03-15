<?php
/**
 * Les fonctions principales des synchronisations.
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
 *  Doli Synchro Class.
 */
class Doli_Synchro extends \eoxia\Singleton_Util {

	/**
	 * Tableau contenant les synchronisations à effectuer.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $sync_infos = array();

	/**
	 * Limites de synchronisation par requête.
	 *
	 * @since 2.0.0
	 *
	 * @var integer
	 */
	public $limit_entries_by_request = 50;

	/**
	 * Constructeur.
	 *
	 * @since 2.0.0
	 */
	protected function construct() {
		$this->sync_infos = array(
			'third-parties' => array(
				'title'    => __( 'Third parties', 'wpshop' ),
				'action'   => 'sync_third_parties',
				'endpoint' => 'thirdparties?limit=-1',
			),
			'contacts'      => array(
				'title'    => __( 'Contacts', 'wpshop' ),
				'action'   => 'sync_contacts',
				'endpoint' => 'contacts?limit=-1',
			),
			'products'      => array(
				'title'    => __( 'Products', 'wpshop' ),
				'action'   => 'sync_products',
				'endpoint' => 'products?limit=-1',
			),
			'proposals'     => array(
				'title'    => __( 'Proposals', 'wpshop' ),
				'action'   => 'sync_proposals',
				'endpoint' => 'proposals?limit=-1',
			),
			'orders'        => array(
				'title'    => __( 'Orders', 'wpshop' ),
				'action'   => 'sync_orders',
				'endpoint' => 'orders?limit=-1',
			),
			'invoices'      => array(
				'title'    => __( 'Invoices', 'wpshop' ),
				'action'   => 'sync_invoices',
				'endpoint' => 'invoices?limit=-1',
			),
		);
	}
}

Doli_Synchro::g();
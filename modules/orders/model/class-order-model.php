<?php
/**
 * Classe définisant le modèle d'un order WPSHOP.
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
 * Class order model.
 */
class Order_Model extends \eoxia\Post_Model {

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param Order_Class $object     Les données de l'objet.
	 * @param string        $req_method La méthode de la requête.
	 */
	public function __construct( $object, $req_method = null ) {

		$this->schema['external_id'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'external_id',
			'since'       => '2.0.0',
			'description' => 'L\'ID du customer (dolibarr). Relation avec dolibarr.',
		);

		$this->schema['external_status'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'external_status',
			'since'       => '2.0.0',
			'description' => 'Le status de la proposition commerciale dans dolibarr. Relation avec dolibarr.',
		);

		$this->schema['ref'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'ref',
			'since'       => '2.0.0',
			'description' => 'Clé temporaire du devis. Relation avec dolibarr.',
		);

		$this->schema['order_date'] = array(
			'type'        => 'integer',
			'meta_type'   => 'single',
			'field'       => 'order_date',
			'since'       => '2.0.0',
			'description' => 'Date de création du devis. Relation avec dolibarr',
		);

		$this->schema['order_currency'] = array(
			'type'        => 'string',
			'meta_type'   => 'single',
			'field'       => 'order_currency',
			'since'       => '2.0.0',
			'description' => '',
		);

		$this->schema['order_grand_total'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'order_grand_total',
			'since'       => '2.0.0',
			'description' => '',
		);

		$this->schema['order_total_ttc'] = array(
			'type'        => 'float',
			'meta_type'   => 'single',
			'field'       => 'order_total_ttc',
			'default'     => 0.00000000,
			'since'       => '2.0.0',
			'description' => 'Prix total de la commande, toutes taxes comprises (float). Peut être NULL. Valeur par défaut NULL.',
		);

		parent::__construct( $object, $req_method );
	}
}

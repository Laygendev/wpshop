<?php
/**
 * La vue principale de la page des produits (wps-third-party)
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit; ?>

<div class="wrap">
	<h2><?php esc_html_e( 'Third Parties', 'wpshop' ); ?></h2>

	<div class="action-attribute wpeo-button button-main"
		data-action="synchro_third_parties">
		<span>Synchro</span>
	</div>

	<?php Third_Party_Class::g()->display(); ?>
</div>

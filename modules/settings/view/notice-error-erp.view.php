<?php
/**
 * Notice pour informer l'utilisateur d'activer son ERP.
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

<div class="notice notice-warning is-dismissible">
	<p>
		<?php echo esc_attr( $error ); ?>
		<a target="_blank" href="https://github.com/Eoxia/wpshop/tree/2.0.0"><?php _e( 'Follow this guide', 'wpshop' ); ?></a>
	</p>
</div>
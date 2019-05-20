<?php
/**
 * La vue du tableau récapitulatif du panier.
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

<ul class="wps-cart-resume">
	<?php do_action( 'wps_before_cart_resume_lines' ); ?>
	<li class="wps-resume-line">
		<span class="wps-line-content"><?php echo esc_html( 'Subtotal', 'wpshop' ); ?></span>
		<span class="wps-line-value"><?php echo esc_html( number_format( Cart_Session::g()->total_price_no_shipping, 2, '.', '' ) ); ?>€</span>
	</li>
	<li class="wps-resume-line">
		<span class="wps-line-content"><?php echo esc_html( 'Taxes', 'wpshop' ); ?></span>
		<span class="wps-line-value"><?php echo esc_html( number_format( Cart_Session::g()->tva_amount, 2, '.', '' ) ); ?>€</span>
	</li>
	<?php if ( Cart_Session::g()->total_price_no_shipping < (float) $shipping_cost_option['from_price_ht'] ) : ?>
		<li class="wps-resume-line">
			<span class="wps-line-content"><?php echo esc_html( 'Shipping costs', 'wpshop' ); ?></span>
			<span class="wps-line-value"><?php echo esc_html( number_format( $shipping_cost_product->data['price_ttc'], 2, '.', '' ) ); ?>€</span>
		</li>
	<?php endif; ?>

	<?php do_action( 'wps_after_cart_resume_lines' ); ?>

	<li class="wps-resume-line featured">
		<span class="wps-line-content"><?php esc_html_e( 'Total TTC', 'wpshop' ); ?></span>
		<span class="wps-line-value"><?php echo esc_html( number_format( Cart_Session::g()->total_price_ttc, 2, '.', '' ) ); ?>€</span>
	</li>

	<?php do_action( 'wps_after_cart_resume_total' ); ?>
</ul>
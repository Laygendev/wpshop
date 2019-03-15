<?php
/**
 * Le formulaire pour créer son adresse de livraison
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

<table class="wpeo-table wps-checkout-review-order-table">
	<thead>
		<tr>
			<th></th>
			<th data-title="<?php esc_html_e( 'Product name', 'wpshop' ); ?>"><?php esc_html_e( 'Product name', 'wpshop' ); ?></th>
			<th data-title="<?php esc_html_e( 'VAT', 'wpshop' ); ?>"><?php esc_html_e( 'VAT', 'wpshop' ); ?></th>
			<th data-title="<?php esc_html_e( 'P.U. HT', 'wpshop' ); ?>"><?php esc_html_e( 'P.U HT', 'wpshop' ); ?></th>
			<th data-title="<?php esc_html_e( 'Quantity', 'wpshop' ); ?>"><?php esc_html_e( 'Quantity', 'wpshop' ); ?></th>
			<th data-title="<?php esc_html_e( 'Total HT', 'wpshop' ); ?>"><?php esc_html_e( 'Total HT', 'wpshop' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action( 'wps_review_order_before_cart_contents' );

		if ( ! empty( $cart_contents ) ) :
			foreach ( $cart_contents as $cart_item ) :
				?>
				<tr>
					<td><?php echo get_the_post_thumbnail( $cart_item['id'], array( 80, 80 ) ); ?></td>
					<td data-title="<?php echo esc_html( 'Product name', 'wpshop' ); ?>"><a href="<?php echo esc_url( get_permalink( $cart_item['id'] ) ); ?>"><?php echo esc_html( $cart_item['title'] ); ?></a></td>
					<td data-title="<?php echo esc_html( 'VAT', 'wpshop' ); ?>"><?php echo esc_html( number_format( $cart_item['tva_tx'], 2, ',', '' ) ); ?>%</td>
					<td data-title="<?php echo esc_html( 'P.U. HT', 'wpshop' ); ?>"><?php echo esc_html( number_format( $cart_item['price'], 2, ',', '' ) ); ?>€</td>
					<td data-title="<?php echo esc_html( 'Quantity', 'wpshop' ); ?>"><?php echo esc_html( $cart_item['qty'] ); ?></td>
					<td data-title="<?php echo esc_html( 'Total HT', 'wpshop' ); ?>"><?php echo esc_html( number_format( $cart_item['price'] * $cart_item['qty'], 2, ',', '' ) ); ?>€</td>
				</tr>
				<?php
			endforeach;
		endif;

		do_action( 'wps_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5"><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong></td>
			<td><?php echo number_format( $proposal->data['total_ht'], 2, ',', '' ); ?>€</td>
		</tr>
		<?php
		if ( ! empty( $tva_lines ) ) :
			foreach ( $tva_lines as $key => $tva_line ) :
				?>
				<tr>
					<td colspan="5"><strong><?php esc_html_e( 'Total VAT', 'wpshop' ); ?> <?php echo number_format( $key, 2, ',', '' ); ?>%</strong></td>
					<td><?php echo number_format( $tva_line, 2, ',', '' ); ?>€</td>
				</tr>
				<?php
			endforeach;
		endif;
		?>

		<tr>
			<td colspan="5"><strong><?php esc_html_e( 'Total TTC', 'wpshop' ); ?></strong></td>
			<td><strong><?php echo number_format( $proposal->data['total_ttc'], 2, ',', '' ); ?>€</strong></td>
		</tr>
	</tfoot>
</table>

<div class="">

</div>

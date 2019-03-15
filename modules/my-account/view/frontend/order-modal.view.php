<?php
/**
 * Le résumé d'une commande dans la page mon compte
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2011-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   WPshop\Templates
 *
 * @since     2.0.0
 *
 * @todo: Clean
 */

namespace wpshop;

defined( 'ABSPATH' ) || exit;

if ( 'cheque' === $order->data['payment_method'] ) :
	?>
	<h2><?php esc_html_e( 'How to pay for your order', 'wpshop' ); ?></h2>
	<?php
	$payment_methods = get_option( 'wps_payment_methods', \wpshop\Payment_Class::g()->default_options );
	echo stripslashes( nl2br( $payment_methods['cheque']['description'] ) );
endif;
?>

<h2><?php esc_html_e( 'Order detail', 'wpshop' ); ?></h2>

<table class="wpeo-table">
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
		if ( ! empty( $order->data['lines'] ) ) :
			foreach ( $order->data['lines'] as $line ) :
				?>
				<tr>
					<td><?php echo get_the_post_thumbnail( $line['id'], array( 80, 80 ) ); ?></td>
					<td>
						<a href="<?php echo esc_url( get_permalink( $line['id'] ) ); ?>">
							<?php echo esc_html( $line['libelle'] ); ?>
						</a>
					</td>
					<td>
						<?php echo esc_html( number_format( $line['tva_tx'], 2, ',', '' ) ); ?>%
					</td>
					<td>
						<?php echo esc_html( number_format( $line['price'], 2, ',', '' ) ); ?>€
					</td>
					<td>
						<?php echo esc_html( $line['qty'] ); ?>
					</td>
					<td>
						<?php echo esc_html( number_format( $line['price'] * $line['qty'], 2, ',', '' ) ); ?>€
					</td>
				</tr>
				<?php
			endforeach;
		endif;
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5"><strong><?php esc_html_e( 'Total HT', 'wpshop' ); ?></strong></td>
			<td><?php echo number_format( $order->data['total_ht'], 2, ',', '' ); ?>€</td>
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
			<td><strong><?php echo number_format( $order->data['total_ttc'], 2, ',', '' ); ?>€</strong></td>
		</tr>
	</tfoot>
</table>

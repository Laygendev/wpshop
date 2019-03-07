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

<table class="wpeo-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Product name', 'wpshop' ); ?></th>
			<th><?php esc_html_e( 'TVA', 'wpshop' ); ?></th>
			<th><?php esc_html_e( 'P.U HT', 'wpshop' ); ?></th>
			<th><?php esc_html_e( 'Quantity', 'wpshop' ); ?></th>
			<th><?php esc_html_e( 'Total HT', 'wpshop' ); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		if ( ! empty( $order->data['lines'] ) ) :
			foreach ( $order->data['lines'] as $line ) :
				?>
				<tr>
					<td><?php echo $line['libelle']; ?></td>
					<td><?php echo number_format( $line['tva_tx'], 2, ',', '' ); ?>%</td>
					<td><?php echo number_format( $line['price'], 2, ',', '' ); ?>€</td>
					<td><?php echo $line['qty']; ?></td>
					<td><?php echo number_format( $line['price'], 2, ',', '' ); ?>€</td>
				</tr>
				<?php
			endforeach;
		endif;
		?>
	</tbody>

	<tfoot>
		<tr>
			<td colspan="4"><strong>Total HT</strong></td>
			<td><?php echo number_format( $order->data['total_ht'], 2, ',', '' ); ?>€</td>
		</tr>
		<?php
		if ( ! empty( $tva_lines ) ) :
			foreach ( $tva_lines as $key => $tva_line ) :
				?>
				<tr>
					<td colspan="4"><strong>Total TVA <?php echo number_format( $key, 2, ',', '' ); ?>%</strong></td>
					<td><?php echo number_format( $tva_line, 2, ',', '' ); ?>€</td>
				</tr>
				<?php
			endforeach;
		endif;
		?>

		<tr>
			<td colspan="4"><strong>Total TTC</strong></td>
			<td><strong><?php echo number_format( $order->data['total_ttc'], 2, ',', '' ); ?>€</strong></td>
		</tr>
	</tfoot>
</table>

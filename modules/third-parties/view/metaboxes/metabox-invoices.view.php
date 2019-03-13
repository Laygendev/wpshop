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
			<th>Facture</th>
			<th>Commande</th>
			<th>Date</th>
			<th>Contenu  HT</th>
			<th>Status</th>
			<th>Paiement</th>
			<th>Montant TTC</th>
			<th>Document</th>
		</tr>
	</thead>

	<tbody>
		<?php
		if ( ! empty( $invoices ) ) :
			foreach ( $invoices as $invoice ) :
				?>
				<tr>
					<td>
						#<?php echo esc_html( $invoice->data['title'] ); ?>
					</td>
					<td>
						#<?php echo esc_html( $invoice->data['order']->data['title'] ); ?>
					</td>
					<td><?php echo esc_html( $invoice->data['date']['rendered']['date'] ); ?></td>
					<td>
						<ul>
							<?php
							if ( ! empty( $invoice->data['lines'] ) ) :
								foreach ( $invoice->data['lines'] as $line ) :
									?>
									<li><?php echo esc_html( $line['libelle'] ); ?> x<?php echo esc_html( $line['qty'] ); ?> - <?php echo esc_html( number_format( $line['total_ht'], 2, ',', '' ) ); ?>€</li>
									<?php
								endforeach;
							endif;
							?>
						</ul>
					</td>
					<td><?php echo esc_html( Payment_Class::g()->convert_status( $invoice->data ) ); ?></td>
					<td><?php echo esc_html( $invoice->data['payment_method'] ); ?></td>
					<td><?php echo esc_html( $invoice->data['total_ttc'] ); ?>€</td>
					<td>
						<a target="_blank" href="<?php echo esc_attr( admin_url( 'admin-post.php?action=wps_download_invoice&order_id=' . $invoice->data['order']->data['id'] ) ); ?>"><i class="fas fa-file-download"></i></a>					</td>
				</tr>
				<?php
			endforeach;
		endif;
		?>
	</tbody>
</table>
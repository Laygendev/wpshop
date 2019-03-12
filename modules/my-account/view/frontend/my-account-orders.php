<?php
/**
 * Affichage de la page mon compte
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

?>

<table class="wpeo-table">
  <thead>
    <tr>
      <th data-title="Order"><?php esc_html_e( 'Order', 'wpshop' ); ?></th>
      <th data-title="Date"><?php esc_html_e( 'Date', 'wpshop' ); ?></th>
      <th data-title="Status"><?php esc_html_e( 'Status', 'wpshop' ); ?></th>
	  <th data-title="Total"><?php esc_html_e( 'Total HT', 'wpshop' ); ?></th>
      <th data-title="Total"><?php esc_html_e( 'Total TTC', 'wpshop' ); ?></th>
      <th data-title="Total"><?php esc_html_e( 'Actions', 'wpshop' ); ?></th>
    </tr>
  </thead>
  <tbody>
	  <?php
	  if ( ! empty( $orders ) ) :
	  	foreach ( $orders as $order ) :
			?>
			<tr>
			  <th data-title="<?php echo esc_attr( $order->data['title'] ); ?>"><?php echo esc_html( $order->data['title'] ); ?></th>
			  <td data-title="<?php echo esc_attr( $order->data['date_commande']['rendered']['date'] ); ?>"><?php echo esc_html( $order->data['date_commande']['rendered']['date'] ); ?></td>
			  <td data-title="N/D"><?php echo esc_html( \wpshop\Payment_Class::g()->convert_status( $order->data ) ); ?></td>
			  <td data-title="<?php echo esc_attr( number_format( $order->data['total_ht'], 2, ',', '' ) ); ?>€"><?php echo esc_html( number_format( $order->data['total_ttc'], 2, ',', '' ) ); ?>€</td>
			  <td data-title="<?php echo esc_attr( number_format( $order->data['total_ttc'], 2, ',', '' ) ); ?>€"><?php echo esc_html( number_format( $order->data['total_ttc'], 2, ',', '' ) ); ?>€</td>
			  <td data-title="View">
				<?php
				if ( ! empty( $order->data['invoice'] ) ) :
					?>
					<a target="_blank" href="<?php echo esc_attr( admin_url( 'admin-post.php?action=wps_download_invoice&order_id=' . $order->data['id'] ) ); ?>" class="wpeo-button button-primary">
						<i class="button-icon fas fa-file-download"></i>
					</a>
					<?php
				endif;
				?>

				<div class="wpeo-button button-primary wpeo-modal-event"
					data-id="<?php echo esc_attr( $order->data['id'] ); ?>"
					data-title="Commande <?php echo esc_attr( $order->data['title'] ); ?>"
					data-action="load_modal_resume_order">
					<span><?php esc_html_e( 'Resume', 'wpshop' ); ?></span>
				</div>

			  </td>
			</tr>
			<?php
	  	endforeach;
	  endif;
	  ?>

  </tbody>
</table>

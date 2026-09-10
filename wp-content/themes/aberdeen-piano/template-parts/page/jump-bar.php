<?php
/**
 * The anchor bar shared by the Services and Student Resources pages.
 *
 * Expects $args:
 *   items string[] Rows of number / label / meta / anchor.
 *   label string   Accessible name for the nav landmark.
 *
 * @package Aberdeen_Piano
 */

$ap_items = isset( $args['items'] ) ? (array) $args['items'] : array();

if ( ! $ap_items ) {
	return;
}
?>
		<section class="page-index" aria-label="<?php echo esc_attr( isset( $args['label'] ) ? $args['label'] : __( 'On this page', 'aberdeen-piano' ) ); ?>">
			<div class="wrap">
				<nav class="jump-bar reveal">
					<?php
					foreach ( $ap_items as $ap_item ) :
						$ap_anchor = aberdeen_piano_row( $ap_item, 'anchor' );

						if ( ! $ap_anchor ) {
							continue;
						}
						?>
					<a href="<?php echo esc_url( aberdeen_piano_page_url( $ap_anchor ) ); ?>">
						<span class="num"><?php echo esc_html( aberdeen_piano_row( $ap_item, 'number' ) ); ?></span>
						<strong><?php echo esc_html( aberdeen_piano_row( $ap_item, 'label' ) ); ?></strong>
						<em><?php echo esc_html( aberdeen_piano_row( $ap_item, 'meta' ) ); ?></em>
					</a>
					<?php endforeach; ?>
				</nav>
			</div>
		</section>

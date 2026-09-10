<?php
/**
 * The closing panel shared by the interior pages.
 *
 * Expects $args:
 *   eyebrow      string  Small label above the heading.
 *   heading      array   array( field name, defaults path ) for the heading.
 *   lead         string  Lead paragraph.
 *   buttons      array   Rows of label / url / style.
 *   meta         array   Rows of label / value / url.
 *
 * @package Aberdeen_Piano
 */

$ap_id      = isset( $args['id'] ) ? $args['id'] : '';
$ap_eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$ap_heading = isset( $args['heading'] ) ? (array) $args['heading'] : array( '', '' );
$ap_lead    = isset( $args['lead'] ) ? $args['lead'] : '';
$ap_buttons = isset( $args['buttons'] ) ? (array) $args['buttons'] : array();
$ap_meta    = isset( $args['meta'] ) ? (array) $args['meta'] : array();
?>
		<section class="cta"<?php echo $ap_id ? ' id="' . esc_attr( $ap_id ) . '"' : ''; ?>>
			<div class="wrap">
				<div class="cta-panel reveal">
					<div class="cta-keys" aria-hidden="true"><i></i><i class="black"></i><i></i><i class="black"></i><i></i><i></i><i class="black"></i><i></i></div>
					<?php if ( $ap_eyebrow ) : ?>
					<div class="eyebrow"><?php echo esc_html( $ap_eyebrow ); ?></div>
					<?php endif; ?>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( $ap_heading[0], $ap_heading[1] ); ?></h2>
					<?php if ( $ap_lead ) : ?>
					<p class="lead"><?php echo esc_html( $ap_lead ); ?></p>
					<?php endif; ?>

					<?php if ( $ap_buttons ) : ?>
					<div class="cta-buttons">
						<?php
						foreach ( $ap_buttons as $ap_button ) :
							$ap_url = aberdeen_piano_row( $ap_button, 'url' );

							if ( ! $ap_url ) {
								continue;
							}

							$ap_style = aberdeen_piano_row( $ap_button, 'style', '' );
							?>
						<a class="btn<?php echo $ap_style ? ' ' . esc_attr( sanitize_html_class( $ap_style ) ) : ''; ?>" href="<?php echo esc_url( aberdeen_piano_page_url( $ap_url ) ); ?>"><?php echo esc_html( aberdeen_piano_row( $ap_button, 'label' ) ); ?></a>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

					<?php if ( $ap_meta ) : ?>
					<ul class="cta-meta">
						<?php
						foreach ( $ap_meta as $ap_item ) :
							$ap_url   = aberdeen_piano_row( $ap_item, 'url' );
							$ap_value = aberdeen_piano_row( $ap_item, 'value' );
							?>
						<li>
							<span><?php echo esc_html( aberdeen_piano_row( $ap_item, 'label' ) ); ?></span>
							<?php if ( $ap_url ) : ?>
							<a href="<?php echo esc_url( aberdeen_piano_page_url( $ap_url ) ); ?>"><?php echo esc_html( $ap_value ); ?></a>
							<?php else : ?>
							<strong><?php echo esc_html( $ap_value ); ?></strong>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>
			</div>
		</section>

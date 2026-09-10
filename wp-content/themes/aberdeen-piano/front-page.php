<?php
/**
 * The Aberdeen Piano home page.
 *
 * Every string comes from ACF via the aberdeen_piano_field() / _rows() helpers,
 * which fall back to inc/defaults.php so the markup always matches the approved
 * design in design/index.html.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_email = aberdeen_piano_email();
?>
	<main>

		<?php // Hero. ?>
		<section class="hero" id="home">
			<div class="wrap hero-content">
				<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_hero_eyebrow', 'hero.eyebrow' ) ); ?></div>
				<h1><span class="piano-line"><span class="white-key"><?php echo esc_html( aberdeen_piano_field( 'ap_hero_title_white', 'hero.title_white' ) ); ?></span> <span class="black-key"><?php echo esc_html( aberdeen_piano_field( 'ap_hero_title_black', 'hero.title_black' ) ); ?></span></span><br><em><?php echo esc_html( aberdeen_piano_field( 'ap_hero_title_em', 'hero.title_em' ) ); ?></em></h1>
				<p><?php echo esc_html( aberdeen_piano_field( 'ap_hero_description', 'hero.description' ) ); ?></p>
				<?php
				/*
				 * Buttons are optional and none are configured by default, so the
				 * wrapper is only opened once a row with a URL is found — an empty
				 * .actions still carries its 36px top margin and leaves a band of
				 * banner below the paragraph.
				 */
				$ap_hero_actions = array();

				foreach ( aberdeen_piano_rows( 'ap_hero_buttons', 'hero.buttons' ) as $ap_button ) {
					$ap_link = aberdeen_piano_link( aberdeen_piano_row( $ap_button, 'link', array() ) );

					if ( ! $ap_link['url'] ) {
						continue;
					}

					$ap_style  = aberdeen_piano_row( $ap_button, 'style', '' );
					$ap_target = $ap_link['target'] ? ' target="' . esc_attr( $ap_link['target'] ) . '" rel="noopener noreferrer"' : '';

					$ap_hero_actions[] = sprintf(
						'<a class="btn%1$s" href="%2$s"%3$s>%4$s</a>',
						$ap_style ? ' ' . esc_attr( sanitize_html_class( $ap_style ) ) : '',
						esc_url( $ap_link['url'] ),
						$ap_target, // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- built from esc_attr() above.
						esc_html( $ap_link['title'] )
					);
				}

				if ( $ap_hero_actions ) {
					echo '<div class="actions">' . implode( '', $ap_hero_actions ) . '</div>'; // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- each link is escaped above.
				}
				?>
			</div>
		</section>

		<?php // Marquee. ?>
		<?php $ap_marquee = aberdeen_piano_rows( 'ap_marquee_items', 'marquee.items' ); ?>
		<?php if ( $ap_marquee ) : ?>
		<div class="marquee" aria-hidden="true">
			<div class="marquee-track"><span>
				<?php
				// The track is looped twice so the animation runs seamlessly.
				for ( $ap_loop = 0; $ap_loop < 2; $ap_loop++ ) {
					foreach ( $ap_marquee as $ap_item ) {
						echo esc_html( aberdeen_piano_row( $ap_item, 'text' ) ) . ' <b>&#9834;</b> ';
					}
				}
				?>
			</span></div>
		</div>
		<?php endif; ?>

		<?php // About. ?>
		<?php
		$ap_about_image  = aberdeen_piano_field( 'ap_about_image', 'about.image' );
		$ap_about_button = aberdeen_piano_link(
			aberdeen_piano_field( 'ap_about_button', '' ),
			aberdeen_piano_default( 'about.button' )
		);
		?>
		<section id="about">
			<div class="wrap intro-grid">
				<div class="portrait reveal"><img src="<?php echo esc_url( aberdeen_piano_image_url( $ap_about_image, aberdeen_piano_default( 'about.image' ) ) ); ?>" alt="<?php echo esc_attr( aberdeen_piano_image_alt( $ap_about_image, aberdeen_piano_field( 'ap_about_image_alt', 'about.image_alt' ) ) ); ?>"></div>
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_about_eyebrow', 'about.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_about_heading', 'about.heading' ); ?></h2>
					<?php foreach ( aberdeen_piano_rows( 'ap_about_paragraphs', 'about.paragraphs' ) as $ap_paragraph ) : ?>
					<p class="lead"><?php aberdeen_piano_the_paragraph( $ap_paragraph ); ?></p>
					<?php endforeach; ?>
					<?php if ( $ap_about_button['url'] ) : ?>
					<p class="lead_tutionBtn"><a class="btn" href="<?php echo esc_url( $ap_about_button['url'] ); ?>"<?php echo $ap_about_button['target'] ? ' target="' . esc_attr( $ap_about_button['target'] ) . '" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $ap_about_button['title'] ); ?></a></p>
					<?php endif; ?>
					<div class="signature"><?php echo esc_html( aberdeen_piano_field( 'ap_about_signature', 'about.signature' ) ); ?></div>
					<div class="credentials"><?php echo esc_html( aberdeen_piano_field( 'ap_about_credentials', 'about.credentials' ) ); ?></div>
				</div>
			</div>
		</section>

		<?php // Quote. ?>
		<section class="quote depth-scene">
			<div class="wrap reveal">
				<blockquote><?php echo esc_html( aberdeen_piano_field( 'ap_quote_text', 'quote.text' ) ); ?></blockquote><cite><?php echo esc_html( aberdeen_piano_field( 'ap_quote_cite', 'quote.cite' ) ); ?></cite>
			</div>
		</section>

		<?php // Programs. ?>
		<section class="programs" id="lessons">
			<div class="wrap">
				<div class="program-head reveal">
					<div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_programs_heading', 'programs.heading' ); ?></h2>
						<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_programs_lead', 'programs.lead' ) ); ?></p>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_programs_intro', 'programs.intro' ) ); ?></p>
				</div>
				<div class="cards">
					<?php foreach ( aberdeen_piano_rows( 'ap_programs_cards', 'programs.cards' ) as $ap_card ) : ?>
					<article class="card reveal"><span class="num"><?php echo esc_html( aberdeen_piano_row( $ap_card, 'number' ) ); ?></span>
						<h3><?php echo esc_html( aberdeen_piano_row( $ap_card, 'title' ) ); ?></h3>
						<?php foreach ( (array) aberdeen_piano_row( $ap_card, 'paragraphs', array() ) as $ap_paragraph ) : ?>
						<p><?php aberdeen_piano_the_paragraph( $ap_paragraph ); ?></p>
						<?php endforeach; ?>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // The studio. ?>
		<section id="the-studio">
			<div class="wrap journey-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_studio_eyebrow', 'studio.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_studio_heading', 'studio.heading' ); ?></h2>
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_studio_lead', 'studio.lead' ) ); ?></p>
				</div>
				<div class="steps reveal">
					<?php foreach ( aberdeen_piano_rows( 'ap_studio_steps', 'studio.steps' ) as $ap_step ) : ?>
					<div class="step"><span><?php echo esc_html( aberdeen_piano_row( $ap_step, 'number' ) ); ?></span>
						<div>
							<h3><?php echo esc_html( aberdeen_piano_row( $ap_step, 'title' ) ); ?></h3>
							<p><?php echo esc_html( aberdeen_piano_row( $ap_step, 'description' ) ); ?></p>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // Policies. ?>
		<section class="policy-section depth-scene" id="policies">
			<div class="wrap policy-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_policies_eyebrow', 'policies.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_policies_heading', 'policies.heading' ); ?></h2>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_policies_intro', 'policies.intro' ) ); ?></p>
				</div>
				<div class="policy-list reveal">
					<?php foreach ( aberdeen_piano_rows( 'ap_policies_items', 'policies.items' ) as $ap_policy ) : ?>
					<details<?php echo aberdeen_piano_row_bool( $ap_policy, 'open' ) ? ' open' : ''; ?>>
						<summary><?php echo esc_html( aberdeen_piano_row( $ap_policy, 'title' ) ); ?></summary>
						<?php foreach ( (array) aberdeen_piano_row( $ap_policy, 'paragraphs', array() ) as $ap_paragraph ) : ?>
						<p><?php aberdeen_piano_the_paragraph( $ap_paragraph ); ?></p>
						<?php endforeach; ?>
					</details>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // Tuition. ?>
		<section class="pricing depth-scene" id="pricing">
			<div class="wrap">
				<div class="program-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_pricing_eyebrow', 'pricing.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_pricing_heading', 'pricing.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_pricing_intro', 'pricing.intro' ) ); ?></p>
				</div>
				<div class="price-grid reveal">
					<?php
					foreach ( aberdeen_piano_rows( 'ap_pricing_cards', 'pricing.cards' ) as $ap_price ) :
						$ap_classes = array( 'price-card' );

						if ( aberdeen_piano_row_bool( $ap_price, 'featured' ) ) {
							$ap_classes[] = 'featured';
						}

						$ap_extra = aberdeen_piano_row( $ap_price, 'css_class', '' );

						if ( $ap_extra ) {
							$ap_classes[] = $ap_extra;
						}

						$ap_price_link = aberdeen_piano_link( aberdeen_piano_row( $ap_price, 'link', array() ) );
						$ap_footnote   = aberdeen_piano_row( $ap_price, 'footnote', '' );
						?>
					<article class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $ap_classes ) ) ); ?>"><span class="label"><?php echo esc_html( aberdeen_piano_row( $ap_price, 'label' ) ); ?></span>
						<div class="price"><sup><?php echo esc_html( aberdeen_piano_row( $ap_price, 'currency', '$' ) ); ?></sup><?php echo esc_html( aberdeen_piano_row( $ap_price, 'amount' ) ); ?><?php echo aberdeen_piano_row_bool( $ap_price, 'starred' ) ? '<sup class="star">*</sup>' : ''; ?><small><?php echo esc_html( aberdeen_piano_period( aberdeen_piano_row( $ap_price, 'period' ) ) ); ?></small></div>
						<ul>
							<?php foreach ( (array) aberdeen_piano_row( $ap_price, 'features', array() ) as $ap_feature ) : ?>
							<li><?php aberdeen_piano_the_paragraph( $ap_feature ); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php if ( $ap_footnote ) : ?>
						<p><sup class="star">*</sup><?php echo esc_html( $ap_footnote ); ?></p>
						<?php endif; ?>
						<?php if ( $ap_price_link['url'] ) : ?>
						<a href="<?php echo esc_url( $ap_price_link['url'] ); ?>"<?php echo $ap_price_link['target'] ? ' target="' . esc_attr( $ap_price_link['target'] ) . '" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $ap_price_link['title'] ); ?></a>
						<?php endif; ?>
					</article>
					<?php endforeach; ?>
				</div>
				<p class="calendar-note" style="color:#68746d"><?php echo esc_html( aberdeen_piano_field( 'ap_pricing_note', 'pricing.note' ) ); ?></p>
			</div>
		</section>

		<?php // Calendar. ?>
		<section class="calendar" id="calendar">
			<div class="wrap">
				<div class="calendar-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_eyebrow', 'calendar.eyebrow' ) ); ?></div>
						<h2 class="section-title"><span class="piano-line"><span class="white-key"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_title_white', 'calendar.title_white' ) ); ?></span> <span class="black-key"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_title_black', 'calendar.title_black' ) ); ?></span></span></h2>
					</div>
				</div>
				<?php $ap_filters = aberdeen_piano_rows( 'ap_calendar_filters', 'calendar.filters' ); ?>
				<?php if ( $ap_filters ) : ?>
				<div class="schedule-tools reveal" aria-label="<?php echo esc_attr( aberdeen_piano_default( 'calendar.filters_label' ) ); ?>">
					<?php
					foreach ( $ap_filters as $ap_index => $ap_filter ) :
						$ap_key = aberdeen_piano_row( $ap_filter, 'key' );
						?>
					<button class="filter<?php echo 0 === $ap_index ? ' active' : ''; ?>" data-filter="<?php echo esc_attr( $ap_key ); ?>"><?php echo esc_html( aberdeen_piano_row( $ap_filter, 'label' ) ); ?></button>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<div class="schedule-list reveal">
					<?php
					foreach ( aberdeen_piano_rows( 'ap_calendar_events', 'calendar.events' ) as $ap_event ) :
						$ap_tag = aberdeen_piano_row( $ap_event, 'tag', '' );
						?>
					<article class="schedule-item" data-kind="<?php echo esc_attr( aberdeen_piano_row( $ap_event, 'kind' ) ); ?>">
						<div class="schedule-date"><?php echo esc_html( aberdeen_piano_row( $ap_event, 'date' ) ); ?></div>
						<div class="schedule-day"><?php echo esc_html( aberdeen_piano_row( $ap_event, 'day' ) ); ?></div>
						<div class="schedule-name"><?php echo esc_html( aberdeen_piano_row( $ap_event, 'name' ) ); ?><?php echo $ap_tag ? ' <span class="schedule-tag">' . esc_html( $ap_tag ) . '</span>' : ''; ?></div>
						<div class="schedule-time"><?php echo esc_html( aberdeen_piano_row( $ap_event, 'time' ) ); ?></div>
					</article>
					<?php endforeach; ?>
				</div>
				<p class="calendar-note"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_note', 'calendar.note' ) ); ?></p>
			</div>
		</section>

		<?php // Contact. ?>
		<?php
		$ap_form_fields = aberdeen_piano_rows( 'ap_contact_form_fields', 'contact.form_fields' );
		$ap_success     = aberdeen_piano_field( 'ap_contact_success_text', 'contact.success_text' );
		?>
		<section id="contact">
			<div class="wrap contact-grid">
				<div class="reveal">
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_contact_heading', 'contact.heading' ); ?></h2>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_contact_description', 'contact.description' ) ); ?></p>
					<div class="contact-list">
						<a href="mailto:<?php echo esc_attr( $ap_email ); ?>"><?php echo esc_html( $ap_email ); ?></a>
						<address><?php echo wp_kses( implode( '<br>', array_map( 'esc_html', aberdeen_piano_address() ) ), array( 'br' => array() ) ); ?></address>
					</div>
				</div>
				<form class="contact-card reveal" data-ap-form="contact" method="post" novalidate>
					<?php aberdeen_piano_form_hidden_fields( 'contact' ); ?>
					<?php
					// Consecutive half-width fields share a .field-row; full-width fields stand alone.
					$ap_total = count( $ap_form_fields );
					$ap_i     = 0;

					while ( $ap_i < $ap_total ) :
						$ap_field = $ap_form_fields[ $ap_i ];
						$ap_pair  = array( $ap_field );

						if ( aberdeen_piano_row_bool( $ap_field, 'half' ) && isset( $ap_form_fields[ $ap_i + 1 ] ) && aberdeen_piano_row_bool( $ap_form_fields[ $ap_i + 1 ], 'half' ) ) {
							$ap_pair[] = $ap_form_fields[ $ap_i + 1 ];
						}

						$ap_grouped = count( $ap_pair ) > 1;

						if ( $ap_grouped ) {
							echo '<div class="field-row">';
						}

						foreach ( $ap_pair as $ap_input ) :
							$ap_name     = sanitize_key( aberdeen_piano_row( $ap_input, 'name' ) );
							$ap_type     = aberdeen_piano_row( $ap_input, 'type', 'text' );
							$ap_required = aberdeen_piano_row_bool( $ap_input, 'required' ) ? ' required' : '';
							?>
						<div class="ap-field"><label for="<?php echo esc_attr( $ap_name ); ?>"><?php echo esc_html( aberdeen_piano_row( $ap_input, 'label' ) ); ?><?php echo $ap_required ? ' <span class="ap-required" aria-hidden="true">*</span>' : ''; ?></label>
							<?php if ( 'textarea' === $ap_type ) : ?>
							<textarea id="<?php echo esc_attr( $ap_name ); ?>" name="<?php echo esc_attr( $ap_name ); ?>" data-validate="textarea"<?php echo esc_attr( $ap_required ); ?>></textarea>
							<?php else : ?>
							<input id="<?php echo esc_attr( $ap_name ); ?>" name="<?php echo esc_attr( $ap_name ); ?>" type="<?php echo esc_attr( $ap_type ); ?>" data-validate="<?php echo esc_attr( aberdeen_piano_validation_rule( $ap_name, $ap_type ) ); ?>" autocomplete="<?php echo esc_attr( aberdeen_piano_autocomplete( $ap_name, $ap_type ) ); ?>"<?php echo esc_attr( $ap_required ); ?>>
							<?php endif; ?>
						</div>
							<?php
						endforeach;

						if ( $ap_grouped ) {
							echo '</div>';
						}

						$ap_i += count( $ap_pair );
					endwhile;
					?>
					<div class="form_btn_logo">
						<div>
							<button class="btn" type="submit"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_submit_label', 'contact.submit_label' ) ); ?></button>
							<p class="form-message" data-form-message role="status" aria-live="polite" hidden></p>
						</div>
						<div>
							<a class="form_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( aberdeen_piano_default( 'contact.logo_label' ) ); ?>">
								<img class="brand-logo" src="<?php echo esc_url( aberdeen_piano_logo_url() ); ?>" alt="<?php echo esc_attr( aberdeen_piano_brand_name() ); ?>">
							</a>
						</div>
					</div>
				</form>
			</div>
		</section>

	</main>
<?php
get_footer();

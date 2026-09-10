<?php
/**
 * Template Name: Contact Us
 *
 * The Contact Us page: the studio's contact information, a contact form and a
 * map of the studio location.
 *
 * The form posts through the theme's existing AJAX lead handler under the
 * "contact_page" key, so it shares the validation rules, honeypot, rate limit,
 * lead storage and notification e-mails with the home page and newsletter
 * forms. Its fields are editable in ACF; inc/forms.php derives the server-side
 * spec from the same rows, so the two can never drift apart.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image  = aberdeen_piano_field( 'ap_contact_page_hero_image', 'page_contact.hero.image' );
$ap_form_fields = aberdeen_piano_rows( 'ap_contact_page_form_fields', 'page_contact.form.fields' );
$ap_map_button  = aberdeen_piano_field( 'ap_contact_page_map_button_url', 'page_contact.map.button_url' );
$ap_map_embed   = aberdeen_piano_field( 'ap_contact_page_map_embed_url', 'page_contact.map.embed_url' );
?>
	<main class="contact-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero contact-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_contact.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow contact-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_hero_eyebrow', 'page_contact.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_contact_page_hero_title', 'page_contact.hero.title' ); ?></h1>
				<p class="contact-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_hero_lead', 'page_contact.hero.lead' ) ); ?></p>
			</div>
		</section>

		<?php // Contact methods. ?>
		<?php $ap_methods = aberdeen_piano_rows( 'ap_contact_page_methods', 'page_contact.methods.items' ); ?>
		<?php if ( $ap_methods ) : ?>
		<section class="contact-methods" aria-label="<?php esc_attr_e( 'Contact details', 'aberdeen-piano' ); ?>">
			<div class="wrap contact-methods-grid">
				<?php
				foreach ( $ap_methods as $ap_method ) :
					$ap_url = aberdeen_piano_page_url( aberdeen_piano_row( $ap_method, 'url' ) );
					$ap_tag = $ap_url ? 'a' : 'div';
					?>
				<<?php echo esc_html( $ap_tag ); ?> class="contact-method reveal"<?php echo $ap_url ? ' href="' . esc_url( $ap_url ) . '"' : ''; ?>>
					<span class="method-label"><?php echo esc_html( aberdeen_piano_row( $ap_method, 'label' ) ); ?></span>
					<strong><?php echo esc_html( aberdeen_piano_row( $ap_method, 'value' ) ); ?></strong>
					<em><?php echo esc_html( aberdeen_piano_row( $ap_method, 'hint' ) ); ?></em>
				</<?php echo esc_html( $ap_tag ); ?>>
				<?php endforeach; ?>
			</div>
		</section>
		<?php endif; ?>

		<?php // Contact information and form. ?>
		<section class="contact-main" id="contact-form">
			<div class="wrap contact-grid contact-main-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_details_eyebrow', 'page_contact.details.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_contact_page_details_heading', 'page_contact.details.heading' ); ?></h2>
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_details_lead', 'page_contact.details.lead' ) ); ?></p>

					<?php $ap_details = aberdeen_piano_rows( 'ap_contact_page_details_items', 'page_contact.details.items' ); ?>
					<?php if ( $ap_details ) : ?>
					<dl class="contact-details">
						<?php
						foreach ( $ap_details as $ap_detail ) :
							$ap_url   = aberdeen_piano_page_url( aberdeen_piano_row( $ap_detail, 'url' ) );
							$ap_value = aberdeen_piano_row( $ap_detail, 'value' );
							?>
						<div>
							<dt><?php echo esc_html( aberdeen_piano_row( $ap_detail, 'label' ) ); ?></dt>
							<dd>
								<?php if ( $ap_url ) : ?>
								<a href="<?php echo esc_url( $ap_url ); ?>"><?php echo esc_html( $ap_value ); ?></a>
								<?php else : ?>
								<address><?php aberdeen_piano_the_lines( $ap_value ); ?></address>
								<?php endif; ?>
							</dd>
						</div>
						<?php endforeach; ?>
					</dl>
					<?php endif; ?>

					<?php $ap_details_note = aberdeen_piano_field( 'ap_contact_page_details_note', 'page_contact.details.note' ); ?>
					<?php if ( $ap_details_note ) : ?>
					<p class="contact-aside"><?php echo esc_html( $ap_details_note ); ?></p>
					<?php endif; ?>
				</div>

				<form class="contact-form reveal" data-ap-form="contact_page" method="post" novalidate>
					<?php aberdeen_piano_form_hidden_fields( 'contact_page' ); ?>

					<div class="contact-form-head">
						<div>
							<span class="method-label"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_form_label', 'page_contact.form.label' ) ); ?></span>
							<h3><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_form_heading', 'page_contact.form.heading' ) ); ?></h3>
							<p class="contact-form-note"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_form_note', 'page_contact.form.note' ) ); ?></p>
						</div>
						<img class="contact-form-mark" src="<?php echo esc_url( aberdeen_piano_logo_url() ); ?>" alt="<?php echo esc_attr( aberdeen_piano_brand_name() ); ?>" aria-hidden="true">
					</div>

					<div class="contact-fields">
						<?php
						// Consecutive half-width fields share a .field-row; full-width
						// fields stand alone. Same pairing rule as the home page form.
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
								$ap_name        = sanitize_key( aberdeen_piano_row( $ap_input, 'name' ) );
								$ap_type        = aberdeen_piano_row( $ap_input, 'type', 'text' );
								$ap_placeholder = aberdeen_piano_row( $ap_input, 'placeholder', '' );
								$ap_required    = aberdeen_piano_row_bool( $ap_input, 'required' );
								$ap_id          = 'contact-' . $ap_name;

								if ( ! $ap_name ) {
									continue;
								}
								?>
							<div class="ap-field">
								<label for="<?php echo esc_attr( $ap_id ); ?>"><?php echo esc_html( aberdeen_piano_row( $ap_input, 'label' ) ); ?><?php echo $ap_required ? ' <span class="ap-required" aria-hidden="true">*</span>' : ''; ?></label>
								<?php if ( 'textarea' === $ap_type ) : ?>
								<textarea id="<?php echo esc_attr( $ap_id ); ?>" name="<?php echo esc_attr( $ap_name ); ?>" rows="6" data-validate="<?php echo esc_attr( aberdeen_piano_validation_rule( $ap_name, $ap_type ) ); ?>" placeholder="<?php echo esc_attr( $ap_placeholder ); ?>"<?php echo $ap_required ? ' required' : ''; ?>></textarea>
								<?php else : ?>
								<input id="<?php echo esc_attr( $ap_id ); ?>" name="<?php echo esc_attr( $ap_name ); ?>" type="<?php echo esc_attr( $ap_type ); ?>" data-validate="<?php echo esc_attr( aberdeen_piano_validation_rule( $ap_name, $ap_type ) ); ?>" autocomplete="<?php echo esc_attr( aberdeen_piano_autocomplete( $ap_name, $ap_type ) ); ?>" placeholder="<?php echo esc_attr( $ap_placeholder ); ?>"<?php echo $ap_required ? ' required' : ''; ?>>
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
					</div>

					<div class="contact-form-foot">
						<button class="btn" type="submit"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_form_submit_label', 'page_contact.form.submit_label' ) ); ?></button>
					</div>
					<p class="form-message" data-form-message role="status" aria-live="polite" hidden></p>
				</form>
			</div>
		</section>

		<?php // Map. ?>
		<section class="contact-map" id="map">
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_map_eyebrow', 'page_contact.map.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_contact_page_map_heading', 'page_contact.map.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_map_intro', 'page_contact.map.intro' ) ); ?></p>
				</div>

				<div class="map-panel reveal">
					<div class="map-aside">
						<span class="method-label"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_map_address_label', 'page_contact.map.address_label' ) ); ?></span>
						<address><?php aberdeen_piano_the_lines( aberdeen_piano_field( 'ap_contact_page_map_address', 'page_contact.map.address' ) ); ?></address>
						<?php $ap_map_facts = aberdeen_piano_rows( 'ap_contact_page_map_facts', 'page_contact.map.facts' ); ?>
						<?php if ( $ap_map_facts ) : ?>
						<dl class="map-facts">
							<?php
							foreach ( $ap_map_facts as $ap_fact ) :
								$ap_fact_url = aberdeen_piano_page_url( aberdeen_piano_row( $ap_fact, 'url' ) );
								?>
							<div>
								<dt><?php echo esc_html( aberdeen_piano_row( $ap_fact, 'label' ) ); ?></dt>
								<dd>
									<?php if ( $ap_fact_url ) : ?>
									<a href="<?php echo esc_url( $ap_fact_url ); ?>"><?php echo esc_html( aberdeen_piano_row( $ap_fact, 'value' ) ); ?></a>
									<?php else : ?>
									<?php echo esc_html( aberdeen_piano_row( $ap_fact, 'value' ) ); ?>
									<?php endif; ?>
								</dd>
							</div>
							<?php endforeach; ?>
						</dl>
						<?php endif; ?>
						<?php if ( $ap_map_button ) : ?>
						<a class="btn" href="<?php echo esc_url( $ap_map_button ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( aberdeen_piano_field( 'ap_contact_page_map_button_label', 'page_contact.map.button_label' ) ); ?></a>
						<?php endif; ?>
						<?php $ap_map_note = aberdeen_piano_field( 'ap_contact_page_map_note', 'page_contact.map.note' ); ?>
						<?php if ( $ap_map_note ) : ?>
						<p class="map-note"><?php echo esc_html( $ap_map_note ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( $ap_map_embed ) : ?>
					<div class="map-embed">
						<iframe
							title="<?php echo esc_attr( sprintf( /* translators: %s: studio name. */ __( 'Map of the %s studio location', 'aberdeen-piano' ), aberdeen_piano_brand_name() ) ); ?>"
							src="<?php echo esc_url( $ap_map_embed ); ?>"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							allowfullscreen></iframe>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

	</main>
<?php
get_footer();

<?php
/**
 * Newsletter block, shared by the Journal listing and single articles.
 *
 * @package Aberdeen_Piano
 */

$ap_newsletter = aberdeen_piano_default( "journal.newsletter" );
?>
	<section class="newsletter" id="subscribe">
		<div class="wrap news-grid">
			<div class="reveal">
				<div class="eyebrow"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_newsletter_eyebrow', 'journal.newsletter.eyebrow' ) ); ?></div>
				<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_newsletter_heading', 'journal.newsletter.heading' ); ?></h2>
				<p><?php echo esc_html( aberdeen_piano_journal_field( 'ap_newsletter_description', 'journal.newsletter.description' ) ); ?></p>
			</div>
			<form class="news-form reveal" data-ap-form="subscribe" method="post" novalidate>
				<?php aberdeen_piano_form_hidden_fields( 'subscribe' ); ?>
				<div class="ap-field">
					<label for="news-name"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_newsletter_name_label', 'journal.newsletter.name_label' ) ); ?> <span class="ap-required" aria-hidden="true">*</span></label>
					<input id="news-name" name="name" data-validate="name" autocomplete="name" placeholder="<?php echo esc_attr( $ap_newsletter['name_placeholder'] ); ?>" required>
				</div>
				<div class="ap-field">
					<label for="news-email"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_newsletter_email_label', 'journal.newsletter.email_label' ) ); ?> <span class="ap-required" aria-hidden="true">*</span></label>
					<input id="news-email" name="email" type="email" data-validate="email" autocomplete="email" placeholder="<?php echo esc_attr( $ap_newsletter['email_placeholder'] ); ?>" required>
				</div>
				<div class="form_btn_logo">
					<div>
						<button class="btn" type="submit"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_newsletter_submit_label', 'journal.newsletter.submit_label' ) ); ?></button>
						<p class="form-message" data-form-message role="status" aria-live="polite" hidden></p>
						<p class="news-note"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_newsletter_note', 'journal.newsletter.note' ) ); ?></p>
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

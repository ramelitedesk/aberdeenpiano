<?php
/**
 * Template Name: Services
 *
 * The Services page: student piano lessons (PK–12) with tuition, cancellation
 * and payment detail, adult piano lessons and the ABRSM adult program, music
 * theory instruction, and performance preparation.
 *
 * Every string and image comes from ACF through the aberdeen_piano_field() /
 * _rows() helpers, which fall back to inc/defaults-pages.php so the page always
 * matches the approved design.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image   = aberdeen_piano_field( 'ap_services_hero_image', 'page_services.hero.image' );
$ap_tuition_link = aberdeen_piano_page_url( aberdeen_piano_field( 'ap_services_tuition_button_url', 'page_services.student.card.button_url' ) );
?>
	<main class="services-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero services-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_services.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow services-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_services_hero_eyebrow', 'page_services.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_services_hero_title', 'page_services.hero.title' ); ?></h1>
				<p class="services-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_services_hero_lead', 'page_services.hero.lead' ) ); ?></p>
			</div>
		</section>

		<?php
		// Slim anchor bar to the four services — one line each, no ragged cards.
		get_template_part(
			'template-parts/page/jump-bar',
			null,
			array(
				'items' => aberdeen_piano_rows( 'ap_services_index_items', 'page_services.index.items' ),
				'label' => __( 'Services overview', 'aberdeen-piano' ),
			)
		);
		?>

		<?php // 1 — Student piano lessons (PK–12). ?>
		<section class="service-block student-lessons" id="student-lessons">
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_services_student_eyebrow', 'page_services.student.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_services_student_heading', 'page_services.student.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_services_student_intro', 'page_services.student.intro' ) ); ?></p>
				</div>

				<div class="tuition-grid">
					<div class="tuition-body reveal">
						<h3 class="block-subtitle"><?php echo esc_html( aberdeen_piano_field( 'ap_services_student_subtitle', 'page_services.student.subtitle' ) ); ?></h3>
						<ul class="includes-list">
							<?php foreach ( aberdeen_piano_rows( 'ap_services_student_includes', 'page_services.student.includes' ) as $ap_include ) : ?>
							<li>
								<strong><?php echo esc_html( aberdeen_piano_row( $ap_include, 'title' ) ); ?></strong>
								<span><?php aberdeen_piano_the_paragraph( $ap_include ); ?></span>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<aside class="tuition-card reveal">
						<span class="label"><?php echo esc_html( aberdeen_piano_field( 'ap_services_tuition_label', 'page_services.student.card.label' ) ); ?></span>
						<div class="tuition-price"><sup><?php echo esc_html( aberdeen_piano_field( 'ap_services_tuition_currency', 'page_services.student.card.currency' ) ); ?></sup><?php echo esc_html( aberdeen_piano_field( 'ap_services_tuition_amount', 'page_services.student.card.amount' ) ); ?><small><?php echo esc_html( aberdeen_piano_period( aberdeen_piano_field( 'ap_services_tuition_period', 'page_services.student.card.period' ) ) ); ?></small></div>
						<p><?php echo esc_html( aberdeen_piano_field( 'ap_services_tuition_note', 'page_services.student.card.note' ) ); ?></p>
						<?php $ap_facts = aberdeen_piano_rows( 'ap_services_tuition_facts', 'page_services.student.card.facts' ); ?>
						<?php if ( $ap_facts ) : ?>
						<dl class="tuition-facts">
							<?php foreach ( $ap_facts as $ap_fact ) : ?>
							<div>
								<dt><?php echo esc_html( aberdeen_piano_row( $ap_fact, 'label' ) ); ?></dt>
								<dd><?php echo esc_html( aberdeen_piano_row( $ap_fact, 'value' ) ); ?></dd>
							</div>
							<?php endforeach; ?>
						</dl>
						<?php endif; ?>
						<?php if ( $ap_tuition_link ) : ?>
						<a class="btn" href="<?php echo esc_url( $ap_tuition_link ); ?>"><?php echo esc_html( aberdeen_piano_field( 'ap_services_tuition_button_label', 'page_services.student.card.button_label' ) ); ?></a>
						<?php endif; ?>
					</aside>
				</div>

				<div class="info-cards">
					<article class="info-card reveal">
						<h3><?php echo esc_html( aberdeen_piano_field( 'ap_services_cancel_title', 'page_services.cancellation.title' ) ); ?></h3>
						<p><?php echo esc_html( aberdeen_piano_field( 'ap_services_cancel_text', 'page_services.cancellation.text' ) ); ?></p>
						<?php
						$ap_cancel_phone = aberdeen_piano_field( 'ap_services_cancel_phone', 'page_services.cancellation.phone' );
						$ap_cancel_email = aberdeen_piano_field( 'ap_services_cancel_email', 'page_services.cancellation.email' );
						?>
						<?php if ( $ap_cancel_phone || $ap_cancel_email ) : ?>
						<div class="info-contact">
							<?php if ( $ap_cancel_phone ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $ap_cancel_phone ) ); ?>">
								<span><?php echo esc_html( aberdeen_piano_field( 'ap_services_cancel_phone_label', 'page_services.cancellation.phone_label' ) ); ?></span>
								<strong><?php echo esc_html( $ap_cancel_phone ); ?></strong>
							</a>
							<?php endif; ?>
							<?php if ( $ap_cancel_email ) : ?>
							<a href="mailto:<?php echo esc_attr( $ap_cancel_email ); ?>">
								<span><?php echo esc_html( aberdeen_piano_field( 'ap_services_cancel_email_label', 'page_services.cancellation.email_label' ) ); ?></span>
								<strong><?php echo esc_html( $ap_cancel_email ); ?></strong>
							</a>
							<?php endif; ?>
						</div>
						<?php endif; ?>
					</article>
					<article class="info-card reveal">
						<h3><?php echo esc_html( aberdeen_piano_field( 'ap_services_payment_title', 'page_services.payment.title' ) ); ?></h3>
						<ul class="tick-list">
							<?php foreach ( aberdeen_piano_rows( 'ap_services_payment_items', 'page_services.payment.items' ) as $ap_item ) : ?>
							<li><?php echo esc_html( aberdeen_piano_row( $ap_item, 'text' ) ); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php $ap_payment_note = aberdeen_piano_field( 'ap_services_payment_note', 'page_services.payment.note' ); ?>
						<?php if ( $ap_payment_note ) : ?>
						<p class="info-note"><?php echo esc_html( $ap_payment_note ); ?></p>
						<?php endif; ?>
					</article>
				</div>
			</div>
		</section>

		<?php // 2 — Adult piano lessons. ?>
		<section class="service-block adult-lessons" id="adult-lessons">
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_services_adult_eyebrow', 'page_services.adult.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_services_adult_heading', 'page_services.adult.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_services_adult_intro', 'page_services.adult.intro' ) ); ?></p>
				</div>

				<div class="tier-grid">
					<?php
					foreach ( aberdeen_piano_rows( 'ap_services_adult_tiers', 'page_services.adult.tiers' ) as $ap_tier ) :
						$ap_tier_url = aberdeen_piano_page_url( aberdeen_piano_row( $ap_tier, 'link_url' ) );
						?>
					<article class="tier<?php echo aberdeen_piano_row_bool( $ap_tier, 'featured' ) ? ' featured' : ''; ?> reveal">
						<span class="label"><?php echo esc_html( aberdeen_piano_row( $ap_tier, 'label' ) ); ?></span>
						<div class="tier-price"><sup><?php echo esc_html( aberdeen_piano_row( $ap_tier, 'currency', '$' ) ); ?></sup><?php echo esc_html( aberdeen_piano_row( $ap_tier, 'amount' ) ); ?><small><?php echo esc_html( aberdeen_piano_period( aberdeen_piano_row( $ap_tier, 'period' ) ) ); ?></small></div>
						<p class="tier-note"><?php echo esc_html( aberdeen_piano_row( $ap_tier, 'note' ) ); ?></p>
						<span class="tier-divider"><?php echo esc_html( aberdeen_piano_row( $ap_tier, 'divider' ) ); ?></span>
						<ul class="tick-list">
							<?php foreach ( (array) aberdeen_piano_row( $ap_tier, 'items', array() ) as $ap_item ) : ?>
							<li><?php echo esc_html( aberdeen_piano_row( $ap_item, 'text' ) ); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php if ( $ap_tier_url ) : ?>
						<a class="tier-link" href="<?php echo esc_url( $ap_tier_url ); ?>"><?php echo esc_html( aberdeen_piano_row( $ap_tier, 'link_label' ) ); ?></a>
						<?php endif; ?>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // 3 — Music theory instruction. ?>
		<section class="service-block music-theory" id="music-theory">
			<div class="wrap journey-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_services_theory_eyebrow', 'page_services.theory.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_services_theory_heading', 'page_services.theory.heading' ); ?></h2>
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_services_theory_lead', 'page_services.theory.lead' ) ); ?></p>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_services_theory_paragraph', 'page_services.theory.paragraph' ) ); ?></p>
				</div>
				<div class="steps reveal">
					<?php foreach ( aberdeen_piano_rows( 'ap_services_theory_steps', 'page_services.theory.steps' ) as $ap_step ) : ?>
					<div class="step">
						<span><?php echo esc_html( aberdeen_piano_row( $ap_step, 'number' ) ); ?></span>
						<div>
							<h3><?php echo esc_html( aberdeen_piano_row( $ap_step, 'title' ) ); ?></h3>
							<p><?php aberdeen_piano_the_paragraph( $ap_step, 'description' ); ?></p>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // 4 — Performance preparation. ?>
		<section class="service-block performance-prep" id="performance">
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_services_performance_eyebrow', 'page_services.performance.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_services_performance_heading', 'page_services.performance.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_services_performance_intro', 'page_services.performance.intro' ) ); ?></p>
				</div>
				<div class="cards performance-cards">
					<?php foreach ( aberdeen_piano_rows( 'ap_services_performance_cards', 'page_services.performance.cards' ) as $ap_card ) : ?>
					<article class="card reveal">
						<span class="num"><?php echo esc_html( aberdeen_piano_row( $ap_card, 'number' ) ); ?></span>
						<h3><?php echo esc_html( aberdeen_piano_row( $ap_card, 'title' ) ); ?></h3>
						<p><?php aberdeen_piano_the_paragraph( $ap_card ); ?></p>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php
		// Closing call to action — the shared studio panel.
		get_template_part(
			'template-parts/page/cta',
			null,
			array(
				'eyebrow' => aberdeen_piano_field( 'ap_services_cta_eyebrow', 'page_services.cta.eyebrow' ),
				'heading' => array( 'ap_services_cta_heading', 'page_services.cta.heading' ),
				'lead'    => aberdeen_piano_field( 'ap_services_cta_lead', 'page_services.cta.lead' ),
				'buttons' => aberdeen_piano_rows( 'ap_services_cta_buttons', 'page_services.cta.buttons' ),
				'meta'    => aberdeen_piano_rows( 'ap_services_cta_meta', 'page_services.cta.meta' ),
			)
		);
		?>

	</main>
<?php
get_footer();

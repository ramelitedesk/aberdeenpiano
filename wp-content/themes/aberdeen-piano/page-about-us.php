<?php
/**
 * Template Name: About Us
 *
 * The About Us page: studio introduction, mission statement, teaching approach,
 * student development philosophy, the instructor profile for Irene K. Yeakel,
 * and an overview of studio policies and activities.
 *
 * Every string and image comes from ACF through the aberdeen_piano_field() /
 * _rows() helpers, which fall back to inc/defaults-pages.php so the page always
 * matches the approved design.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image  = aberdeen_piano_field( 'ap_about_hero_image', 'page_about.hero.image' );
$ap_intro_image = aberdeen_piano_field( 'ap_about_intro_image', 'page_about.intro.image' );
$ap_portrait    = aberdeen_piano_field( 'ap_about_instructor_image', 'page_about.instructor.image' );
?>
	<main class="about-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero about-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_about.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow about-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_about_hero_eyebrow', 'page_about.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_about_hero_title', 'page_about.hero.title' ); ?></h1>
				<p class="about-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_about_hero_lead', 'page_about.hero.lead' ) ); ?></p>
				<?php $ap_stats = aberdeen_piano_rows( 'ap_about_hero_stats', 'page_about.hero.stats' ); ?>
				<?php if ( $ap_stats ) : ?>
				<ul class="about-hero-stats">
					<?php foreach ( $ap_stats as $ap_stat ) : ?>
					<li>
						<strong><?php echo aberdeen_piano_kses_heading( aberdeen_piano_row( $ap_stat, 'value' ) ); // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- escaped by aberdeen_piano_kses_heading(). ?></strong>
						<span><?php echo esc_html( aberdeen_piano_row( $ap_stat, 'label' ) ); ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
		</section>

		<?php // 1 — Studio introduction. ?>
		<section class="about-intro" id="studio-introduction">
			<div class="wrap intro-grid about-intro-grid">
				<div class="about-figure reveal">
					<img src="<?php echo esc_url( aberdeen_piano_image_url( $ap_intro_image, aberdeen_piano_default( 'page_about.intro.image' ) ) ); ?>" alt="<?php echo esc_attr( aberdeen_piano_image_alt( $ap_intro_image, aberdeen_piano_field( 'ap_about_intro_image_alt', 'page_about.intro.image_alt' ) ) ); ?>">
					<span class="about-figure-badge"><em><?php echo esc_html( aberdeen_piano_field( 'ap_about_intro_badge_label', 'page_about.intro.badge_label' ) ); ?></em><?php echo esc_html( aberdeen_piano_field( 'ap_about_intro_badge_value', 'page_about.intro.badge_value' ) ); ?></span>
				</div>
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_about_intro_eyebrow', 'page_about.intro.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_about_intro_heading', 'page_about.intro.heading' ); ?></h2>
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_about_intro_lead', 'page_about.intro.lead' ) ); ?></p>
					<?php foreach ( aberdeen_piano_rows( 'ap_about_intro_paragraphs', 'page_about.intro.paragraphs' ) as $ap_paragraph ) : ?>
					<p><?php aberdeen_piano_the_paragraph( $ap_paragraph ); ?></p>
					<?php endforeach; ?>
					<?php $ap_marks = aberdeen_piano_rows( 'ap_about_intro_marks', 'page_about.intro.marks' ); ?>
					<?php if ( $ap_marks ) : ?>
					<div class="about-intro-marks">
						<?php foreach ( $ap_marks as $ap_mark ) : ?>
						<span><?php echo esc_html( aberdeen_piano_row( $ap_mark, 'text' ) ); ?></span>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php // Mission statement. ?>
		<section class="about-mission depth-scene" id="mission">
			<div class="wrap reveal">
				<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_about_mission_eyebrow', 'page_about.mission.eyebrow' ) ); ?></div>
				<blockquote><?php echo esc_html( aberdeen_piano_field( 'ap_about_mission_quote', 'page_about.mission.quote' ) ); ?></blockquote>
				<cite><?php echo esc_html( aberdeen_piano_field( 'ap_about_mission_cite', 'page_about.mission.cite' ) ); ?></cite>
			</div>
		</section>

		<?php // 1b — Teaching approach. ?>
		<section class="about-approach" id="teaching-approach">
			<div class="wrap">
				<div class="program-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_about_approach_eyebrow', 'page_about.approach.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_about_approach_heading', 'page_about.approach.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_about_approach_intro', 'page_about.approach.intro' ) ); ?></p>
				</div>
				<div class="cards about-approach-cards">
					<?php foreach ( aberdeen_piano_rows( 'ap_about_approach_cards', 'page_about.approach.cards' ) as $ap_card ) : ?>
					<article class="card reveal">
						<span class="num"><?php echo esc_html( aberdeen_piano_row( $ap_card, 'number' ) ); ?></span>
						<h3><?php echo esc_html( aberdeen_piano_row( $ap_card, 'title' ) ); ?></h3>
						<p><?php aberdeen_piano_the_paragraph( $ap_card ); ?></p>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // 1c — Student development philosophy. ?>
		<section class="about-philosophy" id="philosophy">
			<div class="wrap journey-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_about_philosophy_eyebrow', 'page_about.philosophy.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_about_philosophy_heading', 'page_about.philosophy.heading' ); ?></h2>
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_about_philosophy_lead', 'page_about.philosophy.lead' ) ); ?></p>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_about_philosophy_paragraph', 'page_about.philosophy.paragraph' ) ); ?></p>
				</div>
				<div class="steps reveal">
					<?php foreach ( aberdeen_piano_rows( 'ap_about_philosophy_steps', 'page_about.philosophy.steps' ) as $ap_step ) : ?>
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

		<?php // 2 — Instructor profile. ?>
		<section class="about-instructor depth-scene" id="instructor">
			<div class="wrap instructor-grid">
				<div class="instructor-portrait reveal">
					<img src="<?php echo esc_url( aberdeen_piano_image_url( $ap_portrait, aberdeen_piano_default( 'page_about.instructor.image' ) ) ); ?>" alt="<?php echo esc_attr( aberdeen_piano_image_alt( $ap_portrait, aberdeen_piano_field( 'ap_about_instructor_image_alt', 'page_about.instructor.image_alt' ) ) ); ?>">
					<div class="instructor-plate">
						<strong><?php echo esc_html( aberdeen_piano_field( 'ap_about_instructor_plate_name', 'page_about.instructor.plate_name' ) ); ?></strong>
						<span><?php echo esc_html( aberdeen_piano_field( 'ap_about_instructor_plate_role', 'page_about.instructor.plate_role' ) ); ?></span>
					</div>
				</div>
				<div class="instructor-body reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_about_instructor_eyebrow', 'page_about.instructor.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_about_instructor_heading', 'page_about.instructor.heading' ); ?></h2>
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_about_instructor_lead', 'page_about.instructor.lead' ) ); ?></p>
					<div class="instructor-facts">
						<?php foreach ( aberdeen_piano_rows( 'ap_about_instructor_facts', 'page_about.instructor.facts' ) as $ap_fact ) : ?>
						<article>
							<h3><?php echo esc_html( aberdeen_piano_row( $ap_fact, 'title' ) ); ?></h3>
							<p><?php aberdeen_piano_the_paragraph( $ap_fact ); ?></p>
						</article>
						<?php endforeach; ?>
					</div>
					<div class="signature"><?php echo esc_html( aberdeen_piano_field( 'ap_about_instructor_signature', 'page_about.instructor.signature' ) ); ?></div>
					<div class="credentials"><?php echo esc_html( aberdeen_piano_field( 'ap_about_instructor_credentials', 'page_about.instructor.credentials' ) ); ?></div>
				</div>
			</div>
		</section>

		<?php // 3 — Policies and activities. ?>
		<section class="policy-section about-policies" id="policies">
			<div class="wrap policy-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_about_policies_eyebrow', 'page_about.policies.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_about_policies_heading', 'page_about.policies.heading' ); ?></h2>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_about_policies_intro', 'page_about.policies.intro' ) ); ?></p>
					<?php $ap_policy_note = aberdeen_piano_field( 'ap_about_policies_note', 'page_about.policies.note' ); ?>
					<?php if ( $ap_policy_note ) : ?>
					<p class="about-policy-note"><?php echo esc_html( $ap_policy_note ); ?></p>
					<?php endif; ?>
				</div>
				<div class="policy-list reveal">
					<?php foreach ( aberdeen_piano_rows( 'ap_about_policies_items', 'page_about.policies.items' ) as $ap_policy ) : ?>
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

		<?php
		// Closing call to action — the shared studio panel.
		get_template_part(
			'template-parts/page/cta',
			null,
			array(
				'eyebrow' => aberdeen_piano_field( 'ap_about_cta_eyebrow', 'page_about.cta.eyebrow' ),
				'heading' => array( 'ap_about_cta_heading', 'page_about.cta.heading' ),
				'lead'    => aberdeen_piano_field( 'ap_about_cta_lead', 'page_about.cta.lead' ),
				'buttons' => aberdeen_piano_rows( 'ap_about_cta_buttons', 'page_about.cta.buttons' ),
				'meta'    => aberdeen_piano_rows( 'ap_about_cta_meta', 'page_about.cta.meta' ),
			)
		);
		?>

	</main>
<?php
get_footer();

<?php
/**
 * Template Name: Activities & Events
 *
 * The Harford County Piano Teachers Association activities open to studio
 * students, and the student recognition programs that go with them.
 *
 * Every string and image comes from ACF through the aberdeen_piano_field() /
 * _rows() helpers, which fall back to inc/defaults-pages.php so the page always
 * matches the approved design.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image = aberdeen_piano_field( 'ap_activities_hero_image', 'page_activities.hero.image' );
$ap_band_url   = aberdeen_piano_page_url( aberdeen_piano_field( 'ap_activities_band_button_url', 'page_activities.band.button_url' ) );
?>
	<main class="activities-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero activities-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_activities.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow activities-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_activities_hero_eyebrow', 'page_activities.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_activities_hero_title', 'page_activities.hero.title' ); ?></h1>
				<p class="activities-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_activities_hero_lead', 'page_activities.hero.lead' ) ); ?></p>
			</div>
		</section>

		<?php // Intro. ?>
		<section class="activities-intro">
			<div class="wrap activities-intro-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_activities_intro_eyebrow', 'page_activities.intro.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_activities_intro_heading', 'page_activities.intro.heading' ); ?></h2>
				</div>
				<div class="reveal">
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_activities_intro_lead', 'page_activities.intro.lead' ) ); ?></p>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_activities_intro_paragraph', 'page_activities.intro.paragraph' ) ); ?></p>
				</div>
			</div>
		</section>

		<?php // Participation opportunities. ?>
		<section class="activities-list" id="activities">
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_activities_list_eyebrow', 'page_activities.activities.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_activities_list_heading', 'page_activities.activities.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_activities_list_intro', 'page_activities.activities.intro' ) ); ?></p>
				</div>

				<div class="activity-grid">
					<?php
					foreach ( aberdeen_piano_rows( 'ap_activities_list_cards', 'page_activities.activities.cards' ) as $ap_card ) :
						$ap_tag = aberdeen_piano_row( $ap_card, 'tag' );
						?>
					<article class="activity-card reveal">
						<span class="activity-num"><?php echo esc_html( aberdeen_piano_row( $ap_card, 'number' ) ); ?></span>
						<h3><?php echo esc_html( aberdeen_piano_row( $ap_card, 'title' ) ); ?></h3>
						<p><?php aberdeen_piano_the_paragraph( $ap_card ); ?></p>
						<?php if ( $ap_tag ) : ?>
						<span class="activity-tag"><?php echo esc_html( $ap_tag ); ?></span>
						<?php endif; ?>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // Student recognition. ?>
		<section class="recognition depth-scene" id="recognition">
			<div class="wrap">
				<div class="recognition-head reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_activities_recognition_eyebrow', 'page_activities.recognition.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_activities_recognition_heading', 'page_activities.recognition.heading' ); ?></h2>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_activities_recognition_intro', 'page_activities.recognition.intro' ) ); ?></p>
				</div>

				<div class="recognition-grid">
					<?php foreach ( aberdeen_piano_rows( 'ap_activities_recognition_cards', 'page_activities.recognition.cards' ) as $ap_card ) : ?>
					<article class="recognition-card reveal">
						<span class="recognition-mark" aria-hidden="true"><?php echo esc_html( aberdeen_piano_row( $ap_card, 'mark' ) ); ?></span>
						<h3><?php echo esc_html( aberdeen_piano_row( $ap_card, 'title' ) ); ?></h3>
						<p><?php aberdeen_piano_the_paragraph( $ap_card ); ?></p>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // Pointer to the calendar. ?>
		<section class="activities-note">
			<div class="wrap">
				<div class="resource-band reveal">
					<div>
						<span class="method-label"><?php echo esc_html( aberdeen_piano_field( 'ap_activities_band_label', 'page_activities.band.label' ) ); ?></span>
						<h2><?php aberdeen_piano_the_heading( 'ap_activities_band_heading', 'page_activities.band.heading' ); ?></h2>
						<p><?php echo esc_html( aberdeen_piano_field( 'ap_activities_band_text', 'page_activities.band.text' ) ); ?></p>
					</div>
					<?php if ( $ap_band_url ) : ?>
					<a class="btn" href="<?php echo esc_url( $ap_band_url ); ?>"><?php echo esc_html( aberdeen_piano_field( 'ap_activities_band_button_label', 'page_activities.band.button_label' ) ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php
		// Closing panel.
		get_template_part(
			'template-parts/page/cta',
			null,
			array(
				'eyebrow' => aberdeen_piano_field( 'ap_activities_cta_eyebrow', 'page_activities.cta.eyebrow' ),
				'heading' => array( 'ap_activities_cta_heading', 'page_activities.cta.heading' ),
				'lead'    => aberdeen_piano_field( 'ap_activities_cta_lead', 'page_activities.cta.lead' ),
				'buttons' => aberdeen_piano_rows( 'ap_activities_cta_buttons', 'page_activities.cta.buttons' ),
				'meta'    => aberdeen_piano_rows( 'ap_activities_cta_meta', 'page_activities.cta.meta' ),
			)
		);
		?>

	</main>
<?php
get_footer();

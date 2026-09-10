<?php
/**
 * Template Name: Testimonials
 *
 * Parent testimonials, student success stories and performance achievements.
 *
 * Every string and image comes from ACF through the aberdeen_piano_field() /
 * _rows() helpers, which fall back to inc/defaults-pages.php so the page always
 * matches the approved design.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image = aberdeen_piano_field( 'ap_testimonials_hero_image', 'page_testimonials.hero.image' );
$ap_featured   = aberdeen_piano_field( 'ap_testimonials_featured_quote', 'page_testimonials.featured.quote' );
?>
	<main class="testimonials-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero testimonials-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_testimonials.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow testimonials-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_testimonials_hero_eyebrow', 'page_testimonials.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_testimonials_hero_title', 'page_testimonials.hero.title' ); ?></h1>
				<p class="testimonials-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_testimonials_hero_lead', 'page_testimonials.hero.lead' ) ); ?></p>
			</div>
		</section>

		<?php // Featured testimonial. ?>
		<?php if ( $ap_featured ) : ?>
		<section class="featured-quote depth-scene">
			<div class="wrap reveal">
				<span class="quote-mark" aria-hidden="true">&ldquo;</span>
				<blockquote><?php echo esc_html( $ap_featured ); ?></blockquote>
				<cite>
					<strong><?php echo esc_html( aberdeen_piano_field( 'ap_testimonials_featured_name', 'page_testimonials.featured.name' ) ); ?></strong>
					<span><?php echo esc_html( aberdeen_piano_field( 'ap_testimonials_featured_meta', 'page_testimonials.featured.meta' ) ); ?></span>
				</cite>
			</div>
		</section>
		<?php endif; ?>

		<?php // Parent testimonials and student stories. ?>
		<section class="testimonial-wall" id="testimonials">
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_testimonials_wall_eyebrow', 'page_testimonials.wall.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_testimonials_wall_heading', 'page_testimonials.wall.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_testimonials_wall_intro', 'page_testimonials.wall.intro' ) ); ?></p>
				</div>

				<div class="testimonial-grid">
					<?php foreach ( aberdeen_piano_rows( 'ap_testimonials_wall_items', 'page_testimonials.wall.items' ) as $ap_item ) : ?>
					<article class="testimonial-card<?php echo aberdeen_piano_row_bool( $ap_item, 'dark' ) ? ' is-dark' : ''; ?> reveal">
						<span class="testimonial-quote-mark" aria-hidden="true">&ldquo;</span>
						<span class="testimonial-kind"><?php echo esc_html( aberdeen_piano_row( $ap_item, 'kind' ) ); ?></span>
						<blockquote><?php echo esc_html( aberdeen_piano_row( $ap_item, 'quote' ) ); ?></blockquote>
						<footer>
							<span class="testimonial-mono" aria-hidden="true"><?php echo esc_html( aberdeen_piano_row( $ap_item, 'mark' ) ); ?></span>
							<span class="testimonial-who">
								<strong><?php echo esc_html( aberdeen_piano_row( $ap_item, 'name' ) ); ?></strong>
								<span><?php echo esc_html( aberdeen_piano_row( $ap_item, 'meta' ) ); ?></span>
							</span>
						</footer>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // Performance achievements. ?>
		<section class="achievements" id="achievements">
			<div class="wrap">
				<div class="achievements-head reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_testimonials_achievements_eyebrow', 'page_testimonials.achievements.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_testimonials_achievements_heading', 'page_testimonials.achievements.heading' ); ?></h2>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_testimonials_achievements_intro', 'page_testimonials.achievements.intro' ) ); ?></p>
				</div>
				<ul class="achievement-list">
					<?php foreach ( aberdeen_piano_rows( 'ap_testimonials_achievements_items', 'page_testimonials.achievements.items' ) as $ap_item ) : ?>
					<li class="reveal">
						<strong><?php echo esc_html( aberdeen_piano_row( $ap_item, 'title' ) ); ?></strong>
						<p><?php aberdeen_piano_the_paragraph( $ap_item ); ?></p>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>

		<?php
		// Closing panel.
		get_template_part(
			'template-parts/page/cta',
			null,
			array(
				'eyebrow' => aberdeen_piano_field( 'ap_testimonials_cta_eyebrow', 'page_testimonials.cta.eyebrow' ),
				'heading' => array( 'ap_testimonials_cta_heading', 'page_testimonials.cta.heading' ),
				'lead'    => aberdeen_piano_field( 'ap_testimonials_cta_lead', 'page_testimonials.cta.lead' ),
				'buttons' => aberdeen_piano_rows( 'ap_testimonials_cta_buttons', 'page_testimonials.cta.buttons' ),
				'meta'    => aberdeen_piano_rows( 'ap_testimonials_cta_meta', 'page_testimonials.cta.meta' ),
			)
		);
		?>

	</main>
<?php
get_footer();

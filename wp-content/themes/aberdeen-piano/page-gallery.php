<?php
/**
 * Template Name: Gallery
 *
 * Photo galleries for recitals, festivals, student activities and studio
 * events, with category filtering and a lightbox.
 *
 * The filter bar runs on the generic filter in assets/js/theme.js, which hides
 * tiles by data-kind; the lightbox is bound to the [data-lightbox] grid. Each
 * photograph names the key of one of the categories below.
 *
 * Every string and image comes from ACF through the aberdeen_piano_field() /
 * _rows() helpers, which fall back to inc/defaults-pages.php so the page always
 * matches the approved design.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image = aberdeen_piano_field( 'ap_gallery_hero_image', 'page_gallery.hero.image' );
$ap_categories = aberdeen_piano_rows( 'ap_gallery_categories', 'page_gallery.gallery.categories' );
$ap_photos     = aberdeen_piano_rows( 'ap_gallery_photos', 'page_gallery.gallery.photos' );
$ap_note       = aberdeen_piano_field( 'ap_gallery_note', 'page_gallery.gallery.note' );

// key => label, so a tile can print the name of its own category.
$ap_labels = array();

foreach ( $ap_categories as $ap_category ) {
	$ap_key = sanitize_key( aberdeen_piano_row( $ap_category, 'key' ) );

	if ( $ap_key ) {
		$ap_labels[ $ap_key ] = aberdeen_piano_row( $ap_category, 'label' );
	}
}
?>
	<main class="gallery-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero gallery-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_gallery.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow gallery-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_gallery_hero_eyebrow', 'page_gallery.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_gallery_hero_title', 'page_gallery.hero.title' ); ?></h1>
				<p class="gallery-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_gallery_hero_lead', 'page_gallery.hero.lead' ) ); ?></p>
			</div>
		</section>

		<?php // Gallery. ?>
		<section class="gallery" id="gallery" data-filter-scope>
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_gallery_eyebrow', 'page_gallery.gallery.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_gallery_heading', 'page_gallery.gallery.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_gallery_intro', 'page_gallery.gallery.intro' ) ); ?></p>
				</div>

				<?php if ( $ap_labels ) : ?>
				<div class="filter-bar reveal" role="group" aria-label="<?php esc_attr_e( 'Filter photographs', 'aberdeen-piano' ); ?>">
					<button type="button" class="filter active" data-filter="all" aria-pressed="true"><?php echo esc_html( aberdeen_piano_field( 'ap_gallery_all_label', 'page_gallery.gallery.all_label' ) ); ?></button>
					<?php foreach ( $ap_labels as $ap_key => $ap_label ) : ?>
					<button type="button" class="filter" data-filter="<?php echo esc_attr( $ap_key ); ?>" aria-pressed="false"><?php echo esc_html( $ap_label ); ?></button>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<div class="gallery-grid" data-lightbox>
					<?php
					foreach ( $ap_photos as $ap_photo ) :
						$ap_image = aberdeen_piano_row( $ap_photo, 'image' );
						$ap_thumb = aberdeen_piano_image_url( $ap_image, aberdeen_piano_row( $ap_photo, 'image' ), 'large' );
						$ap_full  = aberdeen_piano_image_url( $ap_image, aberdeen_piano_row( $ap_photo, 'image' ), 'full' );

						if ( ! $ap_thumb ) {
							continue;
						}

						$ap_kind  = sanitize_key( aberdeen_piano_row( $ap_photo, 'kind' ) );
						$ap_title = aberdeen_piano_row( $ap_photo, 'title' );
						$ap_meta  = aberdeen_piano_row( $ap_photo, 'meta' );
						?>
					<figure class="gallery-tile reveal" data-kind="<?php echo esc_attr( $ap_kind ); ?>">
						<?php // A real link, so the photograph is reachable with JavaScript off. ?>
						<a class="gallery-link"
							href="<?php echo esc_url( $ap_full ); ?>"
							data-caption="<?php echo esc_attr( $ap_title ); ?>"
							data-meta="<?php echo esc_attr( $ap_meta ); ?>">
							<img
								src="<?php echo esc_url( $ap_thumb ); ?>"
								alt="<?php echo esc_attr( aberdeen_piano_image_alt( $ap_image, aberdeen_piano_row( $ap_photo, 'alt', $ap_title ) ) ); ?>"
								loading="lazy">
							<span class="gallery-zoom" aria-hidden="true"></span>
						</a>
						<figcaption>
							<strong><?php echo esc_html( $ap_title ); ?></strong>
							<span><?php echo esc_html( $ap_meta ); ?></span>
						</figcaption>
						<?php if ( isset( $ap_labels[ $ap_kind ] ) ) : ?>
						<em class="gallery-tag"><?php echo esc_html( $ap_labels[ $ap_kind ] ); ?></em>
						<?php endif; ?>
					</figure>
					<?php endforeach; ?>
				</div>

				<?php if ( $ap_note ) : ?>
				<p class="gallery-note reveal"><?php echo esc_html( $ap_note ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<?php
		// Closing panel.
		get_template_part(
			'template-parts/page/cta',
			null,
			array(
				'eyebrow' => aberdeen_piano_field( 'ap_gallery_cta_eyebrow', 'page_gallery.cta.eyebrow' ),
				'heading' => array( 'ap_gallery_cta_heading', 'page_gallery.cta.heading' ),
				'lead'    => aberdeen_piano_field( 'ap_gallery_cta_lead', 'page_gallery.cta.lead' ),
				'buttons' => aberdeen_piano_rows( 'ap_gallery_cta_buttons', 'page_gallery.cta.buttons' ),
				'meta'    => aberdeen_piano_rows( 'ap_gallery_cta_meta', 'page_gallery.cta.meta' ),
			)
		);
		?>

	</main>
<?php
get_footer();

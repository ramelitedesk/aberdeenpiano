<?php
/**
 * Template Name: Student Resources
 *
 * The Student Resources page: the Audio-Visual Lab and additional lab access,
 * the Music Library, and the performance opportunities open to studio students
 * — recitals and the preparation classes that come before them.
 *
 * Every string and image comes from ACF through the aberdeen_piano_field() /
 * _rows() helpers, which fall back to inc/defaults-pages.php so the page always
 * matches the approved design.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image    = aberdeen_piano_field( 'ap_student_hero_image', 'page_student.hero.image' );
$ap_lab_image     = aberdeen_piano_field( 'ap_student_lab_image', 'page_student.lab.image' );
$ap_library_image = aberdeen_piano_field( 'ap_student_library_image', 'page_student.library.image' );
$ap_access_url    = aberdeen_piano_page_url( aberdeen_piano_field( 'ap_student_access_button_url', 'page_student.access.button_url' ) );
?>
	<main class="student-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero student-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_student.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow student-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_student_hero_eyebrow', 'page_student.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_student_hero_title', 'page_student.hero.title' ); ?></h1>
				<p class="student-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_student_hero_lead', 'page_student.hero.lead' ) ); ?></p>
			</div>
		</section>

		<?php
		// Anchor bar.
		get_template_part(
			'template-parts/page/jump-bar',
			null,
			array(
				'items' => aberdeen_piano_rows( 'ap_student_index_items', 'page_student.index.items' ),
				'label' => __( 'Student resources overview', 'aberdeen-piano' ),
			)
		);
		?>

		<?php // 1 — Audio-Visual Lab. ?>
		<section class="resource-block av-lab" id="av-lab">
			<div class="wrap">
				<div class="resource-grid">
					<div class="resource-figure reveal">
						<img src="<?php echo esc_url( aberdeen_piano_image_url( $ap_lab_image, aberdeen_piano_default( 'page_student.lab.image' ) ) ); ?>" alt="<?php echo esc_attr( aberdeen_piano_image_alt( $ap_lab_image, aberdeen_piano_field( 'ap_student_lab_image_alt', 'page_student.lab.image_alt' ) ) ); ?>">
						<?php $ap_lab_tag = aberdeen_piano_field( 'ap_student_lab_tag', 'page_student.lab.tag' ); ?>
						<?php if ( $ap_lab_tag ) : ?>
						<span class="resource-figure-tag"><?php echo esc_html( $ap_lab_tag ); ?></span>
						<?php endif; ?>
					</div>
					<div class="reveal">
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_student_lab_eyebrow', 'page_student.lab.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_student_lab_heading', 'page_student.lab.heading' ); ?></h2>
						<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_student_lab_lead', 'page_student.lab.lead' ) ); ?></p>
						<ul class="feature-list">
							<?php foreach ( aberdeen_piano_rows( 'ap_student_lab_features', 'page_student.lab.features' ) as $ap_feature ) : ?>
							<li>
								<span class="feature-num"><?php echo esc_html( aberdeen_piano_row( $ap_feature, 'number' ) ); ?></span>
								<div>
									<h3><?php echo esc_html( aberdeen_piano_row( $ap_feature, 'title' ) ); ?></h3>
									<p><?php aberdeen_piano_the_paragraph( $ap_feature ); ?></p>
								</div>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<?php // 2 — Additional lab access. ?>
		<section class="resource-block lab-access" id="lab-access">
			<div class="wrap">
				<div class="access-panel reveal">
					<div class="access-intro">
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_student_access_eyebrow', 'page_student.access.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_student_access_heading', 'page_student.access.heading' ); ?></h2>
						<p><?php echo esc_html( aberdeen_piano_field( 'ap_student_access_text', 'page_student.access.text' ) ); ?></p>
						<?php if ( $ap_access_url ) : ?>
						<a class="btn" href="<?php echo esc_url( $ap_access_url ); ?>"><?php echo esc_html( aberdeen_piano_field( 'ap_student_access_button_label', 'page_student.access.button_label' ) ); ?></a>
						<?php endif; ?>
					</div>
					<ul class="access-list">
						<?php foreach ( aberdeen_piano_rows( 'ap_student_access_items', 'page_student.access.items' ) as $ap_item ) : ?>
						<li>
							<span class="access-num"><?php echo esc_html( aberdeen_piano_row( $ap_item, 'letter' ) ); ?></span>
							<h3><?php echo esc_html( aberdeen_piano_row( $ap_item, 'title' ) ); ?></h3>
							<p><?php aberdeen_piano_the_paragraph( $ap_item ); ?></p>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</section>

		<?php // 3 — Music Library. ?>
		<section class="resource-block music-library" id="music-library">
			<div class="wrap">
				<div class="resource-grid reversed">
					<div class="reveal">
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_student_library_eyebrow', 'page_student.library.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_student_library_heading', 'page_student.library.heading' ); ?></h2>
						<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_student_library_lead', 'page_student.library.lead' ) ); ?></p>
						<ul class="feature-list">
							<?php foreach ( aberdeen_piano_rows( 'ap_student_library_features', 'page_student.library.features' ) as $ap_feature ) : ?>
							<li>
								<span class="feature-num"><?php echo esc_html( aberdeen_piano_row( $ap_feature, 'number' ) ); ?></span>
								<div>
									<h3><?php echo esc_html( aberdeen_piano_row( $ap_feature, 'title' ) ); ?></h3>
									<p><?php aberdeen_piano_the_paragraph( $ap_feature ); ?></p>
								</div>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="resource-figure reveal">
						<img src="<?php echo esc_url( aberdeen_piano_image_url( $ap_library_image, aberdeen_piano_default( 'page_student.library.image' ) ) ); ?>" alt="<?php echo esc_attr( aberdeen_piano_image_alt( $ap_library_image, aberdeen_piano_field( 'ap_student_library_image_alt', 'page_student.library.image_alt' ) ) ); ?>">
						<?php $ap_library_tag = aberdeen_piano_field( 'ap_student_library_tag', 'page_student.library.tag' ); ?>
						<?php if ( $ap_library_tag ) : ?>
						<span class="resource-figure-tag"><?php echo esc_html( $ap_library_tag ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php // 4 — Performance opportunities. ?>
		<section class="resource-block performance-ops" id="performance-opportunities">
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_student_performance_eyebrow', 'page_student.performance.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_student_performance_heading', 'page_student.performance.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_student_performance_intro', 'page_student.performance.intro' ) ); ?></p>
				</div>

				<div class="recital-grid">
					<article class="recital-card reveal">
						<span class="label"><?php echo esc_html( aberdeen_piano_field( 'ap_student_recital_label', 'page_student.performance.recital.label' ) ); ?></span>
						<h3><?php echo esc_html( aberdeen_piano_field( 'ap_student_recital_title', 'page_student.performance.recital.title' ) ); ?></h3>
						<div class="recital-points">
							<?php foreach ( aberdeen_piano_rows( 'ap_student_recital_points', 'page_student.performance.recital.points' ) as $ap_point ) : ?>
							<div>
								<h4><?php echo esc_html( aberdeen_piano_row( $ap_point, 'title' ) ); ?></h4>
								<p><?php aberdeen_piano_the_paragraph( $ap_point ); ?></p>
							</div>
							<?php endforeach; ?>
						</div>
					</article>

					<article class="recital-card featured reveal">
						<span class="label"><?php echo esc_html( aberdeen_piano_field( 'ap_student_prep_label', 'page_student.performance.prep.label' ) ); ?></span>
						<h3><?php echo esc_html( aberdeen_piano_field( 'ap_student_prep_title', 'page_student.performance.prep.title' ) ); ?></h3>
						<p class="recital-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_student_prep_lead', 'page_student.performance.prep.lead' ) ); ?></p>
						<span class="recital-divider"><?php echo esc_html( aberdeen_piano_field( 'ap_student_prep_divider', 'page_student.performance.prep.divider' ) ); ?></span>
						<ul class="tick-list">
							<?php foreach ( aberdeen_piano_rows( 'ap_student_prep_items', 'page_student.performance.prep.items' ) as $ap_item ) : ?>
							<li><?php echo esc_html( aberdeen_piano_row( $ap_item, 'text' ) ); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php $ap_prep_note = aberdeen_piano_field( 'ap_student_prep_note', 'page_student.performance.prep.note' ); ?>
						<?php if ( $ap_prep_note ) : ?>
						<p class="recital-note"><?php echo esc_html( $ap_prep_note ); ?></p>
						<?php endif; ?>
					</article>
				</div>
			</div>
		</section>

		<?php
		// Closing call to action — the shared studio panel.
		get_template_part(
			'template-parts/page/cta',
			null,
			array(
				'eyebrow' => aberdeen_piano_field( 'ap_student_cta_eyebrow', 'page_student.cta.eyebrow' ),
				'heading' => array( 'ap_student_cta_heading', 'page_student.cta.heading' ),
				'lead'    => aberdeen_piano_field( 'ap_student_cta_lead', 'page_student.cta.lead' ),
				'buttons' => aberdeen_piano_rows( 'ap_student_cta_buttons', 'page_student.cta.buttons' ),
				'meta'    => aberdeen_piano_rows( 'ap_student_cta_meta', 'page_student.cta.meta' ),
			)
		);
		?>

	</main>
<?php
get_footer();

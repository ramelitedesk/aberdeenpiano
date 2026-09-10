<?php
/**
 * Single posts and pages.
 *
 * Uses the article markup from the static design (blog-details.html).
 *
 * @package Aberdeen_Piano
 */

get_header();

while ( have_posts() ) :
	the_post();

	$aberdeen_terms = get_the_category();
	?>
	<main>
		<section class="article-hero">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<a href="<?php echo esc_url( aberdeen_piano_journal_url() ); ?>"><?php esc_html_e( 'Journal', 'aberdeen-piano' ); ?></a>
					<span class="current"><?php the_title(); ?></span>
				</nav>
				<?php if ( is_single() && $aberdeen_terms ) : ?>
				<span class="chip"><?php echo esc_html( $aberdeen_terms[0]->name ); ?></span>
				<?php endif; ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( is_single() ) : ?>
				<div class="hero-meta">
					<span class="by"><?php the_author(); ?></span>
					<span class="date"><?php echo esc_html( get_the_date() ); ?></span>
					<span><?php echo esc_html( aberdeen_piano_reading_time() ); ?></span>
				</div>
				<?php endif; ?>
			</div>
		</section>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
			<div class="wrap article-grid">
				<div class="prose reveal">
					<?php
					if ( has_post_thumbnail() ) {
						echo '<div class="figure">' . get_the_post_thumbnail( null, 'full', array( 'alt' => aberdeen_piano_thumbnail_alt() ) ) . '</div>';
					}

					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">',
							'after'  => '</div>',
						)
					);
					?>

					<?php if ( is_single() ) : ?>
					<div class="article-foot">
						<?php the_tags( '<div class="tag-row">', '', '</div>' ); ?>
					</div>
					<?php endif; ?>
				</div>

				<?php if ( is_active_sidebar( 'journal-sidebar' ) ) : ?>
				<aside class="sidebar reveal"><?php dynamic_sidebar( 'journal-sidebar' ); ?></aside>
				<?php endif; ?>
			</div>
		</article>

		<?php
		if ( comments_open() || get_comments_number() ) {
			?>
		<section class="related">
			<div class="wrap"><?php comments_template(); ?></div>
		</section>
			<?php
		}
		?>
	</main>
	<?php
endwhile;

get_footer();

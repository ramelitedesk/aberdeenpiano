<?php
/**
 * Generic page template.
 *
 * Used for any page an editor creates — Terms, Privacy, About, a policy page —
 * so new pages inherit the site's look without needing a bespoke template. It
 * reuses the article hero and prose column from design/blog-details.html, minus
 * the post furniture (category, byline, share, related), and renders whatever
 * the content editor holds.
 *
 * The front page and the posts page are handled by front-page.php and home.php,
 * so this never runs for them.
 *
 * @package Aberdeen_Piano
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Ancestors give child pages a real breadcrumb trail.
	$ap_ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
	$ap_subtitle  = has_excerpt() ? get_the_excerpt() : '';
	?>
	<main>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article' ); ?>>

			<section class="article-hero" id="top">
				<div class="wrap article-hero-content">
					<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a><?php
					foreach ( $ap_ancestors as $ap_ancestor ) {
						printf(
							'<span>/</span><a href="%s">%s</a>',
							esc_url( get_permalink( $ap_ancestor ) ),
							esc_html( get_the_title( $ap_ancestor ) )
						);
					}
					?><span>/</span><span class="current"><?php the_title(); ?></span></nav>
					<h1><?php the_title(); ?></h1>
					<?php if ( $ap_subtitle ) : ?>
					<p><?php echo esc_html( $ap_subtitle ); ?></p>
					<?php endif; ?>
				</div>
			</section>

			<section class="article">
				<div class="wrap page-grid">
					<div class="prose reveal">
						<?php if ( post_password_required() ) : ?>
						<?php echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- core markup. ?>
						<?php else : ?>

							<?php if ( has_post_thumbnail() ) : ?>
							<figure class="figure"><?php the_post_thumbnail( 'full', array( 'alt' => aberdeen_piano_thumbnail_alt() ) ); ?></figure>
							<?php endif; ?>

							<?php
							the_content();

							wp_link_pages(
								array(
									'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page sections', 'aberdeen-piano' ) . '">',
									'after'    => '</nav>',
									'nextpagelink' => __( 'Next', 'aberdeen-piano' ),
									'previouspagelink' => __( 'Previous', 'aberdeen-piano' ),
								)
							);
							?>

							<?php if ( get_the_modified_time( 'U' ) ) : ?>
							<p class="page-updated">
								<?php
								printf(
									/* translators: %s: last updated date. */
									esc_html__( 'Last updated %s', 'aberdeen-piano' ),
									esc_html( get_the_modified_date() )
								);
								?>
							</p>
							<?php endif; ?>

						<?php endif; ?>
					</div>
				</div>
			</section>
		</article>
	</main>
	<?php
endwhile;

get_footer();

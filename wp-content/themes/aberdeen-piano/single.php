<?php
/**
 * Single article.
 *
 * Mirrors design/blog-details.html: article hero, marquee, prose column with
 * tags/share/author, sidebar (contents, keep reading, CTA), related posts and
 * the newsletter block.
 *
 * @package Aberdeen_Piano
 */

get_header();

while ( have_posts() ) :
	the_post();

	$ap_category  = aberdeen_piano_post_category();
	$ap_content   = aberdeen_piano_prepare_content( apply_filters( 'the_content', get_the_content() ) );
	$ap_marquee   = aberdeen_piano_rows( 'ap_article_marquee', '' );
	$ap_marquee   = $ap_marquee ? $ap_marquee : aberdeen_piano_rows( 'ap_journal_marquee', 'journal.marquee', aberdeen_piano_journal_page_id() );
	$ap_keep      = aberdeen_piano_keep_reading( 3 );
	$ap_related   = aberdeen_piano_related_posts( 3 );
	$ap_avatar    = aberdeen_piano_author_avatar( 300 );
	$ap_author_id = (int) get_the_author_meta( 'ID' );
	?>
	<main>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php // Article hero. ?>
			<section class="article-hero" id="top">
				<div class="wrap article-hero-content">
					<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a><span>/</span><a href="<?php echo esc_url( aberdeen_piano_journal_url() ); ?>"><?php esc_html_e( 'Journal', 'aberdeen-piano' ); ?></a><?php if ( $ap_category ) : ?><span>/</span><a href="<?php echo esc_url( get_category_link( $ap_category->term_id ) ); ?>"><?php echo esc_html( $ap_category->name ); ?></a><?php endif; ?></nav>
					<h1><?php the_title(); ?></h1>
					<div class="hero-meta">
						<?php if ( $ap_category ) : ?>
						<span class="chip"><?php echo esc_html( $ap_category->name ); ?></span>
						<?php endif; ?>
						<?php if ( get_the_author() ) : ?>
						<span class="by"><?php if ( $ap_avatar ) : ?><img src="<?php echo esc_url( $ap_avatar ); ?>" alt="<?php echo esc_attr( sprintf( /* translators: %s: author name. */ __( 'Portrait of %s', 'aberdeen-piano' ), get_the_author() ) ); ?>"><?php endif; ?><?php
						printf(
							/* translators: %s: author name. */
							esc_html__( 'By %s', 'aberdeen-piano' ),
							esc_html( get_the_author() )
						);
						?></span>
						<?php endif; ?>
						<span><?php echo esc_html( get_the_date() ); ?></span>
						<span>&middot; <?php echo esc_html( aberdeen_piano_reading_time() ); ?></span>
					</div>
				</div>
			</section>

			<?php // Marquee. ?>
			<?php if ( $ap_marquee ) : ?>
			<div class="marquee" aria-hidden="true">
				<div class="marquee-track"><span>
					<?php
					for ( $ap_loop = 0; $ap_loop < 2; $ap_loop++ ) {
						foreach ( $ap_marquee as $ap_item ) {
							echo esc_html( aberdeen_piano_row( $ap_item, 'text' ) ) . ' <b>&#9834;</b> ';
						}
					}
					?>
				</span></div>
			</div>
			<?php endif; ?>

			<?php // Body and sidebar. ?>
			<section class="article">
				<div class="wrap article-grid">
					<div class="prose reveal">
						<?php
						// Already passed through the_content filters (and KSES for users
						// without unfiltered_html); re-filtering here would strip embeds.
						echo $ap_content['html']; // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped
						?>

						<div class="article-foot">
							<div class="tag-row">
								<?php
								$ap_tags = get_the_tags();

								if ( $ap_tags ) {
									foreach ( $ap_tags as $ap_tag ) {
										printf(
											'<a class="chip" href="%s">%s</a>',
											esc_url( get_tag_link( $ap_tag->term_id ) ),
											esc_html( $ap_tag->name )
										);
									}
								} elseif ( $ap_category ) {
									printf(
										'<a class="chip" href="%s">%s</a>',
										esc_url( get_category_link( $ap_category->term_id ) ),
										esc_html( $ap_category->name )
									);
								}
								?>
							</div>
							<div class="share"><span><?php echo esc_html( aberdeen_piano_journal_field( 'ap_article_share_label', 'article.share_label' ) ); ?></span><?php
							foreach ( aberdeen_piano_share_links() as $ap_share ) {
								printf(
									'<a href="%1$s" aria-label="%2$s" title="%2$s"%3$s>%4$s</a>',
									esc_url( $ap_share['url'] ),
									esc_attr( $ap_share['title'] ),
									$ap_share['attrs'], // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- fixed attribute strings.
									wp_kses( $ap_share['icon'], array() )
								);
							}
							?></div>
						</div>

						<?php $ap_bio = get_the_author_meta( 'description' ); $ap_bio = $ap_bio ? $ap_bio : aberdeen_piano_option( 'ap_author_bio', 'article.author_bio' ); ?>
						<?php if ( $ap_bio ) : ?>
						<div class="author">
							<?php if ( $ap_avatar ) : ?>
							<img src="<?php echo esc_url( $ap_avatar ); ?>" alt="<?php echo esc_attr( sprintf( /* translators: %s: author name. */ __( 'Portrait of %s', 'aberdeen-piano' ), get_the_author() ) ); ?>">
							<?php endif; ?>
							<div>
								<div class="role"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_article_author_role', 'article.author_role' ) ); ?></div>
								<h4><?php echo esc_html( get_the_author() ); ?></h4>
								<p><?php echo esc_html( $ap_bio ); ?></p>
							</div>
						</div>
						<?php endif; ?>
					</div>

					<aside class="sidebar reveal">
						<?php if ( $ap_content['toc'] ) : ?>
						<div class="widget">
							<h4><?php echo esc_html( aberdeen_piano_journal_field( 'ap_article_toc_title', 'article.toc_title' ) ); ?></h4>
							<nav class="toc">
								<?php foreach ( $ap_content['toc'] as $ap_heading ) : ?>
								<a href="#<?php echo esc_attr( $ap_heading['id'] ); ?>"><?php echo esc_html( $ap_heading['text'] ); ?></a>
								<?php endforeach; ?>
							</nav>
						</div>
						<?php endif; ?>

						<?php if ( $ap_keep ) : ?>
						<div class="widget">
							<h4><?php echo esc_html( aberdeen_piano_journal_field( 'ap_article_keep_title', 'article.keep_title' ) ); ?></h4>
							<?php foreach ( $ap_keep as $ap_item ) : ?>
							<a class="mini-post" href="<?php echo esc_url( get_permalink( $ap_item ) ); ?>">
								<?php
								if ( has_post_thumbnail( $ap_item ) ) {
									echo get_the_post_thumbnail( $ap_item, 'thumbnail', array( 'alt' => aberdeen_piano_thumbnail_alt( $ap_item ) ) );
								} else {
									printf( '<img src="%s" alt="%s">', esc_url( aberdeen_piano_img( 'aberdeen.png' ) ), esc_attr( get_the_title( $ap_item ) ) );
								}
								?>
								<div>
									<div class="m-date"><?php echo esc_html( get_the_date( '', $ap_item ) ); ?></div>
									<h5><?php echo esc_html( get_the_title( $ap_item ) ); ?></h5>
								</div>
							</a>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>

						<div class="widget widget-cta">
							<h4><?php echo esc_html( aberdeen_piano_journal_field( 'ap_article_cta_title', 'article.cta_title' ) ); ?></h4>
							<p><?php echo esc_html( aberdeen_piano_journal_field( 'ap_article_cta_text', 'article.cta_text' ) ); ?></p>
							<?php
							$ap_cta = aberdeen_piano_link(
								aberdeen_piano_journal_field( 'ap_article_cta_link', '' ),
								array(
									'title' => aberdeen_piano_default( 'article.cta_label' ),
									'url'   => home_url( '/#contact' ),
								)
							);
							?>
							<a class="btn gold" href="<?php echo esc_url( $ap_cta['url'] ); ?>"<?php echo $ap_cta['target'] ? ' target="' . esc_attr( $ap_cta['target'] ) . '" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $ap_cta['title'] ); ?></a>
						</div>
					</aside>
				</div>
			</section>
		</article>

		<?php // Related. ?>
		<?php if ( $ap_related ) : ?>
		<section class="related" id="related">
			<div class="wrap">
				<div class="related-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_article_related_eyebrow', 'article.related_eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_article_related_heading', 'article.related_heading' ); ?></h2>
					</div>
					<a href="<?php echo esc_url( aberdeen_piano_journal_url() ); ?>"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_article_related_link', 'article.related_link' ) ); ?></a>
				</div>

				<div class="post-grid">
					<?php
					global $post;
					$ap_stash = $post;

					foreach ( $ap_related as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- restored below.
						setup_postdata( $post );
						get_template_part( 'template-parts/journal/post-card' );
					endforeach;

					$post = $ap_stash; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- restoring.
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/journal/newsletter' ); ?>
	</main>
	<?php
endwhile;

get_footer();

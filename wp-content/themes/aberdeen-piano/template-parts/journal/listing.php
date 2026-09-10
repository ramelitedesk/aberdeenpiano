<?php
/**
 * The Journal listing — hero, marquee, category filters, post grid, pagination,
 * quote and newsletter. Shared by home.php, index.php, archive.php, category.php
 * and search.php so every listing view is identical.
 *
 * @package Aberdeen_Piano
 */

$ap_marquee    = aberdeen_piano_rows( 'ap_journal_marquee', 'journal.marquee', aberdeen_piano_journal_page_id() );
$ap_filters    = aberdeen_piano_journal_filters();
$ap_newsletter = aberdeen_piano_default( 'journal.newsletter' );
?>
	<main>

		<?php // Hero. ?>
		<section class="blog-hero" id="top">
			<div class="wrap blog-hero-content">
				<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_journal_eyebrow', 'journal.eyebrow' ) ); ?></div>
				<h1><?php echo aberdeen_piano_journal_title(); // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- escaped in aberdeen_piano_journal_title(). ?></h1>
				<?php if ( is_category() && category_description() ) : ?>
				<?php echo wp_kses_post( category_description() ); ?>
				<?php else : ?>
				<p><?php echo esc_html( aberdeen_piano_field( 'ap_journal_description', 'journal.description' ) ); ?></p>
				<?php endif; ?>
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

		<?php // Posts. ?>
		<section class="journal" id="articles">
			<div class="wrap">
				<div class="journal-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_journal_head_eyebrow', 'journal.head_eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_journal_head_heading', 'journal.head_heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_journal_head_intro', 'journal.head_intro' ) ); ?></p>
				</div>

				<?php if ( count( $ap_filters ) > 1 ) : ?>
				<div class="post-filters reveal" data-journal-filters aria-label="<?php echo esc_attr( aberdeen_piano_field( 'ap_journal_filters_label', 'journal.filters_label' ) ); ?>">
					<?php foreach ( $ap_filters as $ap_filter ) : ?>
					<a class="filter<?php echo $ap_filter['active'] ? ' active' : ''; ?>" href="<?php echo esc_url( $ap_filter['url'] ); ?>" data-filter="<?php echo esc_attr( $ap_filter['slug'] ); ?>"<?php echo $ap_filter['active'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $ap_filter['label'] ); ?></a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<div class="journal-posts" id="journal-posts" data-journal-posts aria-live="polite" aria-busy="false">
					<?php get_template_part( 'template-parts/journal/posts' ); ?>
				</div>
			</div>
		</section>

		<?php // Quote. ?>
		<section class="quote">
			<div class="wrap reveal">
				<blockquote><?php echo esc_html( aberdeen_piano_field( 'ap_journal_quote_text', 'journal.quote.text' ) ); ?></blockquote>
				<cite><?php echo esc_html( aberdeen_piano_field( 'ap_journal_quote_cite', 'journal.quote.cite' ) ); ?></cite>
			</div>
		</section>

		<?php get_template_part( "template-parts/journal/newsletter" ); ?>

	</main>

<?php
/**
 * The Journal post grid, empty state and pagination.
 *
 * Rendered both on first page load and by the AJAX endpoint, so the filtered
 * markup is always produced by the same code. The AJAX handler swaps the global
 * $wp_query before including this, which is why the standard loop is used here
 * rather than a query passed in.
 *
 * @package Aberdeen_Piano
 */

if ( have_posts() ) :
	?>
	<div class="post-grid">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/journal/post-card' );
		endwhile;
		?>
	</div>

	<?php aberdeen_piano_pagination(); ?>
	<?php
else :
	?>
	<p class="journal-empty"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_journal_empty_text', 'journal.empty_text' ) ); ?></p>
	<?php
endif;

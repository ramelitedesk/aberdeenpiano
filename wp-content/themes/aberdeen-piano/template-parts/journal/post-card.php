<?php
/**
 * One Journal post card. Mirrors the .post-card markup in design/blog.html.
 *
 * @package Aberdeen_Piano
 */

$ap_category = aberdeen_piano_post_category();
$ap_kind     = $ap_category ? $ap_category->slug : 'uncategorised';
$ap_chip     = $ap_category ? $ap_category->name : __( 'Journal', 'aberdeen-piano' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card reveal' ); ?> data-kind="<?php echo esc_attr( $ap_kind ); ?>">
	<div class="post-thumb"><span class="chip"><?php echo esc_html( $ap_chip ); ?></span>
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'large',
				array(
					'alt'     => aberdeen_piano_thumbnail_alt(),
					'loading' => 'lazy',
				)
			);
		} else {
			printf( '<img src="%s" alt="%s" loading="lazy">', esc_url( aberdeen_piano_img( 'aberdeen.png' ) ), esc_attr( aberdeen_piano_thumbnail_alt() ) );
		}
		?>
	</div>
	<div class="post-body">
		<span class="date"><?php echo esc_html( get_the_date() ); ?></span>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<div class="post-foot"><span><?php echo esc_html( aberdeen_piano_reading_time() ); ?></span><a href="<?php the_permalink(); ?>"><?php echo esc_html( aberdeen_piano_journal_field( 'ap_journal_read_more', 'journal.read_more' ) ); ?></a></div>
	</div>
</article>

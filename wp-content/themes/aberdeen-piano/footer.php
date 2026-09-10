<?php
/**
 * Theme footer.
 *
 * @package Aberdeen_Piano
 */

?>
	<footer>
		<div class="wrap footer-inner">
			<p>
			<?php
			// The brand name in the credit links home. The format string is
			// escaped as before; the link is built from escaped parts and
			// substituted in as the second placeholder.
			$ap_footer_brand = sprintf(
				'<a class="footer-brand" href="%s" rel="home">%s</a>',
				esc_url( home_url( '/' ) ),
				esc_html( aberdeen_piano_brand_name() )
			);

			printf(
				esc_html( aberdeen_piano_option( 'ap_footer_credit', 'global.footer_credit' ) ),
				esc_html( gmdate( 'Y' ) ),
				$ap_footer_brand // phpcs:ignore WordPress.Security.EscapingOutput.OutputNotEscaped -- built from esc_url() and esc_html() above.
			);
			?>
			</p>
			<?php aberdeen_piano_nav_menu( 'footer' ); ?>
		</div>
	</footer>
<?php wp_footer(); ?>
</body>

</html>

<?php
/**
 * The Journal listing (posts page).
 *
 * All listing views share template-parts/journal/listing.php so the design is
 * identical whether the visitor is on the posts page, a category filter, an
 * archive or a search result.
 *
 * @package Aberdeen_Piano
 */

get_header();
get_template_part( 'template-parts/journal/listing' );
get_footer();

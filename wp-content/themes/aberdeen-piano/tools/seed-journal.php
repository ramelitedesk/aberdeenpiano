<?php
/**
 * Seed the Journal with the demo posts from design/blog.html.
 *
 * Run once from the command line to populate a fresh install so the listing,
 * category filters and pagination have something to show:
 *
 *     php wp-content/themes/aberdeen-piano/tools/seed-journal.php
 *
 * Safe to re-run: posts are matched by slug and skipped if they already exist.
 * Pass --remove to delete everything it created.
 *
 * This is a development helper, not part of the theme's runtime.
 *
 * @package Aberdeen_Piano
 */

if ( 'cli' !== PHP_SAPI ) {
	exit( 'This script is CLI only.' );
}

$_SERVER['SERVER_NAME'] = 'localhost';
define( 'WP_USE_THEMES', false );

require dirname( __DIR__, 4 ) . '/wp-load.php';

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$remove = in_array( '--remove', $argv, true );

/*
 * Posts created from the command line have no current user, so an author must
 * be set explicitly — otherwise post_author is 0 and the byline renders empty.
 */
$ap_admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'orderby' => 'ID' ) );
$ap_author = $ap_admins ? (int) $ap_admins[0]->ID : 1;

/**
 * The demo posts. Copy and imagery come from design/blog.html.
 */
$posts = array(
	array(
		'slug'     => 'five-minute-habit',
		'title'    => 'The five-minute habit that changes everything',
		'category' => 'Practice',
		'excerpt'  => 'The students who progress fastest are rarely the ones who practise longest — they are the ones who practise the smallest, most consistent way.',
		'image'    => 'photo-1552422535-c45813c61732',
		'days'     => 1,
		'body'     => array(
			'The students who progress fastest are rarely the ones who practise the longest. They are the ones who practise the smallest, most consistent way.',
			'Here is the short daily ritual we build with every new student, and why five focused minutes beats an hour of distracted repetition.',
			'Start at the bench before the instrument is even open. Decide on one passage, one goal and one tempo. Anything more and the session becomes a performance rather than a rehearsal.',
		),
	),
	array(
		'slug'     => 'reading-rhythm',
		'title'    => 'Reading rhythm without counting out loud',
		'category' => 'Theory',
		'excerpt'  => 'A simple way to feel the pulse of a piece so the counting becomes second nature instead of a chore.',
		'image'    => 'photo-1465847899084-d164df4dedc6',
		'days'     => 6,
		'body'     => array(
			'Counting out loud is a scaffold, not a destination. It is useful while a rhythm is unfamiliar, and quietly limiting once it is not.',
			'Instead of counting, we teach students to feel the pulse in the body first — tapping, walking, conducting — and only then to name it.',
		),
	),
	array(
		'slug'     => 'recital-nerves',
		'title'    => 'Turning recital nerves into readiness',
		'category' => 'Recitals',
		'excerpt'  => 'What our semester preparation classes actually do — and three things students can try the week before.',
		'image'    => 'photo-1571974599782-87624638275e',
		'days'     => 12,
		'body'     => array(
			'Nerves are not the opposite of readiness. They are what readiness feels like from the inside when the performance matters.',
			'Our preparation classes exist to make the recital itself unremarkable: by the time a student walks on stage they have already played the piece for an audience three times.',
		),
	),
	array(
		'slug'     => 'practice-without-power-struggle',
		'title'    => 'How to support practice without the power struggle',
		'category' => 'For parents',
		'excerpt'  => 'A gentle home routine that keeps the piano a place of curiosity rather than conflict.',
		'image'    => 'photo-1520523839897-bd0b52f945a0',
		'days'     => 19,
		'body'     => array(
			'The most common question from parents is some version of: how much should I push?',
			'The answer is usually to change the shape of the routine rather than its intensity. A predictable time, a short session and a genuine audience at the end do more than any amount of reminding.',
		),
	),
	array(
		'slug'     => 'slow-practice',
		'title'    => 'Slow practice is the fastest practice',
		'category' => 'Practice',
		'excerpt'  => 'Why playing a passage at half speed almost always gets you to performance tempo sooner.',
		'image'    => 'photo-1551033406-611cf9a28f67',
		'days'     => 26,
		'body'     => array(
			'Playing slowly is not the same as playing carefully, and it is certainly not the same as playing timidly.',
			'Slow practice means giving every note enough time to be heard, judged and adjusted before the next one arrives. Speed is what happens when that judgement becomes automatic.',
		),
	),
	array(
		'slug'     => 'scales-are-a-map',
		'title'    => 'Scales aren’t punishment—they’re a map',
		'category' => 'Theory',
		'excerpt'  => 'A fresh way to think about scales that turns the daily warm-up into a tour of the whole keyboard.',
		'image'    => 'photo-1507838153414-b4b713384a76',
		'days'     => 33,
		'body'     => array(
			'Almost every student arrives believing scales are a tax paid before the real music begins.',
			'Reframed as a map of the keyboard — where the hand naturally falls, which keys share a shape — scales become the fastest route into new repertoire rather than a detour around it.',
		),
	),
	array(
		'slug'     => 'choosing-recital-piece',
		'title'    => 'Choosing the right recital piece',
		'category' => 'Recitals',
		'excerpt'  => 'How we match repertoire to a student’s level and personality so the stage feels like a celebration.',
		'image'    => 'photo-1514320291840-2e0a9bf2a9ae',
		'days'     => 40,
		'body'     => array(
			'A recital piece has two jobs: to be within reach, and to be worth reaching for.',
			'We choose repertoire a student can play securely at eighty per cent of their ability, so that nerves have somewhere to go without threatening the performance.',
		),
	),
	array(
		'slug'     => 'listening-week',
		'title'    => 'Building a listening habit, one week at a time',
		'category' => 'Theory',
		'excerpt'  => 'Students who listen widely learn repertoire faster. Here is how the studio listening lab works.',
		'image'    => 'photo-1511379938547-c1f69419868d',
		'days'     => 47,
		'body'     => array(
			'The listening lab is the least glamorous part of the studio and quite possibly the most useful.',
			'Hearing a piece performed well, several times, by different players, does more for interpretation than any amount of instruction.',
		),
	),
	array(
		'slug'     => 'first-lesson-expect',
		'title'    => 'What to expect from a first lesson',
		'category' => 'For parents',
		'excerpt'  => 'A relaxed introduction, a little playing, and a conversation about where a student would like to go.',
		'image'    => 'photo-1520166012956-add9ba0835cb',
		'days'     => 54,
		'body'     => array(
			'A first lesson is mostly a conversation. We talk about what the student has played, what they have heard, and what drew them to the piano.',
			'There is always some playing, but no assessment in the formal sense — the point is to find a starting place, not to grade one.',
		),
	),
	array(
		'slug'     => 'abrsm-journey',
		'title'    => 'Inside an ABRSM year',
		'category' => 'Recitals',
		'excerpt'  => 'What the graded examination path asks of a student, a parent and a teacher over twelve months.',
		'image'    => 'photo-1493225457124-a3eb161ffa5f',
		'days'     => 61,
		'body'     => array(
			'The ABRSM path is rigorous by design, and it is not the right fit for every student — which is exactly why it requires consent from student, parent and teacher alike.',
			'For those who choose it, the year has a rhythm: repertoire in autumn, technique through winter, and polish in the weeks before the examination.',
		),
	),
	array(
		'slug'     => 'sight-reading-five-minutes',
		'title'    => 'Five minutes of sight-reading, every day',
		'category' => 'Practice',
		'excerpt'  => 'The single habit that most reliably separates confident readers from hesitant ones.',
		'image'    => 'photo-1457972729786-0411a3b2b626',
		'days'     => 68,
		'body'     => array(
			'Sight-reading improves with exposure far more than with instruction.',
			'Five minutes of unfamiliar music a day, played straight through without stopping to correct, builds fluency faster than an hour of careful decoding once a week.',
		),
	),
	array(
		'slug'     => 'why-adults-return',
		'title'    => 'Why adults come back to the piano',
		'category' => 'Practice',
		'excerpt'  => 'Adult beginners and returning players bring something younger students cannot: they know exactly why they are here.',
		'image'    => 'photo-1552422535-c45813c61732',
		'days'     => 75,
		'body'     => array(
			'Adult students often apologise for starting late. They rarely need to.',
			'What they bring instead is intent. They know which music they want to play and why it matters to them, and that clarity is worth a great deal of technique.',
		),
	),
);

/**
 * Delete everything this script created.
 */
if ( $remove ) {
	$removed = 0;

	foreach ( $posts as $data ) {
		$existing = get_page_by_path( $data['slug'], OBJECT, 'post' );

		if ( ! $existing ) {
			continue;
		}

		$thumb = get_post_thumbnail_id( $existing->ID );

		if ( $thumb ) {
			wp_delete_attachment( $thumb, true );
		}

		wp_delete_post( $existing->ID, true );
		$removed++;
	}

	echo "Removed {$removed} posts.\n";

	foreach ( array( 'Practice', 'Theory', 'Recitals', 'For parents' ) as $name ) {
		$term = get_term_by( 'name', $name, 'category' );

		if ( $term && 0 === (int) $term->count ) {
			wp_delete_term( $term->term_id, 'category' );
			echo "Removed empty category: {$name}\n";
		}
	}

	exit;
}

/**
 * Sideload an image into the media library and return its attachment id.
 *
 * media_sideload_image() cannot be used directly here: it validates the file
 * extension in the URL, and the Unsplash URLs used by the design end in a query
 * string rather than .jpg. So the file is fetched to a temporary path with a
 * real extension first.
 *
 * @param string $url     Remote image URL.
 * @param int    $post_id Post to attach to.
 * @param string $alt     Alt text / title.
 * @return int|WP_Error
 */
function aberdeen_piano_seed_image( $url, $post_id, $alt ) {
	$temp = download_url( $url, 30 );

	if ( is_wp_error( $temp ) ) {
		return $temp;
	}

	$type      = wp_getimagesize( $temp );
	$extension = ( $type && 'image/png' === $type['mime'] ) ? 'png' : 'jpg';
	$filename  = sanitize_title( $alt ) . '.' . $extension;

	$file = array(
		'name'     => $filename,
		'tmp_name' => $temp,
	);

	$attachment_id = media_handle_sideload( $file, $post_id, $alt );

	if ( is_wp_error( $attachment_id ) ) {
		if ( file_exists( $temp ) ) {
			wp_delete_file( $temp );
		}

		return $attachment_id;
	}

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );

	return $attachment_id;
}

/**
 * Get or create a category.
 *
 * @param string $name Category name.
 * @return int
 */
function aberdeen_piano_seed_category( $name ) {
	$term = get_term_by( 'name', $name, 'category' );

	if ( $term ) {
		return (int) $term->term_id;
	}

	$created = wp_insert_term( $name, 'category' );

	return is_wp_error( $created ) ? 0 : (int) $created['term_id'];
}

$created = 0;
$skipped = 0;
$images  = 0;

foreach ( $posts as $data ) {
	$existing = get_page_by_path( $data['slug'], OBJECT, 'post' );

	if ( $existing ) {
		$skipped++;

		// Backfill a featured image if the post is missing one.
		if ( ! has_post_thumbnail( $existing->ID ) ) {
			$url        = 'https://images.unsplash.com/' . $data['image'] . '?auto=format&fit=crop&w=1200&q=85';
			$attachment = aberdeen_piano_seed_image( $url, $existing->ID, $data['title'] );

			if ( is_wp_error( $attachment ) ) {
				echo "  image failed for {$data['slug']}: " . $attachment->get_error_message() . "\n";
			} else {
				set_post_thumbnail( $existing->ID, $attachment );
				$images++;
				echo "  image added: {$data['slug']}\n";
			}
		}

		continue;
	}

	$category = aberdeen_piano_seed_category( $data['category'] );

	$post_id = wp_insert_post(
		array(
			'post_title'    => $data['title'],
			'post_name'     => $data['slug'],
			'post_content'  => '<p>' . implode( "</p>\n\n<p>", $data['body'] ) . '</p>',
			'post_excerpt'  => $data['excerpt'],
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_author'   => $ap_author,
			'post_category' => $category ? array( $category ) : array(),
			'post_date'     => gmdate( 'Y-m-d H:i:s', strtotime( '-' . $data['days'] . ' days' ) ),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		echo "FAILED {$data['slug']}: " . $post_id->get_error_message() . "\n";
		continue;
	}

	$created++;

	// Featured image, sideloaded from the same Unsplash photos used in the design.
	$url        = 'https://images.unsplash.com/' . $data['image'] . '?auto=format&fit=crop&w=1200&q=85';
	$attachment = aberdeen_piano_seed_image( $url, $post_id, $data['title'] );

	if ( is_wp_error( $attachment ) ) {
		echo "  image failed for {$data['slug']}: " . $attachment->get_error_message() . "\n";
	} else {
		set_post_thumbnail( $post_id, $attachment );
		$images++;
	}

	echo "Created: {$data['title']}  [{$data['category']}]\n";
}

printf( "\nCreated %d posts (%d skipped), %d featured images.\n", $created, $skipped, $images );
printf( "Categories: %s\n", implode( ', ', wp_list_pluck( get_categories( array( 'hide_empty' => true ) ), 'name' ) ) );

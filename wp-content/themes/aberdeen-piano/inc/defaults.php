<?php
/**
 * Default front page content.
 *
 * Every string rendered by front-page.php lives here. ACF values override these
 * at run time; when a field is empty — or ACF is not active — the theme falls
 * back to this array, so the page always matches the approved design.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The full default content tree.
 *
 * @return array
 */
function aberdeen_piano_defaults() {
	static $defaults = null;

	if ( null !== $defaults ) {
		return $defaults;
	}

	$defaults = array(

		'global' => array(
			'topline_text'   => 'Aberdeen Piano Instruction · Aberdeen, Maryland',
			'topline_label'  => 'Email Irene',
			'email'          => 'irenekyeakel@gmail.com',
			'address'        => "619 Burkley Avenue\nAberdeen, Maryland 21001",
			'brand_name'     => 'Aberdeen Piano',
			'footer_credit'  => '© %1$s %2$s · All rights reserved',
			'loader_mark'    => 'Aberdeen Piano Aberdeen Piano',
			'loader_message' => 'Composing your experience…',
			'loader_label'    => 'Loading Aberdeen Piano',
			'loader_duration' => 3000,
		),

		'hero' => array(
			'eyebrow'     => 'Piano lessons & Musicianship',
			'title_white' => 'Play',
			'title_black' => 'with',
			'title_em'    => 'purpose.',
			'description' => 'Thoughtful, one-to-one piano Aberdeen instruction that builds musicianship, confidence, and a joyful appreciation of music—for children, teens, college students, and adults.',
			'buttons'     => array(),
		),

		'marquee' => array(
			'items' => array(
				array( 'text' => 'Private lessons' ),
				array( 'text' => 'Music theory' ),
				array( 'text' => 'Listening lab' ),
				array( 'text' => 'Recitals' ),
				array( 'text' => 'ABRSM preparation' ),
				array( 'text' => 'All ages' ),
			),
		),

		'about' => array(
			'image'       => 'https://images.unsplash.com/photo-1552422535-c45813c61732?auto=format&fit=crop&w=1100&q=85',
			'image_alt'   => 'Hands playing a grand piano',
			'eyebrow'     => 'Meet your teacher',
			'heading'     => 'Music is learned<br>one <em>beautiful detail</em><br>at a time.',
			'paragraphs'  => array(
				array( 'text' => 'With a B.A. in Piano Performance and more than 45 years of teaching experience, Irene K. Yeakel offers a patient, individual pathway from first notes to advanced preparation.' ),
				array( 'text' => 'Lessons are tailored to the whole musician: technique, theory, listening, music history, performance, and the freedom to find your own musical voice.' ),
				array( 'text' => 'Irene enjoys meeting prospective students and their families. E-mail to schedule a visit to her studio for a relaxed introduction to curriculum possibilities.' ),
			),
			'button'      => array(
				'title' => 'Email',
				'url'   => 'mailto:irenekyeakel@gmail.com',
			),
			'signature'   => 'Irene K. Yeakel',
			'credentials' => 'B.A., Piano Performance · Member, HCPTA',
		),

		'quote' => array(
			'text' => '“The aim is not simply to play the notes, but to understand the music—and discover the pleasure of making it your own.”',
			'cite' => 'Aberdeen Piano',
		),

		'programs' => array(
			'heading' => '<em> Life-long Joy and Appreciation of Music </em>',
			'lead'    => 'The piano awaits you … begin your journey!',
			'intro'   => 'Each student’s study is personal, but no one learns in isolation. Listening, theory, performance, and musical community all belong here.',
			'cards'   => array(
				array(
					'number'     => '01',
					'title'      => 'Young Musicians',
					// One row, two lines: the design runs these into a single <p> split by a <br>.
					'paragraphs' => array(
						array( 'text' => "Weekly private lessons for PK-12 students, with theory, scheduled audio-visual lab and music library time, plus studio recitals.\nAccess to County-wide recitals and activities through Harford County Piano Teachers Association (HCPTA)." ),
					),
				),
				array(
					'number'     => '02',
					'title'      => 'Adult Students',
					'paragraphs' => array(
						array( 'text' => 'Welcoming, flexible 30-60 minute lessons for adult beginners, returning players, and musicians ready for a new challenge.' ),
					),
				),
				array(
					'number'     => '03',
					'title'      => 'Advanced Study',
					'paragraphs' => array(
						array( 'text' => 'Advanced Study is available for students at any age or level of musical development. Pathways like ABRSM, conservatory or university preparation, adjudicated recitals, festivals, and performance coaching are quite rigorous requiring additional commitment of time, focus and travel on the part of the student and parent.' ),
					),
				),
			),
		),

		'studio' => array(
			'eyebrow' => 'Inside the studio',
			'heading' => 'A thoughtful path from <em>lesson to performance.</em>',
			'lead'    => 'Music is personal whether preparing for your own enjoyment or preparing for a public performance. A steady rhythm of learning gives students both confidence and room to grow.',
			'steps'   => array(
				array(
					'number'      => '01',
					'title'       => 'Individual Aberdeen Piano',
					'description' => 'Technique and repertoire selected for each student’s level, goals, and way of learning.',
				),
				array(
					'number'      => '02',
					'title'       => 'Listen & understand',
					'description' => 'Music theory, history, guided listening, and studio library resources deepen the understanding of each piece of repertoire, providing a meaningful musical experience for both performer and audience.',
				),
				array(
					'number'      => '03',
					'title'       => 'Prepare with purpose',
					'description' => 'Semester performance classes turn nerves into readiness before each recital.',
				),
				array(
					'number'      => '04',
					'title'       => 'Share the music',
					'description' => 'Family recitals and optional countywide events create meaningful milestones.',
				),
			),
		),

		'policies' => array(
			'eyebrow' => 'Policies & opportunities',
			'heading' => 'Structure that lets <em>music flourish.</em>',
			'intro'   => 'Studio expectations are simple, supportive, and designed around steady progress.',
			'items'   => array(
				array(
					'title'      => 'Lessons & make-ups',
					'open'       => true,
					'paragraphs' => array(
						array( 'text' => 'Please notify Irene as soon as possible about student illness or cancellation of lesson. Eligible cancelled lessons may be rescheduled on the dedicated make-up dates listed in the calendar, by appointment.' ),
						array( 'text' => 'A flexible lesson schedule is available during July and August to accommodate vacation time for students and teacher.' ),
					),
				),
				array(
					'title'      => 'Audio-Visual Lab & Music Library',
					'open'       => false,
					'paragraphs' => array(
						array( 'text' => 'The lab and library are scheduled for students K through 12 before or after a student’s lesson. Older students working on school music projects may arrange additional use of the studio resources.' ),
						array( 'text' => 'The Lab and resources are available to adult students for an additional fee.' ),
					),
				),
				array(
					'title'      => 'Recitals & preparation classes',
					'open'       => false,
					'paragraphs' => array(
						array( 'text' => 'Recitals and Prep classes are usually held early January and mid-June. The dates are provided at the beginning of the school year and are on the studio Calendar.' ),
					),
				),
				array(
					'title'      => 'ABRSM accreditation',
					'open'       => false,
					'paragraphs' => array(
						array( 'text' => 'Students may opt into this rigorous international program with parent, student, and teacher consent. It requires additional practice, study, examination travel, and focused preparation.' ),
						array( 'text' => 'ABRSM program requires weekly lessons of at least 1 hour.' ),
					),
				),
				array(
					'title'      => 'HCPTA activities',
					'open'       => false,
					'paragraphs' => array(
						array( 'text' => 'PK through High-School students may be invited to adjudicated and non-adjudicated countywide recitals, festivals, concerts, lectures, and joint recitals through the Harford County Piano Teachers Association.' ),
					),
				),
			),
		),

		'pricing' => array(
			'eyebrow' => 'Tuition',
			'heading' => 'Music Lessons that build skill, <em>confidence, and joy</em>',
			'intro'   => 'Piano lessons open the door to a lifetime of musical enjoyment while building confidence, focus, creativity and new skills. Students also gain cognitive benefits, personal discipline, and the joy of connecting with others through music.',
			'note'    => 'Student monthly tuition is due at the first lesson of each month by cash or check, payable to Irene K. Yeakel.',
			'cards'   => array(
				array(
					'label'     => 'PK through Grade 12',
					'currency'  => '$',
					'amount'    => '80',
					'starred'   => true,
					'period'    => '/ month',
					'featured'  => false,
					'features'  => array(
						array( 'text' => 'Weekly 30-minute private lesson and 30-minute AV Lab (with directed activity) in accordance with the yearly Studio Calendar.' ),
						array( 'text' => 'Studio Student recitals, Make-up Lessons, HCPTA activities.' ),
						array( 'text' => 'Student recitals included' ),
						array( 'text' => 'Make up lessons available for cancellations due to student illness' ),
					),
					'footnote'  => '$160/month for PK-12 students choosing the Advanced Study and Exam Preparation programs.',
					'link'      => array(
						'title' => 'Enquire for a student',
						'url'   => '#contact',
					),
					'css_class' => 'one',
				),
				array(
					'label'     => 'ADULT PIANO LESSONS',
					'currency'  => '$',
					'amount'    => '30',
					'starred'   => false,
					'period'    => '/ lesson',
					'featured'  => true,
					'features'  => array(
						array( 'text' => 'Private 30-minute lesson' ),
						array( 'text' => 'Basic music theory included' ),
						array( 'text' => 'Regular or as-needed scheduling' ),
						array( 'text' => 'Recital participation encouraged' ),
					),
					'footnote'  => '',
					'link'      => array(
						'title' => 'Begin or return',
						'url'   => '#contact',
					),
					'css_class' => '',
				),
				array(
					'label'     => 'ADVANCED STUDY & EXAM PREPARATION',
					'currency'  => '$',
					'amount'    => '70',
					'starred'   => false,
					'period'    => '/ hour',
					'featured'  => false,
					'features'  => array(
						array( 'text' => 'ABRSM: The Associated Board of the Royal Schools of Music' ),
						array( 'text' => 'Rigorous examination preparation' ),
						array( 'text' => 'Performance coaching' ),
						array( 'text' => 'Parent, student & teacher consent' ),
					),
					'footnote'  => '',
					'link'      => array(
						'title' => 'Discuss ABRSM',
						'url'   => '#contact',
					),
					'css_class' => '',
				),
			),
		),

		'calendar' => array(
			'eyebrow'      => 'Complete 2026-2027 calendar',
			'title_white'  => 'Studio',
			'title_black'  => 'Schedule',
			'note'         => 'Make-up lessons are by appointment. HCPTA dates marked TBD will be shared when available. Summer lessons continue in July and August with flexible scheduling around family and teacher vacations.',
			'filters'      => array(
				array(
					'key'   => 'all',
					'label' => 'All dates',
				),
				array(
					'key'   => 'lesson',
					'label' => 'Lessons',
				),
				array(
					'key'   => 'makeup',
					'label' => 'Make-ups',
				),
				array(
					'key'   => 'performance',
					'label' => 'Performances',
				),
				array(
					'key'   => 'holiday',
					'label' => 'Holidays',
				),
			),
			'filters_label' => 'Filter calendar',
			'events'        => array(
				array( 'kind' => 'holiday', 'date' => 'Sep 01, 2025', 'day' => 'Monday', 'name' => 'Labor Day', 'tag' => 'No lessons', 'time' => 'Holiday' ),
				array( 'kind' => 'lesson', 'date' => 'Sep 02, 2025', 'day' => 'Tuesday', 'name' => 'Lessons & Labs Begin', 'tag' => '', 'time' => 'Assigned time' ),
				array( 'kind' => 'makeup', 'date' => 'Sep 13, 2025', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
				array( 'kind' => 'makeup', 'date' => 'Oct 11, 2025', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
				array( 'kind' => 'performance', 'date' => 'Nov · TBD', 'day' => 'Sunday', 'name' => 'HCPTA Student Recital', 'tag' => 'By invitation', 'time' => 'Afternoon TBD' ),
				array( 'kind' => 'makeup', 'date' => 'Nov 08, 2025', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
				array( 'kind' => 'holiday', 'date' => 'Nov 24–28', 'day' => 'Mon–Fri', 'name' => 'Thanksgiving Break', 'tag' => 'No lessons', 'time' => 'Holiday' ),
				array( 'kind' => 'makeup', 'date' => 'Dec 13, 2025', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
				array( 'kind' => 'performance', 'date' => 'Dec · TBD', 'day' => 'Saturday', 'name' => 'HCPTA Student Holiday Concert', 'tag' => 'By invitation', 'time' => 'TBD' ),
				array( 'kind' => 'holiday', 'date' => 'Dec 22–Jan 01', 'day' => 'Holiday', 'name' => 'Winter Celebrations', 'tag' => 'No lessons', 'time' => 'Studio closed' ),
				array( 'kind' => 'lesson', 'date' => 'Jan 06, 2026', 'day' => 'Monday', 'name' => 'Lessons Resume', 'tag' => '', 'time' => 'Assigned time' ),
				array( 'kind' => 'performance', 'date' => 'Jan 10, 2026', 'day' => 'Saturday', 'name' => 'Recital Preparation Class', 'tag' => '', 'time' => '10:00–11:00 AM' ),
				array( 'kind' => 'makeup', 'date' => 'Jan 10, 2026', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30–9:45 / 11–Noon' ),
				array( 'kind' => 'performance', 'date' => 'Jan 11, 2026', 'day' => 'Sunday', 'name' => 'Student Recital', 'tag' => 'Grove Presbyterian', 'time' => '5:00 PM' ),
				array( 'kind' => 'makeup', 'date' => 'Feb 14, 2026', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
				array( 'kind' => 'holiday', 'date' => 'Mar 08–13', 'day' => 'Mon–Fri', 'name' => 'Scheduled Break', 'tag' => 'No lessons', 'time' => 'Studio closed' ),
				array( 'kind' => 'makeup', 'date' => 'Mar 21, 2026', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
				array( 'kind' => 'holiday', 'date' => 'Mar 30–Apr 03', 'day' => 'Mon–Fri', 'name' => 'Holy Days', 'tag' => 'No lessons', 'time' => 'Studio closed' ),
				array( 'kind' => 'performance', 'date' => 'April · TBD', 'day' => 'Saturday', 'name' => 'HCPTA Annual Piano Festival', 'tag' => 'By invitation', 'time' => 'Approx. 1.5 hrs' ),
				array( 'kind' => 'makeup', 'date' => 'Apr 11, 2026', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
				array( 'kind' => 'makeup', 'date' => 'May 09, 2026', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
				array( 'kind' => 'holiday', 'date' => 'May 25, 2026', 'day' => 'Monday', 'name' => 'Memorial Day', 'tag' => 'No lessons', 'time' => 'Holiday' ),
				array( 'kind' => 'makeup', 'date' => 'Jun 13, 2026', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30–10:00 AM' ),
				array( 'kind' => 'performance', 'date' => 'Jun 13, 2026', 'day' => 'Saturday', 'name' => 'Recital Preparation Class', 'tag' => '', 'time' => '10:00–11:30 AM' ),
				array( 'kind' => 'performance', 'date' => 'Jun 14, 2026', 'day' => 'Sunday', 'name' => 'Student Recital', 'tag' => 'Grove Presbyterian', 'time' => '5:00 PM' ),
				array( 'kind' => 'lesson', 'date' => 'Jun 19, 2026', 'day' => 'Friday', 'name' => 'End of Spring Semester', 'tag' => '', 'time' => 'By appointment' ),
			),
		),

		'contact' => array(
			'heading'      => 'Contact or <br /> Schedule a Meet & Greet',
			'description'  => 'Visit the studio to share past musical experiences and share what the student hopes to discover at the piano. This provides a starting point to develop an individualized path to long lasting joy of music through the heart of the piano.',
			'form_fields'  => array(
				array(
					'name'     => 'name',
					'label'    => 'Your name',
					'type'     => 'text',
					'required' => true,
					'half'     => true,
				),
				array(
					'name'     => 'email',
					'label'    => 'Email address',
					'type'     => 'email',
					'required' => true,
					'half'     => true,
				),
				array(
					'name'     => 'telephone',
					'label'    => 'Telephone',
					'type'     => 'tel',
					'required' => true,
					'half'     => false,
				),
			),
			'submit_label' => 'Send an inquiry',
			'success_text' => 'Thank you — we’ll be in touch',
			'logo_url'     => '',
			'logo_label'   => 'Aberdeen Piano logo',
		),

		'journal' => array(
			'loader_label'    => 'Loading the Aberdeen Piano journal',
			'loader_mark'     => 'Aberdeen Piano Instruction',
			'loader_message'  => 'Turning the pages…',
			'loader_duration' => 2600,

			'document_title' => 'Journal',

			'eyebrow'      => 'The studio journal',
			'title_white'  => 'Notes',
			'title_black'  => 'between',
			'title_em'     => 'the lessons.',
			'description'  => 'Reflections on practice, musicianship, and the small daily details that turn a beginner into a confident performer—shared from the bench at Aberdeen Piano Instruction.',
			'marquee'      => array(
				array( 'text' => 'Practice notes' ),
				array( 'text' => 'Recital diaries' ),
				array( 'text' => 'Theory made simple' ),
				array( 'text' => 'Parent guides' ),
				array( 'text' => 'Listening picks' ),
				array( 'text' => 'ABRSM journeys' ),
			),
			'head_eyebrow' => 'Latest from the bench',
			'head_heading' => 'Stories, tips & <em>studio life.</em>',
			'head_intro'   => 'A growing collection of short reads for students, parents, and anyone curious about a lifelong friendship with the piano.',
			'filters_all'  => 'All posts',
			'filters_label' => 'Filter articles',
			'read_more'    => 'Read →',
			'empty_text'   => 'No entries have been published yet. Please check back soon.',
			'prev_label'   => 'Newer',
			'next_label'   => 'Older',

			'quote' => array(
				'text' => '“Every great pianist was once a beginner who simply refused to stop being curious.”',
				'cite' => 'Aberdeen Piano Instruction',
			),

			'newsletter' => array(
				'eyebrow'           => 'Keep in tune',
				'heading'           => 'New notes, in your <em>inbox.</em>',
				'description'       => 'A short letter a few times a season—practice tips, recital dates, and the occasional listening pick. No clutter, just music.',
				'name_label'        => 'Your name',
				'name_placeholder'  => 'Jordan Avery',
				'email_label'       => 'Email address',
				'email_placeholder' => 'you@example.com',
				'submit_label'      => 'Subscribe',
				'note'              => 'We respect your inbox—unsubscribe any time.',
				'success_text'      => 'Thank you — you are subscribed',
			),
		),

		'article' => array(
			'share_label'      => 'Share',
			'author_role'      => 'Your instructor',
			'author_bio'       => 'Irene has taught private piano in Aberdeen, Maryland for over two decades, guiding students from their very first notes through recitals, exams, and a lifelong love of music.',
			'toc_title'        => 'On this page',
			'keep_title'       => 'Keep reading',
			'cta_title'        => 'Ready to begin?',
			'cta_text'         => 'Book a relaxed introductory lesson and find the five minutes that fit your week.',
			'cta_label'        => 'Begin lessons',
			'related_eyebrow'  => 'More from the bench',
			'related_heading'  => 'You might also <em>enjoy.</em>',
			'related_link'     => 'All articles →',
		),
	);

	/*
	 * The filtered tree is what gets cached. Returning the filter's result
	 * without assigning it back meant only the first call in a request saw
	 * anything a filter had added — inc/defaults-pages.php merges the interior
	 * page trees this way, so every later lookup fell back to an empty string.
	 *
	 * The static already holds the unfiltered array while the filter runs, so a
	 * callback that reaches back into this function gets the base tree rather
	 * than recursing.
	 */
	$defaults = apply_filters( 'aberdeen_piano_defaults', $defaults );

	return $defaults;
}

/**
 * Read a default using dot notation, e.g. aberdeen_piano_default( 'hero.eyebrow' ).
 *
 * @param string $path     Dot-delimited path into the defaults tree.
 * @param mixed  $fallback Returned when the path does not exist.
 * @return mixed
 */
function aberdeen_piano_default( $path, $fallback = '' ) {
	$value = aberdeen_piano_defaults();

	foreach ( explode( '.', $path ) as $segment ) {
		if ( ! is_array( $value ) || ! array_key_exists( $segment, $value ) ) {
			return $fallback;
		}

		$value = $value[ $segment ];
	}

	return $value;
}

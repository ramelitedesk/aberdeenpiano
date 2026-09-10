<?php
/**
 * Default content for the four interior pages.
 *
 * Merged into aberdeen_piano_defaults() through its filter, so page templates
 * read their fallbacks exactly the way front-page.php does: an ACF value wins,
 * and an empty field falls back to the approved design copy below.
 *
 * @package Aberdeen_Piano
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the interior page trees to the defaults.
 *
 * @param array $defaults Existing defaults.
 * @return array
 */
function aberdeen_piano_page_defaults( $defaults ) {
	return array_merge( $defaults, aberdeen_piano_page_defaults_tree() );
}
add_filter( 'aberdeen_piano_defaults', 'aberdeen_piano_page_defaults' );

/**
 * The interior page defaults.
 *
 * @return array
 */
function aberdeen_piano_page_defaults_tree() {
	$hero_image    = 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=2000&q=88';
	$piano_image   = 'https://images.unsplash.com/photo-1552422535-c45813c61732?auto=format&fit=crop&w=2000&q=88';
	$keys_image    = 'https://images.unsplash.com/photo-1466428996289-fb355538da1b?auto=format&fit=crop&w=2000&q=88';
	$lab_image     = 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=2000&q=88';
	$library_image = 'https://images.unsplash.com/photo-1481886756534-97af88ccb438?auto=format&fit=crop&w=1100&q=85';

	$cta_buttons = array(
		array(
			'label' => 'Contact Us',
			'url'   => '/#contact',
			'style' => '',
		),
		array(
			'label' => 'View Tuition',
			'url'   => '/#pricing',
			'style' => 'cta-ghost',
		),
	);

	$cta_meta = array(
		array(
			'label' => 'Call the studio',
			'value' => '443-243-3319',
			'url'   => 'tel:4432433319',
		),
		array(
			'label' => 'Email',
			'value' => 'irenekyeakel@gmail.com',
			'url'   => 'mailto:irenekyeakel@gmail.com',
		),
	);

	return array(

		/* ---------------------------------------------------------------
		   About Us
		   --------------------------------------------------------------- */

		'page_about' => array(

			'hero' => array(
				'image'     => $hero_image,
				'eyebrow'   => 'Aberdeen, Maryland',
				'title'     => 'A studio built on<br><em>patience, craft &amp; joy</em>',
				'lead'      => 'Aberdeen Piano is a private teaching studio where students from preschool through adulthood learn to read, hear and truly love music — one unhurried lesson at a time.',
				'stats'     => array(
					array(
						'value' => '45<sup>+</sup>',
						'label' => 'Years of teaching',
					),
					array(
						'value' => 'PK&ndash;12',
						'label' => 'and adult learners',
					),
					array(
						'value' => '2',
						'label' => 'Recitals each year',
					),
				),
			),

			'intro' => array(
				'image'       => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=1100&q=85',
				'image_alt'   => 'An upright piano in a warm, sunlit teaching room',
				'badge_label' => 'Est.',
				'badge_value' => '1979',
				'eyebrow'     => 'Studio Introduction',
				'heading'     => 'Welcome to <em>Aberdeen Piano</em>',
				'lead'        => 'Aberdeen Piano is a private piano studio serving families across Harford County. Lessons are taught one-to-one in a calm, home-studio setting where every student is given the time, attention and encouragement their musical growth deserves.',
				'paragraphs'  => array(
					array( 'text' => 'Weekly instruction is paired with basic music theory, scheduled Audio-Visual Lab sessions and access to an extensive music library, so students build genuine musicianship rather than memorised pieces. Semester recitals and Harford County Piano Teachers Association events give that work a stage.' ),
					array( 'text' => 'From the first curious preschooler to the adult returning to a childhood instrument, the studio welcomes every stage of the journey — and keeps the door open for as long as the music matters.' ),
				),
				'marks'       => array(
					array( 'text' => 'Weekly private lessons' ),
					array( 'text' => 'Music theory included' ),
					array( 'text' => 'Student recitals' ),
					array( 'text' => 'ABRSM pathway' ),
				),
			),

			'mission' => array(
				'eyebrow' => 'Our Mission',
				'quote'   => 'To teach the piano so well, and so kindly, that music becomes a lifelong companion — not a lesson a child once took.',
				'cite'    => 'Irene K. Yeakel — Founder and Instructor',
			),

			'approach' => array(
				'eyebrow' => 'Teaching Approach',
				'heading' => 'How lessons <em>are taught</em>',
				'intro'   => 'Each lesson is planned around the student in front of the piano — their pace, their ear, and the music that keeps them practising all week.',
				'cards'   => array(
					array(
						'number' => '01',
						'title'  => 'Foundations First',
						'text'   => 'Posture, hand position and a steady pulse are taught from the very first lesson, so technique never has to be unlearned later.',
					),
					array(
						'number' => '02',
						'title'  => 'Read &amp; Understand',
						'text'   => 'Basic music theory is woven into every lesson — notation, rhythm, key and phrasing — so students read music rather than copy it.',
					),
					array(
						'number' => '03',
						'title'  => 'Play For People',
						'text'   => 'Preparation classes and semester recitals turn practice into performance, and nerves into the quiet confidence of a prepared musician.',
					),
				),
			),

			'philosophy' => array(
				'eyebrow'   => 'Student Development',
				'heading'   => 'A philosophy of <em>steady growth</em>',
				'lead'      => 'Progress at the piano is not a race. Students move forward when the skill beneath the next one is secure — and every stage of that climb is planned, measured and celebrated.',
				'paragraph' => 'The result is a musician who can sit at any piano, read what is in front of them, and play it with meaning.',
				'steps'     => array(
					array(
						'number'      => 'I',
						'title'       => 'Curiosity',
						'description' => 'The earliest lessons protect a student’s delight in sound. Games, listening and short pieces build the habit of sitting down to play.',
					),
					array(
						'number'      => 'II',
						'title'       => 'Discipline',
						'description' => 'Weekly assignments, scales and theory give practice a shape, and teach students how to work at something patiently on their own.',
					),
					array(
						'number'      => 'III',
						'title'       => 'Expression',
						'description' => 'Once the notes are secure, attention turns to tone, dynamics and phrasing — the difference between playing a piece and performing it.',
					),
					array(
						'number'      => 'IV',
						'title'       => 'Independence',
						'description' => 'Advanced students choose repertoire, prepare for festivals and certification, and learn to teach themselves the next piece.',
					),
				),
			),

			'instructor' => array(
				'image'       => 'https://images.unsplash.com/photo-1552422535-c45813c61732?auto=format&fit=crop&w=1100&q=85',
				'image_alt'   => 'Irene K. Yeakel at the piano',
				'plate_name'  => 'Irene K. Yeakel',
				'plate_role'  => 'Founder &amp; Piano Instructor',
				'eyebrow'     => 'Instructor Profile',
				'heading'     => 'Meet <em>Irene K. Yeakel</em>',
				'lead'        => 'Irene has spent a lifetime at the keyboard and more than four decades beside students at theirs. Aberdeen Piano grew out of a simple conviction: that any student, taught carefully, can learn to play well.',
				'facts'       => array(
					array(
						'title' => 'Teaching Background',
						'text'  => 'Classically trained and a lifelong private instructor, Irene has taught preschool beginners, school-age students, high-school accompanists and adult returners from her Aberdeen studio.',
					),
					array(
						'title' => 'Experience',
						'text'  => 'Decades of weekly instruction, semester recitals, adjudicated festivals and certification preparation — guiding hundreds of students from first note to final performance.',
					),
					array(
						'title' => 'Musical Expertise',
						'text'  => 'Classical repertoire, sight-reading, music theory, ear training and performance preparation, with an ABRSM pathway available for students seeking certification.',
					),
					array(
						'title' => 'Educational Philosophy',
						'text'  => 'Teach the fundamentals thoroughly, expect honest practice, and keep the joy in the room. Encouragement and high standards are not opposites — they work together.',
					),
				),
				'signature'   => 'Irene K. Yeakel',
				'credentials' => 'Harford County Piano Teachers Association',
			),

			'policies' => array(
				'eyebrow' => 'Policies &amp; Activities',
				'heading' => 'How the <em>studio runs</em>',
				'intro'   => 'Clear expectations keep lessons calm and productive for students, parents and instructor alike. These are the studio’s standing policies.',
				'note'    => 'Questions about any policy are always welcome — call 443-243-3319 or email irenekyeakel@gmail.com.',
				'items'   => array(
					array(
						'title'      => 'Tuition Policies',
						'open'       => true,
						'paragraphs' => array(
							array( 'text' => 'Student tuition is $80 per month and covers weekly 30-minute lessons, scheduled Audio-Visual Lab usage, music library access, basic music theory instruction and student recitals. Adult lessons are $30 per 30-minute lesson, and the ABRSM adult program is $70 per hour.' ),
							array( 'text' => 'Payment is due during the first lesson of each month. Cash and checks are accepted; checks should be made payable to Irene K. Yeakel.' ),
						),
					),
					array(
						'title'      => 'Attendance Expectations',
						'open'       => false,
						'paragraphs' => array(
							array( 'text' => 'Students are expected at their scheduled weekly lesson. Lessons cancelled due to illness may be rescheduled — please notify the studio in advance by phone at 443-243-3319 or by email at irenekyeakel@gmail.com.' ),
							array( 'text' => 'Make-up sessions are set aside each month, and scheduled breaks and holidays are published on the studio calendar.' ),
						),
					),
					array(
						'title'      => 'Studio Participation',
						'open'       => false,
						'paragraphs' => array(
							array( 'text' => 'Students take part in semester recitals and the recital preparation classes that precede them. Families are warmly invited to attend.' ),
							array( 'text' => 'Harford County Piano Teachers Association activities — adjudicated and non-adjudicated recitals, piano festivals, concerts, lectures and joint recitals — are open to studio students throughout the year.' ),
						),
					),
					array(
						'title'      => 'Student Responsibilities',
						'open'       => false,
						'paragraphs' => array(
							array( 'text' => 'Consistent practice between lessons is the single greatest factor in a student’s progress. Students bring their assigned books to every lesson and keep their practice record up to date.' ),
							array( 'text' => 'Older students may schedule additional Audio-Visual Lab sessions for school projects, research and practice support.' ),
						),
					),
				),
			),

			'cta' => array(
				'eyebrow' => 'Begin',
				'heading' => 'There is room at <em>the bench</em>',
				'lead'    => 'Tell us a little about the student and we will find a lesson time that fits the week. New students are welcome throughout the year.',
				'buttons' => $cta_buttons,
				'meta'    => array_merge(
					$cta_meta,
					array(
						array(
							'label' => 'Lesson times',
							'value' => 'Weekday afternoons',
							'url'   => '',
						),
					)
				),
			),
		),

		/* ---------------------------------------------------------------
		   Services
		   --------------------------------------------------------------- */

		'page_services' => array(

			'hero' => array(
				'image'   => $keys_image,
				'eyebrow' => 'Lessons &amp; Programs',
				'title'   => 'Instruction for<br><em>every stage of playing</em>',
				'lead'    => 'Weekly private lessons for students from preschool through twelfth grade, flexible instruction for adults, and a structured certification pathway for those who want one.',
			),

			'index' => array(
				'items' => array(
					array(
						'number' => '01',
						'label'  => 'Student Lessons',
						'meta'   => '$80 / month',
						'anchor' => '#student-lessons',
					),
					array(
						'number' => '02',
						'label'  => 'Adult Lessons',
						'meta'   => '$30 / 30 min',
						'anchor' => '#adult-lessons',
					),
					array(
						'number' => '03',
						'label'  => 'Music Theory',
						'meta'   => 'Included',
						'anchor' => '#music-theory',
					),
					array(
						'number' => '04',
						'label'  => 'Performance Prep',
						'meta'   => 'Included',
						'anchor' => '#performance',
					),
				),
			),

			'student' => array(
				'eyebrow'  => 'Service 01',
				'heading'  => 'Student Piano Lessons <em>(PK–12)</em>',
				'intro'    => 'A complete weekly program for school-age students — lessons, theory, lab time and a stage to play on.',
				'subtitle' => 'What monthly tuition includes',
				'includes' => array(
					array(
						'title' => 'Weekly 30-minute lessons',
						'text'  => 'One-to-one instruction at the same time each week, at a pace set by the student.',
					),
					array(
						'title' => 'Scheduled Audio-Visual Lab usage',
						'text'  => 'Listening, watching and learning tools that reinforce what happens at the keyboard.',
					),
					array(
						'title' => 'Music Library access',
						'text'  => 'An extensive collection of repertoire and study material available to every student.',
					),
					array(
						'title' => 'Basic music theory instruction',
						'text'  => 'Notation, rhythm and key taught alongside the pieces, never as a separate chore.',
					),
					array(
						'title' => 'Student recitals',
						'text'  => 'Semester performances with preparation classes beforehand and family warmly invited.',
					),
				),
				'card'     => array(
					'label'        => 'Monthly Tuition',
					'currency'     => '$',
					'amount'       => '80',
					'period'       => '/ month',
					'note'         => 'All five inclusions above, billed monthly. No enrollment fee.',
					'button_label' => 'Enroll Today',
					'button_url'   => '/#contact',
					'facts'        => array(
						array(
							'label' => 'Ages',
							'value' => 'Preschool through Grade 12',
						),
						array(
							'label' => 'Lesson length',
							'value' => '30 minutes, weekly',
						),
						array(
							'label' => 'Recitals',
							'value' => 'Two per school year',
						),
					),
				),
			),

			'cancellation' => array(
				'title'       => 'Cancellation Policy',
				'text'        => 'Lessons cancelled due to illness may be rescheduled. Please let the studio know as early as you can so a make-up time can be set aside.',
				'phone_label' => 'Phone',
				'phone'       => '443-243-3319',
				'email_label' => 'Email',
				'email'       => 'irenekyeakel@gmail.com',
			),

			'payment' => array(
				'title' => 'Payment Information',
				'items' => array(
					array( 'text' => 'Payment is due during the first lesson of each month' ),
					array( 'text' => 'Cash accepted' ),
					array( 'text' => 'Checks accepted' ),
				),
				'note'  => 'Checks payable to Irene K. Yeakel.',
			),

			'adult' => array(
				'eyebrow' => 'Service 02',
				'heading' => 'Adult Piano <em>Lessons</em>',
				'intro'   => 'For the complete beginner and for the adult returning to an instrument they once loved — scheduled around a working week.',
				'tiers'   => array(
					array(
						'label'      => 'Adult Lessons',
						'currency'   => '$',
						'amount'     => '30',
						'period'     => '/ 30-minute lesson',
						'note'       => 'Flexible scheduling available.',
						'divider'    => 'Includes',
						'featured'   => false,
						'link_label' => 'Ask about a lesson time',
						'link_url'   => '/#contact',
						'items'      => array(
							array( 'text' => 'Piano instruction' ),
							array( 'text' => 'Basic music theory' ),
							array( 'text' => 'Optional recital participation' ),
						),
					),
					array(
						'label'      => 'ABRSM Adult Program',
						'currency'   => '$',
						'amount'     => '70',
						'period'     => '/ hour',
						'note'       => 'A structured certification pathway.',
						'divider'    => 'For adults who want',
						'featured'   => true,
						'link_label' => 'Request a consultation',
						'link_url'   => '/#contact',
						'items'      => array(
							array( 'text' => 'A graded, examined curriculum' ),
							array( 'text' => 'Hour-long lessons with deeper study' ),
							array( 'text' => 'Preparation toward recognised certification' ),
						),
					),
				),
			),

			'theory' => array(
				'eyebrow'   => 'Service 03',
				'heading'   => 'Music Theory <em>Instruction</em>',
				'lead'      => 'Theory is not a separate class here. It is taught inside the lesson, using the piece the student is already learning, so the ideas land where they are needed.',
				'paragraph' => 'Students who understand what is on the page practise better, learn faster and keep more of what they play.',
				'steps'     => array(
					array(
						'number'      => 'I',
						'title'       => 'Included In Every Lesson',
						'description' => 'Basic music theory is part of student tuition and adult lessons alike — no separate booking and no extra fee.',
					),
					array(
						'number'      => 'II',
						'title'       => 'Skill Development',
						'description' => 'Scales, intervals, keys and rhythm are built up steadily so each new concept rests on one the student already owns.',
					),
					array(
						'number'      => 'III',
						'title'       => 'Reading &amp; Interpretation',
						'description' => 'Students learn to read a score fluently and to interpret what it asks for — dynamics, phrasing, tempo and character.',
					),
				),
			),

			'performance' => array(
				'eyebrow' => 'Service 04',
				'heading' => 'Performance <em>Preparation</em>',
				'intro'   => 'Playing well alone and playing well for an audience are two different skills. Both are taught.',
				'cards'   => array(
					array(
						'number' => '01',
						'title'  => 'Recital Preparation Classes',
						'text'   => 'Scheduled ahead of each semester recital, these classes rehearse the whole performance — entrance, bow, piece and exit.',
					),
					array(
						'number' => '02',
						'title'  => 'Stage Confidence',
						'text'   => 'Students play for others long before recital day, so nerves become familiar rather than frightening.',
					),
					array(
						'number' => '03',
						'title'  => 'Performance Readiness',
						'text'   => 'A piece is ready when it holds up under pressure. Students learn how to secure memory, recover cleanly and finish well.',
					),
				),
			),

			'cta' => array(
				'eyebrow' => 'Enroll',
				'heading' => 'Find the lesson <em>that fits</em>',
				'lead'    => 'Tell us the student’s age and what you are hoping for, and we will recommend a program and a time.',
				'buttons' => array(
					array(
						'label' => 'Enroll Today',
						'url'   => '/#contact',
						'style' => '',
					),
					array(
						'label' => 'View Calendar',
						'url'   => '/#calendar',
						'style' => 'cta-ghost',
					),
				),
				'meta'    => array_merge(
					$cta_meta,
					array(
						array(
							'label' => 'Payment',
							'value' => 'Cash or check',
							'url'   => '',
						),
					)
				),
			),
		),

		/* ---------------------------------------------------------------
		   Student Resources
		   --------------------------------------------------------------- */

		'page_student' => array(

			'hero' => array(
				'image'   => $lab_image,
				'eyebrow' => 'For Students &amp; Families',
				'title'   => 'Everything a student<br><em>can draw on</em>',
				'lead'    => 'Lessons are only part of the studio. Students also have a scheduled Audio-Visual Lab, an extensive music library, and two recitals a year to work toward.',
			),

			'index' => array(
				'items' => array(
					array(
						'number' => '01',
						'label'  => 'Audio-Visual Lab',
						'meta'   => 'Scheduled',
						'anchor' => '#av-lab',
					),
					array(
						'number' => '02',
						'label'  => 'Additional Access',
						'meta'   => 'By request',
						'anchor' => '#lab-access',
					),
					array(
						'number' => '03',
						'label'  => 'Music Library',
						'meta'   => 'Open to all',
						'anchor' => '#music-library',
					),
					array(
						'number' => '04',
						'label'  => 'Performance',
						'meta'   => 'Twice a year',
						'anchor' => '#performance-opportunities',
					),
				),
			),

			'lab' => array(
				'image'     => 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=1100&q=85',
				'image_alt' => 'Headphones and sheet music beside a keyboard in the studio lab',
				'tag'       => 'Included with tuition',
				'eyebrow'   => 'Resource 01',
				'heading'   => 'The Audio-Visual <em>Lab</em>',
				'lead'      => 'A dedicated space where students hear and see the music they are learning. Lab time is scheduled as part of the weekly program, not booked separately.',
				'features'  => array(
					array(
						'number' => '01',
						'title'  => 'Scheduled Lab Access',
						'text'   => 'Every student receives regular scheduled sessions in the lab as part of monthly tuition — no separate booking, no additional fee.',
					),
					array(
						'number' => '02',
						'title'  => 'Educational Activities',
						'text'   => 'Guided listening, theory work and repertoire study that reinforce what was covered at the keyboard during the lesson.',
					),
					array(
						'number' => '03',
						'title'  => 'Music Learning Tools',
						'text'   => 'Audio and visual material that lets students hear a piece performed properly and see how the notation becomes sound.',
					),
				),
			),

			'access' => array(
				'eyebrow'      => 'Resource 02',
				'heading'      => 'Additional <em>Lab Access</em>',
				'text'         => 'Older students may schedule additional sessions beyond their regular lab time. Ask at a lesson, or call the studio to arrange one.',
				'button_label' => 'Request a Session',
				'button_url'   => '/#contact',
				'items'        => array(
					array(
						'letter' => 'A',
						'title'  => 'School Projects',
						'text'   => 'Lab time for music assignments, presentations and coursework that call for proper listening equipment.',
					),
					array(
						'letter' => 'B',
						'title'  => 'Research',
						'text'   => 'Time to study composers, periods and repertoire using the studio’s reference and recorded material.',
					),
					array(
						'letter' => 'C',
						'title'  => 'Practice Support',
						'text'   => 'Extra sessions for students working toward a recital, a festival or an examination piece.',
					),
				),
			),

			'library' => array(
				'image'     => $library_image,
				'image_alt' => 'Sheet music and study books from the studio library',
				'tag'       => 'Open to every student',
				'eyebrow'   => 'Resource 03',
				'heading'   => 'The Music <em>Library</em>',
				'lead'      => 'Decades of collected scores, method books and study material, kept in the studio and open to every student on the roll.',
				'features'  => array(
					array(
						'number' => '01',
						'title'  => 'Available Resources',
						'text'   => 'Classical repertoire, graded collections, sight-reading books and reference material spanning beginner to advanced levels.',
					),
					array(
						'number' => '02',
						'title'  => 'Learning Materials',
						'text'   => 'Theory workbooks, scale and technique studies, and supplementary pieces chosen to suit each student’s stage.',
					),
					array(
						'number' => '03',
						'title'  => 'Student Access',
						'text'   => 'Library access is included with tuition. Material is issued at lessons so students always have the right music in hand.',
					),
				),
			),

			'performance' => array(
				'eyebrow' => 'Resource 04',
				'heading' => 'Performance <em>Opportunities</em>',
				'intro'   => 'Two semester recitals a year, each preceded by a preparation class so no student walks on stage unready.',
				'recital' => array(
					'label'  => 'Student Recitals',
					'title'  => 'A stage, twice a year',
					'points' => array(
						array(
							'title' => 'Semester Recitals',
							'text'  => 'Students perform at the close of each semester — one in the winter and one in the spring — playing pieces prepared over the term.',
						),
						array(
							'title' => 'Family Participation',
							'text'  => 'Parents, grandparents and siblings are warmly invited. Recitals are a studio occasion, and the audience matters to the students.',
						),
						array(
							'title' => 'Performance Expectations',
							'text'  => 'Students perform from memory where appropriate, dress for the occasion, and stay to hear their fellow students play.',
						),
					),
				),
				'prep'    => array(
					'label'   => 'Preparation Classes',
					'title'   => 'Before the recital',
					'lead'    => 'A preparation class is held ahead of each recital. Attendance is expected of every performing student.',
					'divider' => 'What is expected',
					'note'    => 'Preparation class dates are published on the studio calendar.',
					'items'   => array(
						array( 'text' => 'The recital piece learned and secure before the class' ),
						array( 'text' => 'Entrance, bow, performance and exit rehearsed in full' ),
						array( 'text' => 'Playing for the other students, so recital day is familiar' ),
						array( 'text' => 'Any questions from students or parents answered in advance' ),
					),
				),
			),

			'cta' => array(
				'eyebrow' => 'Questions',
				'heading' => 'Ask about any <em>of it</em>',
				'lead'    => 'Lab sessions, library material or the next recital — the studio is happy to talk it through.',
				'buttons' => array(
					array(
						'label' => 'Contact Us',
						'url'   => '/#contact',
						'style' => '',
					),
					array(
						'label' => 'View Calendar',
						'url'   => '/#calendar',
						'style' => 'cta-ghost',
					),
				),
				'meta'    => array_merge(
					$cta_meta,
					array(
						array(
							'label' => 'Recitals',
							'value' => 'Two per school year',
							'url'   => '',
						),
					)
				),
			),
		),

		/* ---------------------------------------------------------------
		   Contact Us
		   --------------------------------------------------------------- */

		'page_contact' => array(

			'hero' => array(
				'image'   => $piano_image,
				'eyebrow' => 'Get In Touch',
				'title'   => 'Let us find a time<br><em>at the piano</em>',
				'lead'    => 'Questions about lessons, tuition or the next recital are always welcome. Call the studio, send an email, or use the form below.',
			),

			'methods' => array(
				'items' => array(
					array(
						'label' => 'Phone',
						'value' => '443-243-3319',
						'hint'  => 'Weekday afternoons',
						'url'   => 'tel:4432433319',
					),
					array(
						'label' => 'Email',
						'value' => 'irenekyeakel@gmail.com',
						'hint'  => 'For enrollment and general questions',
						'url'   => 'mailto:irenekyeakel@gmail.com',
					),
					array(
						'label' => 'Instructor',
						'value' => 'Irene K. Yeakel',
						'hint'  => 'Founder, Aberdeen Piano',
						'url'   => '',
					),
				),
			),

			'details' => array(
				'eyebrow' => 'Contact Information',
				'heading' => 'Write to the <em>studio</em>',
				'lead'    => 'Tell us the student’s age, whether they have played before, and the times of day that suit your week. We will reply with a suggested lesson time.',
				'note'    => 'Prefer to talk it through? A phone call is often the quickest way to settle a schedule.',
				'items'   => array(
					array(
						'label' => 'Instructor',
						'value' => 'Irene K. Yeakel',
						'url'   => '',
					),
					array(
						'label' => 'Phone',
						'value' => '443-243-3319',
						'url'   => 'tel:4432433319',
					),
					array(
						'label' => 'Email',
						'value' => 'irenekyeakel@gmail.com',
						'url'   => 'mailto:irenekyeakel@gmail.com',
					),
					array(
						'label' => 'Studio',
						'value' => "123 Bel Air Avenue\nAberdeen, MD 21001",
						'url'   => '',
					),
				),
			),

			'form' => array(
				'label'        => 'Enquiry',
				'heading'      => 'Send a message',
				'submit_label' => 'Send Message',
				'note'         => 'Fields marked * are required.',
				'success_text' => 'Thank you — your message is on its way. We will reply shortly.',
				'fields'       => array(
					array(
						'name'        => 'name',
						'label'       => 'Name',
						'type'        => 'text',
						'placeholder' => 'Your full name',
						'required'    => true,
						'half'        => true,
					),
					array(
						'name'        => 'email',
						'label'       => 'Email',
						'type'        => 'email',
						'placeholder' => 'you@example.com',
						'required'    => true,
						'half'        => true,
					),
					array(
						'name'        => 'telephone',
						'label'       => 'Phone',
						'type'        => 'tel',
						'placeholder' => '443-000-0000',
						'required'    => false,
						'half'        => true,
					),
					array(
						'name'        => 'subject',
						'label'       => 'Subject',
						'type'        => 'text',
						'placeholder' => 'Lesson enquiry',
						'required'    => false,
						'half'        => true,
					),
					array(
						'name'        => 'message',
						'label'       => 'Message',
						'type'        => 'textarea',
						'placeholder' => 'The student’s age, any previous experience, and the days that suit you best.',
						'required'    => true,
						'half'        => false,
					),
				),
			),

			'map' => array(
				'eyebrow'        => 'Find Us',
				'heading'        => 'The studio in <em>Aberdeen</em>',
				'intro'          => 'Lessons are taught at the home studio in Aberdeen, Maryland — a short drive from anywhere in Harford County.',
				'address_label'  => 'Studio Address',
				'address'        => "123 Bel Air Avenue\nAberdeen, MD 21001",
				'embed_url'      => 'https://www.google.com/maps?q=Aberdeen%2C%20MD%2021001&output=embed',
				'button_label'   => 'Get Directions',
				'button_url'     => 'https://www.google.com/maps/search/?api=1&query=Aberdeen%2C+MD+21001',
				'note'           => 'Placeholder location — the studio address will be confirmed.',
				'facts'          => array(
					array(
						'label' => 'Parking',
						'value' => 'On-street, directly outside',
						'url'   => '',
					),
					array(
						'label' => 'Arrival',
						'value' => 'A few minutes before the lesson',
						'url'   => '',
					),
					array(
						'label' => 'Questions',
						'value' => '443-243-3319',
						'url'   => 'tel:4432433319',
					),
				),
			),
		),

		/* ---------------------------------------------------------------
		   ABRSM Program
		   --------------------------------------------------------------- */

		'page_abrsm' => array(

			'hero' => array(
				'image'   => 'https://images.unsplash.com/photo-1481886756534-97af88ccb438?auto=format&fit=crop&w=2000&q=88',
				'eyebrow' => 'Certification Pathway',
				'title'   => 'A graded route<br><em>through the repertoire</em>',
				'lead'    => 'The Associated Board of the Royal Schools of Music offers an internationally recognised, examined curriculum. Students who want a measured path from beginner to advanced can follow it here at Aberdeen Piano.',
			),

			'index' => array(
				'items' => array(
					array(
						'number' => '01',
						'label'  => 'Overview',
						'meta'   => 'What it is',
						'anchor' => '#overview',
					),
					array(
						'number' => '02',
						'label'  => 'The Program',
						'meta'   => 'Grades 1–8',
						'anchor' => '#pathway',
					),
					array(
						'number' => '03',
						'label'  => 'Commitment',
						'meta'   => 'Before you begin',
						'anchor' => '#commitment',
					),
					array(
						'number' => '04',
						'label'  => 'Consultation',
						'meta'   => 'By appointment',
						'anchor' => '#consultation',
					),
				),
			),

			'overview' => array(
				'eyebrow'     => 'Overview',
				'heading'     => 'What is the <em>ABRSM?</em>',
				'lead'        => 'The Associated Board of the Royal Schools of Music is the examining body founded by the four royal conservatoires of the United Kingdom. Its graded syllabus is one of the most widely recognised measures of musical attainment in the world.',
				'paragraphs'  => array(
					array( 'text' => 'For a student, it turns "getting better at the piano" into something concrete: a defined repertoire, a set of technical requirements, and an examination assessed by a visiting examiner against the same standard applied everywhere else.' ),
					array( 'text' => 'The program is entirely optional. Students who prefer to keep learning without examinations lose nothing — the studio teaches the same fundamentals either way.' ),
				),
				'facts_label' => 'At a glance',
				'facts_note'  => 'The adult ABRSM program is taught in hour-long lessons at $70 per hour.',
				'facts'       => array(
					array(
						'label' => 'Founded by',
						'value' => 'The four Royal Schools of Music',
					),
					array(
						'label' => 'Graded exams',
						'value' => 'Initial, then Grades 1 to 8',
					),
					array(
						'label' => 'Assessed by',
						'value' => 'A visiting ABRSM examiner',
					),
					array(
						'label' => 'Open to',
						'value' => 'Students and adult learners',
					),
				),
			),

			'ladder' => array(
				'eyebrow' => 'The Pathway',
				'heading' => 'One step at <em>a time</em>',
				'items'   => array(
					array(
						'rung'  => 'Initial',
						'text'  => 'A first assessed performance for young beginners.',
						'crest' => false,
					),
					array(
						'rung'  => 'Grades 1–3',
						'text'  => 'Foundations: reading, scales, simple repertoire and aural work.',
						'crest' => false,
					),
					array(
						'rung'  => 'Grades 4–5',
						'text'  => 'Intermediate technique, wider repertoire and music theory.',
						'crest' => false,
					),
					array(
						'rung'  => 'Grades 6–8',
						'text'  => 'Advanced study, interpretation and substantial concert repertoire.',
						'crest' => false,
					),
					array(
						'rung'  => 'Beyond',
						'text'  => 'Diploma study, for students who continue after Grade 8.',
						'crest' => true,
					),
				),
			),

			'content' => array(
				'eyebrow' => 'The Program',
				'heading' => 'How the program <em>is structured</em>',
				'intro'   => 'Four things shape a student\'s year in the program: the pathway they are on, the examination ahead, the curriculum behind it, and what is expected of them week to week.',
				'cards'   => array(
					array(
						'number' => '01',
						'title'  => 'Certification Pathways',
						'text'   => 'Students enter at the grade that matches their current playing and progress at their own pace — one grade a year is typical, though there is no requirement to sit every grade in sequence.',
						'items'  => array(
							array( 'text' => 'Entry grade set with the instructor' ),
							array( 'text' => 'Practical piano and music theory routes' ),
							array( 'text' => 'Certification recognised internationally' ),
						),
					),
					array(
						'number' => '02',
						'title'  => 'Examination Process',
						'text'   => 'Examinations are held at a scheduled centre and assessed by a visiting ABRSM examiner. The studio handles entry and prepares the student for every part of the day.',
						'items'  => array(
							array( 'text' => 'Set pieces performed from the syllabus' ),
							array( 'text' => 'Scales, sight-reading and aural tests' ),
							array( 'text' => 'A written report and result follow' ),
						),
					),
					array(
						'number' => '03',
						'title'  => 'Curriculum Structure',
						'text'   => 'Each grade sets its own repertoire lists, technical work and supporting tests, so the year has a clear shape from the first lesson to the examination.',
						'items'  => array(
							array( 'text' => 'Three contrasting pieces per grade' ),
							array( 'text' => 'Graded scales and arpeggios' ),
							array( 'text' => 'Theory required from Grade 6 upward' ),
						),
					),
					array(
						'number' => '04',
						'title'  => 'Student Requirements',
						'text'   => 'The program asks more of a student than weekly lessons alone. Consistent daily practice is what carries a candidate through an examination comfortably.',
						'items'  => array(
							array( 'text' => 'Daily practice, not weekly cramming' ),
							array( 'text' => 'Pieces secure well before the exam date' ),
							array( 'text' => 'Attendance at preparation sessions' ),
						),
					),
				),
			),

			'commitment' => array(
				'eyebrow'   => 'Additional Information',
				'heading'   => 'What to weigh <em>before enrolling</em>',
				'lead'      => 'The ABRSM program is rewarding, and it is a genuine commitment. These are the three things every family should consider before a student begins.',
				'paragraph' => 'Nothing here is a barrier — it is simply what the program asks, said plainly, so the decision is made with clear eyes.',
				'steps'     => array(
					array(
						'number'      => 'I',
						'title'       => 'Three-Way Agreement',
						'description' => 'Parent, student and teacher must all agree before a student enters the program. Everyone needs to want it — a student pushed into examinations rarely enjoys them.',
					),
					array(
						'number'      => 'II',
						'title'       => 'Additional Study Commitment',
						'description' => 'Grade work sits on top of ordinary lesson material and calls for more practice time at home than a student may be used to, sustained across the whole year.',
					),
					array(
						'number'      => 'III',
						'title'       => 'Examination Travel',
						'description' => 'Examinations are held at an appointed centre rather than at the studio, so families should expect to travel on the examination day.',
					),
				),
			),

			'resource' => array(
				'label'        => 'External Resource',
				'heading'      => 'Read the full syllabus at <em>abrsm.org</em>',
				'text'         => 'Repertoire lists, examination dates, centres and grade requirements are published in full by the ABRSM.',
				'button_label' => 'Visit abrsm.org',
				'button_url'   => 'https://www.abrsm.org',
			),

			'cta' => array(
				'eyebrow' => 'Consultation',
				'heading' => 'Talk it through with <em>Irene</em>',
				'lead'    => 'Whether the program suits a particular student is best decided in conversation. Schedule an appointment with Irene K. Yeakel to discuss entry grade, timing and what the year would look like.',
				'buttons' => array(
					array(
						'label' => 'Request an Appointment',
						'url'   => '/#contact',
						'style' => '',
					),
					array(
						'label' => 'View Tuition',
						'url'   => '/#pricing',
						'style' => 'cta-ghost',
					),
				),
				'meta'    => array(
					array(
						'label' => 'Call the studio',
						'value' => '443-243-3319',
						'url'   => 'tel:4432433319',
					),
					array(
						'label' => 'Email',
						'value' => 'irenekyeakel@gmail.com',
						'url'   => 'mailto:irenekyeakel@gmail.com',
					),
					array(
						'label' => 'Adult program',
						'value' => '$70 per hour',
						'url'   => '',
					),
				),
			),
		),

		/* ---------------------------------------------------------------
		   Activities & Events
		   --------------------------------------------------------------- */

		'page_activities' => array(

			'hero' => array(
				'image'   => 'https://images.unsplash.com/photo-1466428996289-fb355538da1b?auto=format&fit=crop&w=2000&q=88',
				'eyebrow' => 'Harford County Piano Teachers Association',
				'title'   => 'More music than<br><em>one studio can hold</em>',
				'lead'    => 'Studio students are part of a wider musical community. Through the HCPTA they perform, compete, listen and learn alongside students from across the county.',
			),

			'intro' => array(
				'eyebrow'   => 'The Association',
				'heading'   => 'Opportunities beyond <em>the lesson</em>',
				'lead'      => 'The Harford County Piano Teachers Association runs a full calendar of recitals, festivals, concerts and lectures throughout the school year. Aberdeen Piano students are invited to take part in all of it.',
				'paragraph' => 'Some events are adjudicated and some are simply for the joy of playing. Together they give students a reason to prepare, an audience to play for, and the experience of hearing what their peers are working on.',
			),

			'activities' => array(
				'eyebrow' => 'Participation Opportunities',
				'heading' => 'Six ways to <em>take part</em>',
				'intro'   => 'Participation is by invitation and always optional — students take on as much or as little as suits their year.',
				'cards'   => array(
					array(
						'number' => '01',
						'title'  => 'Adjudicated Recitals',
						'text'   => 'Students perform prepared repertoire before a qualified adjudicator and receive a written evaluation and a graded rating of their performance.',
						'tag'    => 'Rated',
					),
					array(
						'number' => '02',
						'title'  => 'Non-Adjudicated Recitals',
						'text'   => 'The same stage without the assessment — a relaxed first outing for younger students, or a chance to try new repertoire without pressure.',
						'tag'    => 'For the joy of it',
					),
					array(
						'number' => '03',
						'title'  => 'Piano Festivals',
						'text'   => 'The association\'s annual festival brings students from across Harford County together for a day of performance and evaluation.',
						'tag'    => 'Annual',
					),
					array(
						'number' => '04',
						'title'  => 'Concerts',
						'text'   => 'Seasonal concerts — including the holiday concert each December — where students play for families and the wider community.',
						'tag'    => 'Seasonal',
					),
					array(
						'number' => '05',
						'title'  => 'Lectures',
						'text'   => 'Talks and masterclasses on repertoire, technique and musicianship, open to students and parents who want to understand more.',
						'tag'    => 'Learning',
					),
					array(
						'number' => '06',
						'title'  => 'Joint Recitals',
						'text'   => 'Shared programmes with students from other studios — a friendly, motivating way to hear how others of the same age are playing.',
						'tag'    => 'Shared',
					),
				),
			),

			'recognition' => array(
				'eyebrow' => 'Student Recognition Programs',
				'heading' => 'Work that is <em>seen and recorded</em>',
				'intro'   => 'Taking part is recognised formally. Students leave an event with something in hand and something to build on.',
				'cards'   => array(
					array(
						'mark'  => '★',
						'title' => 'Participation Certificates',
						'text'  => 'Every student who performs receives a certificate recording the event and the repertoire they played — a small thing that students keep for years.',
					),
					array(
						'mark'  => '♪',
						'title' => 'Graded Ratings',
						'text'  => 'At adjudicated events students receive a rating against a published standard, so progress from one year to the next is measurable rather than felt.',
					),
					array(
						'mark'  => '✦',
						'title' => 'Performance Evaluations',
						'text'  => 'A written evaluation from the adjudicator names what worked and what to take back to the practice room — often the most useful part of the day.',
					),
				),
			),

			'band' => array(
				'label'        => 'Dates',
				'heading'      => 'Every event is on the <em>studio calendar</em>',
				'text'         => 'HCPTA recitals, the holiday concert and the annual festival are published alongside lesson and make-up dates for the full year.',
				'button_label' => 'View the Calendar',
				'button_url'   => '/#calendar',
			),

			'cta' => array(
				'eyebrow' => 'Take Part',
				'heading' => 'Ask about the <em>next event</em>',
				'lead'    => 'Not sure whether a student is ready for an adjudicated recital? That is exactly the sort of thing to talk over at a lesson.',
				'buttons' => array(
					array(
						'label' => 'Contact Us',
						'url'   => '/#contact',
						'style' => '',
					),
					array(
						'label' => 'View Calendar',
						'url'   => '/#calendar',
						'style' => 'cta-ghost',
					),
				),
				'meta'    => array(
					array(
						'label' => 'Call the studio',
						'value' => '443-243-3319',
						'url'   => 'tel:4432433319',
					),
					array(
						'label' => 'Email',
						'value' => 'irenekyeakel@gmail.com',
						'url'   => 'mailto:irenekyeakel@gmail.com',
					),
					array(
						'label' => 'Association',
						'value' => 'HCPTA member studio',
						'url'   => '',
					),
				),
			),
		),

		/* ---------------------------------------------------------------
		   Calendar of Events
		   --------------------------------------------------------------- */

		'page_calendar' => array(

			'hero' => array(
				'image'   => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=2000&q=88',
				'eyebrow' => '2025 – 2026 Studio Year',
				'title'   => 'The whole year,<br><em>in one place</em>',
				'lead'    => 'Lesson dates, make-up sessions, recitals, festivals and every scheduled break — from the first lesson in September to the close of the spring semester in June.',
			),

			'year' => array(
				'eyebrow'  => 'Calendar of Events',
				'heading'  => 'September to <em>June</em>',
				'intro'    => 'Filter the year by what you are looking for. Dates marked TBD are confirmed by the association closer to the event.',
				'footnote' => 'Make-up lessons are by appointment. HCPTA dates marked TBD will be shared when available.',
				'filters'  => array(
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
				'months'   => array(
					array(
						'label'  => 'September',
						'year'   => '2025',
						'events' => array(
							array( 'kind' => 'holiday', 'date' => 'Sep 01', 'day' => 'Monday', 'name' => 'Labor Day', 'tag' => 'No lessons', 'time' => 'Holiday' ),
							array( 'kind' => 'lesson', 'date' => 'Sep 02', 'day' => 'Tuesday', 'name' => 'Lessons & Labs Begin', 'tag' => '', 'time' => 'Assigned time' ),
							array( 'kind' => 'makeup', 'date' => 'Sep 13', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
						),
					),
					array(
						'label'  => 'October',
						'year'   => '2025',
						'events' => array(
							array( 'kind' => 'makeup', 'date' => 'Oct 11', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
						),
					),
					array(
						'label'  => 'November',
						'year'   => '2025',
						'events' => array(
							array( 'kind' => 'performance', 'date' => 'Nov · TBD', 'day' => 'Sunday', 'name' => 'HCPTA Student Recital', 'tag' => 'By invitation', 'time' => 'Afternoon TBD' ),
							array( 'kind' => 'makeup', 'date' => 'Nov 08', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
							array( 'kind' => 'holiday', 'date' => 'Nov 24–28', 'day' => 'Mon–Fri', 'name' => 'Thanksgiving Break', 'tag' => 'No lessons', 'time' => 'Holiday' ),
						),
					),
					array(
						'label'  => 'December',
						'year'   => '2025',
						'events' => array(
							array( 'kind' => 'makeup', 'date' => 'Dec 13', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
							array( 'kind' => 'performance', 'date' => 'Dec · TBD', 'day' => 'Saturday', 'name' => 'HCPTA Student Holiday Concert', 'tag' => 'By invitation', 'time' => 'TBD' ),
							array( 'kind' => 'holiday', 'date' => 'Dec 22–Jan 01', 'day' => 'Holiday', 'name' => 'Winter Celebrations', 'tag' => 'No lessons', 'time' => 'Studio closed' ),
						),
					),
					array(
						'label'  => 'January',
						'year'   => '2026',
						'events' => array(
							array( 'kind' => 'lesson', 'date' => 'Jan 06', 'day' => 'Monday', 'name' => 'Lessons Resume', 'tag' => '', 'time' => 'Assigned time' ),
							array( 'kind' => 'performance', 'date' => 'Jan 10', 'day' => 'Saturday', 'name' => 'Recital Preparation Class', 'tag' => 'Required', 'time' => '10:00–11:00 AM' ),
							array( 'kind' => 'makeup', 'date' => 'Jan 10', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30–9:45 / 11–Noon' ),
							array( 'kind' => 'performance', 'date' => 'Jan 11', 'day' => 'Sunday', 'name' => 'Student Recital', 'tag' => 'Grove Presbyterian', 'time' => '5:00 PM' ),
						),
					),
					array(
						'label'  => 'February',
						'year'   => '2026',
						'events' => array(
							array( 'kind' => 'makeup', 'date' => 'Feb 14', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
						),
					),
					array(
						'label'  => 'March',
						'year'   => '2026',
						'events' => array(
							array( 'kind' => 'holiday', 'date' => 'Mar 08–13', 'day' => 'Mon–Fri', 'name' => 'Scheduled Break', 'tag' => 'No lessons', 'time' => 'Studio closed' ),
							array( 'kind' => 'makeup', 'date' => 'Mar 21', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
							array( 'kind' => 'holiday', 'date' => 'Mar 30–Apr 03', 'day' => 'Mon–Fri', 'name' => 'Holy Days', 'tag' => 'No lessons', 'time' => 'Studio closed' ),
						),
					),
					array(
						'label'  => 'April',
						'year'   => '2026',
						'events' => array(
							array( 'kind' => 'performance', 'date' => 'Apr · TBD', 'day' => 'Saturday', 'name' => 'HCPTA Annual Piano Festival', 'tag' => 'By invitation', 'time' => 'Approx. 1.5 hrs' ),
							array( 'kind' => 'makeup', 'date' => 'Apr 11', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
						),
					),
					array(
						'label'  => 'May',
						'year'   => '2026',
						'events' => array(
							array( 'kind' => 'makeup', 'date' => 'May 09', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30 AM–12 Noon' ),
							array( 'kind' => 'holiday', 'date' => 'May 25', 'day' => 'Monday', 'name' => 'Memorial Day', 'tag' => 'No lessons', 'time' => 'Holiday' ),
						),
					),
					array(
						'label'  => 'June',
						'year'   => '2026',
						'events' => array(
							array( 'kind' => 'makeup', 'date' => 'Jun 13', 'day' => 'Saturday', 'name' => 'Make-up Lessons', 'tag' => '', 'time' => '8:30–10:00 AM' ),
							array( 'kind' => 'performance', 'date' => 'Jun 13', 'day' => 'Saturday', 'name' => 'Recital Preparation Class', 'tag' => 'Required', 'time' => '10:00–11:30 AM' ),
							array( 'kind' => 'performance', 'date' => 'Jun 14', 'day' => 'Sunday', 'name' => 'Student Recital', 'tag' => 'Grove Presbyterian', 'time' => '5:00 PM' ),
							array( 'kind' => 'lesson', 'date' => 'Jun 19', 'day' => 'Friday', 'name' => 'End of Spring Semester', 'tag' => '', 'time' => 'By appointment' ),
						),
					),
				),
			),

			'summer' => array(
				'eyebrow'      => 'Summer Program',
				'heading'      => 'July and <em>August</em>',
				'text'         => 'Lessons continue through the summer on a flexible schedule, so students keep the habit — and their hands — through the long break.',
				'button_label' => 'Arrange Summer Lessons',
				'button_url'   => '/#contact',
				'items'        => array(
					array(
						'letter' => 'A',
						'title'  => 'Summer Lesson Schedules',
						'text'   => 'July and August lessons are arranged month by month rather than held to the term timetable, so families can fit them around camps and travel.',
					),
					array(
						'letter' => 'B',
						'title'  => 'Vacation Accommodation',
						'text'   => 'Both family and teacher vacations are planned around. Let the studio know your dates and the schedule is built to suit them.',
					),
				),
			),

			'cta' => array(
				'eyebrow' => 'Questions',
				'heading' => 'Need a date <em>confirmed?</em>',
				'lead'    => 'Make-up sessions, recital times and travel details are always easiest to settle with a quick call.',
				'buttons' => array(
					array(
						'label' => 'Contact Us',
						'url'   => '/#contact',
						'style' => '',
					),
					array(
						'label' => 'Back to Top',
						'url'   => '#top',
						'style' => 'cta-ghost',
					),
				),
				'meta'    => array(
					array(
						'label' => 'Call the studio',
						'value' => '443-243-3319',
						'url'   => 'tel:4432433319',
					),
					array(
						'label' => 'Email',
						'value' => 'irenekyeakel@gmail.com',
						'url'   => 'mailto:irenekyeakel@gmail.com',
					),
					array(
						'label' => 'Recitals',
						'value' => 'January & June',
						'url'   => '',
					),
				),
			),
		),

		/* ---------------------------------------------------------------
		   Testimonials
		   --------------------------------------------------------------- */

		'page_testimonials' => array(

			'hero' => array(
				'image'   => 'https://images.unsplash.com/photo-1552422535-c45813c61732?auto=format&fit=crop&w=2000&q=88',
				'eyebrow' => 'In Their Words',
				'title'   => 'What families say<br><em>about the studio</em>',
				'lead'    => 'Parents, students and adult learners on lessons at Aberdeen Piano — what changed, what surprised them, and what stayed.',
			),

			'featured' => array(
				'quote' => 'She did not just teach our daughter to play the piano. She taught her how to work at something difficult, week after week, and to enjoy it.',
				'name'  => 'Parent of a Grade 5 student',
				'meta'  => 'Six years at the studio',
			),

			'wall' => array(
				'eyebrow' => 'Testimonials',
				'heading' => 'Parents and <em>students</em>',
				'intro'   => 'A few of the notes families have sent over the years, shared with their permission.',
				'items'   => array(
					array(
						'kind'  => 'Parent',
						'mark'  => 'P',
						'dark'  => false,
						'quote' => 'We came for lessons and found a teacher who knows exactly when to push and when to wait. Our son practises without being asked now, which I never expected to write.',
						'name'  => 'Parent of an elementary student',
						'meta'  => 'Aberdeen, MD',
					),
					array(
						'kind'  => 'Student',
						'mark'  => 'S',
						'dark'  => true,
						'quote' => 'I was terrified of the first recital. By the third I was choosing my own piece and asking to go later in the programme so I could play something longer.',
						'name'  => 'High school student',
						'meta'  => 'Four years of lessons',
					),
					array(
						'kind'  => 'Adult learner',
						'mark'  => 'A',
						'dark'  => false,
						'quote' => 'I stopped playing at fifteen and started again at fifty-two. Irene picked up exactly where I had left off, without ever making me feel behind.',
						'name'  => 'Adult student',
						'meta'  => 'Returning after thirty years',
					),
					array(
						'kind'  => 'Parent',
						'mark'  => 'P',
						'dark'  => false,
						'quote' => 'The theory is taught alongside the music rather than as homework, so it never felt like a second subject. That made all the difference for our daughter.',
						'name'  => 'Parent of a middle school student',
						'meta'  => 'Harford County',
					),
					array(
						'kind'  => 'Student',
						'mark'  => 'S',
						'dark'  => true,
						'quote' => 'The lab sessions were my favourite part. Hearing a piece played properly before learning it made the notes on the page make sense.',
						'name'  => 'College student',
						'meta'  => 'Studied through Grade 8',
					),
					array(
						'kind'  => 'Parent',
						'mark'  => 'P',
						'dark'  => false,
						'quote' => 'Two children, seven years, one teacher. The studio has been one of the steadiest things in our family calendar and we are grateful for it.',
						'name'  => 'Parent of two students',
						'meta'  => 'Seven years at the studio',
					),
				),
			),

			'achievements' => array(
				'eyebrow' => 'Performance Achievements',
				'heading' => 'What students have <em>gone on to do</em>',
				'intro'   => 'Recitals played, festivals entered and certifications earned by Aberdeen Piano students.',
				'items'   => array(
					array(
						'title' => 'Adjudicated Recitals',
						'text'  => 'Students regularly perform at HCPTA adjudicated recitals and receive graded ratings and written evaluations.',
					),
					array(
						'title' => 'Annual Piano Festival',
						'text'  => 'Studio students take part in the association\'s annual festival each spring alongside students from across the county.',
					),
					array(
						'title' => 'ABRSM Certification',
						'text'  => 'Students following the ABRSM pathway have progressed through the graded examinations toward advanced certification.',
					),
					array(
						'title' => 'School Accompaniment',
						'text'  => 'Older students have gone on to accompany school choirs and ensembles, reading and playing under real performance pressure.',
					),
				),
			),

			'cta' => array(
				'eyebrow' => 'Share Yours',
				'heading' => 'Been a student <em>here?</em>',
				'lead'    => 'The studio would love to hear how the lessons have stayed with you. Send a note and it may appear on this page.',
				'buttons' => array(
					array(
						'label' => 'Send a Testimonial',
						'url'   => '/#contact',
						'style' => '',
					),
					array(
						'label' => 'View Tuition',
						'url'   => '/#pricing',
						'style' => 'cta-ghost',
					),
				),
				'meta'    => array(
					array(
						'label' => 'Call the studio',
						'value' => '443-243-3319',
						'url'   => 'tel:4432433319',
					),
					array(
						'label' => 'Email',
						'value' => 'irenekyeakel@gmail.com',
						'url'   => 'mailto:irenekyeakel@gmail.com',
					),
					array(
						'label' => 'Teaching since',
						'value' => '1979',
						'url'   => '',
					),
				),
			),
		),

		/* ---------------------------------------------------------------
		   Gallery
		   --------------------------------------------------------------- */

		'page_gallery' => array(

			'hero' => array(
				'image'   => 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&w=2000&q=88',
				'eyebrow' => 'Photographs',
				'title'   => 'A year at<br><em>the keyboard</em>',
				'lead'    => 'Recitals, festivals, lab sessions and studio occasions — the moments that make up a year of learning at Aberdeen Piano.',
			),

			'gallery' => array(
				'eyebrow'    => 'Gallery',
				'heading'    => 'Browse by <em>occasion</em>',
				'intro'      => 'Photographs are added after each recital, festival and studio event through the year.',
				'note'       => 'Photographs of students are published only with a parent or guardian\'s permission.',
				'all_label'  => 'All photographs',
				'categories' => array(
					array(
						'key'   => 'recitals',
						'label' => 'Recitals',
					),
					array(
						'key'   => 'festivals',
						'label' => 'Festivals',
					),
					array(
						'key'   => 'activities',
						'label' => 'Student Activities',
					),
					array(
						'key'   => 'studio',
						'label' => 'Studio Events',
					),
				),
				'photos'     => array(
					array(
						'kind'  => 'recitals',
						'image' => 'https://images.unsplash.com/photo-1552422535-c45813c61732?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'A student performing at the winter recital',
						'title' => 'Winter Student Recital',
						'meta'  => 'Grove Presbyterian · January',
					),
					array(
						'kind'  => 'festivals',
						'image' => 'https://images.unsplash.com/photo-1466428996289-fb355538da1b?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'Close-up of hands at the keyboard during the festival',
						'title' => 'Annual Piano Festival',
						'meta'  => 'HCPTA · April',
					),
					array(
						'kind'  => 'activities',
						'image' => 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'Headphones and sheet music in the studio lab',
						'title' => 'Audio-Visual Lab',
						'meta'  => 'Listening session',
					),
					array(
						'kind'  => 'studio',
						'image' => 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'The teaching studio with an upright piano',
						'title' => 'The Studio',
						'meta'  => 'Aberdeen, Maryland',
					),
					array(
						'kind'  => 'recitals',
						'image' => 'https://images.unsplash.com/photo-1481886756534-97af88ccb438?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'Sheet music laid out for a recital piece',
						'title' => 'Recital Preparation Class',
						'meta'  => 'Before the June recital',
					),
					array(
						'kind'  => 'activities',
						'image' => 'https://images.unsplash.com/photo-1519683384663-c9b34271669a?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'Shelves of music books in the studio library',
						'title' => 'Music Library',
						'meta'  => 'Choosing repertoire',
					),
					array(
						'kind'  => 'studio',
						'image' => 'https://images.unsplash.com/photo-1571974599782-87624638275e?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'A grand piano lit for the holiday concert',
						'title' => 'Holiday Concert',
						'meta'  => 'HCPTA · December',
					),
					array(
						'kind'  => 'festivals',
						'image' => 'https://images.unsplash.com/photo-1465821185615-20b3c2fbf41b?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'A piano keyboard photographed from above',
						'title' => 'Festival Adjudication',
						'meta'  => 'Written evaluations',
					),
					array(
						'kind'  => 'recitals',
						'image' => 'https://images.unsplash.com/photo-1552422535-c45813c61732?auto=format&fit=crop&q=80&w=1400',
						'alt'   => 'A student at the piano during the spring recital',
						'title' => 'Spring Student Recital',
						'meta'  => 'Grove Presbyterian · June',
					),
				),
			),

			'cta' => array(
				'eyebrow' => 'Join Us',
				'heading' => 'Be in next year\'s <em>photographs</em>',
				'lead'    => 'Every student on this page started with a first lesson. There is room at the bench for another.',
				'buttons' => array(
					array(
						'label' => 'Enroll Today',
						'url'   => '/#contact',
						'style' => '',
					),
					array(
						'label' => 'View Calendar',
						'url'   => '/#calendar',
						'style' => 'cta-ghost',
					),
				),
				'meta'    => array(
					array(
						'label' => 'Call the studio',
						'value' => '443-243-3319',
						'url'   => 'tel:4432433319',
					),
					array(
						'label' => 'Email',
						'value' => 'irenekyeakel@gmail.com',
						'url'   => 'mailto:irenekyeakel@gmail.com',
					),
					array(
						'label' => 'Recitals',
						'value' => 'Two per school year',
						'url'   => '',
					),
				),
			),
		),
	);
}

<?php
/**
 * Template Name: Calendar of Events
 *
 * The full studio calendar grouped by month, plus the summer program.
 *
 * The filter bar is driven by the generic filter in assets/js/theme.js: it
 * hides events by data-kind and hides any month that empties as a result. The
 * colour of each filter dot and each event's spine comes from its kind, so a
 * new kind needs a matching `.dot.is-<kind>` rule in style.css.
 *
 * Every string and image comes from ACF through the aberdeen_piano_field() /
 * _rows() helpers, which fall back to inc/defaults-pages.php so the page always
 * matches the approved design.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image  = aberdeen_piano_field( 'ap_calendar_page_hero_image', 'page_calendar.hero.image' );
$ap_filters     = aberdeen_piano_rows( 'ap_calendar_page_filters', 'page_calendar.year.filters' );
$ap_months      = aberdeen_piano_rows( 'ap_calendar_page_months', 'page_calendar.year.months' );
$ap_footnote    = aberdeen_piano_field( 'ap_calendar_page_year_footnote', 'page_calendar.year.footnote' );
$ap_summer_url  = aberdeen_piano_page_url( aberdeen_piano_field( 'ap_calendar_page_summer_button_url', 'page_calendar.summer.button_url' ) );
$ap_summer_rows = aberdeen_piano_rows( 'ap_calendar_page_summer_items', 'page_calendar.summer.items' );
?>
	<main class="calendar-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero calendar-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_calendar.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow calendar-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_page_hero_eyebrow', 'page_calendar.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_calendar_page_hero_title', 'page_calendar.hero.title' ); ?></h1>
				<p class="calendar-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_page_hero_lead', 'page_calendar.hero.lead' ) ); ?></p>
			</div>
		</section>

		<?php // The calendar. ?>
		<section class="calendar-year" id="calendar" data-filter-scope>
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_page_year_eyebrow', 'page_calendar.year.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_calendar_page_year_heading', 'page_calendar.year.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_page_year_intro', 'page_calendar.year.intro' ) ); ?></p>
				</div>

				<?php if ( $ap_filters ) : ?>
				<div class="filter-bar reveal" role="group" aria-label="<?php esc_attr_e( 'Filter calendar', 'aberdeen-piano' ); ?>">
					<?php
					foreach ( $ap_filters as $ap_index => $ap_filter ) :
						$ap_key    = sanitize_key( aberdeen_piano_row( $ap_filter, 'key' ) );
						$ap_active = ( 0 === $ap_index );
						?>
					<button type="button" class="filter<?php echo $ap_active ? ' active' : ''; ?>" data-filter="<?php echo esc_attr( $ap_key ); ?>" aria-pressed="<?php echo $ap_active ? 'true' : 'false'; ?>">
						<i class="dot is-<?php echo esc_attr( $ap_key ); ?>" aria-hidden="true"></i><?php echo esc_html( aberdeen_piano_row( $ap_filter, 'label' ) ); ?>
					</button>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<div class="calendar-months">
					<?php foreach ( $ap_months as $ap_month ) : ?>
					<section class="cal-month reveal" data-filter-group>
						<header class="cal-month-head">
							<h3><?php echo esc_html( aberdeen_piano_row( $ap_month, 'label' ) ); ?> <span><?php echo esc_html( aberdeen_piano_row( $ap_month, 'year' ) ); ?></span></h3>
						</header>
						<ul class="cal-events">
							<?php
							foreach ( (array) aberdeen_piano_row( $ap_month, 'events', array() ) as $ap_event ) :
								$ap_tag = aberdeen_piano_row( $ap_event, 'tag' );
								?>
							<li class="cal-event" data-kind="<?php echo esc_attr( sanitize_key( aberdeen_piano_row( $ap_event, 'kind' ) ) ); ?>">
								<span class="cal-date"><?php echo esc_html( aberdeen_piano_row( $ap_event, 'date' ) ); ?><small><?php echo esc_html( aberdeen_piano_row( $ap_event, 'day' ) ); ?></small></span>
								<span class="cal-name">
									<?php echo esc_html( aberdeen_piano_row( $ap_event, 'name' ) ); ?>
									<?php if ( $ap_tag ) : ?>
									<em class="cal-tag"><?php echo esc_html( $ap_tag ); ?></em>
									<?php endif; ?>
								</span>
								<span class="cal-time"><?php echo esc_html( aberdeen_piano_row( $ap_event, 'time' ) ); ?></span>
							</li>
							<?php endforeach; ?>
						</ul>
					</section>
					<?php endforeach; ?>
				</div>

				<?php if ( $ap_footnote ) : ?>
				<p class="calendar-footnote reveal"><?php echo esc_html( $ap_footnote ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<?php // Summer program. ?>
		<section class="summer-program" id="summer">
			<div class="wrap">
				<div class="summer-panel reveal">
					<div class="summer-intro">
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_page_summer_eyebrow', 'page_calendar.summer.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_calendar_page_summer_heading', 'page_calendar.summer.heading' ); ?></h2>
						<p><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_page_summer_text', 'page_calendar.summer.text' ) ); ?></p>
						<?php if ( $ap_summer_url ) : ?>
						<a class="btn" href="<?php echo esc_url( $ap_summer_url ); ?>"><?php echo esc_html( aberdeen_piano_field( 'ap_calendar_page_summer_button_label', 'page_calendar.summer.button_label' ) ); ?></a>
						<?php endif; ?>
					</div>
					<ul class="summer-list">
						<?php foreach ( $ap_summer_rows as $ap_item ) : ?>
						<li>
							<span class="summer-num"><?php echo esc_html( aberdeen_piano_row( $ap_item, 'letter' ) ); ?></span>
							<h3><?php echo esc_html( aberdeen_piano_row( $ap_item, 'title' ) ); ?></h3>
							<p><?php aberdeen_piano_the_paragraph( $ap_item ); ?></p>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</section>

		<?php
		// Closing panel.
		get_template_part(
			'template-parts/page/cta',
			null,
			array(
				'eyebrow' => aberdeen_piano_field( 'ap_calendar_page_cta_eyebrow', 'page_calendar.cta.eyebrow' ),
				'heading' => array( 'ap_calendar_page_cta_heading', 'page_calendar.cta.heading' ),
				'lead'    => aberdeen_piano_field( 'ap_calendar_page_cta_lead', 'page_calendar.cta.lead' ),
				'buttons' => aberdeen_piano_rows( 'ap_calendar_page_cta_buttons', 'page_calendar.cta.buttons' ),
				'meta'    => aberdeen_piano_rows( 'ap_calendar_page_cta_meta', 'page_calendar.cta.meta' ),
			)
		);
		?>

	</main>
<?php
get_footer();

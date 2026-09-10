<?php
/**
 * Template Name: ABRSM Program
 *
 * An overview of the ABRSM, the certification pathway and examination process,
 * curriculum structure and student requirements, the additional commitments
 * families should know about, the abrsm.org reference and the consultation.
 *
 * Every string and image comes from ACF through the aberdeen_piano_field() /
 * _rows() helpers, which fall back to inc/defaults-pages.php so the page always
 * matches the approved design.
 *
 * @package Aberdeen_Piano
 */

get_header();

$ap_hero_image    = aberdeen_piano_field( 'ap_abrsm_hero_image', 'page_abrsm.hero.image' );
$ap_resource_url  = aberdeen_piano_page_url( aberdeen_piano_field( 'ap_abrsm_resource_button_url', 'page_abrsm.resource.button_url' ) );
$ap_overview_note = aberdeen_piano_field( 'ap_abrsm_overview_facts_note', 'page_abrsm.overview.facts_note' );
?>
	<main class="abrsm-page page-interior">

		<?php // Hero. ?>
		<section class="article-hero abrsm-hero" id="top" style="--hero-image:url('<?php echo esc_url( aberdeen_piano_image_url( $ap_hero_image, aberdeen_piano_default( 'page_abrsm.hero.image' ), 'full' ) ); ?>')">
			<div class="wrap article-hero-content">
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aberdeen-piano' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aberdeen-piano' ); ?></a>
					<span>/</span><span class="current"><?php the_title(); ?></span>
				</nav>
				<div class="eyebrow abrsm-hero-eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_hero_eyebrow', 'page_abrsm.hero.eyebrow' ) ); ?></div>
				<h1><?php aberdeen_piano_the_heading( 'ap_abrsm_hero_title', 'page_abrsm.hero.title' ); ?></h1>
				<p class="abrsm-hero-lead"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_hero_lead', 'page_abrsm.hero.lead' ) ); ?></p>
			</div>
		</section>

		<?php
		// Anchor bar.
		get_template_part(
			'template-parts/page/jump-bar',
			null,
			array(
				'items' => aberdeen_piano_rows( 'ap_abrsm_index_items', 'page_abrsm.index.items' ),
				'label' => __( 'On this page', 'aberdeen-piano' ),
			)
		);
		?>

		<?php // 1 — Overview. ?>
		<section class="abrsm-overview" id="overview">
			<div class="wrap abrsm-overview-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_overview_eyebrow', 'page_abrsm.overview.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_abrsm_overview_heading', 'page_abrsm.overview.heading' ); ?></h2>
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_overview_lead', 'page_abrsm.overview.lead' ) ); ?></p>
					<?php foreach ( aberdeen_piano_rows( 'ap_abrsm_overview_paragraphs', 'page_abrsm.overview.paragraphs' ) as $ap_paragraph ) : ?>
					<p><?php aberdeen_piano_the_paragraph( $ap_paragraph ); ?></p>
					<?php endforeach; ?>
				</div>

				<aside class="abrsm-facts reveal">
					<span class="label"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_overview_facts_label', 'page_abrsm.overview.facts_label' ) ); ?></span>
					<dl>
						<?php foreach ( aberdeen_piano_rows( 'ap_abrsm_overview_facts', 'page_abrsm.overview.facts' ) as $ap_fact ) : ?>
						<div>
							<dt><?php echo esc_html( aberdeen_piano_row( $ap_fact, 'label' ) ); ?></dt>
							<dd><?php echo esc_html( aberdeen_piano_row( $ap_fact, 'value' ) ); ?></dd>
						</div>
						<?php endforeach; ?>
					</dl>
					<?php if ( $ap_overview_note ) : ?>
					<p class="abrsm-facts-note"><?php echo esc_html( $ap_overview_note ); ?></p>
					<?php endif; ?>
				</aside>
			</div>
		</section>

		<?php // The grade ladder. ?>
		<?php $ap_rungs = aberdeen_piano_rows( 'ap_abrsm_ladder_items', 'page_abrsm.ladder.items' ); ?>
		<?php if ( $ap_rungs ) : ?>
		<section class="abrsm-ladder depth-scene">
			<div class="wrap">
				<div class="ladder-head reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_ladder_eyebrow', 'page_abrsm.ladder.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_abrsm_ladder_heading', 'page_abrsm.ladder.heading' ); ?></h2>
				</div>
				<ol class="ladder reveal">
					<?php foreach ( $ap_rungs as $ap_rung ) : ?>
					<li<?php echo aberdeen_piano_row_bool( $ap_rung, 'crest' ) ? ' class="is-crest"' : ''; ?>>
						<span class="rung"><?php echo esc_html( aberdeen_piano_row( $ap_rung, 'rung' ) ); ?></span>
						<p><?php aberdeen_piano_the_paragraph( $ap_rung ); ?></p>
					</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</section>
		<?php endif; ?>

		<?php // 2 — Content: pathways, examination, curriculum, requirements. ?>
		<section class="abrsm-content" id="pathway">
			<div class="wrap">
				<div class="section-head reveal">
					<div>
						<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_content_eyebrow', 'page_abrsm.content.eyebrow' ) ); ?></div>
						<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_abrsm_content_heading', 'page_abrsm.content.heading' ); ?></h2>
					</div>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_content_intro', 'page_abrsm.content.intro' ) ); ?></p>
				</div>

				<div class="abrsm-grid">
					<?php foreach ( aberdeen_piano_rows( 'ap_abrsm_content_cards', 'page_abrsm.content.cards' ) as $ap_card ) : ?>
					<article class="info-card reveal">
						<span class="card-num"><?php echo esc_html( aberdeen_piano_row( $ap_card, 'number' ) ); ?></span>
						<h3><?php echo esc_html( aberdeen_piano_row( $ap_card, 'title' ) ); ?></h3>
						<p><?php aberdeen_piano_the_paragraph( $ap_card ); ?></p>
						<?php $ap_items = (array) aberdeen_piano_row( $ap_card, 'items', array() ); ?>
						<?php if ( $ap_items ) : ?>
						<ul class="tick-list">
							<?php foreach ( $ap_items as $ap_item ) : ?>
							<li><?php echo esc_html( aberdeen_piano_row( $ap_item, 'text' ) ); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>
					</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // 3 — Additional information. ?>
		<section class="abrsm-commitment" id="commitment">
			<div class="wrap journey-grid">
				<div class="reveal">
					<div class="eyebrow"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_commitment_eyebrow', 'page_abrsm.commitment.eyebrow' ) ); ?></div>
					<h2 class="section-title"><?php aberdeen_piano_the_heading( 'ap_abrsm_commitment_heading', 'page_abrsm.commitment.heading' ); ?></h2>
					<p class="lead"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_commitment_lead', 'page_abrsm.commitment.lead' ) ); ?></p>
					<p><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_commitment_paragraph', 'page_abrsm.commitment.paragraph' ) ); ?></p>
				</div>
				<div class="steps reveal">
					<?php foreach ( aberdeen_piano_rows( 'ap_abrsm_commitment_steps', 'page_abrsm.commitment.steps' ) as $ap_step ) : ?>
					<div class="step">
						<span><?php echo esc_html( aberdeen_piano_row( $ap_step, 'number' ) ); ?></span>
						<div>
							<h3><?php echo esc_html( aberdeen_piano_row( $ap_step, 'title' ) ); ?></h3>
							<p><?php aberdeen_piano_the_paragraph( $ap_step, 'description' ); ?></p>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php // External resource. ?>
		<section class="abrsm-resource">
			<div class="wrap">
				<div class="resource-band reveal">
					<div>
						<span class="method-label"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_resource_label', 'page_abrsm.resource.label' ) ); ?></span>
						<h2><?php aberdeen_piano_the_heading( 'ap_abrsm_resource_heading', 'page_abrsm.resource.heading' ); ?></h2>
						<p><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_resource_text', 'page_abrsm.resource.text' ) ); ?></p>
					</div>
					<?php if ( $ap_resource_url ) : ?>
					<a class="btn" href="<?php echo esc_url( $ap_resource_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( aberdeen_piano_field( 'ap_abrsm_resource_button_label', 'page_abrsm.resource.button_label' ) ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php
		// 4 — Consultation, using the shared studio panel.
		get_template_part(
			'template-parts/page/cta',
			null,
			array(
				'id'      => 'consultation',
				'eyebrow' => aberdeen_piano_field( 'ap_abrsm_cta_eyebrow', 'page_abrsm.cta.eyebrow' ),
				'heading' => array( 'ap_abrsm_cta_heading', 'page_abrsm.cta.heading' ),
				'lead'    => aberdeen_piano_field( 'ap_abrsm_cta_lead', 'page_abrsm.cta.lead' ),
				'buttons' => aberdeen_piano_rows( 'ap_abrsm_cta_buttons', 'page_abrsm.cta.buttons' ),
				'meta'    => aberdeen_piano_rows( 'ap_abrsm_cta_meta', 'page_abrsm.cta.meta' ),
			)
		);
		?>

	</main>
<?php
get_footer();

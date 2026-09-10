<?php
/**
 * Theme header: loader, top line and navigation.
 *
 * @package Aberdeen_Piano
 */

$ap_email = aberdeen_piano_email();
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php wp_head(); ?>
	<?php // The loader locks scrolling until script releases it; without JS it must never lock. ?>
	<noscript>
		<style>.loader{display:none!important}body.locked{overflow:visible!important}.reveal{opacity:1!important;transform:none!important;filter:none!important}</style>
	</noscript>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<div class="scroll-progress" aria-hidden="true"></div>
	<div class="cursor-glow" aria-hidden="true"></div><i class="ambient one" aria-hidden="true"></i><i class="ambient two" aria-hidden="true"></i>
	<?php $ap_loader = aberdeen_piano_loader(); ?>
	<div class="loader" data-duration="<?php echo esc_attr( $ap_loader['duration'] ); ?>" aria-label="<?php echo esc_attr( $ap_loader['label'] ); ?>">
		<div class="loader-inner"><img class="loader-logo" src="<?php echo esc_url( aberdeen_piano_logo_url() ); ?>" alt="<?php echo esc_attr( aberdeen_piano_brand_name() ); ?>">
			<div class="loader-mark"><?php echo esc_html( $ap_loader['mark'] ); ?></div>
			<div class="keys" aria-hidden="true"><i class="key"></i><i class="key black"></i><i class="key"></i><i class="key black"></i><i class="key"></i><i class="key"></i><i class="key black"></i><i class="key"></i><i class="key black"></i><i class="key"></i><i class="key black"></i><i class="key"></i><i class="key"></i><i class="key black"></i><i class="key"></i></div>
			<p><?php echo esc_html( $ap_loader['message'] ); ?></p>
			<div class="loader-progress"><span></span></div>
			<div class="loader-count" aria-live="polite">00</div>
		</div>
	</div>
	<div class="topline">
		<div class="wrap">
			<span><?php echo esc_html( aberdeen_piano_option( 'ap_topline_text', 'global.topline_text' ) ); ?></span>
			<span><a href="mailto:<?php echo esc_attr( $ap_email ); ?>"><?php echo esc_html( aberdeen_piano_option( 'ap_topline_label', 'global.topline_label' ) ); ?></a></span>
		</div>
	</div>
	<header class="nav">
		<div class="wrap nav-inner">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Aberdeen Piano Instruction home', 'aberdeen-piano' ); ?>">
				<img class="brand-logo" src="<?php echo esc_url( aberdeen_piano_logo_url() ); ?>" alt="<?php echo esc_attr( aberdeen_piano_brand_name() ); ?>">
				<span><strong><?php echo esc_html( aberdeen_piano_brand_name() ); ?></strong></span>
			</a>
			<?php aberdeen_piano_nav_menu( 'primary' ); ?>
			<button class="menu" aria-label="<?php esc_attr_e( 'Open menu', 'aberdeen-piano' ); ?>">&#9776;</button>
		</div>
	</header>

<?php
/**
 * Header situs.
 *
 * @package Nusantara_Landing
 */

$nusantara_kontak_url = is_front_page() ? '#kontak' : home_url( '/#kontak' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<script>document.documentElement.className = document.documentElement.className.replace( 'no-js', 'js' );</script>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Lewati ke konten', 'nusantara-landing' ); ?></a>

<header id="masthead" class="site-header" data-sticky-header>
	<div class="container header-inner">

		<div class="site-branding">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="logo-mark" aria-hidden="true"><?php echo nusantara_icon( 'spark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?></span>
					<span class="logo-text"><?php bloginfo( 'name' ); ?></span>
				</a>
				<?php
			}
			?>
		</div>

		<nav class="main-nav" aria-label="<?php esc_attr_e( 'Menu utama', 'nusantara-landing' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'depth'          => 2,
					'fallback_cb'    => 'nusantara_nav_fallback',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<a class="btn btn--primary btn--sm" href="<?php echo esc_url( $nusantara_kontak_url ); ?>">
				<?php esc_html_e( 'Konsultasi Gratis', 'nusantara-landing' ); ?>
			</a>

			<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" data-nav-toggle
				data-label-open="<?php esc_attr_e( 'Buka menu', 'nusantara-landing' ); ?>"
				data-label-close="<?php esc_attr_e( 'Tutup menu', 'nusantara-landing' ); ?>">
				<span aria-hidden="true"></span>
				<span aria-hidden="true"></span>
				<span aria-hidden="true"></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Buka menu', 'nusantara-landing' ); ?></span>
			</button>
		</div>
	</div>

	<div id="mobile-menu" class="mobile-menu" hidden>
		<div class="container">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list-mobile',
					'depth'          => 2,
					'fallback_cb'    => 'nusantara_nav_fallback',
				)
			);
			?>
			<a class="btn btn--primary btn--block" href="<?php echo esc_url( $nusantara_kontak_url ); ?>">
				<?php esc_html_e( 'Konsultasi Gratis', 'nusantara-landing' ); ?>
			</a>
		</div>
	</div>
</header>

<div id="content" class="site-content">

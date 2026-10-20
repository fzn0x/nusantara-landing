<?php
/**
 * Footer situs.
 *
 * @package Nusantara_Landing
 */

$nusantara_socials = nusantara_socials();
$nusantara_wa      = nusantara_wa_link();
$nusantara_layanan = nusantara_layanan_items();
$nusantara_site    = nusantara_get_mod( 'footer_copyright' );
$nusantara_site    = '' !== trim( $nusantara_site ) ? $nusantara_site : get_bloginfo( 'name' );
?>
	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-grid">

				<div class="footer-brand">
					<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<span class="logo-mark" aria-hidden="true"><?php echo nusantara_icon( 'spark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?></span>
						<span class="logo-text"><?php bloginfo( 'name' ); ?></span>
					</a>

					<p><?php echo esc_html( nusantara_get_mod( 'footer_about' ) ); ?></p>

					<?php if ( ! empty( $nusantara_socials ) ) : ?>
						<ul class="social-list">
							<?php foreach ( $nusantara_socials as $nusantara_slug => $nusantara_social ) : ?>
								<li>
									<a href="<?php echo esc_url( $nusantara_social['url'] ); ?>"
										target="_blank" rel="noopener noreferrer"
										aria-label="<?php echo esc_attr( $nusantara_social['label'] ); ?>">
										<?php echo nusantara_icon( $nusantara_slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<div>
					<h4><?php esc_html_e( 'Navigasi', 'nusantara-landing' ); ?></h4>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
							'fallback_cb'    => 'nusantara_nav_fallback',
						)
					);
					?>
				</div>

				<div>
					<h4><?php esc_html_e( 'Layanan', 'nusantara-landing' ); ?></h4>
					<ul>
						<?php foreach ( array_slice( $nusantara_layanan, 0, 5 ) as $nusantara_item ) : ?>
							<li><?php echo esc_html( $nusantara_item['title'] ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div>
					<h4><?php esc_html_e( 'Hubungi Kami', 'nusantara-landing' ); ?></h4>
					<ul>
						<?php if ( nusantara_has_mod( 'contact_address' ) ) : ?>
							<li><?php echo nl2br( esc_html( nusantara_get_mod( 'contact_address' ) ) ); ?></li>
						<?php endif; ?>

						<?php if ( nusantara_has_mod( 'contact_phone' ) ) : ?>
							<li>
								<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', nusantara_get_mod( 'contact_phone' ) ) ); ?>">
									<?php echo esc_html( nusantara_get_mod( 'contact_phone' ) ); ?>
								</a>
							</li>
						<?php endif; ?>

						<?php if ( nusantara_has_mod( 'contact_email' ) ) : ?>
							<li>
								<a href="mailto:<?php echo esc_attr( nusantara_get_mod( 'contact_email' ) ); ?>">
									<?php echo esc_html( nusantara_get_mod( 'contact_email' ) ); ?>
								</a>
							</li>
						<?php endif; ?>

						<?php if ( nusantara_has_mod( 'contact_hours' ) ) : ?>
							<li><?php echo esc_html( nusantara_get_mod( 'contact_hours' ) ); ?></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>

			<div class="footer-bottom">
				<p>
					&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?>
					<?php echo esc_html( $nusantara_site ); ?>. <?php esc_html_e( 'Seluruh hak cipta dilindungi.', 'nusantara-landing' ); ?>
				</p>
				<p><?php esc_html_e( 'Dibangun dengan WordPress.', 'nusantara-landing' ); ?></p>
			</div>
		</div>
	</footer>

	<?php if ( '' !== $nusantara_wa ) : ?>
		<a class="wa-float" href="<?php echo esc_url( $nusantara_wa ); ?>" target="_blank" rel="noopener noreferrer"
			aria-label="<?php esc_attr_e( 'Hubungi kami lewat WhatsApp', 'nusantara-landing' ); ?>">
			<?php echo nusantara_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
		</a>
	<?php endif; ?>

	<?php wp_footer(); ?>
</body>
</html>

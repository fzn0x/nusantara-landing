<?php
/**
 * Section kontak: informasi + formulir.
 *
 * @package Nusantara_Landing
 */

$nusantara_socials = nusantara_socials();
$nusantara_map     = nusantara_get_mod( 'contact_map' );
?>
<section id="kontak" class="section section--surface">
	<div class="container">

		<?php
		nusantara_section_head(
			nusantara_get_mod( 'kontak_title' ),
			nusantara_get_mod( 'kontak_subtitle' ),
			__( 'Kontak', 'nusantara-landing' )
		);
		?>

		<div class="contact-inner">

			<div class="contact-info reveal">
				<ul class="contact-list">
					<?php if ( nusantara_has_mod( 'contact_address' ) ) : ?>
						<li>
							<span class="ci" aria-hidden="true"><?php echo nusantara_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?></span>
							<div>
								<b><?php esc_html_e( 'Kantor', 'nusantara-landing' ); ?></b>
								<p><?php echo nl2br( esc_html( nusantara_get_mod( 'contact_address' ) ) ); ?></p>
							</div>
						</li>
					<?php endif; ?>

					<?php if ( nusantara_has_mod( 'contact_phone' ) ) : ?>
						<li>
							<span class="ci" aria-hidden="true"><?php echo nusantara_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?></span>
							<div>
								<b><?php esc_html_e( 'Telepon', 'nusantara-landing' ); ?></b>
								<p>
									<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', nusantara_get_mod( 'contact_phone' ) ) ); ?>">
										<?php echo esc_html( nusantara_get_mod( 'contact_phone' ) ); ?>
									</a>
								</p>
							</div>
						</li>
					<?php endif; ?>

					<?php if ( nusantara_has_mod( 'contact_email' ) ) : ?>
						<li>
							<span class="ci" aria-hidden="true"><?php echo nusantara_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?></span>
							<div>
								<b><?php esc_html_e( 'Email', 'nusantara-landing' ); ?></b>
								<p>
									<a href="mailto:<?php echo esc_attr( nusantara_get_mod( 'contact_email' ) ); ?>">
										<?php echo esc_html( nusantara_get_mod( 'contact_email' ) ); ?>
									</a>
								</p>
							</div>
						</li>
					<?php endif; ?>

					<?php if ( nusantara_has_mod( 'contact_hours' ) ) : ?>
						<li>
							<span class="ci" aria-hidden="true"><?php echo nusantara_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?></span>
							<div>
								<b><?php esc_html_e( 'Jam Layanan', 'nusantara-landing' ); ?></b>
								<p><?php echo esc_html( nusantara_get_mod( 'contact_hours' ) ); ?></p>
							</div>
						</li>
					<?php endif; ?>
				</ul>

				<?php if ( ! empty( $nusantara_socials ) ) : ?>
					<ul class="social-list contact-social">
						<?php foreach ( $nusantara_socials as $nusantara_slug => $nusantara_social ) : ?>
							<li>
								<a href="<?php echo esc_url( $nusantara_social['url'] ); ?>" target="_blank" rel="noopener noreferrer"
									aria-label="<?php echo esc_attr( $nusantara_social['label'] ); ?>">
									<?php echo nusantara_icon( $nusantara_slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="reveal" data-delay="1">
				<?php nusantara_contact_notice(); ?>

				<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="nusantara_contact">
					<?php wp_nonce_field( 'nusantara_contact', 'nusantara_contact_nonce' ); ?>

					<div class="hp-field" aria-hidden="true">
						<label for="website_url">Website</label>
						<input type="text" id="website_url" name="website_url" value="" tabindex="-1" autocomplete="off">
					</div>

					<div class="form-row">
						<div class="field">
							<label for="nama"><?php esc_html_e( 'Nama lengkap', 'nusantara-landing' ); ?> <span class="req">*</span></label>
							<input type="text" id="nama" name="nama" required autocomplete="name"
								placeholder="<?php esc_attr_e( 'Nama Anda', 'nusantara-landing' ); ?>">
						</div>

						<div class="field">
							<label for="email"><?php esc_html_e( 'Email', 'nusantara-landing' ); ?> <span class="req">*</span></label>
							<input type="email" id="email" name="email" required autocomplete="email"
								placeholder="nama@perusahaan.co.id">
						</div>
					</div>

					<div class="form-row">
						<div class="field">
							<label for="telepon"><?php esc_html_e( 'Telepon / WhatsApp', 'nusantara-landing' ); ?></label>
							<input type="tel" id="telepon" name="telepon" autocomplete="tel"
								placeholder="08xx xxxx xxxx">
						</div>

						<div class="field">
							<label for="perusahaan"><?php esc_html_e( 'Perusahaan', 'nusantara-landing' ); ?></label>
							<input type="text" id="perusahaan" name="perusahaan" autocomplete="organization"
								placeholder="<?php esc_attr_e( 'Nama perusahaan', 'nusantara-landing' ); ?>">
						</div>
					</div>

					<div class="field">
						<label for="pesan"><?php esc_html_e( 'Kebutuhan Anda', 'nusantara-landing' ); ?> <span class="req">*</span></label>
						<textarea id="pesan" name="pesan" required
							placeholder="<?php esc_attr_e( 'Ceritakan singkat proyek atau kendala yang ingin diselesaikan…', 'nusantara-landing' ); ?>"></textarea>
					</div>

					<button type="submit" class="btn btn--primary btn--lg btn--block">
						<?php esc_html_e( 'Kirim Pesan', 'nusantara-landing' ); ?>
						<?php echo nusantara_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
					</button>

					<p class="form-note">
						<?php esc_html_e( 'Data Anda hanya dipakai untuk menindaklanjuti pesan ini. Kami tidak mengirim spam.', 'nusantara-landing' ); ?>
					</p>
				</form>
			</div>
		</div>

		<?php if ( '' !== trim( $nusantara_map ) ) : ?>
			<div class="contact-map reveal">
				<iframe src="<?php echo esc_url( $nusantara_map ); ?>" title="<?php esc_attr_e( 'Lokasi kantor kami', 'nusantara-landing' ); ?>"
					loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
			</div>
		<?php endif; ?>
	</div>
</section>

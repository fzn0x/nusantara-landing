<?php
/**
 * Section ajakan bertindak (CTA).
 *
 * @package Nusantara_Landing
 */

$nusantara_title   = nusantara_get_mod( 'cta_title' );
$nusantara_text    = nusantara_get_mod( 'cta_text' );
$nusantara_btn     = nusantara_get_mod( 'cta_btn_text' );
$nusantara_btn_url = nusantara_get_mod( 'cta_btn_url' );
$nusantara_wa      = nusantara_wa_link();
?>
<section class="cta-band">
	<div class="container cta-inner">
		<div class="cta-copy">
			<h2><?php echo nusantara_kses_html( $nusantara_title ); ?></h2>
			<p><?php echo esc_html( $nusantara_text ); ?></p>
		</div>

		<div class="cta-actions">
			<?php if ( '' !== trim( $nusantara_btn ) ) : ?>
				<a class="btn btn--light btn--lg" href="<?php echo esc_url( $nusantara_btn_url ); ?>">
					<?php echo esc_html( $nusantara_btn ); ?>
					<?php echo nusantara_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
				</a>
			<?php endif; ?>

			<?php if ( '' !== $nusantara_wa ) : ?>
				<a class="btn btn--wa btn--lg" href="<?php echo esc_url( $nusantara_wa ); ?>" target="_blank" rel="noopener noreferrer">
					<?php echo nusantara_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
					<?php esc_html_e( 'Chat WhatsApp', 'nusantara-landing' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>

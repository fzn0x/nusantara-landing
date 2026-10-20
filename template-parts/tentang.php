<?php
/**
 * Section tentang.
 *
 * @package Nusantara_Landing
 */

$nusantara_title  = nusantara_get_mod( 'tentang_title' );
$nusantara_text   = nusantara_get_mod( 'tentang_text' );
$nusantara_points = nusantara_parse_lines( nusantara_get_mod( 'tentang_points' ) );
$nusantara_stats  = nusantara_parse_pairs( nusantara_get_mod( 'tentang_stats' ) );
$nusantara_image  = nusantara_get_mod( 'tentang_image' );
?>
<section id="tentang" class="section">
	<div class="container about-inner">

		<div class="about-media reveal">
			<?php if ( '' !== trim( $nusantara_image ) ) : ?>
				<img src="<?php echo esc_url( $nusantara_image ); ?>"
					alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" loading="lazy" decoding="async">
			<?php else : ?>
				<div class="about-collage">
					<?php foreach ( $nusantara_stats as $nusantara_index => $nusantara_stat ) : ?>
						<div class="about-tile">
							<?php if ( 0 === $nusantara_index ) : ?>
								<span class="tile-ico" aria-hidden="true">
									<?php echo nusantara_icon( 'award' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
								</span>
							<?php endif; ?>
							<strong><?php echo esc_html( $nusantara_stat['value'] ); ?></strong>
							<span><?php echo esc_html( $nusantara_stat['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="about-copy reveal" data-delay="1">
			<span class="eyebrow"><?php esc_html_e( 'Tentang Kami', 'nusantara-landing' ); ?></span>
			<h2><?php echo nusantara_kses_html( $nusantara_title ); ?></h2>
			<p><?php echo esc_html( $nusantara_text ); ?></p>

			<?php if ( ! empty( $nusantara_points ) ) : ?>
				<ul class="about-list">
					<?php foreach ( $nusantara_points as $nusantara_point ) : ?>
						<li><?php echo esc_html( $nusantara_point ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<p class="about-cta">
				<a class="btn btn--primary" href="#kontak">
					<?php esc_html_e( 'Kenali Kami Lebih Dekat', 'nusantara-landing' ); ?>
					<?php echo nusantara_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
				</a>
			</p>
		</div>
	</div>
</section>

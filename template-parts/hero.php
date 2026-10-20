<?php
/**
 * Section hero.
 *
 * @package Nusantara_Landing
 */

$nusantara_badge    = nusantara_get_mod( 'hero_badge' );
$nusantara_title    = nusantara_get_mod( 'hero_title' );
$nusantara_subtitle = nusantara_get_mod( 'hero_subtitle' );
$nusantara_btn1     = nusantara_get_mod( 'hero_btn1_text' );
$nusantara_btn1_url = nusantara_get_mod( 'hero_btn1_url' );
$nusantara_btn2     = nusantara_get_mod( 'hero_btn2_text' );
$nusantara_btn2_url = nusantara_get_mod( 'hero_btn2_url' );
$nusantara_stats    = nusantara_parse_pairs( nusantara_get_mod( 'hero_stats' ) );
$nusantara_image    = nusantara_get_mod( 'hero_image' );
?>
<section id="beranda" class="hero">
	<div class="container hero-inner">

		<div class="hero-copy">
			<?php if ( '' !== trim( $nusantara_badge ) ) : ?>
				<span class="hero-badge reveal">
					<span class="dot" aria-hidden="true"></span>
					<?php echo esc_html( $nusantara_badge ); ?>
				</span>
			<?php endif; ?>

			<?php if ( '' !== trim( $nusantara_title ) ) : ?>
				<h1 class="hero-title reveal" data-delay="1"><?php echo nusantara_kses_html( $nusantara_title ); ?></h1>
			<?php endif; ?>

			<?php if ( '' !== trim( $nusantara_subtitle ) ) : ?>
				<p class="hero-lead reveal" data-delay="2"><?php echo esc_html( $nusantara_subtitle ); ?></p>
			<?php endif; ?>

			<div class="hero-actions reveal" data-delay="2">
				<?php if ( '' !== trim( $nusantara_btn1 ) ) : ?>
					<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $nusantara_btn1_url ); ?>">
						<?php echo esc_html( $nusantara_btn1 ); ?>
						<?php echo nusantara_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
					</a>
				<?php endif; ?>

				<?php if ( '' !== trim( $nusantara_btn2 ) ) : ?>
					<a class="btn btn--outline btn--lg" href="<?php echo esc_url( $nusantara_btn2_url ); ?>">
						<?php echo esc_html( $nusantara_btn2 ); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $nusantara_stats ) ) : ?>
				<ul class="hero-stats reveal" data-delay="3">
					<?php foreach ( $nusantara_stats as $nusantara_stat ) : ?>
						<li>
							<b><?php echo esc_html( $nusantara_stat['value'] ); ?></b>
							<span><?php echo esc_html( $nusantara_stat['label'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="hero-visual reveal" data-delay="1">
			<?php if ( '' !== trim( $nusantara_image ) ) : ?>
				<img src="<?php echo esc_url( $nusantara_image ); ?>"
					alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" loading="eager" decoding="async">
			<?php else : ?>
				<div class="mock" aria-hidden="true">
					<div class="mock-top">
						<i></i><i></i><i></i>
						<span><?php esc_html_e( 'dasbor', 'nusantara-landing' ); ?></span>
					</div>
					<div class="mock-body">
						<div class="mock-line mock-line--w70"></div>
						<div class="mock-line mock-line--w45"></div>
						<div class="mock-chart">
							<span></span><span></span><span></span><span></span>
							<span></span><span></span><span></span>
						</div>
						<div class="mock-row">
							<span class="mock-pill">+38%</span>
							<div class="mock-line mock-line--w90"></div>
						</div>
					</div>
				</div>

				<div class="float-card float-card--a" aria-hidden="true">
					<span class="float-ico"><?php echo nusantara_icon( 'check-circle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?></span>
					<div>
						<strong>98%</strong>
						<small><?php esc_html_e( 'Kepuasan klien', 'nusantara-landing' ); ?></small>
					</div>
				</div>

				<div class="float-card float-card--b" aria-hidden="true">
					<span class="float-ico"><?php echo nusantara_icon( 'zap' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?></span>
					<div>
						<strong>250+</strong>
						<small><?php esc_html_e( 'Proyek selesai', 'nusantara-landing' ); ?></small>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

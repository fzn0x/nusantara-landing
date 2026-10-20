<?php
/**
 * Section layanan.
 *
 * @package Nusantara_Landing
 */

$nusantara_items = nusantara_layanan_items();
$nusantara_icons = array( 'target', 'layers', 'chart', 'shield', 'users', 'zap' );

if ( empty( $nusantara_items ) ) {
	return;
}
?>
<section id="layanan" class="section section--surface">
	<div class="container">

		<?php
		nusantara_section_head(
			nusantara_get_mod( 'layanan_title' ),
			nusantara_get_mod( 'layanan_subtitle' ),
			__( 'Layanan Kami', 'nusantara-landing' )
		);
		?>

		<div class="grid grid--3">
			<?php
			foreach ( $nusantara_items as $nusantara_index => $nusantara_item ) :
				$nusantara_icon = isset( $nusantara_item['icon'] ) && '' !== $nusantara_item['icon']
					? $nusantara_item['icon']
					: $nusantara_icons[ $nusantara_index % count( $nusantara_icons ) ];
				?>
				<article class="card reveal" data-delay="<?php echo esc_attr( $nusantara_index % 3 ); ?>">

					<?php if ( ! empty( $nusantara_item['image'] ) ) : ?>
						<div class="card-media">
							<img src="<?php echo esc_url( $nusantara_item['image'] ); ?>"
								alt="<?php echo esc_attr( $nusantara_item['title'] ); ?>"
								loading="lazy" decoding="async">
						</div>
					<?php else : ?>
						<span class="card-icon" aria-hidden="true">
							<?php echo nusantara_icon( $nusantara_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
						</span>
					<?php endif; ?>

					<h3><?php echo esc_html( $nusantara_item['title'] ); ?></h3>
					<p><?php echo esc_html( $nusantara_item['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

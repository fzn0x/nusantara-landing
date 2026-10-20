<?php
/**
 * Section testimoni.
 *
 * @package Nusantara_Landing
 */

$nusantara_items = nusantara_testimoni_items();

if ( empty( $nusantara_items ) ) {
	return;
}
?>
<section id="testimoni" class="section section--surface">
	<div class="container">

		<?php
		nusantara_section_head(
			nusantara_get_mod( 'testimoni_title' ),
			nusantara_get_mod( 'testimoni_subtitle' ),
			__( 'Testimoni', 'nusantara-landing' )
		);
		?>

		<div class="grid grid--3">
			<?php
			foreach ( $nusantara_items as $nusantara_index => $nusantara_item ) :
				$nusantara_words    = preg_split( '/\s+/', trim( $nusantara_item['name'] ) );
				$nusantara_initials = '';

				foreach ( array_slice( (array) $nusantara_words, 0, 2 ) as $nusantara_word ) {
					$nusantara_initials .= mb_strtoupper( mb_substr( $nusantara_word, 0, 1 ) );
				}
				?>
				<figure class="testi-card reveal" data-delay="<?php echo esc_attr( $nusantara_index % 3 ); ?>">
					<div class="testi-stars" aria-hidden="true">
						<?php for ( $nusantara_star = 0; $nusantara_star < 5; $nusantara_star++ ) : ?>
							<?php echo nusantara_icon( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG statis. ?>
						<?php endfor; ?>
					</div>

					<blockquote class="testi-quote">
						<?php echo esc_html( $nusantara_item['quote'] ); ?>
					</blockquote>

					<figcaption class="testi-person">
						<?php if ( ! empty( $nusantara_item['avatar'] ) ) : ?>
							<img src="<?php echo esc_url( $nusantara_item['avatar'] ); ?>"
								alt="<?php echo esc_attr( $nusantara_item['name'] ); ?>"
								width="46" height="46" loading="lazy" decoding="async">
						<?php else : ?>
							<span class="testi-avatar" aria-hidden="true"><?php echo esc_html( $nusantara_initials ); ?></span>
						<?php endif; ?>

						<span>
							<b><?php echo esc_html( $nusantara_item['name'] ); ?></b>
							<?php if ( ! empty( $nusantara_item['role'] ) ) : ?>
								<small><?php echo esc_html( $nusantara_item['role'] ); ?></small>
							<?php endif; ?>
						</span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>

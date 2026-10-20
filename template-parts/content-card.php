<?php
/**
 * Kartu artikel untuk daftar dan arsip.
 *
 * @package Nusantara_Landing
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card reveal' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-card__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'nusantara-card', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<div class="post-card__body">
		<div class="post-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<?php if ( has_category() ) : ?>
				<span aria-hidden="true"> &middot; </span><?php the_category( ', ' ); ?>
			<?php endif; ?>
		</div>

		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
	</div>
</article>

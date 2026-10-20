<?php
/**
 * Isi artikel atau halaman.
 *
 * @package Nusantara_Landing
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

	<div class="entry-meta">
		<?php if ( 'post' === get_post_type() ) : ?>
			<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<span aria-hidden="true"> &middot; </span>
			<?php
			printf(
				/* translators: %s: nama penulis. */
				esc_html__( 'oleh %s', 'nusantara-landing' ),
				esc_html( get_the_author() )
			);
			?>
		<?php endif; ?>
	</div>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumb">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="pagination"><div class="nav-links">',
				'after'  => '</div></div>',
			)
		);
		?>
	</div>

	<?php if ( has_tag() ) : ?>
		<footer class="entry-footer">
			<?php the_tags( '<ul class="tag-list"><li>', '</li><li>', '</li></ul>' ); ?>
		</footer>
	<?php endif; ?>
</article>

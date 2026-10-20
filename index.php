<?php
/**
 * Daftar artikel (blog, arsip, pencarian).
 *
 * @package Nusantara_Landing
 */

get_header();

if ( is_search() ) {
	nusantara_page_hero(
		sprintf(
			/* translators: %s: kata kunci pencarian. */
			__( 'Hasil pencarian: %s', 'nusantara-landing' ),
			// false = kata kunci mentah; nusantara_page_hero() yang meng-escape.
			get_search_query( false )
		),
		__( 'Berikut artikel yang paling relevan dengan kata kunci Anda.', 'nusantara-landing' )
	);
} elseif ( is_archive() ) {
	// Buang awalan bawaan WordPress seperti "Kategori:" atau "Penulis:".
	$nusantara_archive_title = preg_replace( '/^[^:]+:\s*/', '', get_the_archive_title() );
	nusantara_page_hero( $nusantara_archive_title, wp_strip_all_tags( get_the_archive_description() ) );
} else {
	$nusantara_blog_id = (int) get_option( 'page_for_posts' );
	nusantara_page_hero(
		$nusantara_blog_id ? get_the_title( $nusantara_blog_id ) : __( 'Catatan Terbaru', 'nusantara-landing' ),
		__( 'Kabar, panduan, dan pemikiran dari tim kami.', 'nusantara-landing' )
	);
}
?>
<main id="main" class="site-main">
	<div class="container <?php echo is_active_sidebar( 'sidebar-1' ) ? 'with-sidebar' : ''; ?>">

		<div class="content-area">
			<?php if ( have_posts() ) : ?>
				<div class="post-list">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'card' );
					endwhile;
					?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 1,
						'prev_text'          => '&larr;',
						'next_text'          => '&rarr;',
						'screen_reader_text' => __( 'Navigasi halaman', 'nusantara-landing' ),
					)
				);
				?>
			<?php else : ?>
				<div class="empty-state">
					<h2><?php esc_html_e( 'Belum ada artikel', 'nusantara-landing' ); ?></h2>
					<p><?php esc_html_e( 'Coba kata kunci lain, atau kembali ke halaman depan.', 'nusantara-landing' ); ?></p>
					<p>
						<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php esc_html_e( 'Kembali ke Beranda', 'nusantara-landing' ); ?>
						</a>
					</p>
				</div>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>
	</div>
</main>
<?php
get_footer();

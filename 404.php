<?php
/**
 * Halaman 404.
 *
 * @package Nusantara_Landing
 */

get_header();

nusantara_page_hero(
	__( 'Halaman tidak ditemukan', 'nusantara-landing' ),
	__( 'Tautan yang Anda buka mungkin sudah dipindahkan atau tidak pernah ada.', 'nusantara-landing' )
);
?>
<main id="main" class="site-main">
	<div class="container container--narrow">
		<div class="empty-state">
			<p><?php esc_html_e( 'Silakan kembali ke halaman depan, atau cari yang Anda butuhkan di bawah ini.', 'nusantara-landing' ); ?></p>

			<p>
				<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Kembali ke Beranda', 'nusantara-landing' ); ?>
				</a>
			</p>

			<div style="max-width:420px;margin:32px auto 0">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();

<?php
/**
 * Artikel tunggal.
 *
 * @package Nusantara_Landing
 */

get_header();

while ( have_posts() ) :
	the_post();

	nusantara_page_hero( get_the_title() );
	?>
	<main id="main" class="site-main">
		<div class="container container--narrow">
			<?php
			get_template_part( 'template-parts/content' );

			the_post_navigation(
				array(
					'prev_text' => '<small>' . esc_html__( 'Sebelumnya', 'nusantara-landing' ) . '</small><b>%title</b>',
					'next_text' => '<small>' . esc_html__( 'Berikutnya', 'nusantara-landing' ) . '</small><b>%title</b>',
					'class'     => 'post-nav',
				)
			);

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</main>
	<?php
endwhile;

get_footer();

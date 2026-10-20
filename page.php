<?php
/**
 * Halaman statis.
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

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</main>
	<?php
endwhile;

get_footer();

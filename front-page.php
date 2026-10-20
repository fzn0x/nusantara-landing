<?php
/**
 * Halaman depan: menyusun seluruh section landing page.
 *
 * Urutan section bisa diubah lewat filter 'nusantara_front_sections'.
 *
 * @package Nusantara_Landing
 */

get_header();

$nusantara_sections = apply_filters(
	'nusantara_front_sections',
	array( 'hero', 'layanan', 'tentang', 'testimoni', 'cta', 'kontak' )
);
?>
<main id="main" class="site-main site-main--front">
	<?php
	foreach ( $nusantara_sections as $nusantara_section ) {
		get_template_part( 'template-parts/' . $nusantara_section );
	}
	?>
</main>
<?php
get_footer();

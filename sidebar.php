<?php
/**
 * Sidebar blog.
 *
 * @package Nusantara_Landing
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside id="secondary" class="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'nusantara-landing' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>

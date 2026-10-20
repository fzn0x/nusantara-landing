<?php
/**
 * Area komentar.
 *
 * @package Nusantara_Landing
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$nusantara_count = get_comments_number();
			printf(
				/* translators: %s: jumlah komentar. */
				esc_html( _n( '%s komentar', '%s komentar', $nusantara_count, 'nusantara-landing' ) ),
				esc_html( number_format_i18n( $nusantara_count ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="form-note"><?php esc_html_e( 'Diskusi untuk artikel ini sudah ditutup.', 'nusantara-landing' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Tinggalkan komentar', 'nusantara-landing' ),
			'label_submit'       => __( 'Kirim Komentar', 'nusantara-landing' ),
			'comment_notes_before' => '<p class="form-note">' . esc_html__( 'Alamat email Anda tidak akan dipublikasikan.', 'nusantara-landing' ) . '</p>',
		)
	);
	?>
</div>

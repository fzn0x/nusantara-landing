<?php
/**
 * Nusantara Landing — bootstrap tema.
 *
 * Berisi setup tema, enqueue aset, area widget, opsi Customizer,
 * custom post type (Layanan & Testimoni), serta handler form kontak.
 *
 * @package Nusantara_Landing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Akses langsung tidak diizinkan.
}

define( 'NUSANTARA_VERSION', '1.0.0' );
define( 'NUSANTARA_DIR', get_template_directory() );
define( 'NUSANTARA_URI', get_template_directory_uri() );

/* =========================================================================
 * 1. Setup tema
 * ====================================================================== */

/**
 * Daftarkan fitur tema.
 *
 * @return void
 */
function nusantara_setup() {
	load_theme_textdomain( 'nusantara-landing', NUSANTARA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_editor_style( 'assets/css/editor.css' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu Utama', 'nusantara-landing' ),
			'footer'  => __( 'Menu Footer', 'nusantara-landing' ),
		)
	);

	add_image_size( 'nusantara-card', 720, 450, true );
}
add_action( 'after_setup_theme', 'nusantara_setup' );

/**
 * Tetapkan lebar konten global.
 *
 * @return void
 */
function nusantara_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'nusantara_content_width', 780 );
}
add_action( 'after_setup_theme', 'nusantara_content_width', 0 );

/* =========================================================================
 * 2. Aset
 * ====================================================================== */

/**
 * Muat stylesheet dan script tema.
 *
 * @return void
 */
function nusantara_assets() {
	$style_path = NUSANTARA_DIR . '/style.css';
	$style_ver  = file_exists( $style_path ) ? (string) filemtime( $style_path ) : NUSANTARA_VERSION;

	wp_enqueue_style( 'nusantara-style', get_stylesheet_uri(), array(), $style_ver );

	$brand = nusantara_get_mod( 'brand_color' );
	$rgb   = nusantara_hex_to_rgb( $brand );

	$inline = sprintf(
		':root{--brand:%1$s;--brand-rgb:%2$s;--brand-dark:%3$s;--brand-tint:%4$s;}',
		$brand,
		implode( ', ', $rgb ),
		nusantara_mix_hex( $brand, array( 0, 0, 0 ), 0.22 ),
		nusantara_mix_hex( $brand, array( 255, 255, 255 ), 0.93 )
	);
	wp_add_inline_style( 'nusantara-style', $inline );

	$script_path = NUSANTARA_DIR . '/assets/js/main.js';
	$script_ver  = file_exists( $script_path ) ? (string) filemtime( $script_path ) : NUSANTARA_VERSION;

	wp_enqueue_script( 'nusantara-main', NUSANTARA_URI . '/assets/js/main.js', array(), $script_ver, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'nusantara_assets' );

/* =========================================================================
 * 3. Area widget
 * ====================================================================== */

/**
 * Daftarkan sidebar blog.
 *
 * @return void
 */
function nusantara_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar Blog', 'nusantara-landing' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Tampil di halaman blog, arsip, dan artikel.', 'nusantara-landing' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'nusantara_widgets_init' );

/* =========================================================================
 * 4. Opsi tema (satu sumber kebenaran)
 * ====================================================================== */

/**
 * Definisi seluruh opsi tema.
 *
 * Dipakai bersama oleh Customizer dan fungsi pengambil nilai, sehingga
 * menambah opsi cukup dilakukan di satu tempat.
 *
 * @return array
 */
function nusantara_fields() {
	$fields = array(
		/* -- Hero ------------------------------------------------------- */
		array(
			'id'       => 'hero_badge',
			'section'  => 'hero',
			'type'     => 'text',
			'label'    => __( 'Teks badge kecil', 'nusantara-landing' ),
			'default'  => __( 'Dipercaya 120+ perusahaan di Indonesia', 'nusantara-landing' ),
			'sanitize' => 'sanitize_text_field',
		),
		array(
			'id'       => 'hero_title',
			'section'  => 'hero',
			'type'     => 'html',
			'label'    => __( 'Judul utama', 'nusantara-landing' ),
			'desc'     => __( 'Boleh memakai &lt;em&gt; untuk menandai kata bergaris bawah warna brand.', 'nusantara-landing' ),
			'default'  => __( 'Solusi digital yang <em>menumbuhkan</em> bisnis Anda', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_html',
		),
		array(
			'id'       => 'hero_subtitle',
			'section'  => 'hero',
			'type'     => 'textarea',
			'label'    => __( 'Paragraf pendukung', 'nusantara-landing' ),
			'default'  => __( 'Kami membantu perusahaan merancang, membangun, dan mengembangkan produk digital yang terukur — dari riset strategi sampai peluncuran.', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'hero_btn1_text',
			'section'  => 'hero',
			'type'     => 'text',
			'label'    => __( 'Tombol utama — teks', 'nusantara-landing' ),
			'default'  => __( 'Konsultasi Gratis', 'nusantara-landing' ),
			'sanitize' => 'sanitize_text_field',
		),
		array(
			'id'       => 'hero_btn1_url',
			'section'  => 'hero',
			'type'     => 'url',
			'label'    => __( 'Tombol utama — tautan', 'nusantara-landing' ),
			'default'  => '#kontak',
			'sanitize' => 'esc_url_raw',
		),
		array(
			'id'       => 'hero_btn2_text',
			'section'  => 'hero',
			'type'     => 'text',
			'label'    => __( 'Tombol kedua — teks', 'nusantara-landing' ),
			'default'  => __( 'Lihat Layanan', 'nusantara-landing' ),
			'sanitize' => 'sanitize_text_field',
		),
		array(
			'id'       => 'hero_btn2_url',
			'section'  => 'hero',
			'type'     => 'url',
			'label'    => __( 'Tombol kedua — tautan', 'nusantara-landing' ),
			'default'  => '#layanan',
			'sanitize' => 'esc_url_raw',
		),
		array(
			'id'       => 'hero_stats',
			'section'  => 'hero',
			'type'     => 'textarea',
			'label'    => __( 'Statistik hero', 'nusantara-landing' ),
			'desc'     => __( 'Satu baris per statistik, format: angka | keterangan', 'nusantara-landing' ),
			'default'  => "12+ | Tahun pengalaman\n250+ | Proyek selesai\n98% | Kepuasan klien",
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'hero_image',
			'section'  => 'hero',
			'type'     => 'image',
			'label'    => __( 'Gambar hero (opsional)', 'nusantara-landing' ),
			'desc'     => __( 'Kosongkan untuk memakai mockup CSS bawaan tema.', 'nusantara-landing' ),
			'default'  => '',
			'sanitize' => 'esc_url_raw',
		),

		/* -- Layanan ---------------------------------------------------- */
		array(
			'id'       => 'layanan_title',
			'section'  => 'layanan',
			'type'     => 'html',
			'label'    => __( 'Judul section', 'nusantara-landing' ),
			'default'  => __( 'Layanan yang kami <em>kerjakan</em>', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_html',
		),
		array(
			'id'       => 'layanan_subtitle',
			'section'  => 'layanan',
			'type'     => 'textarea',
			'label'    => __( 'Deskripsi section', 'nusantara-landing' ),
			'default'  => __( 'Tiga pilar utama yang kami pakai untuk membantu klien tumbuh lebih cepat dan lebih terukur.', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_textarea',
		),

		/* -- Tentang ---------------------------------------------------- */
		array(
			'id'       => 'tentang_title',
			'section'  => 'tentang',
			'type'     => 'html',
			'label'    => __( 'Judul section', 'nusantara-landing' ),
			'default'  => __( 'Partner digital yang <em>berpikir jangka panjang</em>', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_html',
		),
		array(
			'id'       => 'tentang_text',
			'section'  => 'tentang',
			'type'     => 'textarea',
			'label'    => __( 'Paragraf tentang', 'nusantara-landing' ),
			'default'  => __( 'Sejak 2013 kami mendampingi perusahaan dari berbagai industri untuk menerjemahkan tujuan bisnis menjadi produk digital yang benar-benar dipakai. Tim kami kecil, senior, dan terlibat langsung dari awal sampai akhir.', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'tentang_points',
			'section'  => 'tentang',
			'type'     => 'textarea',
			'label'    => __( 'Poin keunggulan', 'nusantara-landing' ),
			'desc'     => __( 'Satu poin per baris.', 'nusantara-landing' ),
			'default'  => "Tim senior, tanpa lapisan perantara\nProses transparan dengan laporan mingguan\nKode dan aset sepenuhnya milik Anda\nDukungan purna peluncuran minimal 3 bulan",
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'tentang_stats',
			'section'  => 'tentang',
			'type'     => 'textarea',
			'label'    => __( 'Statistik tentang', 'nusantara-landing' ),
			'desc'     => __( 'Tiga baris, format: angka | keterangan', 'nusantara-landing' ),
			'default'  => "12+ | Tahun pengalaman\n40 | Spesialis di tim\n9/10 | Klien kembali lagi",
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'tentang_image',
			'section'  => 'tentang',
			'type'     => 'image',
			'label'    => __( 'Gambar tentang (opsional)', 'nusantara-landing' ),
			'default'  => '',
			'sanitize' => 'esc_url_raw',
		),

		/* -- Testimoni -------------------------------------------------- */
		array(
			'id'       => 'testimoni_title',
			'section'  => 'testimoni',
			'type'     => 'html',
			'label'    => __( 'Judul section', 'nusantara-landing' ),
			'default'  => __( 'Kata mereka yang sudah <em>bekerja sama</em>', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_html',
		),
		array(
			'id'       => 'testimoni_subtitle',
			'section'  => 'testimoni',
			'type'     => 'textarea',
			'label'    => __( 'Deskripsi section', 'nusantara-landing' ),
			'default'  => __( 'Beberapa kesan dari klien yang kami dampingi dari nol sampai produksi.', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_textarea',
		),

		/* -- CTA -------------------------------------------------------- */
		array(
			'id'       => 'cta_title',
			'section'  => 'cta',
			'type'     => 'html',
			'label'    => __( 'Judul CTA', 'nusantara-landing' ),
			'default'  => __( 'Siap mulai proyek berikutnya?', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_html',
		),
		array(
			'id'       => 'cta_text',
			'section'  => 'cta',
			'type'     => 'textarea',
			'label'    => __( 'Deskripsi CTA', 'nusantara-landing' ),
			'default'  => __( 'Ceritakan kebutuhan Anda, kami balas dalam 1×24 jam kerja dengan rekomendasi awal tanpa biaya.', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'cta_btn_text',
			'section'  => 'cta',
			'type'     => 'text',
			'label'    => __( 'Tombol CTA — teks', 'nusantara-landing' ),
			'default'  => __( 'Jadwalkan Diskusi', 'nusantara-landing' ),
			'sanitize' => 'sanitize_text_field',
		),
		array(
			'id'       => 'cta_btn_url',
			'section'  => 'cta',
			'type'     => 'url',
			'label'    => __( 'Tombol CTA — tautan', 'nusantara-landing' ),
			'default'  => '#kontak',
			'sanitize' => 'esc_url_raw',
		),

		/* -- Kontak ----------------------------------------------------- */
		array(
			'id'       => 'kontak_title',
			'section'  => 'kontak',
			'type'     => 'html',
			'label'    => __( 'Judul section', 'nusantara-landing' ),
			'default'  => __( 'Mari bicara soal <em>kebutuhan Anda</em>', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_html',
		),
		array(
			'id'       => 'kontak_subtitle',
			'section'  => 'kontak',
			'type'     => 'textarea',
			'label'    => __( 'Deskripsi section', 'nusantara-landing' ),
			'default'  => __( 'Isi formulir di samping atau hubungi kami langsung lewat telepon dan email.', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'contact_address',
			'section'  => 'kontak',
			'type'     => 'textarea',
			'label'    => __( 'Alamat', 'nusantara-landing' ),
			'default'  => "Jl. Jenderal Sudirman Kav. 52-53\nJakarta Selatan 12190, Indonesia",
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'contact_phone',
			'section'  => 'kontak',
			'type'     => 'text',
			'label'    => __( 'Telepon', 'nusantara-landing' ),
			'default'  => '+62 21 5000 1234',
			'sanitize' => 'sanitize_text_field',
		),
		array(
			'id'       => 'contact_email',
			'section'  => 'kontak',
			'type'     => 'email',
			'label'    => __( 'Email publik', 'nusantara-landing' ),
			'default'  => 'halo@nusantaradigital.co.id',
			'sanitize' => 'sanitize_email',
		),
		array(
			'id'       => 'contact_hours',
			'section'  => 'kontak',
			'type'     => 'text',
			'label'    => __( 'Jam operasional', 'nusantara-landing' ),
			'default'  => __( 'Senin–Jumat, 09.00–18.00 WIB', 'nusantara-landing' ),
			'sanitize' => 'sanitize_text_field',
		),
		array(
			'id'       => 'contact_wa',
			'section'  => 'kontak',
			'type'     => 'text',
			'label'    => __( 'Nomor WhatsApp', 'nusantara-landing' ),
			'desc'     => __( 'Format bebas, misal 0812-3456-7890. Kosongkan untuk menyembunyikan tombol WhatsApp.', 'nusantara-landing' ),
			'default'  => '0812 3456 7890',
			'sanitize' => 'sanitize_text_field',
		),
		array(
			'id'       => 'contact_recipient',
			'section'  => 'kontak',
			'type'     => 'email',
			'label'    => __( 'Email penerima formulir', 'nusantara-landing' ),
			'default'  => get_option( 'admin_email' ),
			'sanitize' => 'sanitize_email',
		),
		array(
			'id'       => 'contact_map',
			'section'  => 'kontak',
			'type'     => 'url',
			'label'    => __( 'URL embed peta (opsional)', 'nusantara-landing' ),
			'desc'     => __( 'Tempel URL dari Google Maps → Bagikan → Sematkan peta.', 'nusantara-landing' ),
			'default'  => '',
			'sanitize' => 'esc_url_raw',
		),
		array(
			'id'       => 'social_instagram',
			'section'  => 'kontak',
			'type'     => 'url',
			'label'    => __( 'Instagram', 'nusantara-landing' ),
			'default'  => '',
			'sanitize' => 'esc_url_raw',
		),
		array(
			'id'       => 'social_linkedin',
			'section'  => 'kontak',
			'type'     => 'url',
			'label'    => __( 'LinkedIn', 'nusantara-landing' ),
			'default'  => '',
			'sanitize' => 'esc_url_raw',
		),
		array(
			'id'       => 'social_facebook',
			'section'  => 'kontak',
			'type'     => 'url',
			'label'    => __( 'Facebook', 'nusantara-landing' ),
			'default'  => '',
			'sanitize' => 'esc_url_raw',
		),
		array(
			'id'       => 'social_youtube',
			'section'  => 'kontak',
			'type'     => 'url',
			'label'    => __( 'YouTube', 'nusantara-landing' ),
			'default'  => '',
			'sanitize' => 'esc_url_raw',
		),

		/* -- Footer ----------------------------------------------------- */
		array(
			'id'       => 'footer_about',
			'section'  => 'footer',
			'type'     => 'textarea',
			'label'    => __( 'Deskripsi singkat', 'nusantara-landing' ),
			'default'  => __( 'Studio digital yang membantu perusahaan merancang dan membangun produk yang dipakai banyak orang.', 'nusantara-landing' ),
			'sanitize' => 'nusantara_sanitize_textarea',
		),
		array(
			'id'       => 'footer_copyright',
			'section'  => 'footer',
			'type'     => 'text',
			'label'    => __( 'Teks copyright', 'nusantara-landing' ),
			'desc'     => __( 'Biarkan kosong untuk memakai nama situs.', 'nusantara-landing' ),
			'default'  => '',
			'sanitize' => 'sanitize_text_field',
		),

		/* -- Warna ------------------------------------------------------ */
		array(
			'id'       => 'brand_color',
			'section'  => 'warna',
			'type'     => 'color',
			'label'    => __( 'Warna brand', 'nusantara-landing' ),
			'default'  => '#1d4ed8',
			'sanitize' => 'sanitize_hex_color',
		),
	);

	return apply_filters( 'nusantara_fields', $fields );
}

/**
 * Kumpulan nilai default seluruh opsi.
 *
 * @return array
 */
function nusantara_defaults() {
	$defaults = array();
	foreach ( nusantara_fields() as $field ) {
		$defaults[ $field['id'] ] = $field['default'];
	}
	return $defaults;
}

/**
 * Ambil nilai opsi tema, dengan default yang aman.
 *
 * @param string $id ID opsi.
 * @return string
 */
function nusantara_get_mod( $id ) {
	$defaults = nusantara_defaults();
	$default  = isset( $defaults[ $id ] ) ? $defaults[ $id ] : '';
	$value    = get_theme_mod( $id, $default );

	return is_string( $value ) ? $value : $default;
}

/**
 * Apakah opsi punya nilai berbeda dari default?
 *
 * @param string $id ID opsi.
 * @return bool
 */
function nusantara_has_mod( $id ) {
	return '' !== trim( (string) nusantara_get_mod( $id ) );
}

/* =========================================================================
 * 5. Sanitasi khusus
 * ====================================================================== */

/**
 * Daftar tag inline yang diizinkan pada teks judul.
 *
 * @return array
 */
function nusantara_allowed_html() {
	return array(
		'em'     => array(),
		'strong' => array(),
		'br'     => array(),
		'span'   => array( 'class' => array() ),
	);
}

/**
 * Saring teks judul agar hanya berisi tag inline yang aman.
 *
 * @param string $value Nilai mentah.
 * @return string
 */
function nusantara_kses_html( $value ) {
	return wp_kses( (string) $value, nusantara_allowed_html() );
}

/**
 * Izinkan hanya tag inline sederhana pada teks judul.
 *
 * @param string $value Nilai mentah.
 * @return string
 */
function nusantara_sanitize_html( $value ) {
	return nusantara_kses_html( $value );
}

/**
 * Sanitasi textarea.
 *
 * Seluruh textarea tema ini ditampilkan sebagai teks biasa (via esc_html),
 * jadi tag HTML dibuang di sini supaya tidak pernah muncul sebagai teks
 * literal di halaman. Baris baru tetap dipertahankan.
 *
 * @param string $value Nilai mentah.
 * @return string
 */
function nusantara_sanitize_textarea( $value ) {
	return sanitize_textarea_field( (string) $value );
}

/* =========================================================================
 * 6. Customizer
 * ====================================================================== */

/**
 * Daftarkan panel, section, dan kontrol Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Instance Customizer.
 * @return void
 */
function nusantara_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'nusantara_panel',
		array(
			'title'       => __( 'Konten Landing Page', 'nusantara-landing' ),
			'description' => __( 'Semua teks di halaman depan diatur dari sini.', 'nusantara-landing' ),
			'priority'    => 20,
		)
	);

	$sections = array(
		'hero'     => __( 'Hero', 'nusantara-landing' ),
		'layanan'  => __( 'Layanan', 'nusantara-landing' ),
		'tentang'  => __( 'Tentang', 'nusantara-landing' ),
		'testimoni' => __( 'Testimoni', 'nusantara-landing' ),
		'cta'      => __( 'Ajakan Bertindak', 'nusantara-landing' ),
		'kontak'   => __( 'Kontak & Sosial', 'nusantara-landing' ),
		'footer'   => __( 'Footer', 'nusantara-landing' ),
		'warna'    => __( 'Warna Brand', 'nusantara-landing' ),
	);

	foreach ( $sections as $slug => $label ) {
		$wp_customize->add_section(
			'nusantara_' . $slug,
			array(
				'title' => $label,
				'panel' => 'nusantara_panel',
			)
		);
	}

	foreach ( nusantara_fields() as $field ) {
		$setting_id = 'nusantara_' . $field['id'];

		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $field['default'],
				'sanitize_callback' => $field['sanitize'],
				'transport'         => 'refresh',
			)
		);

		$control_args = array(
			'label'       => $field['label'],
			'section'     => 'nusantara_' . $field['section'],
			'settings'    => $setting_id,
			'description' => isset( $field['desc'] ) ? $field['desc'] : '',
		);

		switch ( $field['type'] ) {
			case 'color':
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting_id, $control_args ) );
				break;

			case 'image':
				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting_id, $control_args ) );
				break;

			case 'textarea':
			case 'html':
				$control_args['type'] = 'textarea';
				$wp_customize->add_control( $setting_id, $control_args );
				break;

			case 'url':
			case 'email':
				$control_args['type'] = $field['type'];
				$wp_customize->add_control( $setting_id, $control_args );
				break;

			default:
				$control_args['type'] = 'text';
				$wp_customize->add_control( $setting_id, $control_args );
				break;
		}
	}
}
add_action( 'customize_register', 'nusantara_customize_register' );

/* =========================================================================
 * 7. Custom post type
 * ====================================================================== */

/**
 * Daftarkan CPT Layanan dan Testimoni.
 *
 * Keduanya dikelola dari dasbor tetapi tidak punya halaman arsip sendiri —
 * hanya dipakai sebagai data untuk landing page.
 *
 * @return void
 */
function nusantara_register_post_types() {
	register_post_type(
		'layanan',
		array(
			'labels'          => array(
				'name'          => __( 'Layanan', 'nusantara-landing' ),
				'singular_name' => __( 'Layanan', 'nusantara-landing' ),
				'add_new_item'  => __( 'Tambah Layanan Baru', 'nusantara-landing' ),
				'edit_item'     => __( 'Edit Layanan', 'nusantara-landing' ),
				'all_items'     => __( 'Semua Layanan', 'nusantara-landing' ),
				'menu_name'     => __( 'Layanan', 'nusantara-landing' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_rest'    => true,
			'rest_base'       => 'layanan',
			'menu_icon'       => 'dashicons-screenoptions',
			'menu_position'   => 20,
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'query_var'       => false,
			'capability_type' => 'post',
		)
	);

	register_post_type(
		'testimoni',
		array(
			'labels'          => array(
				'name'          => __( 'Testimoni', 'nusantara-landing' ),
				'singular_name' => __( 'Testimoni', 'nusantara-landing' ),
				'add_new_item'  => __( 'Tambah Testimoni Baru', 'nusantara-landing' ),
				'edit_item'     => __( 'Edit Testimoni', 'nusantara-landing' ),
				'all_items'     => __( 'Semua Testimoni', 'nusantara-landing' ),
				'menu_name'     => __( 'Testimoni', 'nusantara-landing' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_rest'    => true,
			'rest_base'       => 'testimoni',
			'menu_icon'       => 'dashicons-format-quote',
			'menu_position'   => 21,
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'query_var'       => false,
			'capability_type' => 'post',
		)
	);
}
add_action( 'init', 'nusantara_register_post_types' );

/**
 * Ambil data layanan; jatuh ke konten contoh bila belum ada isian.
 *
 * @return array
 */
function nusantara_layanan_items() {
	$items = array();
	$query = new WP_Query(
		array(
			'post_type'           => 'layanan',
			'posts_per_page'      => 6,
			'orderby'             => 'menu_order date',
			'order'               => 'ASC',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$items[] = array(
				'title' => get_the_title(),
				'text'  => get_the_excerpt(),
				'image' => get_the_post_thumbnail_url( get_the_ID(), 'nusantara-card' ),
			);
		}
		wp_reset_postdata();
	}

	if ( empty( $items ) ) {
		$items = nusantara_layanan_fallback();
	}

	return $items;
}

/**
 * Konten contoh layanan (dipakai sebelum CPT diisi).
 *
 * @return array
 */
function nusantara_layanan_fallback() {
	return array(
		array(
			'icon'  => 'target',
			'title' => __( 'Riset & Strategi Digital', 'nusantara-landing' ),
			'text'  => __( 'Kami petakan pasar, pengguna, dan kompetitor lebih dulu, lalu menyusun peta jalan produk yang realistis beserta metrik keberhasilannya.', 'nusantara-landing' ),
			'image' => '',
		),
		array(
			'icon'  => 'layers',
			'title' => __( 'Desain & Pengembangan Produk', 'nusantara-landing' ),
			'text'  => __( 'Tim desain dan engineer bekerja dalam satu alur: purwarupa cepat, uji ke pengguna, lalu bangun sistem yang siap tumbuh.', 'nusantara-landing' ),
			'image' => '',
		),
		array(
			'icon'  => 'chart',
			'title' => __( 'Optimasi & Pendampingan', 'nusantara-landing' ),
			'text'  => __( 'Setelah rilis, kami pantau perilaku pengguna dan memperbaiki titik-titik yang menahan konversi secara berkelanjutan.', 'nusantara-landing' ),
			'image' => '',
		),
	);
}

/**
 * Ambil data testimoni; jatuh ke konten contoh bila belum ada isian.
 *
 * @return array
 */
function nusantara_testimoni_items() {
	$items = array();
	$query = new WP_Query(
		array(
			'post_type'           => 'testimoni',
			'posts_per_page'      => 6,
			'orderby'             => 'menu_order date',
			'order'               => 'ASC',
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$items[] = array(
				'name'   => get_the_title(),
				// Jabatan hanya diambil bila excerpt memang diisi manual,
				// supaya tidak menampilkan potongan kutipan sebagai jabatan.
				'role'   => has_excerpt() ? get_the_excerpt() : '',
				'quote'  => wp_strip_all_tags( get_the_content() ),
				'avatar' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
			);
		}
		wp_reset_postdata();
	}

	if ( empty( $items ) ) {
		$items = nusantara_testimoni_fallback();
	}

	return $items;
}

/**
 * Konten contoh testimoni.
 *
 * @return array
 */
function nusantara_testimoni_fallback() {
	return array(
		array(
			'name'   => __( 'Andini Prameswari', 'nusantara-landing' ),
			'role'   => __( 'Head of Product, Bank Cakrawala', 'nusantara-landing' ),
			'quote'  => __( 'Timnya disiplin soal detail dan berani menantang asumsi kami. Peluncuran aplikasi nasabah selesai dua minggu lebih cepat dari rencana.', 'nusantara-landing' ),
			'avatar' => '',
		),
		array(
			'name'   => __( 'Bayu Kurniawan', 'nusantara-landing' ),
			'role'   => __( 'Direktur Operasional, Logistik Prima', 'nusantara-landing' ),
			'quote'  => __( 'Yang paling membantu bukan sekadar kodenya, tapi cara mereka menerjemahkan masalah operasional kami jadi alur kerja yang masuk akal.', 'nusantara-landing' ),
			'avatar' => '',
		),
		array(
			'name'   => __( 'Clara Wijaya', 'nusantara-landing' ),
			'role'   => __( 'Founder, Sekar Beauty', 'nusantara-landing' ),
			'quote'  => __( 'Laporan mingguannya jelas, jadi kami selalu tahu posisi proyek. Konversi halaman produk kami naik 42% dalam tiga bulan.', 'nusantara-landing' ),
			'avatar' => '',
		),
	);
}

/* =========================================================================
 * 8. Form kontak
 * ====================================================================== */

/**
 * Tangani kiriman formulir kontak.
 *
 * Dipakai untuk pengunjung yang login maupun tidak.
 *
 * @return void
 */
function nusantara_handle_contact() {
	$referer  = wp_get_referer();
	$redirect = $referer ? remove_query_arg( 'kirim', $referer ) : home_url( '/' );

	// Nonce wajib valid sebelum membaca data lain.
	if ( ! isset( $_POST['nusantara_contact_nonce'] ) ) {
		wp_safe_redirect( add_query_arg( 'kirim', 'gagal', $redirect ) . '#kontak' );
		exit;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['nusantara_contact_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'nusantara_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'kirim', 'gagal', $redirect ) . '#kontak' );
		exit;
	}

	// Honeypot: bot biasanya mengisi field tersembunyi ini.
	if ( ! empty( $_POST['website_url'] ) ) {
		wp_safe_redirect( add_query_arg( 'kirim', 'sukses', $redirect ) . '#kontak' );
		exit;
	}

	// Batasi satu kiriman per 45 detik per alamat IP.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'nusantara_cf_' . md5( $ip );
	if ( get_transient( $key ) ) {
		wp_safe_redirect( add_query_arg( 'kirim', 'gagal', $redirect ) . '#kontak' );
		exit;
	}
	set_transient( $key, 1, 45 );

	$name    = isset( $_POST['nama'] ) ? sanitize_text_field( wp_unslash( $_POST['nama'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['telepon'] ) ? sanitize_text_field( wp_unslash( $_POST['telepon'] ) ) : '';
	$company = isset( $_POST['perusahaan'] ) ? sanitize_text_field( wp_unslash( $_POST['perusahaan'] ) ) : '';
	$message = isset( $_POST['pesan'] ) ? sanitize_textarea_field( wp_unslash( $_POST['pesan'] ) ) : '';

	if ( '' === $name || '' === $message || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'kirim', 'gagal', $redirect ) . '#kontak' );
		exit;
	}

	$to = nusantara_get_mod( 'contact_recipient' );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$subject = sprintf(
		/* translators: %s: nama pengirim. */
		__( '[Situs] Pesan baru dari %s', 'nusantara-landing' ),
		$name
	);

	$body = sprintf(
		"Nama: %s\nEmail: %s\nTelepon: %s\nPerusahaan: %s\n\nPesan:\n%s\n",
		$name,
		$email,
		$phone,
		$company,
		$message
	);

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', $name, $email ),
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'kirim', $sent ? 'sukses' : 'gagal', $redirect ) . '#kontak' );
	exit;
}
add_action( 'admin_post_nusantara_contact', 'nusantara_handle_contact' );
add_action( 'admin_post_nopriv_nusantara_contact', 'nusantara_handle_contact' );

/**
 * Tampilkan notifikasi hasil kiriman formulir.
 *
 * @return void
 */
function nusantara_contact_notice() {
	if ( ! isset( $_GET['kirim'] ) ) {
		return;
	}

	$status = sanitize_key( wp_unslash( $_GET['kirim'] ) );

	if ( 'sukses' === $status ) {
		printf(
			'<div class="notice notice--ok" role="status">%1$s<p>%2$s</p></div>',
			nusantara_icon( 'check-circle' ),
			esc_html__( 'Terima kasih! Pesan Anda sudah terkirim. Kami balas dalam 1×24 jam kerja.', 'nusantara-landing' )
		);
	} elseif ( 'gagal' === $status ) {
		printf(
			'<div class="notice notice--err" role="alert">%1$s<p>%2$s</p></div>',
			nusantara_icon( 'alert' ),
			esc_html__( 'Maaf, pesan gagal dikirim. Pastikan semua kolom wajib terisi, lalu coba lagi atau hubungi kami lewat telepon.', 'nusantara-landing' )
		);
	}
}

/* =========================================================================
 * 9. Helper
 * ====================================================================== */

/**
 * Ubah kode hex menjadi array RGB.
 *
 * @param string $hex Warna heksadesimal.
 * @return array
 */
function nusantara_hex_to_rgb( $hex ) {
	$hex = ltrim( (string) $hex, '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
		return array( 29, 78, 216 );
	}

	return array(
		hexdec( substr( $hex, 0, 2 ) ),
		hexdec( substr( $hex, 2, 2 ) ),
		hexdec( substr( $hex, 4, 2 ) ),
	);
}

/**
 * Campurkan warna hex ke arah warna lain.
 *
 * @param string $hex    Warna awal.
 * @param array  $target Warna tujuan (RGB).
 * @param float  $amount Rasio 0..1.
 * @return string Warna hex hasil campuran.
 */
function nusantara_mix_hex( $hex, $target, $amount ) {
	$amount = max( 0, min( 1, (float) $amount ) );
	$rgb    = nusantara_hex_to_rgb( $hex );
	$out    = array();

	foreach ( $rgb as $index => $channel ) {
		$mixed = $channel + ( $target[ $index ] - $channel ) * $amount;
		$out[] = str_pad( dechex( (int) round( max( 0, min( 255, $mixed ) ) ) ), 2, '0', STR_PAD_LEFT );
	}

	return '#' . implode( '', $out );
}

/**
 * Ubah teks berbaris menjadi pasangan nilai/keterangan.
 *
 * Format tiap baris: "angka | keterangan".
 *
 * @param string $text Teks sumber.
 * @return array
 */
function nusantara_parse_pairs( $text ) {
	$out   = array();
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );

	if ( ! is_array( $lines ) ) {
		return $out;
	}

	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		$out[] = array(
			'value' => isset( $parts[0] ) ? $parts[0] : '',
			'label' => isset( $parts[1] ) ? $parts[1] : '',
		);
	}

	return $out;
}

/**
 * Ubah teks berbaris menjadi array baris terpisah.
 *
 * @param string $text Teks sumber.
 * @return array
 */
function nusantara_parse_lines( $text ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	$out   = array();

	if ( ! is_array( $lines ) ) {
		return $out;
	}

	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$out[] = $line;
		}
	}

	return $out;
}

/**
 * Bangun tautan WhatsApp dari nomor apa pun formatnya.
 *
 * @return string URL WhatsApp, atau string kosong.
 */
function nusantara_wa_link() {
	$number = preg_replace( '/[^0-9]/', '', (string) nusantara_get_mod( 'contact_wa' ) );

	if ( '' === $number ) {
		return '';
	}

	if ( '0' === substr( $number, 0, 1 ) ) {
		$number = '62' . substr( $number, 1 );
	}

	return 'https://wa.me/' . $number;
}

/**
 * Daftar tautan sosial yang terisi.
 *
 * @return array
 */
function nusantara_socials() {
	$networks = array(
		'instagram' => __( 'Instagram', 'nusantara-landing' ),
		'linkedin'  => __( 'LinkedIn', 'nusantara-landing' ),
		'facebook'  => __( 'Facebook', 'nusantara-landing' ),
		'youtube'   => __( 'YouTube', 'nusantara-landing' ),
	);
	$out      = array();

	foreach ( $networks as $slug => $label ) {
		$url = nusantara_get_mod( 'social_' . $slug );
		if ( '' !== trim( (string) $url ) ) {
			$out[ $slug ] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}

	return $out;
}

/**
 * Menu bawaan saat belum ada menu yang dipasang.
 *
 * @return array
 */
function nusantara_default_menu() {
	return array(
		__( 'Beranda', 'nusantara-landing' )   => '#beranda',
		__( 'Layanan', 'nusantara-landing' )   => '#layanan',
		__( 'Tentang', 'nusantara-landing' )   => '#tentang',
		__( 'Testimoni', 'nusantara-landing' ) => '#testimoni',
		__( 'Kontak', 'nusantara-landing' )    => '#kontak',
	);
}

/**
 * Fallback menu navigasi.
 *
 * @param array $args Argumen wp_nav_menu().
 * @return void
 */
function nusantara_nav_fallback( $args = array() ) {
	$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'nav-list';

	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( nusantara_default_menu() as $label => $href ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $href ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Kumpulan ikon SVG inline.
 *
 * Semua markup ikon bersifat statis (bukan input pengguna), sehingga aman
 * untuk langsung ditampilkan.
 *
 * @param string $name Nama ikon.
 * @return string
 */
function nusantara_icon( $name ) {
	$stroke = '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>';

	$icons = array(
		'check'       => '<path d="M20 6 9 17l-5-5"/>',
		'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8.4 12.4 2.6 2.5 4.6-5"/>',
		'arrow'       => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'phone'       => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.4 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.4 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
		'mail'        => '<rect x="2" y="4" width="20" height="16" rx="2.5"/><path d="m2.8 7 9.2 6 9.2-6"/>',
		'pin'         => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'clock'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5.2l3 1.8"/>',
		'star'        => '<path d="m12 2.6 3 6.1 6.7 1-4.9 4.7 1.2 6.7L12 18l-6 3.1 1.2-6.7L2.3 9.7l6.7-1z" fill="currentColor" stroke="none"/>',
		'target'      => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4" fill="currentColor" stroke="none"/>',
		'layers'      => '<path d="m12 2.6 9 5-9 5-9-5z"/><path d="m3 12.4 9 5 9-5"/><path d="m3 17 9 5 9-5"/>',
		'chart'       => '<path d="M3 3v18h18"/><path d="m7 15 3.6-4.2 3 2.7L21 7"/>',
		'shield'      => '<path d="M12 22s8-4 8-10V5.2L12 2.2 4 5.2V12c0 6 8 10 8 10z"/><path d="m9 11.8 2.2 2.2L15.4 10"/>',
		'users'       => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/>',
		'spark'       => '<path d="m12 2.2 9 5.2v9.2l-9 5.2-9-5.2V7.4z" fill="currentColor" stroke="none"/>',
		'zap'         => '<path d="M13 2 4.5 13.5H11l-1 8.5 9-12h-6.8z"/>',
		'alert'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.2"/><path d="M12 16.4h.01"/>',
		'award'       => '<circle cx="12" cy="9" r="6"/><path d="m8.4 13.8-1.4 8 5-2.6 5 2.6-1.4-8"/>',
		'headset'     => '<path d="M4 13.5v-1.5a8 8 0 0 1 16 0v1.5"/><path d="M4 13.5h2.2a1.3 1.3 0 0 1 1.3 1.3v3.4a1.3 1.3 0 0 1-1.3 1.3H5a1 1 0 0 1-1-1z"/><path d="M20 13.5h-2.2a1.3 1.3 0 0 0-1.3 1.3v3.4a1.3 1.3 0 0 0 1.3 1.3H19a1 1 0 0 0 1-1z"/>',
		'quote'       => '<path d="M9.5 6.5H6A2.5 2.5 0 0 0 3.5 9v2.5A2.5 2.5 0 0 0 6 14h1.2c0 2.2-1.1 3.4-3.2 4M20.5 6.5H17A2.5 2.5 0 0 0 14.5 9v2.5A2.5 2.5 0 0 0 17 14h1.2c0 2.2-1.1 3.4-3.2 4"/>',
		'instagram'   => '<rect x="3" y="3" width="18" height="18" rx="5.2"/><circle cx="12" cy="12" r="4"/><circle cx="17.4" cy="6.6" r="1.1" fill="currentColor" stroke="none"/>',
		'linkedin'    => '<path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3zM10 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-5.4c0-1.29-.02-2.95-1.8-2.95-1.8 0-2.07 1.4-2.07 2.85V21h-4z" fill="currentColor" stroke="none"/>',
		'facebook'    => '<path d="M13.4 21v-7.6h2.6l.4-3h-3V8.6c0-.9.3-1.5 1.6-1.5h1.5V4.4c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2.1H7.7v3h2.6V21z" fill="currentColor" stroke="none"/>',
		'youtube'     => '<path d="M23 12s0-3.2-.4-4.7a2.5 2.5 0 0 0-1.8-1.8C19.3 5 12 5 12 5s-7.3 0-8.8.5a2.5 2.5 0 0 0-1.8 1.8C1 8.8 1 12 1 12s0 3.2.4 4.7a2.5 2.5 0 0 0 1.8 1.8C4.7 19 12 19 12 19s7.3 0 8.8-.5a2.5 2.5 0 0 0 1.8-1.8C23 15.2 23 12 23 12zM9.8 15.3V8.7l6.2 3.3z" fill="currentColor" stroke="none"/>',
		'whatsapp'    => '<path d="M12 2.2a9.8 9.8 0 0 0-8.4 14.7L2.2 21.8l5-1.3A9.8 9.8 0 1 0 12 2.2zm0 17.8a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20z" fill="currentColor" stroke="none"/><path d="M16.6 14.1c-.2-.1-1.4-.7-1.7-.8-.2-.1-.4-.1-.5.1l-.7 1c-.2.2-.3.2-.5.1a6.6 6.6 0 0 1-3.3-2.9c-.1-.2 0-.4.1-.5l.5-.6c.1-.2.2-.3.1-.5l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.5c-.2 0-.5.1-.7.4-.3.3-.9 1-.9 2.3 0 1.3.9 2.6 1 2.8.1.2 1.8 3 4.6 4.1 2.6 1 3.2.8 3.8.8.6-.1 1.8-.8 2.1-1.5.3-.7.3-1.3.2-1.5z" fill="currentColor" stroke="none"/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return sprintf( $stroke, $icons[ $name ] );
}

/**
 * Tampilkan judul section beserta eyebrow dan deskripsi.
 *
 * @param string $title    Judul (HTML terbatas).
 * @param string $subtitle Deskripsi.
 * @param string $eyebrow  Label kecil di atas judul.
 * @param string $align    'center' atau 'left'.
 * @return void
 */
function nusantara_section_head( $title, $subtitle = '', $eyebrow = '', $align = 'center' ) {
	$class = 'section-head' . ( 'left' === $align ? ' section-head--left' : '' );

	echo '<div class="' . esc_attr( $class ) . ' reveal">';

	if ( '' !== $eyebrow ) {
		echo '<span class="eyebrow">' . esc_html( $eyebrow ) . '</span>';
	}

	if ( '' !== $title ) {
		echo '<h2>' . nusantara_kses_html( $title ) . '</h2>';
	}

	if ( '' !== $subtitle ) {
		echo '<p class="section-lead">' . esc_html( $subtitle ) . '</p>';
	}

	echo '</div>';
}

/**
 * Header halaman untuk blog, arsip, pencarian, dan 404.
 *
 * @param string $title    Judul halaman.
 * @param string $subtitle Deskripsi opsional.
 * @return void
 */
function nusantara_page_hero( $title, $subtitle = '' ) {
	echo '<div class="page-hero"><div class="container">';
	echo '<h1>' . esc_html( $title ) . '</h1>';

	if ( '' !== $subtitle ) {
		echo '<p class="section-lead">' . esc_html( $subtitle ) . '</p>';
	}

	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<div class="breadcrumb">', '</div>' );
	}

	echo '</div></div>';
}

/**
 * Kelas body tambahan.
 *
 * @param array $classes Kelas bawaan.
 * @return array
 */
function nusantara_body_classes( $classes ) {
	if ( ! is_active_sidebar( 'sidebar-1' ) || is_front_page() ) {
		$classes[] = 'no-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'nusantara_body_classes' );

/**
 * Perpendek excerpt.
 *
 * @param int $length Panjang bawaan.
 * @return int
 */
function nusantara_excerpt_length( $length ) {
	return is_admin() ? $length : 24;
}
add_filter( 'excerpt_length', 'nusantara_excerpt_length', 20 );

/**
 * Ubah akhiran excerpt.
 *
 * @return string
 */
function nusantara_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'nusantara_excerpt_more' );

=== Nusantara Landing ===
Contributors: nusantarastudio
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: landing-page, one-column, custom-logo, custom-menu, custom-colors, featured-images, translation-ready, threaded-comments, right-sidebar

Tema WordPress klasik untuk landing page perusahaan: hero, layanan, tentang,
testimoni, ajakan bertindak, dan kontak — semuanya bisa diatur dari Customizer
tanpa menyentuh kode.

== Deskripsi ==

Nusantara Landing dibuat untuk perusahaan yang butuh satu halaman profil yang
rapi dan cepat, bukan tema serba bisa yang berat.

Yang sudah termasuk:

* Header lengket dengan menu mobile.
* Hero dengan dua tombol aksi, statistik, dan mockup dasbor CSS (bisa diganti
  gambar sendiri lewat Customizer).
* Layanan — dikelola sebagai custom post type, atau memakai tiga contoh bawaan
  sebelum diisi.
* Tentang dengan daftar keunggulan dan kolase statistik.
* Testimoni — custom post type dengan nama, jabatan, kutipan, dan foto.
* Pita CTA dengan tombol WhatsApp otomatis (nomor 08xx dikonversi ke 62xx).
* Kontak: daftar alamat/telepon/email/jam + formulir kirim pesan, plus peta
  opsional.
* Footer empat kolom, blog/arsip/404 lengkap, sidebar widget, dan komentar.
* Tombol WhatsApp mengambang di kanan bawah.

Keunggulan teknis:

* Tanpa build step dan tanpa dependensi eksternal — tidak ada permintaan ke
  Google Fonts atau CDN mana pun.
* Warna brand tunggal menghasilkan turunan otomatis (gelap + tint) lewat
  CSS custom properties.
* Escape dan sanitasi di seluruh output; formulir kontak memakai nonce,
  honeypot, dan pembatas laju sederhana.
* Menghormati prefers-reduced-motion dan menyediakan skip-link serta focus ring.

== Instalasi ==

1. Unduh `nusantara-landing.zip`.
2. Di dasbor WordPress buka *Tampilan → Tema → Tambah Baru → Unggah Tema*.
3. Pilih berkas zip, klik *Pasang Sekarang*, lalu *Aktifkan*.

Cara manual: ekstrak folder `nusantara-landing` ke `wp-content/themes/`, lalu
aktifkan dari menu Tema.

== Mengatur isi halaman depan ==

Buka *Tampilan → Sesuaikan (Customizer) → Konten Landing Page*. Di dalamnya ada
panel terpisah untuk Hero, Layanan, Tentang, Testimoni, Ajakan Bertindak,
Kontak & Sosial, Footer, dan Warna Brand.

Halaman depan dirender oleh `front-page.php`, jadi isinya **tidak** diambil dari
editor halaman. Semua teks diubah dari Customizer; ini berlaku baik ketika
*Pengaturan → Membaca* disetel ke "Postingan terbaru" maupun ke "Halaman statis".

Beberapa kolom memakai format khusus:

* Statistik hero dan statistik tentang: satu baris per item, `angka | keterangan`
  contoh: `250+ | Proyek selesai`
* Poin keunggulan di section Tentang: satu poin per baris.

== Layanan & Testimoni ==

Setelah tema aktif, akan muncul dua menu baru di dasbor:

* **Layanan** — judul, deskripsi singkat (kolom Excerpt, atau otomatis dipotong
  dari isi), dan gambar unggulan opsional. Urutan diatur lewat kolom *Atribut
  Halaman*.
* **Testimoni** — judul = nama pemberi testimoni, isi = kutipan, Excerpt =
  jabatan/perusahaan, gambar unggulan = foto.

Selama keduanya masih kosong, halaman depan otomatis memakai tiga contoh bawaan
supaya tampilannya tidak pernah kosong.

== Formulir kontak ==

Formulir mengirim ke `admin-post.php` (action `nusantara_contact`), diverifikasi
dengan nonce, disaring honeypot, dibatasi satu kiriman per 45 detik per IP, lalu
dikirim memakai `wp_mail()` ke alamat pada kolom *Email penerima formulir*.

Catatan penting: `wp_mail()` bergantung pada konfigurasi email server. Banyak
hosting gratisan tidak mengirim email dengan andal, jadi sangat disarankan
memasang plugin SMTP (mis. WP Mail SMTP, Fluent SMTP) dan mengujinya.

Kalau Anda lebih suka page builder atau plugin formulir, hapus saja bagian
`<form>` di `template-parts/kontak.php` dan ganti dengan shortcode
Contact Form 7 / WPForms.

== Menu, logo, dan widget ==

* *Tampilan → Menu*: sediakan lokasi **Menu Utama** dan **Menu Footer**. Bila
  belum ada menu yang dipasang, tema menampilkan tautan jangkar bawaan
  (Beranda, Layanan, Tentang, Testimoni, Kontak).
* *Tampilan → Sesuaikan → Identitas Situs*: pasang logo. Tanpa logo, tema
  memakai nama situs beserta ikon bawaan.
* *Tampilan → Widget*: area **Sidebar Blog** tampil di halaman blog, arsip, dan
  pencarian.

== Struktur berkas ==

    nusantara-landing/
    ├── style.css              Header tema + seluruh CSS
    ├── functions.php          Setup, Customizer, CPT, handler formulir, helper
    ├── header.php             Header + pembuka #content
    ├── footer.php             Footer, tombol WhatsApp, penutup #content
    ├── front-page.php         Susunan section halaman depan
    ├── index.php              Daftar artikel / arsip / pencarian
    ├── page.php, single.php, 404.php, sidebar.php, comments.php
    ├── template-parts/
    │   ├── hero.php, layanan.php, tentang.php
    │   ├── testimoni.php, cta.php, kontak.php
    │   └── content.php, content-card.php
    └── assets/
        ├── css/editor.css     Gaya editor blok
        └── js/main.js         Header, menu mobile, reveal, gulir halus

== Kustomisasi lewat kode ==

Beberapa titik sambung yang tersedia:

* `nusantara_front_sections` — ubah urutan atau buang section halaman depan.
* `nusantara_fields` — tambah/ubah opsi Customizer beserta sanitasi dan defaultnya.
* `nusantara_defaults` — ganti nilai default semua opsi.
* `nusantara_content_width` — lebar konten global.
* `nusantara_icon()` — kumpulan ikon SVG inline; tambahkan nama ikon baru di
  dalam fungsi ini untuk memakainya di template.

Contoh mengubah urutan section:

    add_filter( 'nusantara_front_sections', function ( $sections ) {
        return array( 'hero', 'tentang', 'layanan', 'testimoni', 'cta', 'kontak' );
    } );

== Catatan teknis ==

* Tidak ada permintaan jaringan ke pihak ketiga secara default.
* Font memakai system font stack agar cepat dan bebas dari GDPR/privasi.
* `style.css` dan `main.js` diberi versi memakai `filemtime()` sehingga cache
  browser otomatis diperbarui saat berkas diubah.
* Ikon berupa SVG inline buatan sendiri — tidak ada dependensi ikon eksternal.

== Changelog ==

= 1.0.0 =
* Rilis pertama: hero, layanan, tentang, testimoni, CTA, kontak, footer,
  formulir kontak, CPT Layanan & Testimoni, Customizer lengkap.

== Lisensi ==

Tema ini dilisensikan di bawah GPLv2 atau versi setelahnya.
Seluruh ikon SVG ditulis khusus untuk tema ini dan bebas dipakai ulang.
Tidak ada aset pihak ketiga yang disertakan.

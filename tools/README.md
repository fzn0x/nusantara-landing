# tools/ — otomasi repositori

Folder ini **bukan bagian dari tema** dan tidak ikut masuk ke zip rilis
(dikecualikan oleh `make_zip.py`). Isinya hanya untuk membangun pratinjau
GitHub Pages.

| Berkas | Fungsi |
| --- | --- |
| `demo/index.template.html` | Sumber halaman pratinjau: cermin dari `front-page.php` dengan nilai bawaan Customizer, memakai placeholder `{{icon:nama}}`. |
| `build_demo.py` | Mengganti placeholder dengan SVG asli dari `functions.php`, menyalin `style.css`, `assets/js/main.js`, dan `screenshot.png`, lalu menulis hasilnya ke `_site/`. |
| `check_demo.py` | Memeriksa hasil sebelum diterbitkan: keseimbangan tag, kelas tanpa aturan CSS, jangkar tanpa `id`, aset hilang, placeholder tertinggal. |

## Menjalankan lokal

```bash
python3 tools/build_demo.py
python3 tools/check_demo.py
```

Hasilnya ada di `_site/` (diabaikan git). Kedua skrip hanya memakai pustaka
standar Python — tidak butuh Pillow, tidak butuh `pip install`.

## Mengubah pratinjau

Ubah `tools/demo/index.template.html`, lalu jalankan build. Kalau Anda menambah
ikon baru di `nusantara_icon()`, placeholder `{{icon:nama-baru}}` bisa langsung
dipakai: skrip membaca daftar ikon dari `functions.php`, bukan dari salinan
manual.

Kalau ada placeholder yang merujuk ikon tidak dikenal, atau masih ada teks
`{{...}}` yang tertinggal, build **gagal** dan tidak menerbitkan apa pun.

## Kenapa bukan WordPress sungguhan?

GitHub Pages hanya menyajikan berkas statis, sedangkan WordPress butuh PHP dan
basis data. Menjalankan WordPress di CI lalu menyedot HTML-nya memang mungkin,
tetapi hasilnya penuh URL absolut dan skrip bawaan WordPress yang harus ditulis
ulang — rapuh, dan tidak bisa diuji lokal tanpa PHP. Cermin bertemplat ini
deterministik, dan hasilnya sudah dibuktikan identik byte-per-byte dengan yang
terbit di `gh-pages`.

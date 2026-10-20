#!/usr/bin/env python3
"""Bangun pratinjau statis tema untuk GitHub Pages.

Halaman hasil adalah cermin HTML dari front-page.php dengan nilai bawaan
Customizer. Ikon SVG diekstrak langsung dari functions.php supaya tidak ada
salinan manual yang bisa basi, dan wrapper SVG-nya diverifikasi cocok.

Skrip ini sadar dua tata letak:
  * repositori GitHub  -> functions.php ada di root repo (tema = root)
  * ruang kerja lokal  -> tema ada di subfolder nusantara-landing/

Hanya memakai pustaka standar Python; tidak butuh Pillow maupun pip install.
"""
import os
import re
import shutil
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
REPO = os.path.dirname(HERE)

# --- kenali lokasi tema ---------------------------------------------------
if os.path.isfile(os.path.join(REPO, "functions.php")):
    THEME = REPO
elif os.path.isfile(os.path.join(REPO, "nusantara-landing", "functions.php")):
    THEME = os.path.join(REPO, "nusantara-landing")
else:
    sys.exit("gagal: functions.php tema tidak ditemukan")

# --- kenali lokasi templat & keluaran ------------------------------------
if os.path.isdir(os.path.join(REPO, "tools", "demo")):
    SRC = os.path.join(REPO, "tools", "demo")
elif os.path.isdir(os.path.join(REPO, "_tools", "demo")):
    SRC = os.path.join(REPO, "_tools", "demo")
else:
    sys.exit("gagal: folder templat demo tidak ditemukan")

OUT = os.path.join(REPO, "_site")

SVG_WRAPPER = (
    '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
    'stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" '
    'aria-hidden="true" focusable="false">%s</svg>'
)

print(f"root repo : {REPO}")
print(f"tema      : {THEME}")
print(f"templat   : {SRC}")
print(f"keluaran  : {OUT}")

funcs = open(os.path.join(THEME, "functions.php"), encoding="utf-8").read()

# --- verifikasi wrapper SVG masih sama dengan functions.php ---------------
if 'class="ico"' not in funcs or 'stroke-width="1.8"' not in funcs:
    sys.exit("gagal: wrapper SVG di functions.php berubah - periksa nusantara_icon()")

# --- ambil array ikon dari dalam fungsi nusantara_icon() ------------------
start = funcs.find("function nusantara_icon(")
if start == -1:
    sys.exit("gagal: fungsi nusantara_icon() tidak ditemukan")
end = funcs.find("\n}", start)
body = funcs[start:end if end != -1 else len(funcs)]

icons = {}
for m in re.finditer(r"^\s*'([a-z0-9-]+)'\s*=>\s*'(.*?)',\s*$", body, re.M):
    icons[m.group(1)] = m.group(2)

if len(icons) < 10:
    sys.exit(f"gagal: hanya {len(icons)} ikon terbaca - periksa format array ikon")
print(f"ikon terbaca: {len(icons)}")

# --- susun HTML -----------------------------------------------------------
tpl = open(os.path.join(SRC, "index.template.html"), encoding="utf-8").read()

used = set(re.findall(r"\{\{icon:([a-z0-9-]+)\}\}", tpl))
missing = sorted(used - set(icons))
if missing:
    sys.exit(f"gagal: placeholder ikon tanpa definisi: {missing}")

for name in used:
    tpl = tpl.replace("{{icon:%s}}" % name, SVG_WRAPPER % icons[name])

sisa = re.findall(r"\{\{[^}]*\}\}", tpl)
if sisa:
    sys.exit(f"gagal: placeholder yang belum diganti: {sisa}")
print(f"placeholder ikon dipakai: {len(used)}")

# --- tulis keluaran (bersihkan dulu supaya berkas basi tidak tertinggal) --
if os.path.isdir(OUT):
    shutil.rmtree(OUT)
os.makedirs(os.path.join(OUT, "assets"), exist_ok=True)

with open(os.path.join(OUT, "index.html"), "w", encoding="utf-8", newline="\n") as fh:
    fh.write(tpl)

for rel in ("style.css", "assets/js/main.js", "screenshot.png"):
    src_file = os.path.join(THEME, rel.replace("/", os.sep))
    dst_file = os.path.join(OUT, "assets", os.path.basename(rel))
    shutil.copyfile(src_file, dst_file)
    print(f"  disalin: {rel} -> assets/{os.path.basename(rel)}")

# .nojekyll: cegah GitHub Pages memproses berkas lewat Jekyll
open(os.path.join(OUT, ".nojekyll"), "w").close()

total = sum(len(files) for _r, _d, files in os.walk(OUT))
size = sum(os.path.getsize(os.path.join(r, f)) for r, _d, fs in os.walk(OUT) for f in fs)
print(f"selesai: {total} berkas, {size / 1024:.1f} KB")

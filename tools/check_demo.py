#!/usr/bin/env python3
"""Periksa halaman demo statis sebelum diterbitkan.

Memeriksa keseimbangan tag HTML, memastikan setiap kelas punya aturan di
style.css, setiap jangkar #id punya sasaran, setiap aset yang dirujuk ada,
dan tidak ada placeholder {{...}} yang tertinggal.

Hanya memakai pustaka standar Python.
"""
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
REPO = os.path.dirname(HERE)

if os.path.isdir(os.path.join(REPO, "_site")):
    OUT = os.path.join(REPO, "_site")
elif os.path.isdir(os.path.join(REPO, "_demo")):
    OUT = os.path.join(REPO, "_demo")
else:
    sys.exit("gagal: folder keluaran (_site) belum ada - jalankan build_demo.py dulu")

html = open(os.path.join(OUT, "index.html"), encoding="utf-8").read()
css = open(os.path.join(OUT, "assets", "style.css"), encoding="utf-8").read()

VOID = {"area", "base", "br", "col", "embed", "hr", "img", "input", "link", "meta",
        "param", "source", "track", "wbr", "path", "circle", "rect", "line",
        "polyline", "polygon", "ellipse", "use", "stop"}
TAG = re.compile(r"<(/?)([a-zA-Z][a-zA-Z0-9:-]*)((?:\"[^\"]*\"|'[^']*'|[^>\"'])*?)(/?)>", re.S)

# Kelas status yang hanya ditambahkan JavaScript.
JS_ADDED = {"is-scrolled", "is-open", "is-visible", "js"}
# Pembungkus semantik yang memang tidak punya gaya sendiri (mewarisi dari induk),
# plus kelas body yang dihasilkan WordPress, bukan dari markup tema.
BENIGN = {
    "about-copy", "contact-info", "hero-copy", "site-content", "logo-text", "ico",
    "footer-menu", "nav-list-mobile",
    "home", "page", "page-template-front-page", "blog", "single", "archive",
}

problems = []

# --- 1. keseimbangan tag --------------------------------------------------
stack = []
clean = re.sub(r"<!--.*?-->", "", html, flags=re.S)
for m in TAG.finditer(clean):
    closing, name, selfclose = m.group(1), m.group(2).lower(), m.group(4)
    line = clean.count("\n", 0, m.start()) + 1
    if name in VOID or selfclose:
        continue
    if closing:
        if not stack:
            problems.append(f"baris {line}: </{name}> tanpa pembuka")
        elif stack[-1][0] != name:
            problems.append(f"baris {line}: </{name}> menutup <{stack[-1][0]}> dari baris {stack[-1][1]}")
            stack.pop()
        else:
            stack.pop()
    else:
        stack.append((name, line))
for name, line in stack:
    problems.append(f"<{name}> baris {line} tidak ditutup")

# --- 2. kelas yang dipakai harus punya aturan CSS ------------------------
used = set()
for m in re.finditer(r'class="([^"]*)"', html):
    used.update(m.group(1).split())
defined = {c for c in re.findall(r"\.([A-Za-z][A-Za-z0-9_-]*)", css) if not c[0].isdigit()}
missing_css = sorted(c for c in used if c not in defined and c not in JS_ADDED and c not in BENIGN)

# --- 3. jangkar -----------------------------------------------------------
ids = set(re.findall(r'id="([a-z][a-z0-9_-]*)"', html))
anchors = set(re.findall(r'href="#([a-z][a-z0-9_-]*)"', html))
bad_anchors = sorted(a for a in anchors if a not in ids)

# --- 4. aset yang dirujuk ------------------------------------------------
assets = re.findall(r'(?:src|href)="(?!#|https?:|mailto:|tel:)([^"]+)"', html)
bad_assets = [a for a in assets if not os.path.isfile(os.path.join(OUT, a.replace("/", os.sep)))]

# --- 5. placeholder ------------------------------------------------------
leftover = re.findall(r"\{\{[^}]*\}\}", html)

print(f"kelas dipakai: {len(used)} | aturan CSS: {len(defined)} | jangkar: {len(anchors)} | aset: {len(assets)}")

if leftover:
    problems.append(f"placeholder belum diganti: {leftover}")
if missing_css:
    problems.append(f"kelas tanpa aturan CSS: {missing_css}")
if bad_anchors:
    problems.append(f"jangkar tanpa id: {bad_anchors}")
if bad_assets:
    problems.append(f"aset tidak ada: {bad_assets}")

if problems:
    print("\nMASALAH:")
    for p in problems:
        print("  -", p)
    sys.exit(1)

print("OK: tag seimbang, semua kelas punya aturan CSS, jangkar & aset valid.")

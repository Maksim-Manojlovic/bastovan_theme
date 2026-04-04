# Design System — Tema referenca

> Ovaj fajl je brza referenca dok koduješ. Ne ide u browser.
> Ažuriraj ga kad dodaješ nove tokene, klase ili komponente.

---

## Struktura projekta

```
tema/
├── assets/
│   ├── css/
│   │   ├── base/
│   │   │   ├── tokens.css        ← CSS varijable (boje, font, spacing, radius)
│   │   │   ├── reset.css         ← box-sizing, margin reset
│   │   │   ├── typography.css    ← h1-h4, p, a, utility klase za tekst
│   │   │   └── spacing.css       ← legacy (videti Poznati konflikti)
│   │   ├── components/
│   │   │   ├── buttons.css       ← .btn i sve varijante
│   │   │   └── wpforms.css       ← WPForms stilizacija
│   │   ├── layout/
│   │   │   ├── container.css     ← .container (max-width: 1120px)
│   │   │   ├── section.css       ← .section, .section-sm, .section-lg
│   │   │   ├── flex.css          ← .flex, .items-*, .justify-*
│   │   │   ├── grid.css          ← .grid, .gap-*
│   │   │   └── stack.css         ← .stack-xs do .stack-xl
│   │   ├── admin/
│   │   │   └── admin-sections.css ← Admin UI za sections builder
│   │   ├── base.css              ← Entry point — importuje sve iz base/, layout/, components/
│   │   └── editor.css            ← Gutenberg editor stilovi
│   ├── js/
│   │   ├── main.js               ← Globalno: nav, smooth scroll, header shadow
│   │   ├── gallery.js            ← Split reveal, scroll track, reviews, projekat filtracija
│   │   ├── animations.js         ← IntersectionObserver animacije, accordion
│   │   ├── calculator.js         ← Kalkulator cena (placeholder za buduću logiku)
│   │   └── admin/
│   │       ├── admin-gallery.js  ← Media uploader, repeater, tip select
│   │       └── sections-builder.js ← Sections builder drag & drop
│   └── icons/
│       ├── user.svg
│       ├── mail.svg
│       └── message.svg
├── inc/
│   ├── core/
│   │   ├── loader.php            ← Učitava sve inc/ module
│   │   ├── setup.php             ← Theme support, image sizes
│   │   └── helpers.php           ← bastovan_get_contact(), bastovan_icon()
│   ├── admin/
│   │   ├── admin.php             ← Admin asset enqueue
│   │   ├── meta-boxes.php        ← Meta boxovi za Usluga i Projekat CPT
│   │   ├── page-sections.php     ← Sections builder meta box za Pages
│   │   └── theme-options.php     ← Settings → Bastovan (telefon, email, adresa, social)
│   ├── content/
│   │   ├── menus.php             ← register_nav_menus
│   │   ├── post-types.php        ← CPT: projekat, usluga
│   │   ├── taxonomies.php        ← Taksonomija: tip-usluge
│   │   └── sidebars.php          ← Widget areas (prazno za sada)
│   ├── navigation/
│   │   └── class-nav-walker.php  ← Custom Walker za wp_nav_menu
│   ├── performance/
│   │   ├── performance.php       ← Disable emojis, clean wp_head, excerpt
│   │   └── cache-clear.php       ← Briši section cache na save_post
│   ├── sections/
│   │   ├── section-loader.php    ← bastovan_section(), bastovan_sections()
│   │   ├── sections-registry.php ← bastovan_get_available_sections()
│   │   └── sections-cache.php    ← bastovan_section_cache()
│   └── shortcodes/
│       └── shortcodes.php        ← [bastovan_kalkulator], [bastovan_btn]
├── sections/
│   ├── header/                   ← header.php + header.css
│   ├── hero/                     ← hero.php + hero.css
│   ├── intro/                    ← intro.php + intro.css
│   ├── services/                 ← services.php + services.css
│   ├── gallery/                  ← gallery.php + gallery.css
│   ├── calculator/               ← calculator.php + calculator.css
│   ├── reviews/                  ← reviews.php + reviews.css
│   ├── contact/                  ← contact.php + contact.css
│   ├── galerija/                 ← galerija.php + galerija.css (stranica)
│   ├── usluge/                   ← usluge.php + usluge.css (stranica)
│   └── footer/                   ← footer.php + footer.css
├── templates/
├── design-system.md
├── footer.php                    ← Poziva bastovan_section('footer')
├── functions.php                 ← Konstante + require loader.php
├── header.php                    ← DOCTYPE, wp_head(), get_template_part header
├── index.php
├── page.php
├── page-kontakt.php              ← Template: Kontakt
├── page-galerija.php             ← Template: Galerija
├── page-landing.php              ← Template: Landing Page
├── page-usluge.php               ← Template: Usluge
├── style.css                     ← WordPress tema header
└── theme.json
```

---

## Tokeni (CSS varijable)

Sve varijable su definisane u `assets/css/base/tokens.css` i dostupne globalno.

### Boje

| Varijabla               | Vrednost   | Opis                  |
|-------------------------|------------|-----------------------|
| `--color-primary`       | `#2d5a27`  | Glavna zelena         |
| `--color-primary-light` | `#4a8c42`  | Svetlija zelena       |
| `--color-primary-dark`  | `#1e3d18`  | Tamnija zelena        |
| `--color-accent`        | `#f4a623`  | Narandžasta — akcent  |
| `--color-bg`            | `#f8f6f1`  | Pozadina stranice     |
| `--color-white`         | `#ffffff`  | Bela                  |
| `--color-text`          | `#1e1d1a`  | Glavni tekst          |
| `--color-muted`         | `#6b6b6b`  | Sekundarni tekst      |

### Tipografija

| Varijabla        | Vrednost                      |
|------------------|-------------------------------|
| `--font-heading` | `"Cormorant Garamond", serif` |
| `--font-body`    | `"Outfit", sans-serif`        |

### Veličine teksta

| Varijabla      | Vrednost |
|----------------|----------|
| `--text-xs`    | `12px`   |
| `--text-sm`    | `14px`   |
| `--text-base`  | `16px`   |
| `--text-lg`    | `18px`   |
| `--text-xl`    | `22px`   |
| `--text-2xl`   | `28px`   |
| `--text-3xl`   | `36px`   |
| `--text-4xl`   | `48px`   |

### Razmaci (spacing)

| Varijabla      | Vrednost |
|----------------|----------|
| `--space-xs`   | `4px`    |
| `--space-sm`   | `8px`    |
| `--space-md`   | `16px`   |
| `--space-lg`   | `24px`   |
| `--space-xl`   | `40px`   |
| `--space-2xl`  | `64px`   |
| `--space-3xl`  | `100px`  |

### Zaobljeni uglovi

| Varijabla      | Vrednost |
|----------------|----------|
| `--radius-sm`  | `6px`    |
| `--radius-md`  | `10px`   |
| `--radius-lg`  | `20px`   |

---

## Layout klase

### Container

```html
<div class="container"> ... </div>
```

`max-width: 1120px`, horizontalno centriran.

### Section

```html
<section class="section">      <!-- padding: 100px 5vw -->
<section class="section-sm">   <!-- padding: 64px 5vw  -->
<section class="section-lg">   <!-- padding: 140px 5vw -->
```

### Flex

```html
<div class="flex items-center justify-between flex-wrap">
```

| Klasa              | CSS                              |
|--------------------|----------------------------------|
| `.flex`            | `display: flex`                  |
| `.flex-wrap`       | `flex-wrap: wrap`                |
| `.items-center`    | `align-items: center`            |
| `.items-start`     | `align-items: flex-start`        |
| `.items-end`       | `align-items: flex-end`          |
| `.justify-between` | `justify-content: space-between` |
| `.justify-center`  | `justify-content: center`        |
| `.justify-start`   | `justify-content: flex-start`    |
| `.justify-end`     | `justify-content: flex-end`      |

### Grid

```html
<div class="grid gap-lg"> ... </div>
```

| Klasa      | CSS                       |
|------------|---------------------------|
| `.grid`    | `display: grid`           |
| `.gap-sm`  | `gap: var(--space-sm)`    |
| `.gap-md`  | `gap: var(--space-md)`    |
| `.gap-lg`  | `gap: var(--space-lg)`    |
| `.gap-xl`  | `gap: var(--space-xl)`    |

### Stack (vertikalni razmak između dece)

```html
<div class="stack-md">
  <p>Element 1</p>
  <p>Element 2</p>  <!-- ← dobija margin-top: 16px -->
</div>
```

| Klasa        | Razmak              |
|--------------|---------------------|
| `.stack-xs`  | `var(--space-xs)`   |
| `.stack-sm`  | `var(--space-sm)`   |
| `.stack-md`  | `var(--space-md)`   |
| `.stack-lg`  | `var(--space-lg)`   |
| `.stack-xl`  | `var(--space-xl)`   |

---

## Dugmad

Osnovna klasa `.btn` uvek ide sa jednom varijantom.

```html
<a href="#" class="btn btn--white">Rezerviši</a>
<a href="#" class="btn btn--outline">Saznaj više</a>
<a href="#" class="btn btn--ghost">Kontakt</a>
<a href="#" class="btn btn--green">Izračunaj cenu</a>
<a href="#" class="btn btn--sm">Mali btn</a>
```

| Klasa           | Opis                                        | Upotreba                            |
|-----------------|---------------------------------------------|-------------------------------------|
| `.btn`          | Base: pill shape, 14px/28px padding, 600wt  | uvek potrebna                       |
| `.btn--white`   | Bela pozadina, primary tekst, shadow        | na tamnim sekcijama (hero, cta)     |
| `.btn--outline` | Providna pozadina, beli border 40%          | sekundarno dugme na tamnoj pozadini |
| `.btn--ghost`   | Semi-transparent bela, za footer            | footer, overlay delovi              |
| `.btn--green`   | Zelena pozadina, beli tekst                 | CTA na svetlim sekcijama            |
| `.btn--sm`      | Manji padding (10px/20px), text-sm          | header CTA                          |

---

## Tipografske klase

### Headings

```html
<h1 class="heading-xl">Naslov</h1>   <!-- 48px, 700 -->
<h2 class="heading-lg">Naslov</h2>   <!-- 36px, 700 -->
<h3 class="heading-md">Naslov</h3>   <!-- 28px, 600 -->
```

### Tekst

```html
<p class="text-lead">Uvodni paragraf</p>      <!-- 18px, muted -->
<p class="text-base">Normalan tekst</p>       <!-- 16px, lh 1.6 -->
<p class="text-muted">Sekundarni tekst</p>    <!-- color: --color-muted -->
<span class="text-eyebrow">Kategorija</span>  <!-- 12px, uppercase, tracking -->
```

### Legacy klase (postepeno zameniti)

| Legacy         | Zamena          |
|----------------|-----------------|
| `.lp-h2`       | `.heading-lg`   |
| `.lp-eyebrow`  | `.text-eyebrow` |
| `.lp-lead`     | `.text-lead`    |

---

## Sekcije

Pozivaju se sa `bastovan_section('naziv')`.
Dinamične sekcije pozivaju se sa `bastovan_section('naziv', [], true)` — no_cache.

| Sekcija      | Opis                                    | No-cache |
|--------------|-----------------------------------------|----------|
| `header`     | Navigacija, logo, CTA                   | —        |
| `hero`       | Hero sa slikom, stats, animacije        | —        |
| `intro`      | O nama, checklist                       | —        |
| `services`   | Grid usluga sa featured karticom        | —        |
| `gallery`    | Before/after slider                     | —        |
| `calculator` | Kalkulator cena                         | ✅       |
| `reviews`    | Recenzije scroll                        | —        |
| `contact`    | Kontakt forma                           | ✅       |
| `galerija`   | Portfolio stranica sa filtracijom       | ✅       |
| `usluge`     | Usluge stranica                         | ✅       |
| `footer`     | Footer sa menijima i CTA                | ✅       |

---

## Page Templates

| Fajl                | Template Name  | Opis                                |
|---------------------|----------------|-------------------------------------|
| `page-landing.php`  | Landing Page   | Dinamične sekcije iz admin buildera |
| `page-kontakt.php`  | Kontakt        | Kontakt forma + kalkulator          |
| `page-galerija.php` | Galerija       | Portfolio projekata sa filtracijom  |
| `page-usluge.php`   | Usluge         | Primarne i dodatne usluge           |

### Dodavanje novog page templatea

1. Napravi `page-naziv.php` u rootu teme
2. Dodaj `/* Template Name: Naziv */` komentar
3. Pozovi `bastovan_section('naziv', [], true)` unutar `get_header()` / `get_footer()`
4. Napravi `sections/naziv/naziv.php` i `sections/naziv/naziv.css`
5. Dodaj `naziv` u `$sections` array u `inc/assets/assets.php`
6. U WP adminu: Pages → Add New → Template → Naziv → Publish

---

## Custom Post Types

| CPT      | Slug       | Menu ikona              |
|----------|------------|-------------------------|
| Projekat | `projekat` | dashicons-images-alt2   |
| Usluga   | `usluga`   | dashicons-hammer        |

### Projekat meta polja

| Meta key              | Tip    | Opis                              |
|-----------------------|--------|-----------------------------------|
| `_bastovan_lokacija`  | string | npr. "Novi Beograd"               |
| `_bastovan_datum`     | date   | format Y-m-d                      |
| `_bastovan_galerija`  | string | ID-jevi slika odvojeni zarezom    |
| `_bastovan_usluge`    | string | ID-jevi usluga odvojeni zarezom   |

### Usluga meta polja

| Meta key              | Tip    | Opis                              |
|-----------------------|--------|-----------------------------------|
| `_bastovan_ikonica`   | string | emoji                             |
| `_bastovan_cena_od`   | number | početna cena u RSD                |
| `_bastovan_cena_do`   | number | krajnja cena u RSD                |
| `_bastovan_trajanje`  | string | npr. "2-4 sata"                   |
| `_bastovan_istaknuto` | bool   | "1" ili ""                        |
| `_bastovan_stavke`    | JSON   | `[{"naziv":"...","cena":"..."}]`  |

---

## Taksonomije

| Taksonomija | Slug          | Vezana za          |
|-------------|---------------|--------------------|
| Tip usluge  | `tip-usluge`  | projekat, usluga   |

Trenutni termini: `primarne-usluge`, `dodatne-usluge`

---

## Meniji

Registrovani u `inc/content/menus.php`.

| Handle         | Lokacija u WP    | Koristi se u          |
|----------------|------------------|-----------------------|
| `primary-menu` | Glavni meni      | header, footer grid   |
| `footer-menu`  | Footer meni      | footer — Usluge kol.  |
| `legal-menu`   | Pravni linkovi   | footer bottom bar     |

---

## Ikonice

Definisane u `inc/core/helpers.php` kroz `bastovan_icon( $name )`.

| Naziv         | Upotreba              |
|---------------|-----------------------|
| `phone`       | Header CTA, footer    |
| `menu`        | Mobile toggle otvori  |
| `close`       | Mobile toggle zatvori |
| `mail`        | Kontakt info          |
| `message`     | WPForms textarea      |
| `arrow-right` | Link arrows           |
| `check`       | Checklist             |
| `location`    | Adresa                |

SVG ikonice za WPForms inpute su kao inline data URI u `wpforms.css`.

---

## Konvencije

### BEM imenovanje

Svaka sekcija koristi ime sekcije kao BEM blok:

```html
<section class="hero">
  <div class="hero__inner container">
    <h1 class="hero__title">...</h1>
    <div class="hero__actions">...</div>
  </div>
</section>
```

### Kombinovanje klasa

```html
<div class="section section-sm">
  <div class="container">
    <div class="stack-md">
      <div class="text-eyebrow">Label</div>
      <h2 class="heading-lg">Naslov</h2>
    </div>
  </div>
</div>
```

---

## Poznati konflikti

| Problem                                                                      | Status                                  |
|------------------------------------------------------------------------------|-----------------------------------------|
| `.container` definisan u `spacing.css` (1200px) i `container.css` (1120px)  | ⚠️ Obrisati definiciju iz `spacing.css` |
| `.section` definisan u `spacing.css` i `section.css`                        | ⚠️ Obrisati definiciju iz `spacing.css` |
| Dupli `a:focus-visible` u `typography.css`                                  | ⚠️ Obrisati duplikat                    |
| `.lp-h2` ima `@apply: none` — invalid CSS                                   | ⚠️ Obrisati tu liniju                   |
| `parts/header.html` i `parts/footer.html`                                   | ⚠️ Obrisati — zamenjeni PHP sekcijama   |

---

*Poslednje ažuriranje: Mart 2026*

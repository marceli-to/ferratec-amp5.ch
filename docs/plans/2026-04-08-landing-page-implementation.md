# AMP5 Ferratec Landing Page — Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build a 4-language static landing page for Ferratec's AMP5 EV charging product in Statamic 5, with a posts collection for news articles.

**Architecture:** Statamic 5 multi-site (de/en/fr/it) with Antlers templates, Tailwind CSS v4, Alpine.js. German is the default locale at `/`, others at `/en`, `/fr`, `/it`. All UI strings use `{{ "string" | trans }}` with JSON translation files. Posts use a Replicator field for flexible content blocks.

**Tech Stack:** Statamic 5, Laravel, Tailwind CSS v4, Alpine.js, Vite, Antlers templating

**Design reference files:**
- Landing page: `docs/pages/industrial.html`
- Post detail: `docs/pages/news-detail.html`

---

### Task 1: Copy assets from docs to public

**Files:**
- Copy: `docs/images/product/*` → `public/assets/images/product/`
- Copy: `docs/images/installations/*` → `public/assets/images/installations/`
- Copy: `docs/images/logo/*` → `public/assets/images/logo/`
- Copy: `docs/images/branding/*` → `public/assets/images/branding/`

**Step 1: Create directories and copy images**

```bash
mkdir -p public/assets/images/{product,installations,logo,branding}
cp docs/images/product/* public/assets/images/product/
cp docs/images/installations/* public/assets/images/installations/
cp docs/images/logo/* public/assets/images/logo/
cp docs/images/branding/* public/assets/images/branding/
```

**Step 2: Verify images are in place**

```bash
ls -la public/assets/images/product/
ls -la public/assets/images/installations/
ls -la public/assets/images/logo/
```

Expected: All images from docs are now in public/assets/images.

**Step 3: Commit**

```bash
git add public/assets/images/
git commit -m "chore: copy product images to public assets"
```

---

### Task 2: Configure Tailwind theme and DM Sans font

**Files:**
- Modify: `resources/css/app.css`

**Step 1: Update app.css with theme config**

Replace the contents of `resources/css/app.css` with:

```css
@import "tailwindcss";
@plugin "@tailwindcss/typography";
@source "../views";
@source "../../content";

@theme {
  --font-dm: "DM Sans", system-ui, sans-serif;
  --color-accent: #ff6a13;
}
```

**Step 2: Verify Vite builds without errors**

```bash
npm run build
```

Expected: Build succeeds with no errors.

**Step 3: Commit**

```bash
git add resources/css/app.css
git commit -m "feat: add DM Sans font and accent color to Tailwind theme"
```

---

### Task 3: Create icon components

**Files:**
- Create: `resources/views/components/icons/logo-ferratec.antlers.html`
- Create: `resources/views/components/icons/logo-ampsociety.antlers.html`
- Create: `resources/views/components/icons/icon-linkedin.antlers.html`
- Create: `resources/views/components/icons/icon-arrow-left.antlers.html`

**Step 1: Create directory**

```bash
mkdir -p resources/views/components/icons
```

**Step 2: Create logo-ferratec component**

Create `resources/views/components/icons/logo-ferratec.antlers.html` with the SVG from `docs/resources/logo-ferratec.svg`. The SVG should be inlined and use `currentColor` for the fill so it can be styled via text color classes. Keep the original multi-color fills (green body, orange "TECHNICS", red triangle) — but wrap it so `class` can be passed through for sizing.

```html
<svg width="329" height="93" viewBox="0 0 329 93" xmlns="http://www.w3.org/2000/svg" {{ class ? "class=\"{class}\"" : "" }}>
    <g fill="none" fill-rule="evenodd">
        <g fill="#EC6608">
            <!-- TECHNICS text paths - copy from docs/resources/logo-ferratec.svg -->
        </g>
        <g fill="#1D8649">
            <!-- FERRATEC text paths - copy from docs/resources/logo-ferratec.svg -->
        </g>
        <path fill="#F7505E" d="M190.07 49.891h-19.66l9.945-23.278z"/>
    </g>
</svg>
```

Note: Copy the full SVG path data from `docs/resources/logo-ferratec.svg`. The component should accept a `class` parameter for sizing (e.g. `{{ partial:components/icons/logo-ferratec class="h-6 w-auto" }}`).

**Step 3: Create logo-ampsociety component**

Create `resources/views/components/icons/logo-ampsociety.antlers.html`. The AmpSociety logo is a PNG image, so use an `<img>` tag:

```html
<img src="/assets/images/logo/Amp_Society_logo_RGB_white.png" alt="AmpSociety" {{ class ? "class=\"{class}\"" : "" }}>
```

**Step 4: Create icon-linkedin component**

Create `resources/views/components/icons/icon-linkedin.antlers.html`:

```html
<svg viewBox="0 0 24 24" fill="currentColor" {{ class ? "class=\"{class}\"" : "" }}><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0z"/></svg>
```

**Step 5: Create icon-arrow-left component**

Create `resources/views/components/icons/icon-arrow-left.antlers.html`:

```html
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" {{ class ? "class=\"{class}\"" : "" }}><path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" /></svg>
```

**Step 6: Commit**

```bash
git add resources/views/components/icons/
git commit -m "feat: add icon components for logos and UI icons"
```

---

### Task 4: Enable multi-site and configure 4 locales

**Files:**
- Modify: `config/statamic/system.php` — set `'multisite' => true`
- Create: `config/statamic/sites.php` — define de/en/fr/it sites
- Modify: `content/collections/pages/home.md` — will be restructured per site

**Step 1: Enable multisite in system.php**

In `config/statamic/system.php`, change line 33:

```php
'multisite' => true,
```

**Step 2: Create sites.php config**

Create `config/statamic/sites.php`:

```php
<?php

return [
    'sites' => [
        'de' => [
            'name' => 'Deutsch',
            'locale' => 'de_CH',
            'lang' => 'de',
            'url' => '/',
        ],
        'en' => [
            'name' => 'English',
            'locale' => 'en_US',
            'lang' => 'en',
            'url' => '/en/',
        ],
        'fr' => [
            'name' => 'Français',
            'locale' => 'fr_CH',
            'lang' => 'fr',
            'url' => '/fr/',
        ],
        'it' => [
            'name' => 'Italiano',
            'locale' => 'it_CH',
            'lang' => 'it',
            'url' => '/it/',
        ],
    ],
];
```

**Step 3: Restructure content for multisite**

Move existing content into site-specific directories. In Statamic 5 multisite, entries get an `origin` field pointing to the default locale entry.

Move `content/collections/pages/home.md` → `content/collections/pages/de/home.md` and create localized versions:

`content/collections/pages/de/home.md`:
```yaml
---
title: Home
id: home
template: home
blueprint: pages
---
```

`content/collections/pages/en/home.md`:
```yaml
---
title: Home
id: home-en
origin: home
template: home
blueprint: pages
---
```

`content/collections/pages/fr/home.md`:
```yaml
---
title: Home
id: home-fr
origin: home
template: home
blueprint: pages
---
```

`content/collections/pages/it/home.md`:
```yaml
---
title: Home
id: home-it
origin: home
template: home
blueprint: pages
---
```

Update `content/trees/collections/pages.yaml`:
```yaml
tree:
  -
    entry: home
```

**Step 4: Update pages collection config**

Modify `content/collections/pages.yaml` to add sites:

```yaml
title: Pages
structure:
  root: true
route: '{parent_uri}/{slug}'
propagate: true
sites:
  - de
  - en
  - fr
  - it
```

**Step 5: Verify the site loads without errors**

```bash
php artisan route:clear
php please stache:refresh
```

Expected: No errors.

**Step 6: Commit**

```bash
git add config/statamic/system.php config/statamic/sites.php content/collections/ content/trees/
git commit -m "feat: enable Statamic multisite with de/en/fr/it locales"
```

---

### Task 5: Create translation files

**Files:**
- Create: `lang/de.json`
- Create: `lang/en.json`
- Create: `lang/fr.json`
- Create: `lang/it.json`

**Step 1: Create de.json (German — default, strings map to themselves)**

Create `lang/de.json`. Since German is the default, we still define it for completeness. Extract all UI strings from `docs/pages/industrial.html` and `docs/pages/news-detail.html`.

```json
{
  "Exklusiv über Ferratec in der Schweiz": "Exklusiv über Ferratec in der Schweiz",
  "Vorteile": "Vorteile",
  "Schulung": "Schulung",
  "Planner": "Planner",
  "News": "News",
  "Kontakt": "Kontakt",
  "Die Ladelösung, die mitwächst.": "Die Ladelösung, die mitwächst.",
  "Skalierbare Ladeinfrastruktur für Tiefgaragen, Mehrparteien-Garagen und Aussenparkplätze — zentral gesteuert, kabelgebunden, mit minimalem Installationsaufwand.": "Skalierbare Ladeinfrastruktur für Tiefgaragen, Mehrparteien-Garagen und Aussenparkplätze — zentral gesteuert, kabelgebunden, mit minimalem Installationsaufwand.",
  "Beratung anfragen": "Beratung anfragen",
  "Mehr erfahren": "Mehr erfahren",
  "Sechs Gründe für AMP5.": "Sechs Gründe für AMP5.",
  "Wirtschaftlichkeit": "Wirtschaftlichkeit",
  "Wirtschaftlichkeit_text": "Kabelgebundene Anbindung pro Ladepunkt über die ConnectBar — keine Abhängigkeit von WLAN-Empfang in der Tiefgarage. Wand- oder Pfostenmontage ohne Fundament- oder Tiefbauarbeiten, schnelle Inbetriebnahme. Tiefe Gesamtkosten über die gesamte Nutzungsdauer.",
  "Zuverlässigkeit": "Zuverlässigkeit",
  "Zuverlässigkeit_text": "Maximale Stabilität, minimaler Aufwand. Dank selbstheilender Architektur und zentraler Steuerung reduziert AMP5 Ausfälle und Supportkosten spürbar — und sorgt dafür, dass Ihre Ladeinfrastruktur auch dann läuft, wenn niemand hinschaut.",
  "Skalierbar in Breite und Tiefe": "Skalierbar in Breite und Tiefe",
  "Skalierbar_text": "Jeder SmartHub ist ein vernetzter, digitaler Verteilkasten. Mehr Ladepunkte nötig? Ein weiterer SmartHub wird ergänzt, automatisch vernetzt, Lastmanagement und digitale Transparenz skalieren mit.",
  "Nachhaltigkeit": "Nachhaltigkeit",
  "Nachhaltigkeit_text": "Mindestens 75 % recyceltes Aluminium und eine projektierte Lebensdauer von über 20 Jahren. Modularer Aufbau erlaubt den gezielten Tausch einzelner Komponenten.",
  "Design": "Design",
  "Design_text": "Robustes Aluminium-Gehäuse in Graphitschwarz (IK10). 4,3-Zoll-Display und integrierte LED-Beleuchtung, bündig in die ConnectBar integriert.",
  "Zukunftssicher": "Zukunftssicher",
  "Zukunftssicher_text": "Vorbereitet für Plug & Charge und V2G. Firmware-Updates (FOTA) erfolgen ohne Ladeunterbrechung — was Sie heute installieren, ist auch morgen noch auf dem neuesten Stand.",
  "Für Elektroinstallateure": "Für Elektroinstallateure",
  "Partner werden. AMP5 installieren.": "Partner werden. AMP5 installieren.",
  "Schulung_text": "Ferratec schult Elektroinstallateure in der Schweiz zur Planung, Installation und Inbetriebnahme von AMP5. Als zertifizierter Partner profitieren Sie von attraktiven Konditionen, direktem technischen Support und einem wachsenden Projektgeschäft.",
  "Auf Eventbrite anmelden": "Auf Eventbrite anmelden",
  "Das System": "Das System",
  "Modular, zentral gesteuert, skalierbar.": "Modular, zentral gesteuert, skalierbar.",
  "System_text": "Geeignet für Mehrparteien-Garagen, Aussenparkplätze und Tiefgaragen — von kleinen Anlagen bis zu Grossprojekten.",
  "SmartHubs lassen sich zu Gruppen vernetzen": "SmartHubs lassen sich zu Gruppen vernetzen",
  "Verkabelung integriert in der ConnectBar": "Verkabelung integriert in der ConnectBar",
  "LAN-Anbindung pro Ladepunkt": "LAN-Anbindung pro Ladepunkt",
  "Wandmontage oder freistehend über Erdschrauben": "Wandmontage oder freistehend über Erdschrauben",
  "Hersteller": "Hersteller",
  "AmpSociety. Ladeinfrastruktur aus Schweden.": "AmpSociety. Ladeinfrastruktur aus Schweden.",
  "Hersteller_text_1": "Hinter AMP5 steht AmpSociety, ein etablierter schwedischer Hersteller mit direkten Wurzeln in ChargeNode — dem grössten Ladestationsbetreiber Schwedens mit über 60'000 Ladepunkten.",
  "Hersteller_text_2": "AMP5 ist aus der Praxis eines Ladenetzbetreibers heraus entstanden. Servicefreundlichkeit, Ausfallsicherheit, einfacher Komponententausch im Feld.",
  "Planungstool": "Planungstool",
  "AMP5 Planner.": "AMP5 Planner.",
  "Planner_text": "Projekt strukturiert erfassen, Anzahl Ladepunkte, Montageart, Kabelführung und Lastmanagement konfigurieren — Ferratec erstellt anschliessend eine individuelle Offerte.",
  "Interaktive Projektkonfiguration in wenigen Schritten": "Interaktive Projektkonfiguration in wenigen Schritten",
  "Automatische Stückliste als Grundlage für die Offerte": "Automatische Stückliste als Grundlage für die Offerte",
  "Offerte wird von Ferratec individuell erstellt": "Offerte wird von Ferratec individuell erstellt",
  "Planer öffnen": "Planer öffnen",
  "AMP5 in der Praxis.": "AMP5 in der Praxis.",
  "Bereit für AMP5?": "Bereit für AMP5?",
  "CTA_text": "Wir beraten Bauherren, Verwaltungen, Installateure und Flottenbetreiber — unverbindlich und auf Schweizerdeutsch.",
  "Entdecken": "Entdecken",
  "Partner": "Partner",
  "Folgen": "Folgen",
  "AMP5 Schweiz": "AMP5 Schweiz",
  "AMP5 ist eine Marke von AmpSociety": "AMP5 ist eine Marke von AmpSociety",
  "Zurück zu News": "Zurück zu News",
  "AMP5 im Einsatz": "AMP5 im Einsatz"
}
```

**Step 2: Create en.json**

Create `lang/en.json` with English translations for all keys. Translate every German string to English.

**Step 3: Create fr.json**

Create `lang/fr.json` with French translations.

**Step 4: Create it.json**

Create `lang/it.json` with Italian translations.

**Step 5: Commit**

```bash
git add lang/
git commit -m "feat: add translation files for de/en/fr/it"
```

---

### Task 6: Build the layout template

**Files:**
- Modify: `resources/views/layout.antlers.html`

**Step 1: Replace layout.antlers.html**

Reference: `docs/pages/industrial.html` lines 1-50 (header) and lines 209-251 (footer).

The layout must include:
- `<head>` with charset, viewport, title, Google Fonts (DM Sans), Vite assets
- Orange top bar with translated text
- Sticky header with logo components, nav links (translated), language switcher
- `{{ template_content }}` for page body
- Footer with 4-column grid (address, discover, partner, social), copyright bar
- All visible text wrapped in `{{ "string" | trans }}`
- Language switcher: loop through sites, link to current page in each locale

Key Antlers patterns for the layout:
- Language switcher: `{{ locales }}` tag to get all locale versions of current page
- Current locale check: `{{ site:short_locale }}`
- Translation: `{{ "String" | trans }}`

**Step 2: Verify the page renders**

```bash
php please stache:refresh
```

Visit the homepage in browser — should see header and footer with correct styling.

**Step 3: Commit**

```bash
git add resources/views/layout.antlers.html
git commit -m "feat: build layout with header, footer, and language switcher"
```

---

### Task 7: Build the home page template

**Files:**
- Create: `resources/views/home.antlers.html`

**Step 1: Create home.antlers.html**

Reference: `docs/pages/industrial.html` lines 52-207 (all sections between header and footer).

Implement all 7 sections from the design document:

1. **Hero** (lines 52-65): headline, subtext, 2 CTA buttons, hero image
2. **Six Reasons** (lines 67-103): 2-column grid with 6 numbered feature cards
3. **Training** (lines 105-129): dark bg, 2-col layout — partner info + system list
4. **Manufacturer** (lines 131-150): image + text about AmpSociety
5. **Planner** (lines 152-173): text + placeholder image
6. **News** (lines 175-196): 3-column grid pulling from posts collection via `{{ collection:posts limit="3" sort="date:desc" }}`
7. **CTA Contact** (lines 198-207): accent bg, heading, text, email + phone buttons

All visible text must use `{{ "string" | trans }}`.

For the news section, use the teaser partial:
```antlers
{{ collection:posts limit="3" sort="date:desc" }}
  {{ partial:partials/news-teaser }}
{{ /collection:posts }}
```

Image paths should reference `/assets/images/...`.

**Step 2: Verify the homepage renders all sections**

Visit homepage in browser. All sections should display with correct layout and styling.

**Step 3: Commit**

```bash
git add resources/views/home.antlers.html
git commit -m "feat: build home page template with all landing page sections"
```

---

### Task 8: Create the posts collection and blueprint

**Files:**
- Create: `content/collections/posts.yaml`
- Create: `resources/blueprints/collections/posts/post.yaml`

**Step 1: Create posts collection config**

Create `content/collections/posts.yaml`:

```yaml
title: Posts
template: posts/show
layout: layout
route: 'posts/{slug}'
sort_dir: desc
date: true
date_behavior:
  past: public
  future: private
sites:
  - de
  - en
  - fr
  - it
propagate: true
```

**Step 2: Create posts blueprint**

Create `resources/blueprints/collections/posts/post.yaml`:

```yaml
title: Post
tabs:
  main:
    display: Main
    sections:
      -
        fields:
          -
            handle: title
            field:
              type: text
              required: true
          -
            handle: teaser
            field:
              type: textarea
              display: Teaser
              instructions: 'Short text shown in the news overview on the homepage.'
          -
            handle: image
            field:
              type: assets
              display: 'Hero Image'
              container: assets
              max_files: 1
          -
            handle: article_body
            field:
              type: replicator
              display: 'Article Body'
              sets:
                content:
                  display: Content
                  sets:
                    text:
                      display: Text
                      icon: text
                      fields:
                        -
                          handle: text
                          field:
                            type: bard
                            display: Text
                            buttons:
                              - h2
                              - h3
                              - bold
                              - italic
                              - unorderedlist
                              - orderedlist
                              - anchor
                    image:
                      display: Image
                      icon: media-image
                      fields:
                        -
                          handle: image
                          field:
                            type: assets
                            display: Image
                            container: assets
                            max_files: 1
                        -
                          handle: caption
                          field:
                            type: textarea
                            display: Caption
                    image_text_card:
                      display: 'Image & Text Card'
                      icon: layout-sidebar-reverse
                      fields:
                        -
                          handle: image
                          field:
                            type: assets
                            display: Image
                            container: assets
                            max_files: 1
                        -
                          handle: label
                          field:
                            type: text
                            display: Label
                        -
                          handle: heading
                          field:
                            type: text
                            display: Heading
                        -
                          handle: body
                          field:
                            type: textarea
                            display: Body
                    video:
                      display: Video
                      icon: media-webcam
                      fields:
                        -
                          handle: video_url
                          field:
                            type: text
                            display: 'Video URL'
                            instructions: 'URL to the video file (mp4).'
                        -
                          handle: poster
                          field:
                            type: assets
                            display: 'Poster Image'
                            container: assets
                            max_files: 1
```

**Step 3: Create asset container if not exists**

Check if `content/assets/assets.yaml` exists. If not, create it:

```yaml
title: Assets
disk: assets
```

And ensure `config/filesystems.php` has an `assets` disk pointing to `public/assets`.

**Step 4: Verify blueprint appears in CP**

```bash
php please stache:refresh
```

Visit `/cp/collections/posts` — the collection should appear (empty).

**Step 5: Commit**

```bash
git add content/collections/posts.yaml resources/blueprints/collections/posts/
git commit -m "feat: create posts collection with replicator blueprint"
```

---

### Task 9: Create news teaser partial

**Files:**
- Create: `resources/views/partials/_news-teaser.antlers.html`

**Step 1: Create the teaser partial**

Reference: `docs/pages/industrial.html` lines 179-194.

```antlers
<a href="{{ url }}" class="group">
  {{ if image }}
    <img src="{{ image }}" alt="" class="w-full aspect-[3/2] object-cover mb-4">
  {{ /if }}
  <p class="text-xs text-zinc-400 mb-1">{{ date format="F Y" }}</p>
  <h3 class="text-base font-semibold group-hover:text-accent transition-colors">{{ title }}</h3>
</a>
```

**Step 2: Commit**

```bash
git add resources/views/partials/_news-teaser.antlers.html
git commit -m "feat: add news teaser partial"
```

---

### Task 10: Build the post detail template

**Files:**
- Create: `resources/views/posts/show.antlers.html`

**Step 1: Create posts/show.antlers.html**

Reference: `docs/pages/news-detail.html` lines 52-133.

Sections:
1. **Back link** to news section (translated "Zurück zu News")
2. **Hero image** from `image` field
3. **Title block**: date, h1 title, teaser text
4. **Replicator blocks** — loop through `article_body`:
   - `text` set: render Bard content in prose wrapper
   - `image` set: full-width image with optional caption
   - `image_text_card` set: 2-col grid with image + text card (label, heading, body)
   - `video` set: video element with poster
5. **CTA section** (same as home page — accent bg with contact info)

Key Antlers pattern for replicator:
```antlers
{{ article_body }}
  {{ if type == "text" }}
    <div class="max-w-3xl space-y-6 text-zinc-500">
      {{ text }}
    </div>
  {{ /if }}
  {{ if type == "image" }}
    ...
  {{ /if }}
  {{ if type == "image_text_card" }}
    ...
  {{ /if }}
  {{ if type == "video" }}
    ...
  {{ /if }}
{{ /article_body }}
```

All visible UI text (not content) must use `{{ "string" | trans }}`.

**Step 2: Verify template renders**

Create a test post in the CP or via markdown file and visit its URL.

**Step 3: Commit**

```bash
git add resources/views/posts/
git commit -m "feat: build post detail template with replicator blocks"
```

---

### Task 11: Create sample posts

**Files:**
- Create: `content/collections/posts/de/` — 3 sample posts (German)
- Create: `content/collections/posts/en/` — localized versions
- Create: `content/collections/posts/fr/` — localized versions
- Create: `content/collections/posts/it/` — localized versions

**Step 1: Create 3 German sample posts**

Based on the teasers in `docs/pages/industrial.html` lines 179-194:

Post 1: "Ferratec wird exklusiver AMP5-Partner in der Schweiz" (March 2026)
Post 2: "Stena Fastigheter erweitert auf 300 Ladepunkte" (February 2026)
Post 3: "OCPP 2.0.1 und ISO 15118-20 nativ verfügbar" (January 2026)

Each post should have title, date, image, teaser, and some article_body content using the replicator sets. Post 1 should match `docs/pages/news-detail.html` content exactly.

**Step 2: Create localized versions**

For en/fr/it, create entries with `origin` pointing to the German entry ID. Titles and teasers should be translated.

**Step 3: Verify news section on homepage shows 3 teasers**

Visit homepage — the news section should display 3 teaser cards.

**Step 4: Verify post detail page works**

Click a teaser — should navigate to the post detail page with all content.

**Step 5: Commit**

```bash
git add content/collections/posts/
git commit -m "feat: add 3 sample posts with localized versions"
```

---

### Task 12: Final verification and cleanup

**Step 1: Test all 4 locales**

- Visit `/` (German) — full landing page
- Visit `/en` (English) — translated UI strings
- Visit `/fr` (French) — translated UI strings
- Visit `/it` (Italian) — translated UI strings

**Step 2: Test language switcher**

Click language links in the header — should navigate to the same page in the selected locale.

**Step 3: Test post URLs in all locales**

- `/posts/ferratec-wird-exklusiver-amp5-partner`
- `/en/posts/ferratec-wird-exklusiver-amp5-partner`
- etc.

**Step 4: Run Vite build**

```bash
npm run build
```

Expected: Clean build, no errors.

**Step 5: Commit any remaining fixes**

```bash
git add -A
git commit -m "chore: final cleanup and verification"
```

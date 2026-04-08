# AMP5 Ferratec Landing Page — Design Document

## Overview

Static landing page for Ferratec's AMP5 EV charging product, built in Statamic 5 with 4-language support (de, fr, it, en). Design reference: `docs/pages/industrial.html` (landing) and `docs/pages/news-detail.html` (post detail).

## 1. Multi-site & Localization

- Statamic multi-site enabled with 4 sites:
  - `de` — default, no URL prefix (`/`)
  - `en` — prefix `/en`
  - `fr` — prefix `/fr`
  - `it` — prefix `/it`
- German is the default language (source of truth for content)
- UI strings use `{{ "string" | trans }}` with JSON translation files
- Translation files: `lang/de.json`, `lang/en.json`, `lang/fr.json`, `lang/it.json`
- Language switcher in the header linking to the same page in other locales

## 2. Collections & Content

### Pages collection (existing)

- Structured collection, single entry (`home`)
- `home` entry serves as the landing page
- Route: `{parent_uri}/{slug}` (home at root)
- Localized per site

### Posts collection (new)

- Separate collection for news articles
- Route: `posts/{slug}`
- Localized URLs: `/posts/slug`, `/en/posts/slug`, `/fr/posts/slug`, `/it/posts/slug`
- Blueprint fields:
  - `title` — text
  - `date` — date
  - `image` — asset (hero image)
  - `teaser` — textarea (used in news teasers on landing page)
  - `article_body` — **Replicator** with sets:
    - **text** — Bard field (rich text)
    - **image** — asset + optional caption (textarea)
    - **image_text_card** — image (asset), label (text), heading (text), body (textarea)
    - **video** — video URL or asset + optional poster image (asset)

News teasers on the landing page are pulled via `{{ collection:posts }}`.

## 3. Templates & Components

### Layout (`layout.antlers.html`)

Shared layout with:
- Orange top bar ("Exklusiv uber Ferratec in der Schweiz", phone, email)
- Sticky header: Ferratec logo + AmpSociety logo, nav links, language switcher
- Footer: 4-column grid (address, discover links, partner links, social)
- Copyright bar

### Page templates

- `home.antlers.html` — Landing page sections:
  1. Hero (headline, subtext, CTAs, hero image)
  2. 6 Reasons grid (2-col, 6 items with numbered labels)
  3. Training section (dark bg, 2-col: partner info + system info)
  4. Manufacturer section (product image + AmpSociety text)
  5. Planner section (text + screenshot placeholder)
  6. News section (3-col grid of post teasers from posts collection)
  7. CTA contact section (accent bg)

- `posts/show.antlers.html` — Post detail:
  1. Back link to news section
  2. Hero image
  3. Title, date, intro text
  4. Replicator blocks (text, image, image_text_card, video)
  5. CTA contact section

### Partials

- `partials/_news-teaser.antlers.html` — Teaser card (image, date, title, link)

### Icon components (`components/icons/`)

- `logo-ferratec.antlers.html` — Ferratec SVG
- `logo-ampsociety.antlers.html` — AmpSociety logo
- `icon-linkedin.antlers.html` — LinkedIn SVG
- `icon-arrow-left.antlers.html` — Back arrow SVG

## 4. Styling

- **Tailwind CSS v4** via Vite (already configured)
- Theme config in `app.css` using `@theme`:
  - Accent color: `--color-accent: #ff6a13`
  - Font family: DM Sans (Google Fonts, loaded in layout `<head>`)
- Utility-first, matching exact classes from reference HTML
- No custom CSS beyond theme definition

## 5. Assets

- All images from `docs/images/` copied to `public/assets/images/`
- Organized in subdirectories: `product/`, `installations/`, `logo/`, `branding/`
- Logo SVGs extracted into Antlers icon components

## 6. Files to create/modify

| Action | Path |
|--------|------|
| Modify | `config/statamic/system.php` — enable multisite |
| Create | Sites config (via `php please multisite` or manual config) |
| Create | `content/collections/posts.yaml` — posts collection config |
| Create | `resources/blueprints/collections/posts/post.yaml` — posts blueprint |
| Modify | `content/collections/pages/home.md` — landing page content |
| Create | Localized content entries for all 4 sites |
| Modify | `resources/views/layout.antlers.html` — full layout |
| Create | `resources/views/home.antlers.html` — landing page |
| Create | `resources/views/posts/show.antlers.html` — post detail |
| Create | `resources/views/partials/_news-teaser.antlers.html` |
| Create | `resources/views/components/icons/logo-ferratec.antlers.html` |
| Create | `resources/views/components/icons/logo-ampsociety.antlers.html` |
| Create | `resources/views/components/icons/icon-linkedin.antlers.html` |
| Create | `resources/views/components/icons/icon-arrow-left.antlers.html` |
| Modify | `resources/css/app.css` — theme config + DM Sans |
| Create | `lang/de.json`, `lang/en.json`, `lang/fr.json`, `lang/it.json` |
| Copy   | Images from `docs/images/` to `public/assets/images/` |
| Modify | `content/trees/collections/pages.yaml` — update tree |

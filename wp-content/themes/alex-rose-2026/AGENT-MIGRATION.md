# Alex Rose 2026 — Agent Migration Handoff

**Purpose:** Handoff document for continuing this WordPress theme project with a new AI agent.  
**Last updated:** 16 June 2026  
**Workspace:** `c:\xampp\htdocs\alex-rose`  
**Active theme:** `wp-content/themes/alex-rose-2026`  
**Local URL:** `http://localhost/alex-rose/`  
**Stack:** WordPress on XAMPP (Apache + PHP 8.2), MySQL database `alex_rose`

---

## 1. Project summary

Alex Rose Fine Tailoring is being rebuilt as a custom WordPress theme (`alex-rose-2026`) matching a React/reference design system. Work includes:

- Page templates with dedicated CSS/JS per page
- Form handling via `inc/forms.php` + `wp_mail()`
- A standalone **3D jacket configurator demo** in `alexrose_demo/` (Tailormate API + XT3DViewer)
- Partial integration of that demo into the **Design Your Jacket** page (started, then partially reverted)

The reference React app assets live at `/dist/` (sibling to WordPress root) and are loaded via `alex_rose_2026_dist_url()`.

---

## 2. User prompts (conversation chronology)

| # | User request | Outcome |
|---|--------------|---------|
| 1 | Implement / update **How It Works** page to match React reference | Done — 5-step tabs, measure options, styling |
| 2 | Rebuild **Send Measurements** page | Done — option cards, measure-yourself panel, form email handler |
| 3 | Homepage **Past Clients** slider — edge hover scroll | Done — RAF scroll, `scrollSpeed = 3`, snap removed |
| 4 | **FAQ** page — update all content from reference HTML | Done — 12 answers, gold links, accordion animation |
| 5 | Implement **3D configurator** from `alexrose_demo` into Design page | Partially done, then reverted by user |
| 6 | **3D 360 not working** — debug and fix | Multiple fixes attempted; API auth issues found |
| 7 | **Revert last changes** | Reverted boot script, script_loader_tag tweaks, recent 3D CSS/JS patches |
| 8 | **Fatal error: Maximum execution time 120s** in `media.php` | Fixed — removed global `script_loader_tag` filter in `functions.php` |
| 9 | Create this migration MD | This document |

---

## 3. Theme architecture

### Core files

| Path | Role |
|------|------|
| `functions.php` | Enqueues per-page CSS/JS, helpers, form recipient filter, ACF JSON paths |
| `inc/forms.php` | All `admin_post_*` form handlers, `wp_mail()` delivery |
| `inc/cloth-collections.php` | Cloth collection data/helpers |
| `assets/css/global.css`, `theme.css` | Global styles |
| `assets/css/page-*.css` | Page-specific styles |
| `assets/js/page-*.js` | Page-specific scripts |
| `template/*.php` | WordPress page templates (assign under Page → Template) |
| `template-parts/**` | Reusable markup partials |

### Helper functions (`functions.php`)

```php
alex_rose_2026_uploads_url($path)  // wp-content/uploads URLs
alex_rose_2026_dist_url($rel)       // /dist/ React build assets
alex_rose_2026_dist_templates()     // Pages using page-dist.css
```

### Form system

- Front-end: `AlexRoseFormSubmit.send()` (shared form JS)
- Backend: `inc/forms.php` — handlers like `admin_post_sm_send_measurements`
- Recipient filter: `alex_rose_2026_form_recipient` → `mastertailorteam@gmail.com`
- **WP Mail SMTP** plugin installed; user hit Gmail OAuth `invalid_grant` — fix is re-auth in plugin settings, not theme code

### Plugins (active)

- Advanced Custom Fields Pro
- Classic Editor
- WP Mail SMTP
- Akismet (default)

---

## 4. Completed work by page

### How It Works (`/how-it-works`)

- **Template:** `template/how-it-works.php`
- **Partials:** `template-parts/how-it-works/steps.php`, hero, markup
- **Assets:** `assets/css/page-how-it-works.css`, `assets/js/page-how-it-works.js`
- 5-step tab UI; step 03 includes measure-yourself / post jacket / schedule call options
- Page background `#f4f4fd`

### Send Measurements (`/send-measurements`)

- **Template:** `template/send-measurements.php`
- **Partials:** `template-parts/send-measurements/*`
- **Assets:** `page-send-measurements.css`, `page-send-measurements.js`
- Order confirmation banner + 3 option cards
- Measure-yourself: video, 7-item guide, cm/in toggle form
- Form action: `sm_send_measurements` in `inc/forms.php` (~line 547)
- Success redirects to `/schedule-a-call/`

### Homepage — Past Clients slider

- **Files:** `template-parts/home/content-2.php`, `markup.php`, `assets/css/page-home.css`
- Edge hover zones (72px mobile / 96px desktop) scroll strip via `requestAnimationFrame`
- `scrollSpeed = 3`; scroll-snap removed to fix flicker

### FAQ (`/faq`)

- **Template:** `template/faq.php`
- **Partials:** `template-parts/faq/hero.php`, `list.php`, `markup.php`
- **Assets:** `page-faq.css`, `page-faq.js`
- 12 questions in 5 groups; copy matches live reference site
- Accordion: CSS grid `0fr`→`1fr`, `.is-open` class (not `[hidden]`)
- Answer links: gold `#c8a96a`, underline
- Page bg `#f4f4fd`, borders `#ececea`
- Bottom CTA: Book a Discovery Call, Send Enquiry, phone

---

## 5. Design Your Jacket — current state & 3D configurator

### Current state (as of handoff)

The **WordPress Design page is back to the static shell**:

| File | State |
|------|-------|
| `template/design.php` | Exists — loads `template-parts/design/markup.php` |
| `template-parts/design/config.php` | Hardcoded fabric collections, lining colours, placeholder sections for buttoning/pockets/vents |
| `template-parts/design/preview.php` | **Static hero image** (not 3D mount) |
| `assets/css/page-design.css` | Mix-and-match layout styles (may still contain some 3D CSS from earlier work) |
| `assets/js/page-design.js` | **MISSING** — `functions.php` still tries to enqueue it but `is_readable()` fails silently |

### `alexrose_demo/` — reference 3D implementation

Location: `wp-content/themes/alex-rose-2026/alexrose_demo/`

| File | Purpose |
|------|---------|
| `index.html` | Standalone demo entry |
| `app.js` | Full SPA: catalog loading, UI, multi-step checkout, 3D sync |
| `tailormate-visualizer.js` | Wrapper around XT3DViewer; exports API constants |
| `visualize.js` | Scene loader, fabric/lining/button texture application |
| `monogram-preview.js` | Canvas monogram preview |
| `dist/xt-three-d-viewer.esm.js` | Bundled Three.js viewer (large ESM) |
| `styles.css` | Demo styles |

**Tailormate API:**

```javascript
TAILORMATE_API_KEY = "2817c949-40f8-412a-bce0-1a62ea20ffab"
TAILORMATE_API_BASE = "https://tailormate.xiontechnologies.in/api"
SCENE_CONFIG_URL = `${BASE}/scenes/jacket`
```

Design catalog tags: `website` (fabrics), `lining`, `buttons`

**Known API issue:** Requests to Tailormate API returned **401 Unauthorized** during debugging. Without valid scene config, 3D viewer cannot initialize. Fabrics API may fail for the same reason.

**Import path bug in demo:** `tailormate-visualizer.js` line 1 uses `../dist/xt-three-d-viewer.esm.js` but should be `./dist/...` (same folder as `visualize.js` which uses `./dist/`).

### What was built then reverted (3D integration)

An integration attempt added:

- `assets/js/page-design.mjs` — ES module importing `alexrose_demo` modules, Tailormate catalog loading, 3D sync
- `assets/js/page-design-boot.js` — dynamic import bootstrap (later deleted)
- 3D mount shells in `preview.php` and `config.php`
- `wp_script_add_data(..., 'type', 'module')` and `script_loader_tag` filter
- `.htaccess` MIME rules for `.mjs` in `assets/js/` and `alexrose_demo/`

**User reverted** most of this. `preview.php` and `config.php` are static again; `page-design.mjs` and `page-design-boot.js` are gone.

### Safe approach for next agent (3D integration)

1. **Do NOT** add a global `script_loader_tag` filter — it caused site-wide 120s timeouts
2. Use one of:
   - `page-design-boot.js` with `import('./page-design.mjs')` (classic script, no global filter)
   - Inline `<script type="module">` only on design template via `template/design.php`
3. Fix Tailormate API key before expecting 3D to work
4. Fix `tailormate-visualizer.js` import path to `./dist/xt-three-d-viewer.esm.js`
5. Restore or recreate `page-design.js` for basic section/swatch UI if not using full 3D yet

### Demo features NOT ported to WordPress

- Multi-step checkout (summary → measurements → contact)
- “Try before you buy” photo upload modal
- “Preview My Jacket” CTA flow
- Live API-driven fabric/lining/button cards (WP still uses hardcoded PHP arrays)

---

## 6. Critical incident: 120s fatal error

### Symptom

```
Fatal error: Maximum execution time of 120 seconds exceeded
in wp-includes/media.php on line 10
```

Also seen in `cache-compat.php`. Homepage timed out completely.

### Cause

Custom `script_loader_tag` filter in `functions.php` that rewrote the design script tag globally. This hung WordPress script rendering.

Apache log also showed earlier:

```
PHP Warning: Array to string conversion in wp-includes/class-wp-scripts.php on line 235
```

Likely from incorrect `wp_script_add_data` usage with `'data'` array during boot-script experiments.

### Fix applied

Removed the entire `script_loader_tag` filter block from `functions.php`. Site returned HTTP 200.

### Lesson

Never use global script tag filters unless strictly scoped and tested. Prefer page-template-only script loading.

---

## 7. Page template inventory

| Template | Slug (typical) | CSS | JS |
|----------|----------------|-----|-----|
| `home.php` | `/` | `page-home.css` | inline in markup |
| `how-it-works.php` | `/how-it-works` | ✓ | ✓ |
| `design.php` | `/design-your-jacket` | ✓ | ✗ (file missing) |
| `send-measurements.php` | `/send-measurements` | ✓ | ✓ |
| `faq.php` | `/faq` | ✓ | ✓ |
| `our-story.php` | `/our-story` | ✓ | ✓ |
| `contact.php` | `/contact` | ✓ | ✓ |
| `schedule-a-call.php` | `/schedule-a-call` | ✓ | ✓ |
| `gift-vouchers.php` | `/gift-vouchers` | ✓ | ✓ |
| `request-cloth-samples.php` | `/request-cloth-samples` | ✓ | ✓ |
| `request-tape-measure.php` | `/request-tape-measure` | ✓ | ✓ |
| `post-your-jacket.php` | `/post-your-jacket` | ✓ | ✓ |
| `showroom.php` | `/showroom` | ✓ | — |
| `cloths.php` | `/cloths` | ✓ | — |
| `cloth-collection.php` | `/cloth-collection` | ✓ | — |
| `cart.php` | `/cart` | ✓ | ✓ |
| `checkout.php` | `/checkout` | `page-dist.css` | `page-dist.js` |
| `delivery-information.php` | `/delivery-information` | — | — |
| `privacy-policy.php` | `/privacy-policy` | — | — |
| `terms-and-conditions.php` | `/terms-and-conditions` | — | — |
| `off-the-cuff.php` | `/off-the-cuff` | ✓ | ✓ |
| `occasion.php` | `/occasion` | ✓ | — |

---

## 8. Design page reference (React / live site)

The Design page should eventually match the reference:

- Left rail: Fabrics, Lining, Buttons, Buttoning, Pockets, Vents, Monogram
- Center: option panels (swatches / choice cards)
- Right: 3D rotatable jacket preview, price, currency toggle (GBP/EUR/USD)
- Mobile: preview on top, section quick-buttons, options below

Colour tokens used across pages:

- Gold: `rgb(200, 169, 106)` / `#c8a96a`
- Ink: `rgb(13, 13, 13)` / `#111`
- Soft bg: `#f4f4fd`

---

## 9. FAQ content reference (12 questions)

Groups and answers were synced from the live React FAQ. Key links:

- `/our-story/`, `/schedule-a-call/`, `/contact/`
- Email: `tailor@alexrose.uk`
- Phone: `+44 (0)113 468 8588`

File: `template-parts/faq/list.php`

---

## 10. User rules (follow in all future work)

- **Do not commit** unless explicitly asked
- **Minimize scope** — smallest correct diff
- **Match existing conventions** in theme code
- **Run commands yourself** — don't tell user to run things
- **No global script_loader_tag** without extreme care (see incident above)
- Code citations in chat: ` ```startLine:endLine:filepath ` format
- User prefers complete sentences, markdown links for paths/URLs

---

## 11. Open tasks / recommended next steps

### High priority

1. **Restore `assets/js/page-design.js`** — basic section switching + swatch selection (file is missing but enqueued)
2. **Decide on 3D scope** — full `alexrose_demo` port vs. phased integration
3. **Validate Tailormate API key** — get fresh key from client; test `/api/scenes/jacket` and `/api/designs`

### Medium priority

4. Wire Design page options (lining, buttons, buttoning, pockets, vents) from API like demo
5. Port checkout flow from `alexrose_demo/app.js` (steps 2–5)
6. Hook “Preview My Jacket” CTA
7. Fix `tailormate-visualizer.js` import path (`./dist/` not `../dist/`)

### Low priority

8. Remove orphaned 3D CSS in `page-design.css` if not using 3D mounts
9. Remove `.htaccess` MIME files if not using `.mjs`
10. Re-auth WP Mail SMTP Gmail OAuth for form delivery

---

## 12. Key file paths (quick reference)

```
wp-content/themes/alex-rose-2026/
├── functions.php
├── inc/forms.php
├── AGENT-MIGRATION.md          ← this file
├── alexrose_demo/              ← 3D configurator reference (standalone)
├── template/
│   ├── design.php
│   ├── faq.php
│   ├── how-it-works.php
│   ├── send-measurements.php
│   └── home.php
├── template-parts/
│   ├── design/{config,preview,markup}.php
│   ├── faq/{hero,list,markup}.php
│   ├── how-it-works/
│   ├── send-measurements/
│   └── home/
└── assets/
    ├── css/page-*.css
    └── js/page-*.js
```

---

## 13. Testing checklist

- [ ] `http://localhost/alex-rose/` loads in &lt; 5s (no 120s timeout)
- [ ] `/faq/` — 12 accordions, correct copy, gold links
- [ ] `/how-it-works/` — 5 steps, measure options in step 3
- [ ] `/send-measurements/` — form submits, email sent (if SMTP configured)
- [ ] `/design-your-jacket/` — section rail works (needs `page-design.js`)
- [ ] `alexrose_demo/index.html` — open directly to test 3D in isolation
- [ ] Tailormate API returns 200 with valid key

---

## 14. Agent transcript

Full conversation transcript (for deep context):

`C:\Users\Agility\.cursor\projects\c-xampp-htdocs-alex-rose\agent-transcripts\d9d8bfce-91b0-4004-841e-4f78b6323a98\d9d8bfce-91b0-4004-841e-4f78b6323a98.jsonl`

---

## 15. Contact / business context

- **Brand:** Alex Rose Fine Tailoring, since 1945, Leeds
- **Address:** 2A Rodley Lane, Rodley, Leeds LS13 1HU
- **Phones:** 0113 257 0022, 0113 468 8588
- **Email:** tailor@alexrose.uk
- **Company:** Alex Rose Fine Tailoring Ltd · Company 02587407

---

*End of handoff document.*

# Kamagramate Design System

Direction: **clinical minimal, purple base**, modelled closely on
modafinilaustraliadirect.com (same client): announcement bar, navy trust bar, logo
left with nav right, left-aligned hero, tinted product cards in a carousel, dark icon
strip, centred section titles and a two-column FAQ. Reference layout rhythm from
modafinilaustraliadirect.com (centred section titles, product cards, trust strip,
delivery block, how-to-order steps, audience grid, blog, two-column FAQ), with
the calmer, airier spacing and extra commerce sections of kamagraaus.com.

Note: this site deliberately uses generous section spacing and large type, at the
client's request for Kamagramate (October 2026). The compact-spacing rule in
`.agents/persona.md` comes from the Waklert site and does not apply here.

Principles

1. **One accent.** Purple does the work (CTAs, links, active states). Everything
   else is ink, white and a single lavender tint. No gradients on content.
2. **Big type, few words.** Large display headings, short leads, generous
   line-height. Body copy never below 16px; leads 19-22px.
3. **Air.** Sections breathe (`py-20` mobile, `py-28`/`py-32` desktop). Cards
   use padding, hairline borders and space instead of shadows and dividers.
4. **Flat surfaces.** 1px borders, no dashed borders, no drop shadows except a
   soft hover lift on product cards.
5. **Every visible word comes from ACF.** Templates only lay out fields.

## Colour tokens (`assets/css/source.css` `:root`)

| Token | Value | Role |
|---|---|---|
| `--primary` | `#6B2BD9` | CTAs, links, active nav, focus ring |
| `--primary-dark` | `#5420B0` | CTA hover |
| `--primary-light` | `#8B5CF6` | Icons on dark surfaces |
| `--primary-soft` | `#F3EEFE` | Tinted panels, chips, image wells |
| `--primary-softer` | `#FAF8FF` | Alternate section background |
| `--ink` | `#22124F` | Headings, dark bands, footer (deep navy-purple) |
| `--foreground` | `#2A2340` | Body text |
| `--muted-foreground` | `#6B6580` | Secondary text |
| `--border` | `#E9E4F3` | Hairlines |
| `--accent` | `#F5B400` | Star ratings only |
| `--success` | `#15803D` | In-stock, ticks |
| `--destructive` | `#DC2626` | Errors, Australia Post mark |

Tailwind utilities map to these (`bg-primary`, `text-ink`, `border-border`, ...).
Never write raw hex in templates.

## Typography

- Everything: **Afacad** 400-700 (Google Fonts), the same family as
  modafinilaustraliadirect.com. Headings 600-700 with near-normal tracking; body 18px
  because Afacad has a small x-height.
- Scale (fluid, defined as component classes):
  - `.kmg-display` hero H1: `clamp(2.75rem, 2rem + 4vw, 5.25rem)` / 1.02
  - `.kmg-h2` section title: `clamp(2.25rem, 1.6rem + 2.6vw, 3.75rem)` / 1.05
  - `.kmg-h3` card title: `1.375rem-1.625rem` / 1.2
  - `.kmg-lead`: `clamp(1.125rem, 1rem + 0.5vw, 1.375rem)` / 1.6
  - `.kmg-eyebrow`: 13px, 700, uppercase, `0.14em`, purple

## Layout

- Container: `.kmg-container` max 1280px, 20px gutter mobile, 32px desktop.
- Section: `.kmg-section` = `py-20 md:py-28 lg:py-32`; `.kmg-section-tint`
  swaps background to `--primary-softer`.
- Section header: centred, eyebrow then H2 then lead, max 760px, `mb-14 md:mb-20`.
- Grid gaps: 24px mobile, 32px desktop.

## Components

- Buttons: `.kmg-btn` (12px radius, 56px tall, 19px semibold; primary buttons carry the
  arrow in a white circle like the reference CTA) + `.kmg-btn-primary`,
  `.kmg-btn-outline`, `.kmg-btn-light` (on dark), `.kmg-btn-sm`.
- Card: `.kmg-card` white, 1px border, `rounded-[28px]`, `p-8 md:p-10`.
- Chip: `.kmg-chip` rounded-full lavender pill for badges and quick links.
- Icon well: `.kmg-icon` 56px rounded-2xl lavender square, purple icon.
- Product card: image on lavender well, name, stars, price, 2-col quantity pills
  (`rounded-lg`), full-width CTA.
- Dark band: `bg-ink text-white`; every heading inside gets explicit `text-white`.

## Motion

Only `transform`/`opacity`/colour transitions, 200ms ease-out. Product card hover
lift `-translate-y-1`. Marquee strip pauses on hover and respects
`prefers-reduced-motion`.

## Logo

Australia outline filled with the Australian flag, then "KAMAGRA" (purple) over "MATE"
(ink) in Afacad Bold uppercase, matching the structure of the Modafinil Australia logo.
Sources in `docs/brand/`: `make_map.py` generates `australia-flag-map.svg`;
`logo-dark.html` / `logo-light.html` are rendered to transparent PNG at 4x and uploaded
to the Media Library, then set in Theme Settings (Site Logo, Footer Logo).

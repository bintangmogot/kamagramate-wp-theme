# ModafinilMY — Complete WordPress Migration Guide

> **Purpose**: This document is the **single source of truth** for any AI or developer
> converting this TanStack Start (React 19) prototype into a production WordPress theme
> using **ACF Pro** and **WooCommerce**. After reading this document, you should be able
> to reproduce the site pixel-for-pixel without needing to read the React source code.

> **Source Stack**: TanStack Start · React 19 · Tailwind CSS v4 (no config file — all tokens in `src/styles.css`)

---

## Table of Contents

1. [Brand Identity](#1-brand-identity)
2. [Colour Palette](#2-colour-palette)
3. [Typography](#3-typography)
4. [Spacing, Layout & Container System](#4-spacing-layout--container-system)
5. [Elevation, Shadows & Radius](#5-elevation-shadows--radius)
6. [Responsive Breakpoints & Device Behaviour](#6-responsive-breakpoints--device-behaviour)
7. [Global Layout Structure](#7-global-layout-structure)
8. [Component Specifications](#8-component-specifications)
9. [Page-by-Page Blueprint](#9-page-by-page-blueprint)
10. [Data Architecture & ACF Mapping](#10-data-architecture--acf-mapping)
11. [WooCommerce Product Setup](#11-woocommerce-product-setup)
12. [Internationalization (i18n) — Bilingual System](#12-internationalization-i18n--bilingual-system)
13. [SEO & Meta Tags](#13-seo--meta-tags)
14. [Icon System](#14-icon-system)
15. [Animation & Interaction Patterns](#15-animation--interaction-patterns)
16. [WordPress Theme Architecture](#16-wordpress-theme-architecture)
17. [Non-Negotiable Design Rules](#17-non-negotiable-design-rules)
18. [Appendix A: Complete CSS Custom Properties](#appendix-a-complete-css-custom-properties)
19. [Appendix B: Product Data Dump](#appendix-b-product-data-dump)
20. [Appendix C: Cities / Location Pages Data Structure](#appendix-c-cities--location-pages-data-structure)
21. [Appendix D: Content Strings (Reviews, FAQs, Blog)](#appendix-d-content-strings)

---

## 1. Brand Identity

| Property | Value |
|---|---|
| **Brand Name** | **ModafinilMY** |
| **Logo Rendering** | Two-tone text: "Modafinil" in `--foreground` (dark slate) + "MY" in `--primary` (emerald green) |
| **Logo Icon** | 36×36px rounded-square (`border-radius: 8px`), `--primary` emerald background, white lightbulb icon (Lucide `Lightbulb`) |
| **Tagline (EN)** | Sharper Focus. Higher Performance. |
| **Tagline (MS)** | Fokus Lebih Tajam. Perform Lebih Tinggi. |
| **Primary Language** | Bahasa Malaysia (MS) — default; English (EN) as secondary |
| **Tone** | Direct, practical, trust-first. Numbers over adjectives. Medical disclaimer always visible. |
| **WhatsApp CTA** | `+60 18-575 4182` → `https://wa.me/60185754182` |
| **Email** | `support@modafinil-malaysia.com` |
| **Currency** | Malaysian Ringgit (RM) — format: `RM1,234.56` |
| **Locale** | `ms-MY` |

### Logo Component Breakdown

```
┌──────────────────────────────────────────────┐
│ [■ 36×36 emerald square] [Modafinil][MY]     │
│  └─ lightbulb icon       └─ dark    └─ green │
│     white, 20×20px        font-heading        │
│     border-radius: 8px    text-xl, extrabold  │
│                            tracking-tight     │
└──────────────────────────────────────────────┘
```

- In **light/default context**: "Modafinil" is `--foreground` (#0f172a), "MY" is `--primary` (#059669)
- In **inverted/dark context** (footer): "Modafinil" is `--ink-foreground` (#ffffff), "MY" is `--primary` (#059669)
- Gap between icon and text: `0.625rem` (10px)

---

## 2. Colour Palette

> **Rule**: NEVER hardcode hex values in templates. All colours must reference CSS custom properties or WordPress `theme.json` palette slugs.

### 2.1 Core Brand Greens

| Token | CSS Variable | oklch | Hex | Usage |
|---|---|---|---|---|
| `primary` | `--primary` | `oklch(0.596 0.145 163.225)` | `#059669` | Trust bar, buttons, logo icon, badges, all green surfaces, ring/focus |
| `primary-dark` | `--primary-dark` | `oklch(0.508 0.118 165.612)` | `#047857` | Button hover states, eyebrow text, "as low as" price text |
| `primary-light` | `--primary-light` | `oklch(0.696 0.17 162.48)` | `#10b981` | Footer link hover, accents on dark backgrounds, star ratings |
| `primary-soft` | `--primary-soft` | `oklch(0.95 0.052 163.051)` | `#d1fae5` | Rarely used; stronger green tint backgrounds |
| `primary-softer` | `--primary-softer` | `oklch(0.979 0.021 166.113)` | `#ecfdf5` | Icon tile backgrounds, eyebrow pill backgrounds, contact WhatsApp card accent |
| `primary-foreground` | `--primary-foreground` | `oklch(1 0 0)` | `#ffffff` | All text on green backgrounds |

### 2.2 Neutrals

| Token | CSS Variable | oklch | Hex | Usage |
|---|---|---|---|---|
| `background` | `--background` | `oklch(1 0 0)` | `#ffffff` | Page base, odd-numbered sections |
| `surface` | `--surface` | `oklch(0.984 0.003 247.858)` | `#f8fafc` | Even-numbered section bands, product image plates, breadcrumb bar |
| `foreground` | `--foreground` | `oklch(0.208 0.042 265.755)` | `#0f172a` | All headings and body text |
| `muted-foreground` | `--muted-foreground` | `oklch(0.554 0.046 257.417)` | `#64748b` | Paragraph text, secondary text, meta text |
| `border` | `--border` | `oklch(0.929 0.013 255.508)` | `#e2e8f0` | All card borders, dividers, input borders |
| `ink` | `--ink` | `oklch(0.208 0.042 265.755)` | `#0f172a` | Footer background, inner-page hero backgrounds |
| `ink-foreground` | `--ink-foreground` | `oklch(1 0 0)` | `#ffffff` | All text on ink/dark backgrounds |

### 2.3 Status / Functional Colours

| Token | CSS Variable | Hex | Usage |
|---|---|---|---|
| `price` | `--price` | `#2563eb` (blue-600) | Product prices ONLY — intentionally not green |
| `destructive` | `--destructive` | `#dc2626` (red-600) | "Out of Stock" badge background |
| `destructive-soft` | `--destructive-soft` | `#fef2f2` (red-50) | Disabled "Out of Stock" button fill |
| `destructive-foreground` | `--destructive-foreground` | `#ffffff` | Text on destructive backgrounds |

### 2.4 Section Background Alternation Pattern

```
Section 1: --background (#ffffff)   ← White
Section 2: --surface    (#f8fafc)   ← Light slate
Section 3: --background (#ffffff)   ← White
Section 4: --surface    (#f8fafc)   ← Light slate
... alternating forever
```

This alternation is consistent across ALL pages.

---

## 3. Typography

### 3.1 Font Families

| Role | Token | Font Stack | Google Fonts URL |
|---|---|---|---|
| **Headings** | `--font-heading` | `"Poppins", ui-sans-serif, system-ui, sans-serif` | `https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap` |
| **Body** | `--font-sans` | `ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif` | System font — no external load needed |
| **Mono** | `--font-mono` | `ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace` | System font |

### 3.2 Global Typography Rules

```css
/* All headings (h1-h6) */
font-family: var(--font-heading);  /* Poppins */
letter-spacing: -0.02em;           /* Tight tracking */

/* Body */
font-family: var(--font-sans);     /* System UI stack */
-webkit-font-smoothing: antialiased;
```

### 3.3 Type Scale

| Element | Mobile Size | Desktop Size | Weight | Line Height | Extra |
|---|---|---|---|---|---|
| **Homepage Hero H1** | `text-4xl` = 36px | `text-[3.5rem]` = 56px | 900 (black) | 1.08 | `--font-heading` |
| **Hero Sub-headline** | `text-2xl` = 24px | `text-3xl` = 30px | 700 (bold) | default | Poppins |
| **Section H2** (`.h-section`) | 28px | 40px | 800 (extrabold) | 1.15 | `clamp(1.75rem, 1.2rem + 2vw, 2.5rem)`, `letter-spacing: -0.025em` |
| **Inner Page H1** | `text-3xl` = 30px | `text-[2.75rem]` = 44px | 800 (extrabold) | 1.1 | `tracking-tight` |
| **Inner Page H1 (Contact)** | `text-4xl` = 36px | `text-5xl` = 48px | 800 (extrabold) | default | `tracking-tight` |
| **Card Title (H3)** | `text-base` = 16px | `text-lg` = 18px | 700 (bold) | default | Poppins |
| **Body Text** | `text-base` = 16px | `text-base` = 16px | 400 (regular) | `leading-relaxed` (1.625) | System UI |
| **Small / Meta** | `text-sm` = 14px | `text-sm` = 14px | 400–600 | default | System UI |
| **Eyebrow** (`.eyebrow`) | 11px | 11px | 700 (bold) | default | `letter-spacing: 0.12em`, `text-transform: uppercase` |
| **Button Label** | `text-sm` = 14px | `text-sm` = 14px | 700 (bold) | default | `tracking-wider`, `text-transform: uppercase` |
| **Trust Bar Text** | `text-xs` = 12px on mobile | `text-sm` = 14px ≥640px | 600 (semibold) | default | `tracking-wide` |

### 3.4 Eyebrow Component (`.eyebrow`)

Used above every section heading as a green pill label:

```css
.eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  border-radius: 9999px;          /* fully rounded pill */
  background-color: #ecfdf5;      /* --primary-softer */
  color: #047857;                 /* --primary-dark */
  font-size: 0.6875rem;           /* 11px */
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 0.375rem 0.875rem;     /* 6px 14px */
}
```

---

## 4. Spacing, Layout & Container System

### 4.1 Container

```css
.container-site {
  width: 100%;
  max-width: 80rem;        /* 1280px */
  margin-inline: auto;     /* centered */
  padding-inline: 1.5rem;  /* 24px gutters */
}
```

### 4.2 Section Vertical Rhythm

```css
.section-y,
.section-padding {
  padding-block: clamp(3.5rem, 2rem + 5vw, 6rem);
  /* 56px on mobile → 96px on desktop */
}
```

### 4.3 Spacing Tokens

| Use Case | Value | Notes |
|---|---|---|
| Card grid gap | `1.25rem–1.5rem` (20–24px) | `gap-5` or `gap-6` |
| Step columns gap | `2rem` (32px) | `gap-8` |
| Section heading margin-bottom | `2.5rem–3rem` (40–48px) | `mb-10` or `mb-12` |
| Eyebrow to heading | `1rem` (16px) | `mb-4` |
| Card internal padding | `1.5rem` (24px) | `p-6` |
| Product card body padding | `1.25rem` (20px) | `p-5` |
| Form field spacing | `1.25rem` (20px) | `gap-5` between fields |
| Button padding (pill CTA) | `px-8 py-3.5` = `32px 14px` | All primary CTAs |
| Button padding (smaller) | `px-7 py-3` = `28px 12px` | Outline buttons |
| Input padding | `px-4 py-3` = `16px 12px` | All text inputs and textareas |

---

## 5. Elevation, Shadows & Radius

### 5.1 Border Radius

| Token | Value | Usage |
|---|---|---|
| `--radius` (base) | `0.75rem` (12px) | Cards, inputs, icon tiles |
| `rounded-full` | `9999px` | All CTA buttons, badges, eyebrow pills, city chips |
| `rounded-xl` | `0.75rem` (12px) | Cards, FAQ items, contact cards |
| `rounded-2xl` | `1rem` (16px) | Product image plates, contact form container, checkout cards |
| `rounded-lg` | `0.5rem` (8px) | Logo icon, hero stat boxes, quantity selector buttons |
| `rounded-md` | `0.375rem` (6px) | Stock badges, nav hover states, hamburger button |

### 5.2 Shadows

| Token | CSS Value | Usage |
|---|---|---|
| `--shadow-card` | `0 1px 2px 0 rgb(15 23 42 / 6%)` | Resting card state |
| `--shadow-card-hover` | `0 12px 28px -12px rgb(15 23 42 / 18%)` | Card on hover |
| `--shadow-pill` | `0 8px 20px -8px rgb(5 150 105 / 55%)` | Green CTA buttons (glow effect) |
| `--shadow-header` | `0 1px 0 0 rgb(226 232 240)` | Header bottom border replacement |

### 5.3 Borders

- **Standard card border**: `1px solid var(--border)` → `#e2e8f0`
- **Card hover border**: changes to `border-primary` → `#059669`
- **Footer section dividers**: `border-t border-white/10` (10% white)
- **Header bottom**: `border-b border-border` (1px solid `#e2e8f0`)

---

## 6. Responsive Breakpoints & Device Behaviour

### 6.1 Breakpoints (Tailwind defaults)

| Name | Min-width | CSS Media Query |
|---|---|---|
| `sm` | 640px | `@media (min-width: 640px)` |
| `md` | 768px | `@media (min-width: 768px)` |
| `lg` | 1024px | `@media (min-width: 1024px)` |
| `xl` | 1280px | `@media (min-width: 1280px)` |

### 6.2 Layout Shifts by Breakpoint

#### Header
| Feature | < 640px (Mobile) | 640–1023px (Tablet) | ≥ 1024px (Desktop) |
|---|---|---|---|
| Navigation | Hamburger → slide-in drawer | Hamburger → slide-in drawer | Inline horizontal nav |
| WhatsApp button | Hidden | Visible pill button | Visible pill button |
| Logo position | Centered (absolutely) | Centered (absolutely) | Left-aligned (static) |
| Cart icon | Always visible | Always visible | Always visible |
| Hamburger | Visible | Visible | Hidden |

#### Trust Bar
| Feature | < 640px | ≥ 640px |
|---|---|---|
| Text content | Short version ("🚚 Percuma Penghantaran RM399+") | Long version ("Penghantaran Percuma RM399+ · 100% Asli") |
| Box icon | Hidden | Visible |

#### Product Grids
| Context | < 640px | 640–767px | 768–1023px | ≥ 1024px |
|---|---|---|---|---|
| Homepage featured (4 items) | 2 columns | 2 columns | 4 columns | 4 columns |
| Shop page (12 items) | 2 columns | 2 columns | 2 columns | 4 columns |
| Related products (4 items) | 1 column | 2 columns | 2 columns | 4 columns |

#### Audience Cards
| < 640px | 640–1023px | ≥ 1024px |
|---|---|---|
| 1 column | 2 columns | 4 columns |

#### Shipping City Cards
| < 640px | 640–1023px | ≥ 1024px |
|---|---|---|
| 1 column | 2 columns | 3 columns |

#### Footer
| < 768px | 768–1023px | ≥ 1024px |
|---|---|---|
| 1 column stacked | 2 columns | 4 columns |

#### Footer Badge Strip
| < 768px | ≥ 768px |
|---|---|
| 2 columns | 4 columns |

#### Contact Page
| < 1024px | ≥ 1024px |
|---|---|
| Stacked (channels → form) | Side-by-side (`1fr 1.2fr` grid) |

#### Product Detail Page
| < 1024px | ≥ 1024px |
|---|---|
| Stacked (image → buy box) | Side-by-side 2-column grid |

#### Checkout Page
| < 768px | ≥ 768px |
|---|---|
| Stacked (form → summary) | Side-by-side 2-column grid |

### 6.3 Mobile Navigation Drawer

- **Width**: `85%` of viewport, max `24rem` (384px)
- **Position**: Fixed, slides in from left
- **Overlay**: `bg-black/40 backdrop-blur-sm`
- **Animation**: `transition-transform duration-300 ease-in-out`
- **Contains**:
  1. Brand name header with close button (68px tall, matching header height)
  2. Full navigation links list
  3. "Buy Modafinil In" section with city links in 2-column grid

---

## 7. Global Layout Structure

```
┌─────────────────────────────────────────────┐
│ TRUST BAR (full-width, bg-primary)          │
├─────────────────────────────────────────────┤
│ HEADER (sticky top-0, 68px, white, border)  │
├─────────────────────────────────────────────┤
│                                             │
│ MAIN CONTENT (flex-1)                       │
│   └─ Page-specific sections                 │
│                                             │
├─────────────────────────────────────────────┤
│ FOOTER                                      │
│   ├─ Badge Strip (bg-primary)               │
│   ├─ 4-Column Links (bg-ink)                │
│   ├─ Disclaimer (border-t white/10)         │
│   └─ Copyright Bar (border-t white/10)      │
└─────────────────────────────────────────────┘
```

- The entire page is `flex min-h-screen flex-col bg-background`
- `<main>` has `flex-1` to push footer to bottom

---

## 8. Component Specifications

### 8.1 Trust Bar

```
┌──────────────────────────────────────────────────────────┐
│ [📦 icon] Penghantaran Percuma RM399+ · 100% Asli  EN|MS│
│                                                          │
│ bg-primary (#059669) · text-primary-foreground (#fff)    │
│ Full width · py-2 · text-sm (≥sm) / text-xs (<sm)       │
│ Language toggle: EN | MS (right side)                    │
└──────────────────────────────────────────────────────────┘
```

**WordPress**: ACF Options Page → Trust Bar Long Text (EN/MS), Trust Bar Short Text (EN/MS)

### 8.2 Header

```
┌──────────────────────────────────────────────────────────┐
│ [☰]    [Logo: ■ ModafinilMY]    [WhatsApp] [🛒]         │
│                                                          │
│ sticky top-0 z-50 · bg-background · h-[68px]            │
│ border-b border-border                                   │
│ Container: max-w-1280px                                  │
└──────────────────────────────────────────────────────────┘
Desktop (≥lg):
┌──────────────────────────────────────────────────────────┐
│ [Logo]  Utama Kedai Tentang Kami Ulasan Blog Hubungi  [WhatsApp][🛒]│
└──────────────────────────────────────────────────────────┘
```

**Nav items**: Home, Shop, About Us, Reviews, Blog, Contact
- Active state: `font-semibold text-primary`
- Hover state: `text-primary bg-[#ecfdf5]`
- Padding: `px-3 py-2`, `text-[15px]`, `rounded-md`

**WhatsApp button**: Green pill, `rounded-full bg-primary px-4 py-2 text-sm font-semibold`, hidden below `sm` breakpoint

**WordPress**: `register_nav_menus()` for primary menu. WhatsApp URL from ACF Options.

### 8.3 Homepage Hero

```
┌──────────────────────────────────────────────────────────┐
│ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░│
│ ░░ Background image with emerald gradient overlay ░░░░░░│
│ ░░                                                   ░░░│
│ ░░ [📍 Penghantaran Pantas]  ← glassmorphic badge   ░░░│
│ ░░                                                   ░░░│
│ ░░ # Modafinil Malaysia                             ░░░│
│ ░░ ## Fokus Lebih Tajam. Perform Lebih Tinggi.      ░░░│
│ ░░                                                   ░░░│
│ ░░ Body text...                                      ░░░│
│ ░░                                                   ░░░│
│ ░░ [Beli Sekarang →]  [Ketahui Lebih]               ░░░│
│ ░░                                                   ░░░│
│ ░░ ┌──────┐ ┌──────┐ ┌──────┐                       ░░░│
│ ░░ │2,000+│ │ 7-14 │ │ RM0  │  ← glassmorphic stats ░░░│
│ ░░ │Pelanggan│ Hari │ │RM399+│                       ░░░│
│ ░░ └──────┘ └──────┘ └──────┘                       ░░░│
│ ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░│
└──────────────────────────────────────────────────────────┘
```

- **Min-height**: 520px
- **Background**: `url('/images/hero-banner.png')` with `bg-cover bg-center`
- **Gradient overlay**: `bg-gradient-to-br from-emerald-600/90 via-emerald-600/85 to-emerald-700/90`
- **Content alignment**: Left-aligned, `max-w-lg`
- **Location badge**: `bg-white/20 backdrop-blur`, pill shape, uppercase, `text-xs font-bold tracking-widest`
- **Hero H1**: Poppins, 36px → 56px, weight 900, white
- **Sub-headline**: Poppins, 24px → 30px, weight 700, `text-white/90`
- **Body**: 18px, `text-white/90`, `leading-relaxed`
- **Primary CTA**: White bg, `text-emerald-700`, pill, `shadow-lg hover:shadow-xl hover:-translate-y-0.5`
- **Secondary CTA**: Outline, `border-2 border-white/40`, pill, `hover:bg-white/10`
- **Stat boxes**: `bg-white/15 backdrop-blur rounded-lg`, value in `text-xl font-black text-white`, label in `text-[11px] uppercase tracking-wider text-white/80`

### 8.4 Steps Section (3-Up "How It Works")

- Section bg: `bg-white`, section-padding
- Grid: `md:grid-cols-3 gap-8`, `max-w-4xl mx-auto`
- Icon tile: `w-16 h-16 rounded-2xl bg-emerald-50 border-2 border-emerald-200`, icon `w-6 h-6 text-emerald-600`
- Step number: `text-emerald-600 font-black text-sm tracking-widest`
- Title: Poppins, `font-bold text-lg text-slate-900`
- Body: `text-slate-500 text-sm leading-relaxed`

### 8.5 Audience Cards (4-Up)

- Section bg: `bg-stone-50` (surface variant)
- Grid: `sm:grid-cols-2 lg:grid-cols-4 gap-5`
- Card: `bg-white border border-stone-200 rounded-xl p-6 hover:border-emerald-300 hover:shadow-md`
- Icon tile: `w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600`
- Title: Poppins `font-bold text-slate-900`
- Body: `text-sm text-slate-500 leading-relaxed`

### 8.6 Product Card

- Container: `rounded-xl border border-border bg-card`, `shadow-card hover:shadow-card-hover`
- Hover border: `hover:border-primary`
- Stock badge: Absolute positioned `left-4 top-4`, `rounded-md px-2.5 py-1`, `text-[10px] font-bold uppercase tracking-wider`
  - In stock: `bg-primary text-primary-foreground`
  - Out of stock: `bg-destructive text-destructive-foreground`
- Image container: `bg-surface p-6 pt-14`
- Image: `h-44 w-full object-contain`, `transition-transform duration-300 group-hover:scale-[1.03]`
- Title: Poppins, `text-base font-bold`
- Price range: `text-lg font-bold text-price` (blue!)
- Per-tab price: `text-sm font-medium text-primary-dark`
- CTA button: `w-full rounded-full bg-primary text-primary-foreground font-bold uppercase tracking-wider shadow-pill hover:bg-primary-dark`
- OOS button: `w-full rounded-full bg-destructive-soft text-destructive font-semibold`

**WooCommerce**: Map to `content-product.php` or product card template override.

### 8.7 Shipping Map / City Cards

- Day tile: `w-12 h-12 rounded-xl bg-emerald-600 text-white font-heading font-black text-sm`
- City name: Poppins `font-bold text-slate-900`, hover: `text-emerald-600`
- Region: `text-xs text-slate-400`
- Arrow: Lucide `ChevronRight`, `w-4 h-4 text-slate-300`, hover: `text-emerald-600`
- More cities chips: `text-xs border border-stone-200 text-slate-500 px-3 py-1.5 rounded-full`, hover: `border-emerald-400 text-emerald-600`

### 8.8 Comparison Table

- Header row: `border-b-2 border-stone-200`
- Modafinil column header: `text-emerald-700 bg-emerald-50 rounded-t-lg`
- Modafinil column data: `bg-emerald-50/50 font-semibold text-emerald-700`
- Other columns: `text-slate-500`
- Row dividers: `divide-y divide-stone-100`

### 8.9 Review Card

- Stars: `text-emerald-400`, filled SVG star icons, `w-4 h-4`
- Title: Poppins `font-bold text-slate-900 text-sm`
- Body: `text-sm text-slate-500 leading-relaxed`
- Avatar: `w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs` — shows first letter of name
- Name: `text-xs font-bold text-slate-900`
- Meta: `text-[11px] text-slate-400`
- Homepage display: Horizontal scroll, snap, `w-[320px]` per card, `gap-5`

### 8.10 FAQ Accordion

- Uses native `<details>` / `<summary>` elements
- Container: `border border-stone-200 rounded-xl`
- Chevron icon: `w-5 h-5 text-emerald-600`, rotates 180° on open (`group-open:rotate-180`)
- Summary: `p-5`, `cursor-pointer`, `list-none` (removes default marker)
- Question: Poppins `font-bold text-slate-900 text-sm`
- Answer: `px-5 pb-5 text-sm text-slate-500 leading-relaxed border-t border-stone-100 pt-4`
- Spacing between items: `space-y-4`

### 8.11 Inner Page Hero (PageHero)

- Background: `bg-ink` (#0f172a)
- Text: `text-ink-foreground` (white)
- Padding: `py-16 md:py-20`
- H1: `font-heading text-3xl md:text-[2.75rem] font-extrabold tracking-tight`
- Subtitle: `text-ink-foreground/75 max-w-2xl text-base leading-relaxed`
- Bullets: `flex flex-wrap gap-x-8 gap-y-2`, each with green check icon `text-primary-light`

Used on: Shop, Reviews, Blog, FAQ, Shipping, Terms, Privacy, Refund Policy

### 8.12 Footer

- **Badge strip**: `bg-primary py-8`, 4 icons in `grid-cols-2 md:grid-cols-4`, icon `h-7 w-7`, label `text-[11px] font-bold tracking-widest uppercase`
- **Main columns**: `bg-ink py-14`, `grid md:grid-cols-2 lg:grid-cols-4 gap-10`
- Column titles: `text-sm font-bold uppercase tracking-widest text-ink-foreground`
- Links: `text-sm text-ink-foreground/70 hover:text-primary-light`
- **Disclaimer**: `border-t border-white/10 py-8`, `text-xs text-ink-foreground/50`, centered, `max-w-[90%]`
- **Copyright**: `border-t border-white/10 py-5`, `text-xs text-ink-foreground/60`
- City column links to `/buy-modafinil/{city-slug}`

---

## 9. Page-by-Page Blueprint

### 9.1 Homepage (`/`)

| Order | Section | Background | Component |
|---|---|---|---|
| 1 | Hero | Emerald gradient overlay on image | `Hero` |
| 2 | Steps ("How It Works") | `bg-white` | `Steps` |
| 3 | Audiences ("Who Uses") | `bg-stone-50` (surface) | `Audiences` |
| 4 | Featured Products (4 items) | `bg-white` | `FeaturedProducts` |
| 5 | Shipping Map | `bg-stone-50` (surface) | `ShippingMap` |
| 6 | Comparison Table | `bg-white` | `Comparison` |
| 7 | Trust Strip (4 badges) | `bg-white border-y` | `TrustStrip` |
| 8 | Reviews (horizontal scroll) | `bg-stone-50` (surface) | `ReviewsSection` |
| 9 | FAQ (top 5) | `bg-white` | `FaqSection` |

**WP Template**: `front-page.php` — each section as a block pattern or ACF Flexible Content layout.

### 9.2 Shop / Products (`/products`)

| Order | Section | Background |
|---|---|---|
| 1 | PageHero (ink) with bullets | `bg-ink` |
| 2 | Product grid (all 12) | `bg-background` |
| 3 | "Choosing the Right Modafinil" guide (3 cards) | `bg-surface` |
| 4 | FAQ (all questions) | `bg-white` |

**WP Template**: `archive-product.php`

### 9.3 Single Product (`/product/:slug`)

| Order | Section | Background |
|---|---|---|
| 1 | Breadcrumb bar | `bg-surface` |
| 2 | Product detail (image + buy box) | `bg-background` |
| 3 | Related products (4) | `bg-surface` |

**Buy Box Contains**:
- Brand name (eyebrow, uppercase, `text-primary`)
- Product name (H1, Poppins `text-3xl md:text-4xl font-extrabold`)
- Summary text
- Price range (`text-2xl text-price` — blue)
- Per-tab price (`text-sm text-primary-dark`)
- Quantity selector: buttons for 10/30/50/100/200/300 tabs
  - Selected: `border-2 border-primary bg-primary-softer text-primary-dark`
  - Unselected: `border border-border bg-card text-muted-foreground hover:border-primary`
- Estimated price box: `rounded-xl border bg-surface p-5`
- Checkout CTA: Full-width green pill, `shadow-pill`
- Trust features list: 3 items with Truck, ShieldCheck, Check icons

**WP Template**: `single-product.php`

### 9.4 Checkout (`/checkout`)

Multi-step flow:
1. **Form step**: Shipping details form + Order summary side-by-side
2. **Loading step**: Spinner + "Generating QRIS..."
3. **QRIS step**: QR code display + total + simulate button
4. **Success step**: Green checkmark + success message

**WP**: WooCommerce checkout with custom template override.

### 9.5 About (`/about`)

| Order | Section |
|---|---|
| 1 | Page header (eyebrow + H1 + subtitle, `bg-white`) |
| 2 | Mission + Why Choose Us (2-col cards) |
| 3 | Products We Sell (2 product info cards) |
| 4 | Delivery Across Malaysia (checklist) |
| 5 | Contact CTA (emerald `bg-emerald-600` card) |

### 9.6 Reviews (`/reviews`)

| Order | Section |
|---|---|
| 1 | PageHero (ink) |
| 2 | Stats bar (optional) |
| 3 | Review cards grid |

### 9.7 Blog (`/blog`)

| Order | Section |
|---|---|
| 1 | PageHero (ink) |
| 2 | Blog post card grid |

### 9.8 Contact (`/contact`)

**Note**: Contact page uses a DIFFERENT hero style — NOT the dark ink PageHero. Uses white background with centered eyebrow + H1 + subtitle.

| Order | Section | Background |
|---|---|---|
| 1 | Custom hero (white, centered) | `bg-background` |
| 2 | Contact info + Form | `bg-background` |

Contact channels (left column):
1. WhatsApp card: `border-primary/20 bg-primary-softer/30`, elevated style
2. Email card: `border-border bg-card shadow-card`
3. Operating hours card: `border-border bg-muted/30`

Form (right column): `rounded-2xl border bg-card p-7 shadow-card`

### 9.9 FAQ (`/faq`)

| Order | Section |
|---|---|
| 1 | PageHero (ink) |
| 2 | Full accordion (all questions, all categories) |

### 9.10 City Landing Pages (`/buy-modafinil/:city-slug`)

**SEO programmatic pages** — one per Malaysian city/town.

| Order | Section | Background |
|---|---|---|
| 1 | Green gradient hero with breadcrumb, city stats, delivery days box | Emerald gradient |
| 2 | 4 feature cards + long-form description | `bg-background` |
| 3 | Product grid (4 items) + "View All" CTA | `bg-surface` |
| 4 | Trust badges strip | `bg-background` |
| 5 | City-specific reviews | `bg-background` |
| 6 | City-specific FAQs | `bg-white` |

**WP**: Custom post type `city` with ACF fields.

### 9.11 Other Pages

| Route | Template | Hero Style |
|---|---|---|
| `/shipping` | `page-shipping.php` | PageHero (ink) |
| `/privacy` | `page-privacy.php` | PageHero (ink) |
| `/terms` | `page-terms.php` | PageHero (ink) |
| `/refund-policy` | `page-refund-policy.php` | PageHero (ink) |
| `/track-order` | `page-track-order.php` | PageHero (ink) |
| `/sitemap` | `page-sitemap.php` | PageHero (ink) |
| `/where-to-buy-modafinil` | `page-where-to-buy.php` | PageHero (ink) |

---

## 10. Data Architecture & ACF Mapping

### 10.1 ACF Options Page (Global Site Settings)

Maps to `src/data/site.ts`:

| ACF Field | Field Type | Source |
|---|---|---|
| `site_name` | Text | `SITE.name` |
| `site_tagline_en` | Text | `SITE.tagline.en` |
| `site_tagline_ms` | Text | `SITE.tagline.ms` |
| `free_shipping_threshold` | Text | `SITE.freeShippingThreshold` |
| `whatsapp_url` | URL | `SITE.whatsapp` |
| `trust_bar_long_en` | Text | `TRUST_BAR.long.en` |
| `trust_bar_long_ms` | Text | `TRUST_BAR.long.ms` |
| `trust_bar_short_en` | Text | `TRUST_BAR.short.en` |
| `trust_bar_short_ms` | Text | `TRUST_BAR.short.ms` |
| `disclaimer_en` | Textarea | `DISCLAIMER.en` |
| `disclaimer_ms` | Textarea | `DISCLAIMER.ms` |
| `trust_badges` | Repeater | `TRUST_BADGES` array |
| `footer_badges` | Repeater | `FOOTER_BADGES` array |

### 10.2 Navigation

| ACF / WP Feature | Source |
|---|---|
| Primary Menu | `NAV` array — register via `register_nav_menus` |
| Footer Quick Links | `FOOTER_LINKS.quick` — ACF Repeater or WP Menu |
| Footer Info Links | `FOOTER_LINKS.info` — ACF Repeater or WP Menu |
| Footer Cities | First 12 from `CITIES_DATA` |

### 10.3 Content Post Types

| Source | WP Post Type | ACF Fields |
|---|---|---|
| `REVIEWS` | CPT `review` | `title_en`, `title_ms`, `body_en`, `body_ms`, `author_name`, `author_meta` |
| `FAQS` | ACF Repeater on Options page, grouped by category | `category_en`, `category_ms`, `question_en`, `question_ms`, `answer_en`, `answer_ms` |
| `BLOG_POSTS` | Native `post` type | `excerpt_en`, `excerpt_ms`, `category` (taxonomy) |
| `CITIES_DATA` | CPT `city` or ACF flexible | See Appendix C |

---

## 11. WooCommerce Product Setup

### 11.1 Product Type

All products are **Variable Products** with a single variation attribute:

- **Attribute**: `pa_quantity` (Quantity / Kuantiti)
- **Values**: `10-tabs`, `30-tabs`, `50-tabs`, `100-tabs`, `200-tabs`, `300-tabs`

### 11.2 Product Fields

| WooCommerce Field | Source Data |
|---|---|
| Product Title | `product.name` (e.g., "Modalert 200mg") |
| Slug | `product.slug` (e.g., `modalert-200mg`) |
| Short Description | `product.summary.en` / `product.summary.ms` |
| Product Image | `product.image` (external URL or import to Media Library) |
| Regular Price (min) | `product.priceFrom` |
| Regular Price (max) | `product.priceTo` |
| Stock Status | `product.inStock` → "In stock" / "Out of stock" |

### 11.3 Custom Meta Fields (ACF on Products)

| Field | Type | Source |
|---|---|---|
| `per_tab_price` | Number | `product.perTab` — lowest per-tablet price |
| `brand` | Text | `product.brand` |
| `summary_en` | Textarea | `product.summary.en` |
| `summary_ms` | Textarea | `product.summary.ms` |

### 11.4 Price Display Logic

```
Price range: RM{priceFrom} - RM{priceTo}   ← displayed in blue (--price)
Per tab:     Serendah RM{perTab}/biji        ← displayed in green (--primary-dark)
```

### 11.5 Variation Price Calculation

The source calculates price dynamically:
```javascript
const unit = perTab + (priceFrom / 10 - perTab) * Math.max(0, 1 - qty / 300);
const total = unit * qty;
```

In WooCommerce, set each variation's price directly:
- 10 tabs = `priceFrom`
- 300 tabs = `perTab × 300`
- Intermediate values follow the curve

### 11.6 Featured Products

Homepage shows 4 specific products by slug:
```
modvigil-200mg, modalert-100mg, modalert-200mg, modafinil-200mg
```

---

## 12. Internationalization (i18n) — Bilingual System

### 12.1 How It Works

The site is **bilingual**: Bahasa Malaysia (MS) as default, English (EN) as secondary.

- **Language toggle** is in the Trust Bar (top-right): `EN | MS`
- Selected language is persisted in `localStorage` as `site-lang`
- `<html lang>` switches between `ms-MY` and `en-MY`
- Every user-facing string has both EN and MS variants

### 12.2 WordPress Implementation Options

**Option A: WPML / Polylang Plugin**
- Register each string pair as a translatable string

**Option B: ACF Bilingual Fields**
- For every text field, create `_en` and `_ms` suffixed fields
- In PHP template, read the current language and select the appropriate field:
```php
function t($en, $ms) {
    $lang = isset($_COOKIE['site-lang']) ? $_COOKIE['site-lang'] : 'ms';
    return $lang === 'en' ? $en : $ms;
}
```

**Option C: JavaScript-based (client-side switching)**
- Store all translations in a JS object
- Toggle on click without page reload (matches current behaviour)
- Use `localStorage` for persistence

### 12.3 Content That Needs Translation

- Trust bar text, navigation labels, all section headings/eyebrows
- Product summaries, review titles/bodies, FAQ Q&A
- Blog post titles/excerpts, city page content
- Button labels, footer text, disclaimer
- Form labels/placeholders, meta descriptions (SEO)

---

## 13. SEO & Meta Tags

### 13.1 Per-Page Meta Tags

| Page | Title | Description |
|---|---|---|
| Homepage | `Modafinil Malaysia \| Beli Modafinil Asli Online — ModafinilMY` | `Beli Modafinil asli di Malaysia...` |
| Products | `Buy Modafinil Online in Malaysia \| Shop All — ModafinilMY` | `Browse genuine Modafinil...` |
| Product Detail | `Buy {name} Online in Malaysia \| ModafinilMY` | `{name} by {brand}. {summary}...` |
| About | `Tentang Kami \| ModafinilMY — Modafinil Malaysia` | `ModafinilMY membekalkan...` |
| Contact | `Hubungi Kami \| ModafinilMY — Sokongan Malaysia` | `Hubungi pasukan sokongan...` |
| City Pages | `Buy Modafinil in {city} \| ModafinilMY` | `Buy Modafinil online in {city}...` |

### 13.2 Open Graph Tags

All pages include: `og:title`, `og:description`, `og:type`, `og:image`, `twitter:card`, `twitter:image`

### 13.3 Structured Data (implement via Yoast/Rank Math)

- `Product` schema on single product pages
- `FAQPage` schema on FAQ page
- `Organization` schema site-wide
- `BreadcrumbList` on product pages

---

## 14. Icon System

### 14.1 Icon Library

Source uses **Lucide React**. For WordPress, use Lucide SVG sprites or inline SVGs.

### 14.2 Icons Used

| Icon | Where Used |
|---|---|
| Lightbulb | Logo mark |
| Menu / X | Mobile nav hamburger / close |
| ShoppingBag | Cart icon in header |
| ChevronRight | Breadcrumbs, city card arrows |
| Truck | Footer badges, trust features |
| ShieldCheck | Footer badges, trust features |
| Lock | Footer badges |
| Mail | Footer badges, contact page |
| MapPin | Footer, city pages |
| Check | Inner page hero bullets, verified buyer |
| ShoppingCart | Checkout CTA |
| QrCode | QRIS checkout step |
| ArrowLeft | Checkout back button |
| Loader2 | Loading spinner (animate-spin) |
| CheckCircle2 | Payment success |
| Star | Ratings (filled) |
| Users / Briefcase | City page features |

### 14.3 Custom SVG Icons

- **WhatsApp icon**: Custom SVG (see `WhatsAppIcon.tsx`)
- **Hero step icons**: Inline SVGs (search, money, truck)
- **Audience icons**: Inline SVGs (graduation cap, briefcase, moon, monitor)

---

## 15. Animation & Interaction Patterns

### 15.1 Transitions

| Element | Duration | Notes |
|---|---|---|
| All links & buttons | 150ms (default) | `transition-colors` |
| Card hover shadow | 150ms | `transition-shadow` |
| Product image hover scale | 300ms | `transition-transform` |
| Mobile nav drawer | 300ms | `ease-in-out` |
| FAQ chevron rotation | 150ms | `transition-transform` |
| Hero CTA hover lift | 150ms | `transition-all` |

### 15.2 Hover Effects

| Element | Hover Behaviour |
|---|---|
| Cards | Border → `border-primary`, shadow deepens |
| Product images | `scale(1.03)` |
| Nav links | Text → `text-primary`, bg → `#ecfdf5` |
| Footer links | Text → `text-primary-light` |
| Green pill CTA | bg → `bg-primary-dark` |
| Hero primary CTA | `-translate-y-0.5` (lift 2px), `shadow-xl` |
| Outline buttons | bg → `bg-primary-light`, text → white |
| City chips | border → `border-emerald-400`, text → `text-emerald-600` |

### 15.3 Scroll Behaviour

- **Reviews carousel**: Horizontal scroll with `snap-x snap-mandatory`, `snap-start`
- **Header**: Sticky `top-0 z-50` (always visible, does NOT auto-hide)
- **Trust bar**: NOT sticky — scrolls with page

---

## 16. WordPress Theme Architecture

### 16.1 Theme File Structure

```
modafinilmy/
├── style.css
├── theme.json
├── functions.php
├── templates/
│   ├── front-page.html
│   ├── archive-product.html
│   ├── single-product.html
│   ├── page-about.html
│   ├── page-contact.html
│   ├── page-reviews.html
│   ├── page-faq.html
│   ├── single-city.html
│   └── 404.html
├── parts/
│   ├── header.html
│   └── footer.html
├── patterns/
│   ├── hero.php
│   ├── steps.php
│   ├── audiences.php
│   ├── featured-products.php
│   ├── shipping-map.php
│   ├── comparison-table.php
│   ├── trust-strip.php
│   ├── reviews-carousel.php
│   ├── faq-accordion.php
│   ├── page-hero-ink.php
│   ├── product-card.php
│   └── review-card.php
├── assets/
│   ├── css/theme.css
│   ├── js/i18n.js
│   ├── fonts/poppins/
│   └── images/
└── woocommerce/
    ├── content-product.php
    ├── single-product.php
    └── checkout/
```

### 16.2 theme.json Colour Palette

```json
{
  "settings": {
    "color": {
      "palette": [
        { "slug": "primary", "color": "#059669", "name": "Primary" },
        { "slug": "primary-dark", "color": "#047857", "name": "Primary Dark" },
        { "slug": "primary-light", "color": "#10b981", "name": "Primary Light" },
        { "slug": "primary-soft", "color": "#d1fae5", "name": "Primary Soft" },
        { "slug": "primary-softer", "color": "#ecfdf5", "name": "Primary Softer" },
        { "slug": "background", "color": "#ffffff", "name": "Background" },
        { "slug": "surface", "color": "#f8fafc", "name": "Surface" },
        { "slug": "foreground", "color": "#0f172a", "name": "Foreground" },
        { "slug": "muted-foreground", "color": "#64748b", "name": "Muted" },
        { "slug": "border", "color": "#e2e8f0", "name": "Border" },
        { "slug": "ink", "color": "#0f172a", "name": "Ink" },
        { "slug": "price", "color": "#2563eb", "name": "Price" },
        { "slug": "destructive", "color": "#dc2626", "name": "Destructive" },
        { "slug": "destructive-soft", "color": "#fef2f2", "name": "Destructive Soft" }
      ]
    },
    "typography": {
      "fontFamilies": [
        {
          "fontFamily": "\"Poppins\", ui-sans-serif, system-ui, sans-serif",
          "slug": "heading",
          "name": "Heading (Poppins)"
        },
        {
          "fontFamily": "ui-sans-serif, system-ui, -apple-system, \"Segoe UI\", sans-serif",
          "slug": "body",
          "name": "Body (System)"
        }
      ]
    }
  }
}
```

### 16.3 Required WordPress Plugins

| Plugin | Purpose |
|---|---|
| **ACF Pro** | Custom fields for site settings, product meta, content management |
| **WooCommerce** | Product catalog, cart, checkout, variable products |
| **WPForms / Contact Form 7** | Contact form |
| **Yoast SEO / Rank Math** | SEO meta tags, OG tags, sitemaps |
| **Polylang / WPML** (optional) | If using server-side i18n instead of client-side JS |

---

## 17. Non-Negotiable Design Rules

1. **Green is the only accent colour.** `--primary` (#059669) is used for ALL accent surfaces. Prices are the single exception — they use blue (`--price`).

2. **Headings are always Poppins**, weight 800, with negative tracking (`letter-spacing: -0.02em`).

3. **Every CTA is a full pill** (`border-radius: 9999px`). No square or rounded-corner buttons anywhere.

4. **Cards**: White background, 1px `--border`, 12px radius, subtle shadow that deepens on hover with border changing to green.

5. **Uppercase + wide tracking** is reserved for: eyebrows, badges, button labels, and trust bar text. Never on body text or headings.

6. **Inner-page heroes are always dark ink** (`--ink` #0f172a). Only the homepage hero uses the emerald gradient.

7. **Medical disclaimer must appear in the footer** on every page. This is a legal requirement.

8. **Section backgrounds alternate** between `--background` (white) and `--surface` (light slate).

9. **Never hardcode hex colours** in templates — always reference CSS variables or `theme.json` palette slugs.

10. **Product prices are always blue** (`--price` #2563eb), never green.

11. **Per-tab prices are always green** (`--primary-dark` #047857).

12. **The WhatsApp button must be present** in both the header AND footer.

13. **Language default is Bahasa Malaysia (MS)**, not English.

---

## Appendix A: Complete CSS Custom Properties

```css
:root {
  --radius: 0.75rem;

  /* Surfaces */
  --background: oklch(1 0 0);                    /* #ffffff */
  --foreground: oklch(0.208 0.042 265.755);       /* #0f172a */
  --surface: oklch(0.984 0.003 247.858);          /* #f8fafc */
  --card: oklch(1 0 0);
  --card-foreground: oklch(0.208 0.042 265.755);

  /* Brand green */
  --primary: oklch(0.596 0.145 163.225);          /* #059669 */
  --primary-foreground: oklch(1 0 0);             /* #ffffff */
  --primary-dark: oklch(0.508 0.118 165.612);     /* #047857 */
  --primary-light: oklch(0.696 0.17 162.48);      /* #10b981 */
  --primary-soft: oklch(0.95 0.052 163.051);      /* #d1fae5 */
  --primary-softer: oklch(0.979 0.021 166.113);   /* #ecfdf5 */

  /* Dark ink */
  --ink: oklch(0.208 0.042 265.755);              /* #0f172a */
  --ink-foreground: oklch(1 0 0);                 /* #ffffff */

  /* Muted */
  --muted-foreground: oklch(0.554 0.046 257.417); /* #64748b */

  /* Functional */
  --destructive: oklch(0.577 0.245 27.325);       /* #dc2626 */
  --destructive-foreground: oklch(1 0 0);
  --destructive-soft: oklch(0.971 0.013 17.38);   /* #fef2f2 */
  --price: oklch(0.546 0.245 262.881);            /* #2563eb */

  /* Borders & inputs */
  --border: oklch(0.929 0.013 255.508);           /* #e2e8f0 */
  --input: oklch(0.929 0.013 255.508);
  --ring: oklch(0.596 0.145 163.225);

  /* Shadows */
  --shadow-card: 0 1px 2px 0 rgb(15 23 42 / 6%);
  --shadow-card-hover: 0 12px 28px -12px rgb(15 23 42 / 18%);
  --shadow-pill: 0 8px 20px -8px rgb(5 150 105 / 55%);
  --shadow-header: 0 1px 0 0 rgb(226 232 240);

  /* Typography */
  --font-heading: "Poppins", ui-sans-serif, system-ui, sans-serif;
  --font-sans: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
  --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
}
```

---

## Appendix B: Product Data Dump

All 12 products with complete data:

| # | Slug | Name | Brand | Price From | Price To | Per Tab | In Stock |
|---|---|---|---|---|---|---|---|
| 1 | `modvigil-200mg` | Modvigil 200mg | HAB Pharma | RM210.16 | RM2,064.68 | RM4.13 | Yes |
| 2 | `modafresh-200mg` | Modafresh 200mg | Sunrise Remedies | RM218.68 | RM1,658.56 | RM3.32 | Yes |
| 3 | `modalert-100mg` | Modalert 100mg | Sun Pharma | RM278.32 | RM1,533.60 | RM3.07 | Yes |
| 4 | `smartfinil-200mg` | Smartfinil 200mg | Acteza | RM170.40 | RM1,050.80 | RM2.10 | No |
| 5 | `modasmart-400mg` | Modasmart 400mg | Healing Pharma | RM403.28 | RM2,260.64 | RM4.52 | Yes |
| 6 | `armod-50mg-armodafinil` | Armod 50mg (Armodafinil) | Intas | RM193.12 | RM994.00 | RM2.07 | Yes |
| 7 | `vilafinil-200mg` | Vilafinil 200mg | Centurion Labs | RM184.60 | RM681.60 | RM2.27 | Yes |
| 8 | `waklert-150mg` | Waklert 150mg | Sun Pharma | RM434.52 | RM1,928.36 | RM4.82 | Yes |
| 9 | `artvigil-50mg` | Artvigil 50mg | HAB Pharma | RM170.40 | RM624.80 | RM2.08 | Yes |
| 10 | `modalert-200mg` | Modalert 200mg | Sun Pharma | RM335.12 | RM2,834.32 | RM5.67 | Yes |
| 11 | `modawake-200mg` | Modawake 200mg | HAB Pharma | RM218.68 | RM1,834.64 | RM3.67 | Yes |
| 12 | `modafinil-200mg` | Modafinil 200mg | Generic | RM230.04 | RM1,633.00 | RM3.27 | Yes |

**Product image base URL**: `https://modafinil-malaysia.com/images/products/{uuid}`

**Homepage featured product slugs** (in order): `modvigil-200mg`, `modalert-100mg`, `modalert-200mg`, `modafinil-200mg`

---

## Appendix C: Cities / Location Pages Data Structure

Each city has the following data structure:

```typescript
interface CityData {
  slug: string;            // URL slug (e.g., "kuala-lumpur")
  name: string;            // Display name (e.g., "Kuala Lumpur")
  region: string;          // Region (e.g., "Federal Territory")
  population: string;      // Population string (e.g., "1.8M Penduduk")
  days: string;            // Delivery days (e.g., "7-12")
  demographic: { en, ms }; // Target demographic text
  industry: { en, ms };    // Key industry/professional text
  description: { en, ms }; // Long SEO description
  heroDescription: { en, ms }; // Shorter hero subtitle
  features: [              // 4 feature cards
    { en, ms },            // Delivery speed
    { en, ms },            // Popular demographic
    { en, ms },            // Trusted by
    { en, ms },            // Safety/quality
  ];
  reviews: CityReview[];   // City-specific reviews
}
```

Cities in the system: Kuala Lumpur, Petaling Jaya, Johor Bahru, George Town, Shah Alam, Subang Jaya, Ipoh, Kota Kinabalu, Kuching, Seremban, Melaka, Alor Setar, Kuantan, Kota Bharu, Klang, and more.

**WordPress ACF setup**: Create CPT `city` with ACF field group matching the interface. Each bilingual field pair gets `_en` and `_ms` suffixed fields. Reviews as a repeater sub-field.

---

## Appendix D: Content Strings

### D.1 Reviews (6 total)

All reviews are in `src/data/content.ts` — each has `title`, `body`, `name`, and `meta` with EN/MS variants.

### D.2 FAQ Categories & Items

| Category | # Items |
|---|---|
| Legal Status (Status Undang-Undang) | 3 |
| Delivery (Penghantaran) | 5 |
| Payment (Pembayaran) | 3 |
| Products (Produk) | 4 |
| **Total** | **15** |

### D.3 Blog Posts (11 total)

All blog posts are stub entries with `slug`, `title`, `excerpt`, `date`, and `category`. Full article content needs to be created for the WordPress port.

| Category | Count |
|---|---|
| Use Cases (Penggunaan) | 4 |
| Guides (Panduan) | 4 |
| Comparisons (Perbandingan) | 2 |
| Dosage (Dos) | 1 |

### D.4 Comparison Table Data

| Feature | Modafinil | Coffee | Energy Drink |
|---|---|---|---|
| Effect Duration | 10-15 hours | 2-4 hours | 1-3 hours |
| Crash Effect | None | Moderate | Severe |
| Focus Quality | Deep, sustained | Mild boost | Jittery |
| Addictiveness | Very low | Moderate | Moderate |
| Sleep disruption | Low (morning dose) | Moderate | High |
| Cost per productive day | ~RM8-15 | ~RM10-25 | ~RM12-20 |

---

## Appendix E: Form Specifications

### Contact Form Fields

| Field | Type | Required | Placeholder |
|---|---|---|---|
| Full Name | text | Yes | Ahmad bin Ismail |
| Email | email | Yes | nama@email.com |
| Order No. | text | No | MY-10234 |
| Message | textarea (5 rows) | Yes | How can we help? |

### Checkout Form Fields

| Field | Type | Required |
|---|---|---|
| Full Name | text | Yes |
| Phone Number | tel | Yes |
| Full Address | textarea (3 rows) | Yes |
| City | text | Yes |
| Postcode | text | Yes |

### Input Styling

```css
input, textarea {
  width: 100%;
  border-radius: 0.5rem;           /* rounded-lg */
  border: 1px solid var(--input);  /* #e2e8f0 */
  background: var(--background);   /* white */
  padding: 0.75rem 1rem;           /* py-3 px-4 */
  font-size: 0.875rem;             /* text-sm */
  outline: none;
  transition: border-color, box-shadow;
}

input:focus, textarea:focus {
  border-color: var(--primary);    /* #059669 */
  box-shadow: 0 0 0 3px rgb(5 150 105 / 30%);
}
```

---

*End of documentation. This file, combined with the source `src/styles.css`, provides everything needed to reproduce the ModafinilMY site in WordPress + ACF Pro + WooCommerce.*

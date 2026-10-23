# Waklert Australia Theme — Developer Notes

> Last updated: 2026-10-21

---

## 1. Phone Number in Header (ACF-controlled, Zero Hardcoding)

The header phone button is 100% admin-editable via **WP Admin → Theme Settings → Header & Top Bar**:
- **Header & Contact Phone Number** (`whatsapp_number`)
- **Header Phone Button Text** (`whatsapp_button_text`)
- **Header Phone Button Link / URL** (`whatsapp_button_url`)

- **Zero Hardcoding:** No hardcoded phone number fallback in `header.php`. If cleared in WP Admin, the phone button hides cleanly.
- **Styling:** Solid brand blue (`bg-[#0c4161]`) pill button with white phone icon and white text.
- **Locations:** Appears in:
  - Trust bar (small-screen pill, `md:hidden`)
  - Desktop header right actions (hidden on `<sm`)
  - Mobile icon button (visible on `<sm`)
  - Mobile nav drawer call-to-action

### To update:
1. Go to **WP Admin → Theme Settings (ACF Options)**  
2. Find **Header & Contact Phone Number**  
3. Enter the number (e.g. `0488 841 833` or `+61 488 841 833` — either format works)  
4. Save

---

## 2. About Us Page — White Heading Text on Dark Card

The About Us page uses a rich text module (`modules/content-rich_text.php`) that detects if it's on the About page and applies `prose prose-invert` Tailwind classes.

- **File:** `modules/content-rich_text.php`
- **Detection:** `is_page('about-us')` flag applied to the panel wrapper
- **CSS:** `prose-invert` ensures all `h1–h6`, `p`, `strong`, `b`, `span` inside the dark navy card (`bg-[#0c4161]`) render in white
- **Custom classes added in `assets/css/source.css`:**
  ```css
  .prose-invert h1, .prose-invert h2, ... { color: #ffffff; }
  .prose-invert strong, .prose-invert b, .prose-invert span { color: inherit; }
  ```

### If headings go dark again:
Run `npm run build` after any `source.css` change to regenerate `main.css`.

---

## 3. Product Page — Price Display (No "From ", No "/tablet")

**File:** `woocommerce/single-product.php`

### What changed:
| Before | After |
|--------|-------|
| `From $XX.XX` | `$XX.XX` (exact variation price) |
| `$X.XX /tablet` unit badge | Removed entirely |

### How it works:
- On page load, the **cheapest available in-stock variation** price is shown directly (no "From " prefix)
- Each variation button has a `data-display-price-html` attribute with the pre-rendered WooCommerce price HTML
- JS listens for variation button clicks and swaps the main price span (`[data-testid="text-product-price"]`) with the selected variation's price HTML
- The `$unit_price_lead` PHP variable (`'From '`) is computed but **never echoed** to the page — it's vestigial and safe to remove in a future cleanup

### JS snippet location:
Look for `variation-row-btn` event listeners near the bottom of `single-product.php`.

---

## 4. ACF JSON Group Reference

| Group | File | Key Fields |
|-------|------|-----------|
| Theme Settings | `acf-json/group_theme_settings.json` | `header_phone_number`, `whatsapp_number`, `topbar_left`, `topbar_center`, `free_shipping_threshold` |
| Product Presentation | `acf-json/group_product_presentation.json` | `product_badge`, `product_specs_*`, `product_trust_*` |

---

## 5. CSS Build Process

```bash
npm run build
```

This runs Tailwind CSS CLI to compile `assets/css/source.css` → `assets/css/main.css`.

> **Always run this after any Tailwind class additions or `source.css` changes before pushing.**

---

## 6. Git Workflow (Per `.agents/persona.md`)

```
gh issue create → git checkout -b feat/... → code → npm run build → php -l → git commit → git push → gh pr create → gh pr merge --squash --delete-branch → git checkout main → git pull
```

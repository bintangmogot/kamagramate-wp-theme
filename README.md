# Kamagramate WordPress theme

Classic PHP theme for kamagramate (Australian online pharmacy) built on the
Steven Waldberg engine: ACF Pro Flexible Content, WooCommerce and Tailwind CSS 4.
Design system: [docs/DESIGN.md](docs/DESIGN.md). Project rules: the master SOP in
`Wordpress Needs/STEVEN-WALDBERG-WP-PROJECT-SOP.md`.

## Rules that apply to every change

- No copy, prices, phone numbers, emails or images in PHP. Everything visible comes
  from ACF (page modules or Theme Settings) or WooCommerce/menu settings. A module
  with empty fields prints nothing.
- No emojis, no long dashes, no plugin edits (use hooks and template overrides).
- Use the `kmg-*` component classes and the colour tokens; no raw hex in templates.

## Development

```bash
npm install
npm run build   # assets/css/source.css -> assets/css/main.css (commit main.css)
npm run watch
```

Local site: Laragon, `http://kamagramate.test`, theme folder `wp-content/themes/kamagramate-wp`.

## Where content lives

| What | Where in WP Admin |
|---|---|
| Header, top bar, search, mobile help card, footer, socials, Trustpilot, disclaimer | Theme Settings |
| Floating WhatsApp/Telegram buttons, coupon toast, GA ID, interface labels | Theme Settings (Floating Contact & Promo, Tracking, Interface Labels tabs) |
| Product card and product page labels (incl. `Choose Quantity (prices in AUD)`) | Theme Settings (Product Cards, Product Page tabs) |
| Page sections | Pages -> Edit -> Modules (ACF Flexible Content) |
| Menus (primary, mobile, header category row, 4 footer columns) | Appearance -> Menus |
| Bank details shown on the Thank You page | WooCommerce -> Payments -> Direct bank transfer |
| Side cart colours | Side Cart -> Styling |

## Page modules

Existing: hero_section, featured_products, how_to_order, why_choose_us, city_section,
comparison, reviews_carousel, faqs, trust_strip, blog_section/blog_grid, support_cta,
page_hero, products_grid, product_guide, seo_text, reviews, track_order, faq_page,
contact, about_intro/mission/products/delivery, rich_text, cta, legal_policy,
privacy_policy, refund_policy, terms_of_service, terms_contact, sitemap, dosage_guide,
product_faqs, product_summary, product_description, delivery_cities, city_delivery.

New in the redesign: marquee_strip (scrolling trust ticker), category_grid (shop by
category), condition_links (quick guide links), guarantees (commitments + fact-check
links), audience_grid ("used by"), popular_searches.

## Git and deployment

- `dev`: active work. `main`: production, merged from `dev` only after client approval.
- Before every push run the SOP checks (php -l, long dashes/mojibake, emojis, `A$`).
- cPanel: Git Version Control -> Create -> clone
  `https://github.com/bintangmogot/kamagramate-wp-theme.git`, branch `main`, path
  `public_html/wp-content/themes/kamagramate-wp`. Updates: Manage -> Update from Remote.
  The built `assets/css/main.css` is committed, so no build step runs on the server.

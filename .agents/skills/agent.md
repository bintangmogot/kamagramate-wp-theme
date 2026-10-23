# WordPress Project Workflow & Standards

## 1. Project Overview
- **Project Type:** Custom WordPress Theme / Website.
- **Goal:** Build a highly customized, scalable, and SEO-optimized website based on this proven template structure, but tailored to a new brand.
- **Development Philosophy:** Use clean, modular PHP, native WordPress hooks, and modern frontend practices.

## 2. Directory Structure & Architecture
- **Root Directory:** This is a WordPress theme directory. All code here relates to the theme.
- **`assets/`**: Contains compiled CSS, JS, images, and fonts.
- **`inc/`**: Contains core PHP logic (Custom Post Types, taxonomies, ACF integrations, API endpoints).
- **`template-parts/`**: Reusable UI components and sections.
- **`woocommerce/`** (if applicable): WooCommerce template overrides.
- **`acf-json/`**: Local JSON sync for Advanced Custom Fields.

## 3. Brand & Design System Implementation
Before making UI changes, Antigravity must:
1. Review any provided `brand.md` or design assets in the `.agents/` folder or root.
2. Ensure the color palette and typography match the new brand.
3. Use CSS Custom Properties (`:root`) in the stylesheets to manage brand colors globally.

## 4. Strict Development Rules for AI (Antigravity)
- **NO HARDCODING:** Never hardcode brand-specific colors, fonts, or logos. Always use CSS variables or ACF fields.
- **BEST PRACTICES:** Write clean, well-commented PHP and JavaScript using standard WordPress functions (e.g., `wp_enqueue_script`, `get_template_directory_uri`).
- **SECURITY:** Always sanitize user inputs (`sanitize_text_field`, etc.) and escape outputs (`esc_html`, `esc_attr`).
- **ASSETS:** Ensure any new CSS/JS is properly enqueued in `functions.php`.
- **RESPONSIVENESS:** Ensure all HTML/CSS generated is mobile-responsive by default.

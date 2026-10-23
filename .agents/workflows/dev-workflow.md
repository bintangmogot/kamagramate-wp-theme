# Development Workflow for New WordPress Sites

This workflow outlines the standard process for taking this project template and adapting it to a new brand/website.

## Phase 1: Environment Setup
1. **Clone the Template:** Duplicate this theme directory to the new WordPress environment's `wp-content/themes/` folder.
2. **Rename the Theme:** Update `style.css` with the new Theme Name, Author, and Description.
3. **Dependencies:** Run `npm install` and `composer install` (if applicable) to install dependencies.
4. **ACF Sync:** Ensure the `acf-json` folder is readable and sync the Custom Fields in the WordPress admin panel.

## Phase 2: Branding & Styling Updates
1. **Update Global Variables:** Modify the root CSS variables to match the new brand's color palette and typography.
2. **Update Assets:** Replace `screenshot.jpg` and any logo files in the `assets/` folder.
3. **Header/Footer:** Adapt the `header.php` and `footer.php` templates to reflect the new brand's layout requirements.

## Phase 3: Content & Functionality Adjustments
1. **Custom Post Types:** Review `inc/` for any existing CPTs (like `single-city.php`, reviews, etc.). Rename or adjust them if the new site requires different entities.
2. **Template Parts:** Modify files in `template-parts/` to adjust the UI of specific components (e.g., hero sections, testimonials, product cards).
3. **WooCommerce:** If the new site requires e-commerce, ensure the `woocommerce/` folder templates are styled to match the new brand.

## Phase 4: SEO, Performance & QA
1. **SEO Check:** Ensure semantic HTML5 tags are used. Verify that meta tags are being injected properly (or handled by an SEO plugin).
2. **Performance:** Run the build step for assets (if using Vite/Webpack/Gulp) to minify CSS and JS.
3. **Mobile QA:** Test the responsive layout on mobile, tablet, and desktop breakpoints.
4. **Security Check:** Ensure no debug code is left active and all inputs/outputs are sanitized.

## GitHub Issues Requirement
*MANDATORY:* Every feature, bugfix, or change must have a GitHub Issue created first. Work is organized by Milestones and executed sequentially. Follow the `git-workflow.md` if available.

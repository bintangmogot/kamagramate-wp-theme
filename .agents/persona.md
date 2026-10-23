# User Persona: Steve

## Profile Overview
- **Name / Identity:** Steve (Primary Developer & Project Lead)
- **Role:** Directs frontend architecture, brand identity, and custom WordPress development across e-commerce and local pharmacy web properties (including **Waklert Australia**).
- **Communication Style:** Fast, informal, highly direct, actionable ("bro", "broh", "push now", "make this fit to content").
- **Core Expectation:** Instant grasp of instructions, zero fluff, immediate visual and functional execution, proactive verification, and absolute adherence to his standards.

---

## Steve's Non-Negotiable Directives (The Golden Rules)

### 1. ABSOLUTELY ZERO HARDCODING ("Remember in your life to not make it hardcoded!")
- **Direct quote:** *"bro remember in your life to not make it hardcoded!!!!! so i can still edit the content! fill it up from the wp admin"*
- **Rule:** Every single heading, badge, paragraph, button text, URL, list item, step, or pillar displayed on any page **MUST** be editable by Steve inside WordPress Admin (ACF Flexible Content or Theme Settings).
- **Template Standard:** Template files (`modules/content-*.php`, `page-*.php`) must solely consume `get_sub_field()` / `get_field()` with clean fallbacks.
- **Data Seeding:** Whenever adding new fields or layouts, never leave the admin fields empty. Automatically seed the initial content into the WordPress database (`postmeta`) with correct ACF field keys (`_modules_0_*`) so Steve opens WP Admin and sees every field populated and ready to tweak.

### 2. COMPACT VERTICAL SPACING & TIGHT PADDING
- **Direct quotes:**
  - *"bro delete the divider please, i don't want each point has too big gap"*
  - *"bro i said delete the divider that is cutting between the sections"*
  - *"bro padding y still too big for all the sections"*
- **Rule:** Steve strongly dislikes excessive vertical padding, loose gaps, and bulky horizontal dividers that separate related content.
- **CSS Standard:**
  - Keep `py-*` tight (prefer `py-4` to `py-8` / `py-10`; maximum `py-8` for standard module containers, never `py-16` or `py-20`).
  - Eliminate redundant dividers (`<hr>` or border separators) between cards or section points.
  - Keep related content connected and compact.

### 3. STRICT BRAND COLOR PALETTE & NO RANDOM COLORS
- **Direct quote:** *"fix the color of track my order please, because there is still like green, etc"*
- **Rule:** Never introduce unapproved colors (like random lime greens, teals, or unstyled default framework colors).
- **Brand Palette:**
  - Primary Navy: `#0c4161` (Headers, strong titles, deep accents)
  - Secondary/Brand Blue: `#0284c7` / `#0369a1` (Interactive highlights, borders)
  - Australian Amber/Gold: `#f59e0b` / `#d97706` (Primary CTA buttons, badges, rating stars)
  - Light Sky Background: `#f0f7fc` / `#e0f2fe` (Subtle tinted surfaces, cards)
  - Australia Post Red: `#dc2626` / `#b91c1c` (Tracking badges & AusPost partner highlights)
  - Text Neutral: `#1e293b` (Body text), `#64748b` (Secondary muted text)
  - WhatsApp Green: `#25d366` (Strictly reserved for WhatsApp floating action / buttons)

### 4. VISUAL & UI FIDELITY
- **Direct quotes:**
  - *"change here icon to the real icon with the colorful icon"*
  - *"in footer, make this fit to content"*
  - *"broh you must place the button add to cart to the right side not center! Just add some mor padding right"*
- **Rule:**
  - Use real, recognizable, colorful SVG icons (Australia Post, card networks, security badges), not generic monochrome placeholders.
  - Content containers must use `fit-content` or compact sizing when appropriate instead of stretching edge-to-edge.
  - In mobile sticky bars, keep CTAs right-aligned or offset with proper right padding to prevent collision with floating side carts or chat bubbles.

### 5. GIT & GITHUB WORKFLOW
- **Direct quote:** *"push now"*
- **Rule:**
  - Always create a GitHub Issue first (`gh issue create`).
  - Checkout a feature/style branch (`feat/...`, `style/...`, `docs/...`).
  - If Tailwind/CSS changes are made, run `npm run build` before pushing.
  - Commit with conventional commits (`feat: ...`, `style: ...`, `fix: ...`).
  - Push branch, create Pull Request with `gh pr create`.
  - Merge PR with `gh pr merge --squash --delete-branch`.
  - Switch back to `main` and pull.

---

## Preferred Agent Interaction Style
- **Tone:** Direct, confident, respectful, concise. Address him naturally as a sharp technical collaborator.
- **Speed & Action:** Immediately begin executing the requested fix or feature.
- **Verification:** Always verify UI and database states (using Puppeteer screenshots and CLI checks) before declaring completion.

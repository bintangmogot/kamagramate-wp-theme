# Australia Delivery

Create city pages in **Australia Delivery → Add New City**. Every city uses the same layout automatically. Existing city URLs are preserved.

For an ordinary WordPress page, select the **Australia Delivery** page template. The **Australia Delivery** flexible-content module also uses this layout.

Enter the city name, region, introduction, local description and feature list. Optional fields include population, delivery-day range, carrier, shipping summary, three reasons, selected products, FAQs, reviews and final call to action. Only enter verified population figures, reviews and delivery terms. Blank shipping estimates ask customers to confirm their postcode; no competitor delivery promises are copied.

The Rockingham example has its displayed copy saved in ACF, including section headings, button labels, delivery summary, features, reasons, trust strip, product selection and FAQs. Edit these in the WordPress form. The local population and industry notes use the ABS 2021 Census for the Rockingham suburb and link to their source. Delivery Days / Estimate can contain a sentence asking customers to confirm their postcode while a confirmed local ETA is unavailable. Add reviews only when genuine, approved feedback exists; never write sample customer quotes.

Product cards use the current theme's WooCommerce component, including current prices, stock and product links. Select up to four products or leave blank for the first catalog products. Hidden products are excluded.

The field definitions are registered from local ACF JSON automatically. No manual field-group import is required. All styling uses the current theme's semantic Tailwind tokens. Rebuild with `npm run build` after layout changes.

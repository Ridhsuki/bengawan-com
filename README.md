# Bengawan Computer — Laptop Catalog & Store Operations System

A Laravel and Filament-based web application for a laptop and computer accessories retailer in Solo Raya, Indonesia. The application combines a public product catalog with an internal store back-office and a two-way integration with the Shopee Open Platform.

---

## Overview

The public website lets customers browse, filter, and search the product catalog. Purchases are not completed on the site — customers are directed to WhatsApp or an external Shopee listing. There is no shopping cart, internal checkout, or payment gateway.

The Filament administration panel handles product and category management, stock control, internal sales recording, reporting, and the full Shopee marketplace integration.

---

## Demo

<p align="center">
    <img
      src="https://onlinekan.netlify.app/archive/z-save-image-gh/bengawan-komputer-assets/bengawan-computer-demo.gif"
      alt="Bengawan Computer application walkthrough"
      width="889"
    >
</p>

<p align="center">
  <em>A short walkthrough of the public product catalog, Filament administration panel, and Shopee integration.</em>
</p>

### Application Preview

<p align="center">
  <img
    src="https://onlinekan.netlify.app/archive/z-save-image-gh/bengawan-komputer-assets/product-catalog.png"
    alt="Public laptop product catalog"
    width="48%"
  >
  <img
    src="https://onlinekan.netlify.app/archive/z-save-image-gh/bengawan-komputer-assets/product-detail.png"
    alt="Laptop product detail page"
    width="48%"
  >
</p>

<p align="center">
  <img
    src="https://onlinekan.netlify.app/archive/z-save-image-gh/bengawan-komputer-assets/admin-dashboard.png"
    alt="Filament administration dashboard"
    width="48%"
  >
  <img
    src="https://onlinekan.netlify.app/archive/z-save-image-gh/bengawan-komputer-assets/shopee-integration.png"
    alt="Shopee marketplace integration page"
    width="48%"
  >
</p>

---
## Key Features

**Public catalog**
- Paginated product listing with category, price-range, and keyword filters.
- Live search suggestions via a JSON endpoint (name and full-text match).
- Product detail pages with image galleries and per-page SEO metadata.
- Dedicated discount/promotions page for products with an active sale price.
- WhatsApp enquiry link per product, pre-filled with product name, price, and URL.
- Optional external Shopee listing link per product.
- Visitor feedback form with per-IP rate limiting.

**Administration (Filament)**
- Product and category management, including soft-delete and restore.
- Per-product image gallery management.
- Stock management with automatic deduction on sale or incoming Shopee order.
- Internal sales recording: product, quantity, cost price, negotiated price, and customer notes.
- Profit calculation stored per sale; date-range filtering on the sales report.
- PDF invoice generation (DomPDF) downloadable per sale.
- Feedback review and status management.
- Website settings: company name, phone number, social media links, and homepage banners.
- Dashboard with product and sales statistics and profit charts.
- Shopee-specific pages: connection management, live Shopee item browser, and Shopee sales report.

---

## Application Flow

1. Customers browse, filter, and search the public product catalog.
2. Purchases continue through WhatsApp enquiry or the linked Shopee listing.
3. Administrators manage products, categories, stock, and settings in the Filament panel.
4. Recording a local sale deducts stock and optionally queues a stock update to Shopee.
5. Shopee orders and stock changes are processed through queued jobs dispatched by webhooks and scheduled commands.

---

## Shopee Integration

The application integrates with the **Shopee Open Platform v2 API**.

**Shop connection.** An administrator initiates an OAuth flow from the admin panel. After the shop owner authorises access on Shopee, the application receives and stores an access token and refresh token. Tokens are stored encrypted and refreshed automatically before expiry.

**Product mapping and publishing.** Each local product can be mapped to an existing Shopee listing by selecting it from a live API-backed browser. Alternatively, an administrator can fill in Shopee-specific fields (category, brand, condition, dimensions, logistics) and publish the product directly to Shopee from the admin panel. The product image is uploaded to Shopee's media space as part of the publish flow.

**Stock synchronisation.** When a local sale is recorded or a product's stock is edited, a queued job pushes the updated stock figure to Shopee. Each push is logged with its status and any error message.

**Order ingestion.** Orders arrive in two ways: Shopee sends real-time push notifications to `POST /shopee/webhook`, and a scheduled command polls for recently updated orders every 10 minutes. Both paths dispatch the same queued job, which imports the order, creates a local sale record, deducts stock for active orders, and restores stock when an order is cancelled.

**Scheduled maintenance.** The Laravel Scheduler refreshes access tokens every three hours and runs a reconciliation check hourly to detect Shopee listings that have been deleted or de-listed, disabling stock sync for affected products.

---

## Technology Stack

| Component | Technology |
|---|---|
| Framework | Laravel 12, PHP 8.2+ |
| Admin panel | Filament 4 |
| PDF generation | barryvdh/laravel-dompdf |
| Frontend build | Vite 7 + Laravel plugin |
| CSS | Tailwind CSS 4 |
| Database | MySQL |
| Queue / Cache / Session | Database driver (default) |
| Shopee | Open Platform API v2 |

---

## Installation

```bash
git clone https://github.com/Ridhsuki/bengawan-com.git
cd bengawan-com

composer install
cp .env.example .env
php artisan key:generate

# Configure DB_DATABASE, DB_USERNAME, and DB_PASSWORD in .env, then:
php artisan migrate --seed
php artisan storage:link

npm install
npm run build
```

The seeder creates one administrator account and seeds application settings. `ProductSeeder` and `SaleSeeder` are included but commented out; uncomment them in `DatabaseSeeder` to load sample data.

---

## Environment Configuration

The `.env.example` file documents all available variables. The values that require attention beyond the standard Laravel database configuration are:

```env
APP_NAME="Bengawan Komputer"
APP_URL=http://localhost
APP_TIMEZONE=Asia/Jakarta
```

**Shopee Open Platform** — obtain these from the Shopee Partner Portal:

```env
# Use the sandbox values below for local development.
# Switch to production hosts when deploying.
SHOPEE_HOST=https://partner.test-stable.shopeemobile.com
SHOPEE_AUTH_HOST=https://openplatform.sandbox.test-stable.shopee.sg
SHOPEE_API_HOST=https://openplatform.sandbox.test-stable.shopee.sg

SHOPEE_PARTNER_ID=your_partner_id
SHOPEE_PARTNER_KEY=your_partner_key

# Must be a publicly reachable URL registered in the Partner Portal.
SHOPEE_REDIRECT_URL="${APP_URL}/shopee/callback"

# Set to true in production to validate webhook HMAC-SHA256 signatures.
SHOPEE_WEBHOOK_VERIFY=false
```

Register `{APP_URL}/shopee/callback` as the OAuth redirect URL and `{APP_URL}/shopee/webhook` as the push endpoint in the Shopee Partner Portal.

---

## Running the Application

```bash
# Development server
php artisan serve

# Frontend assets (watch mode)
npm run dev

# Queue worker
php artisan queue:work

# Scheduler (development)
php artisan schedule:work
```

For production, add a single scheduler cron entry:

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

The queue worker and scheduler are both required for Shopee order import, stock synchronisation, product publishing, and token refresh to function.

A `composer dev` script is available for local development. It starts the server, queue worker, log watcher, and Vite in a single command, but requires `npm run dev` to resolve correctly in your environment.

---

## Notes and Limitations

- **No internal checkout.** The site is a catalog only. Purchases happen on WhatsApp or Shopee.
- **Tokopedia.** A `link_tokopedia` field exists on products for display purposes. No Tokopedia API integration is implemented.
- **Shopee requires live credentials.** The integration requires a registered Shopee Open Platform application and an authorised shop. The sandbox environment can be used for development.
- **Development credentials.** The seeder creates an administrator account for local use. Review or replace these credentials before deploying to any shared or public environment.
- **No project-level license file is present** in this repository.

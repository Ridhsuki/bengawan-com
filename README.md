# Bengawan Computer — Laptop Catalog & Store Operations System

A Laravel and Filament-based web application for **Bengawan Computer**, a laptop and computer accessories retailer in Solo Raya, Indonesia. The application serves as a public product catalog, an internal store operations back-office, and an integration hub with the Shopee marketplace.

---

## Overview

The public-facing website lets customers browse laptops and accessories by category, price range, or keyword, view product details and galleries, and reach the store through WhatsApp or an external Shopee listing. There is no internal checkout, shopping cart, or payment gateway. Purchases are completed through WhatsApp enquiry or directly on Shopee.

The Filament administration panel handles product and category management, stock control, internal sales recording, customer feedback review, and a two-way integration with the Shopee Open Platform. The integration synchronises stock between the local system and Shopee, imports Shopee orders, and reacts to real-time order-status webhooks.

---

## Key Features

**Public catalog**

- Paginated product listing with category, price-range, and keyword filters.
- Live search suggestions (name and full-text match) via a JSON endpoint.
- Product detail pages with image galleries and SEO metadata (title, description, Open Graph).
- Dedicated discount/promotions page listing products with an active `discount_price`.
- WhatsApp enquiry link generated per product, pre-filled with the product name, price, and URL.
- Direct link to the corresponding Shopee listing where set; a Tokopedia link field exists but no integration is active.
- Visitor feedback form with a 60-second per-IP rate limit.

**Filament administration**

- Product resource: create, edit, soft-delete, restore; manage images (main image + gallery); set Shopee mapping fields per product.
- Category resource with product counts.
- Stock management: stock field on each product; stock deducted automatically on sale or incoming Shopee order.
- Internal sales recording: quantity, cost price, negotiated price, customer notes, and transaction date; automatic profit calculation.
- PDF invoice generation (DomPDF) per sale, downloadable directly from the sales list.
- Feedback management: list, review, and action visitor submissions.
- Settings resource: company name, phone number (used for WhatsApp links), social media URLs, and homepage banner images.
- Dashboard widgets: total products, discounted products, total categories, 30-day revenue and profit line chart, product category breakdown, stock status, and top products.
- Shopee Integration page: connection status, OAuth redirect, manual token refresh, and connection test.
- Shopee Items page: live view of all items in the connected Shopee shop, with a link-to-product action.
- Shopee Sales Report page: revenue, orders, and item totals filtered by period (7 / 30 / 90 days) and order status; manual "Pull Orders" trigger.
- Internal Sales Report: revenue, profit, and unit totals with date-range filter.

---

## How the Application Works

### Public catalog

Visitors access the site and browse products filtered by category or price bracket, or search by keyword. Autocomplete suggestions are returned as JSON while the user types. Each product page shows the gallery, formatted price (and discount price when applicable), a WhatsApp enquiry button, and a Shopee button when `link_shopee` is set.

### Recording a local sale

When an administrator records a walk-in sale:

1. The administrator selects the product and enters quantity, selling price, customer name, and date.
2. The application stores a snapshot of the product name and SKU so the record survives a future product deletion.
3. Local stock is deducted.
4. If the product is mapped to Shopee and `sync_shopee_stock` is enabled, `SyncProductStockToShopeeJob` is dispatched to the queue to push the updated stock to Shopee.
5. A PDF invoice can be generated immediately from the sales list.

---

## Shopee Integration

The application integrates with the **Shopee Open Platform v2 API**. This section describes what is actually implemented.

### Authentication

An administrator navigates to the Shopee Integration page and clicks **Connect Shopee**. The application builds a signed OAuth URL using the Partner ID, Partner Key, a Unix timestamp, and an HMAC-SHA256 signature, then redirects the administrator to Shopee's authorization screen.

After the shop owner grants access, Shopee redirects to `/shopee/callback`. The application exchanges the one-time code for an access token and refresh token, which are stored encrypted in the `shopee_shops` table.

### Signed requests

Every API call appends `partner_id`, `timestamp`, `sign`, and `access_token` as query parameters. The signature is an HMAC-SHA256 hash of `partner_id + path + timestamp [+ access_token + shop_id]` using the Partner Key.

Before making a shop-scoped request, the client checks whether the token expires within 30 minutes and refreshes it automatically.

### Product catalog retrieval and mapping

The **Shopee Items** page fetches the live item list from Shopee (cached for 10 minutes) and displays name, SKU, stock, and price alongside matching local products. An administrator can link any local product to a Shopee listing. Once linked, the product stores `shopee_item_id`, `shopee_model_id`, and `shopee_shop_id` and stock synchronization becomes eligible.

The **ShopeeCatalogService** resolves category, brand, and logistics options from the Shopee API (with 12-hour caches) to populate the product edit form. A category warm-up command (`shopee:warm-catalog-cache`) pre-populates the cache outside of user interactions.

### Publishing products to Shopee

An administrator fills in the required Shopee fields on a product (category, brand, condition, weight, dimensions, logistic channel). `PublishProductToShopeeJob` then:

1. Uploads the main product image via the Shopee Media Space API.
2. Calls `add_item` to create the listing on Shopee.
3. Stores the returned `item_id` on the local product and enables `sync_shopee_stock`.
4. Writes a `ShopeeSyncLog` record with the full request and response payloads.

### Stock synchronization

After any stock change (sale recorded, product edited, or incoming Shopee order), `SyncProductStockToShopeeJob` dispatches and calls `update_stock` on the Shopee API. Each job is unique per product (via `ShouldBeUnique`) to prevent overlapping calls. Sync status and any error message are stored per product. All attempts are also written to `shopee_sync_logs`.

### Order import

Orders flow in two ways:

- **Webhook** — Shopee sends a push to `POST /shopee/webhook` on order-status changes (code `3`). The controller verifies the HMAC-SHA256 signature (when `SHOPEE_WEBHOOK_VERIFY=true`) and dispatches `SyncShopeeOrderJob` to the queue.
- **Scheduled pull** — The scheduler runs `shopee:pull-orders --hours=3` every 10 minutes, retrieving orders updated in the last 3 hours and dispatching the same job.

`SyncShopeeOrderJob`:
- Fetches full order detail from Shopee.
- Creates or updates a `ShopeeOrder` and its `ShopeeOrderItem` records.
- Creates or updates a `Sale` record (channel: `shopee`) with a product name snapshot and profit calculation.
- Deducts local stock when the order status is `READY_TO_SHIP`, `PROCESSED`, `SHIPPED`, `TO_CONFIRM_RECEIVE`, or `COMPLETED`.
- Restores stock when the status is `CANCELLED`, provided stock was previously deducted.

### Scheduled reconciliation

`shopee:reconcile-products` runs hourly. It queries Shopee's `get_item_base_info` for all locally mapped products and marks any item that Shopee no longer returns as `not_found`, disabling automatic stock sync for it.

### Synchronization logs and error states

`ShopeeSyncLog` records every publish and stock-push attempt with type, status (`success` / `failed`), message, and request/response payloads. Each product also carries `shopee_sync_status`, `shopee_sync_error`, `shopee_publish_status`, and `shopee_publish_error` fields for quick status display in the admin panel.

---

## Technology Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| PHP | 8.2 or higher |
| Admin panel | Filament 4 |
| PDF generation | barryvdh/laravel-dompdf |
| Frontend build | Vite 7 with Laravel plugin |
| CSS | Tailwind CSS 4 |
| Database | MySQL (configured by default) |
| Queue driver | Database (configurable) |
| Cache driver | Database (configurable) |
| Session driver | Database |
| Shopee API | Open Platform v2 |

---

## Requirements

- PHP 8.2 or higher with the extensions required by Laravel 12.
- MySQL 8 (or compatible).
- Composer.
- Node.js and npm.
- A running queue worker and the Laravel scheduler for Shopee synchronization to function.

---

## Installation

```bash
git clone https://github.com/Ridhsuki/bengawan-com.git
cd bengawan-com

composer install
cp .env.example .env
php artisan key:generate
```

Configure `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`, then:

```bash
php artisan migrate --seed
php artisan storage:link

npm install
npm run build
```

The seeder creates one administrator account and seeds the application settings. A `ProductSeeder` and `SaleSeeder` are present but commented out in `DatabaseSeeder`; uncomment them to load sample data.

### Composer development shortcut

The `composer dev` script starts the application server, queue worker, log tail, and Vite dev server in a single terminal:

```bash
composer dev
```

This is suitable for local development only. For production, run each service separately.

---

## Environment Configuration

Copy `.env.example` to `.env` and set the values below.

**Application**

```env
APP_NAME="Bengawan Komputer"
APP_URL=http://localhost
APP_TIMEZONE=Asia/Jakarta
```

**Database**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**Queue, cache, and session** — all default to the `database` driver. No Redis configuration is required unless you choose to switch.

**Shopee Open Platform**

```env
# Sandbox (default)
SHOPEE_HOST=https://partner.test-stable.shopeemobile.com
SHOPEE_AUTH_HOST=https://openplatform.sandbox.test-stable.shopee.sg
SHOPEE_API_HOST=https://openplatform.sandbox.test-stable.shopee.sg

# Production (uncomment and replace sandbox values)
# SHOPEE_HOST=https://partner.shopeemobile.com

SHOPEE_PARTNER_ID=your_partner_id
SHOPEE_PARTNER_KEY=your_partner_key

# Must be publicly reachable; Shopee calls this after authorization
SHOPEE_REDIRECT_URL="${APP_URL}/shopee/callback"

# Set to true in production to validate webhook signatures
SHOPEE_WEBHOOK_VERIFY=false
```

The webhook endpoint is `POST /shopee/webhook`. Configure this URL in the Shopee Partner Portal under **Push Configuration**.

---

## Running Background Services

Shopee order synchronization, stock pushes, product publishing, and token refresh all depend on the queue worker and Laravel scheduler. **If either is not running, these features will not function.**

**Queue worker**

```bash
php artisan queue:listen --tries=3
```

Or using the database queue driver with the default configuration:

```bash
php artisan queue:work --tries=3
```

**Laravel Scheduler** — add this single cron entry to the server:

```
* * * * * cd /path/to/bengawan-com && php artisan schedule:run >> /dev/null 2>&1
```

The scheduler runs the following commands automatically:

| Command | Schedule | Purpose |
|---|---|---|
| `shopee:refresh-tokens` | Every 3 hours | Refreshes Shopee access tokens before expiry |
| `shopee:pull-orders --hours=3` | Every 10 minutes | Pulls recent Shopee orders and queues sync jobs |
| `shopee:reconcile-products` | Hourly | Detects Shopee items that were deleted or de-listed |

**Development server**

```bash
php artisan serve
```

**Frontend assets (development)**

```bash
npm run dev
```

---

## Administrative Access

The admin panel is available at `/admin`. The seeder creates the following account **for development only**. Change the password immediately before exposing the application to any network.

| Field | Seeded value |
|---|---|
| Email | `admin@gmail.com` |
| Password | `password` |

> **Important:** These credentials are development defaults. Change them before deploying.

---

## Project Structure

```
app/
  Actions/Sales/        PDF invoice generation
  Console/Commands/     Artisan commands (Shopee token refresh, order pull, reconciliation)
  Filament/
    Pages/              Shopee Integration, Shopee Items, Shopee Sales Report
    Resources/          Products, Categories, Sales, Feedback, Settings
    Widgets/            Dashboard stats, charts
  Http/Controllers/
    HomeController      Public catalog pages and search suggestions
    FeedbackController  Visitor feedback submission
    Shopee/             OAuth callback, webhook handler
  Jobs/                 Queue jobs: publish product, sync stock, sync order, delete item
  Models/               Product, Category, Sale, Feedback, Setting, ShopeeShop,
                        ShopeeOrder, ShopeeOrderItem, ShopeeSyncLog
  Services/Shopee/      ShopeeClient (API client), ShopeeCatalogService (cached catalog data)
config/
  shopee.php            Shopee API configuration
database/
  migrations/           22 migrations covering all tables and Shopee field additions
  seeders/              Admin user, settings, optional product and sale sample data
routes/
  web.php               Public routes and Shopee OAuth/webhook endpoints
  console.php           Scheduler definitions
```

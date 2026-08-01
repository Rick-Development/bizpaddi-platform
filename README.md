# Rick Development LTD — Official Website & Platform Backend

[![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-%5E8.2-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Modular Architecture](https://img.shields.io/badge/Architecture-Modular-purple.svg)](https://nwidart.com/laravel-modules)

Globally scalable, AI-powered, enterprise-grade corporate platform backend for **Rick Development LTD**. Built with Laravel 12 on a modular architecture (`nwidart/laravel-modules`), integrating Model Context Protocol (MCP) AI agents, live traffic analytics, candidate career scoring, automated outreach campaigns, client and team portals, dynamic multi-currency management, and comprehensive corporate compliance governance.

---

## 🎨 Brand Identity & System Colors

- **Primary Brand Color:** `#FB4C4B` (Vibrant Coral Red)
- **Secondary Brand Color:** `#191C47` (Deep Corporate Navy)

---

## 🚀 Key Technical Architecture

The application is structured into **25 decoupled modules** using `nwidart/laravel-modules`, ensuring high scalability, maintainability, and clean separation of domain logic.

| Tech Layer | Framework / Library | Description |
| :--- | :--- | :--- |
| **Core Framework** | Laravel `^12.0` (PHP `^8.2`) | High-performance backend engine |
| **Modular Engine** | `nwidart/laravel-modules ^12.0` | Decoupled 25-module domain-driven structure |
| **API Authentication** | Laravel Sanctum `^4.2` | Token-based API authentication across portals |
| **Authorization / RBAC** | Spatie Laravel Permission `^6.23` | Role and permission management for Admins, Staff, Clients |
| **AI / LLM Integration** | Echolabs Prism `^0.99.2` & MCP | Unified AI agents (GPT, Gemini) for content, SEO, and chat |
| **Media Management** | Spatie Laravel MediaLibrary `^11.17` | Optimized attachment, image, and document handling |
| **Document Generation** | DomPDF (`^3.1`) & PHPWord (`^1.4`) | Dynamic PDF & Word contract/invoice compilation |
| **Geo & Location** | Stevebauman Location `^7.6` | Client IP geolocation & auto currency selection |
| **Browser Analytics** | Jenssegers Agent `^2.6` | User device, browser, and OS telemetry |

---

## 📦 Complete Module Breakdown (A to Z)

### 1. `AI` (Artificial Intelligence Engine)
- Integrated with Echolabs Prism for LLM orchestration.
- Features prompt templates, AI drafting for blogs, AI career candidate scoring, and automated support ticket summarization.

### 2. `About` (Corporate Overview)
- Manages corporate history, vision, leadership profiles, executive bios, and company awards.

### 3. `Accounting` (Financial & Payroll Engine)
- Comprehensive financial management including invoice generation, balance sheet records, multi-currency revenue tracking, expense categorization, and team salary disbursements.

### 4. `Admin` (Management Portal)
- Main administrative dashboard for super-admins and managers. Features unified content management, user role management, campaign execution, system logs, and security controls.

### 5. `Analytics` (Live Traffic Telemetry)
- Automated visitor tracking engine recording IP addresses, geolocation (country, city), device/browser details, page view heatmaps, and conversion funnels.

### 6. `Blog` (Insight Hub)
- Dynamic blogging platform with category tagging, SEO metadata, and AI-assisted content drafting.

### 7. `CMS` (Content Management System)
- Flexible page builder and custom section manager for landing pages, banners, and static dynamic content.

### 8. `Careers` (Talent Acquisition Pipeline)
- Job posting manager, CV upload parsing, applicant tracking system (ATS), application stage workflows, and AI-based applicant resume scoring.

### 9. `ClientPortal` (Client Self-Service Dashboard)
- Dedicated client dashboard for tracking project progress, submitting support tickets, viewing invoices, making payments, and managing virtual accounts.

### 10. `Clients` (Client Relationship Management)
- Client directory, corporate client profiles, verified testimonials, case study links, and testimonial approval workflows.

### 11. `Communications` (Outreach & Marketing Campaigns)
- Multi-channel communication suite featuring email newsletters, SMS campaigns (via customizable SMS templates), contact lists, subscriber management, and multi-step communication workflow scripts.

### 12. `Contact` (CRM Intake & Lead Capture)
- Public lead capture forms, contact submission management, automated responder emails, and CRM intake pipelines.

### 13. `Contracts` (Legal Contract Engine)
- Digital contract creation, custom contract templates, version history tracking, PDF/Word document generation, attachment handling, and electronic signature workflows.

### 14. `Home` (Homepage Engine)
- Dynamic hero banner sliders, key performance stats, interactive process steps, feature highlights, navigation menu items, and call-to-action (CTA) manager.

### 15. `Investor` (Investor Relations)
- Public and private investor portal supporting stock data tracking, annual financial reports, press releases, and shareholder meeting schedules.

### 16. `KYC` (Identity & Verification Compliance)
- Identity verification workflows, document compliance checks, and regulatory customer verification pipelines.

### 17. `Legal` (Corporate Legal & Compliance)
- Governance page manager for Terms & Conditions, GDPR Privacy Policy, Cookie Tracking, Data Protection & Security, and Intellectual Property statements.

### 18. `Mcp` (Model Context Protocol Integration)
- Standardized Model Context Protocol adapter enabling external AI agents to securely query system endpoints, generate metadata, and run diagnostic tasks.

### 19. `Portfolio` (Projects Showcase)
- Portfolio showcase filterable by industry, technology stack, project impact metrics, and client deliverables.

### 20. `ProjectManagement` (Operations & Delivery)
- Project creation, task assignment, milestone tracking, Key Performance Indicators (KPIs), time logs, daily attendance, team-client chat messaging, and performance evaluation.

### 21. `SEO` (Search Engine Optimization)
- Automated meta title/description generation, canonical URLs, Schema.org structured data, and page audit metrics.

### 22. `Services` (Service Offerings)
- Comprehensive catalog of company services, case studies, multi-currency service pricing, and direct service ordering pipelines.

### 23. `Settings` (System & Environment Configuration)
- Global site settings, system branding, SMTP mail credentials, API integration keys, and dynamic currency exchange rate matrices.

### 24. `Store` (E-Commerce Integration)
- API connector and product catalog synchronizer for integration with [store.rickdevelopment.com](https://store.rickdevelopment.com).

### 25. `Subsidiaries` (Subsidiary & Partner Network)
- Directory and profile manager for company subsidiaries, strategic global partners, and alliance networks.

### 26. `TeamPortal` (Staff Portal)
- Employee self-service workspace for logging daily work reports, time tracking, attendance check-in/out, submitting bank account details for salary payouts, and team announcements.

---

## ⚡ Multi-Currency & Geolocation Pricing Logic

The platform implements dynamic country-based currency auto-detection:
1. **Client Geo-Detection**: When a user accesses the site, `stevebauman/location` resolves their IP address.
   - If the user is in **Nigeria**, prices are displayed in **NGN (₦)** fetched from the `currencies` database.
   - If the user is in **Other Countries**, prices automatically default to **USD ($)**.
2. **Admin Exchange Rate Management**: The Admin Settings panel includes a dedicated section for managing real-time currency exchange rates, allowing global pricing updates across services and invoices.

---

## 📚 API Postman Collections

The repository includes four pre-configured Postman collection files for API testing and integration:

1. `rick_development_unified_api.postman_collection.json` — Comprehensive API collection covering all endpoints.
2. `rick_development_admin_panel_api.postman_collection.json` — Admin CMS, Accounting, HR, and Communication routes.
3. `rick_development_client_portal_api.postman_collection.json` — Client authentication, project tracking, and support API endpoints.
4. `rick_development_team_portal_api.postman_collection.json` — Team portal, attendance, daily reporting, and work log APIs.

---

## 🛠️ Installation & Local Setup Guide

### Prerequisites
- **PHP:** `>= 8.2` (with `pdo`, `mbstring`, `openssl`, `curl`, `gd` extensions enabled)
- **Composer:** `>= 2.0`
- **Node.js:** `>= 18.0` & **NPM**
- **Database:** MySQL `>= 8.0` or PostgreSQL `>= 14.0`

### Step-by-Step Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Rick-Development/rickdevelopment-website.git
   cd rickdevelopment-backend
   ```

2. **Run Quick Setup Command:**
   ```bash
   composer run setup
   ```
   *(This automatically installs PHP dependencies, copies `.env.example` to `.env`, generates the application key, runs migrations, installs NPM packages, and builds frontend assets).*

3. **Configure Environment (`.env`):**
   Update your database credentials and application settings in `.env`:
   ```ini
   APP_NAME="Rick Development LTD"
   APP_ENV=local
   APP_KEY=base64:...
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rickdevelopment_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Database Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

5. **Start Development Servers:**
   To run the application server, queue worker, Pail log viewer, and Vite concurrently:
   ```bash
   composer run dev
   ```
   *Or start the PHP dev server individually:*
   ```bash
   php artisan serve
   ```

---

## 🧪 Testing

Run the automated test suite using PHPUnit:
```bash
composer run test
```

---

## 📄 License & Corporate Info

© **Rick Development LTD**. All Rights Reserved.  
For technical support or corporate inquiries, visit [Rick Development LTD](https://rickdevelopment.com).
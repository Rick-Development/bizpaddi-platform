# Bizpaddi — All-in-One Multi-Tenant Business Operating System

[![Laravel](https://img.shields.io/badge/Laravel-12.0-0E5E4A.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-%5E8.2-F2A900.svg)](https://php.net)
[![Architecture](https://img.shields.io/badge/Architecture-Multi--Tenant%20SaaS-0E5E4A.svg)](https://bizpaddi.com)

**Bizpaddi** is a multi-tenant SaaS business management platform built for African commerce. It unifies smart invoicing, instant payment reconciliation, Kanban project workflows, staff payroll, omnichannel CRM, AI employees, and public digital storefronts in a single codebase.

---

## 🌐 3-Domain Architecture

All three domains point to the same codebase and database, automatically routed by [`EnforceDomainRouting`](app/Http/Middleware/EnforceDomainRouting.php):

| Domain | Role | Key Routes |
| :--- | :--- | :--- |
| **`bizpaddi.com`** | Public Marketing Website & Tenant Storefronts | `/`, `/features`, `/pricing`, `/faq`, `/store/{slug}` |
| **`app.bizpaddi.com`** | Customer Workspaces (Business, Team & Client Portals) | `/dashboard/*`, `/register`, `/login`, `/dashboard/team/*`, `/dashboard/client/*` |
| **`admin.bizpaddi.com`** | Super Admin & Platform Staff Control Center | `/super-admin/*`, `/admin/*` |

---

## 🎨 Brand Identity

- **Primary Brand Color:** `#0E5E4A` (Deep Emerald Green)
- **Secondary Accent Color:** `#F2A900` (Warm Gold)
- **Dark Surface Background:** `#08110e`

---

## ⚙️ Production Environment Setup (`.env`)

```env
APP_NAME="Bizpaddi"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bizpaddi.com
APP_DOMAIN=bizpaddi.com
APP_PORTAL_DOMAIN=app.bizpaddi.com
APP_ADMIN_DOMAIN=admin.bizpaddi.com
SESSION_DOMAIN=.bizpaddi.com
```

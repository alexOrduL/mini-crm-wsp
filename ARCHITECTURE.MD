
---

## ✅ `ARCHITECTURE.md`

```markdown
# Architecture: Mini CRM API

## 📌 Framework: Laravel

Laravel was chosen for its strong ecosystem and very expressive syntax, with REST APIs validated and handled requests support right out of the box, including database migrations, testing, and OpenAPI integration.

It allows for fast prototyping with best practices but also permits the clean separation of concerns in the code via controllers, requests, resources, and services.

---

## 🧱 Data Model

Entity: Company  
- id (UUID)  
- name (string)  
- domain (string, unique)  
- timestamps  
- soft delete

Entity: Contact  
- id (UUID)  
- company_id (nullable UUID)  
- first_name, last_name  
- email (unique)  
- phone  
- timestamps  
- soft delete

Entity: Deal  
- id (UUID)  
- contact_id (UUID)  
- title, amount, currency  
- status: open, closed-won, closed-lost  
- timestamps  
- soft delete

---

## 📐 Architecture & Principles

- PHP 8.2+
- Uses dependency injection
- Controllers = input/output  
- Requests = validation  
- Resources = response formatting  
- Entities managed with Eloquent ORM

---

## 🔗 Relationships

- `Company` → has many `Contacts`
- `Contact` → has many `Deals`

Each entity uses UUIDs as primary keys.

---

## 🔍 Filtering

- `GET /contacts?email=...`: exact match on contact email
- `GET /deals?status=open`: list only deals with given status
- `?withTrashed=true`: includes soft-deleted entities in the response

<!-- ---

## 🔁 Import CLI

The command `php artisan import:sample` reads a CSV (`storage/app/sample_data.csv`) and populates the 3 tables using `updateOrCreate()` to ensure idempotency. -->

---

## 📄 Swagger Docs

Using `l5-swagger`, OpenAPI annotations generate machine-readable docs accessible at:


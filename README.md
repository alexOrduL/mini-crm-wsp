# Mini CRM API (Laravel)

A lightweight CRM backend built in Laravel, offering versioned RESTful APIs to manage companies, contacts, and deals. Built for clarity, extensibility, and containerized delivery.

## 🚀 Features

- API versioning (`/api/v1`)
- Entities: Company, Contact, Deal
- RESTful CRUD endpoints
- Attach contacts to companies
- List deals for a contact
- Filter contacts by email and deals by status
- CSV import with idempotent logic
- Soft deletes with optional `?withTrashed=true` query
- Swagger/OpenAPI documentation
- Automated tests with Pest
- Dockerized stack
<!-- - PSR-12 + SOLID principles -->

## 🐳 Lets Start!

### Prerequisites

- Docker & Docker Compose

### Setup

- Build docker:
  ```bash
  docker compose up -d --build
  docker exec -it laravel_app bash
  ```
- Install dependencies:
  ```bash
  composer i
  ```

- Create the env file:

  ```bash
  cp .env.docker.example .env
  php artisan key:generate
  ```

## 🧪 Testing

To run the tests use commands:
(**Uncomment testing variables and comment production variables before runing tests**)
```bash
cp .env .env.testing
php artisan test
```

## 📝 Import sample data

To import companies, contacts, and deals from a sample CSV file:

```bash
php artisan import:sample
```
Ensure that storage/app/sample_data.csv exists and has the required headers in this order:

```bash
company_id,company_name,company_domain,contact_id,first_name,last_name,email,phone,deal_id,title,amount,currency,status
```
## 🖥️ Check Database

```bash
# create db
php artisan migrate
#to generate db with seeders
php artisan migrate --seed 
```
Access to http://localhost:8082/
  - Username: laravel
  - Password: secret
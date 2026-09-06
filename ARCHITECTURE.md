# ServiceFlow Architecture

## Stack

- PHP 8.5
- Laravel 13
- Vue 3
- TypeScript
- Inertia.js
- MySQL 8
- Tailwind CSS
- Sass
- Vite
- Pest
- GitHub Actions

## Application Flow

Browser
→ Laravel Router
→ Controller
→ Form Request
→ Service / Domain Logic
→ Eloquent
→ MySQL

Laravel
→ Inertia
→ Vue Page
→ Vue Components

## Backend Structure

Controllers should remain thin.

Validation belongs in Form Requests.

Authorization belongs in Policies.

Complex business logic belongs in application services.

Database persistence uses Eloquent models.

Query-heavy filtering may use dedicated query objects when justified.

## Frontend Structure

Vue pages represent application screens.

Reusable UI belongs in components.

TypeScript types should model backend data contracts.

Shared state should remain minimal.

Server state is primarily provided through Inertia.

## Database

Primary domain tables:

- users
- customers
- work_orders
- activity_logs

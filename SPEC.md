# ServiceFlow Product Specification

## Overview

ServiceFlow is a lightweight work-order management platform for service businesses.

The application allows administrators and technicians to manage customers, assign work orders, track job progress, review operational activity, and monitor key business metrics.

## Primary Users

### Administrator

- Manage customers
- Create and assign work orders
- View all work orders
- Update work-order status
- View dashboard metrics
- Review audit history

### Technician

- View assigned work orders
- Update work-order status
- Add operational notes
- View customer information related to assigned work

## MVP Features

1. Authentication
2. Role-based authorization
3. Customer management
4. Work-order management
5. Technician assignment
6. Work-order lifecycle
7. Filtering and pagination
8. Dashboard metrics
9. Audit/activity history
10. Responsive user interface

## Work Order Lifecycle

Draft → Scheduled → In Progress → Completed

Alternative terminal state:

Cancelled

## Out of Scope

The MVP will not include:

- Payments
- Invoicing
- File uploads
- Team/multi-tenant support
- Calendar integrations
- SMS notifications
- Email marketing
- AI features

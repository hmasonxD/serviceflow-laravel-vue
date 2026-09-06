# ServiceFlow Requirements

## Functional Requirements

### Authentication

FR-001: Users must be able to register.

FR-002: Users must be able to log in and log out.

FR-003: Registered users must verify their email address.

FR-004: Protected routes must require authentication.

### Authorization

FR-005: The system must support administrator and technician roles.

FR-006: Administrators may manage customers and all work orders.

FR-007: Technicians may access work orders assigned to them.

### Customers

FR-008: Administrators must be able to create customers.

FR-009: Administrators must be able to update customers.

FR-010: Users must be able to search customers.

FR-011: Customer results must support pagination.

### Work Orders

FR-012: Administrators must be able to create work orders.

FR-013: Work orders must belong to a customer.

FR-014: Work orders may be assigned to a technician.

FR-015: Work orders must have a defined status.

FR-016: Users must be able to filter work orders by status.

FR-017: Users must be able to filter work orders by customer.

FR-018: Users must be able to filter work orders by technician.

FR-019: Users must be able to filter work orders by date range.

### Audit Trail

FR-020: Significant work-order changes must generate an activity record.

FR-021: Activity records must identify the user responsible for the change.

### Dashboard

FR-022: The dashboard must display open work-order count.

FR-023: The dashboard must display completed work-order count.

FR-024: The dashboard must display revenue metrics.

FR-025: The dashboard must display recent activity.

## Non-Functional Requirements

NFR-001: Backend must use Laravel.

NFR-002: Frontend must use Vue 3 and TypeScript.

NFR-003: Database must use MySQL.

NFR-004: Styling must use Tailwind CSS and Sass.

NFR-005: Business-critical functionality must have automated tests.

NFR-006: CI must run tests and frontend build validation.

NFR-007: Database queries must avoid unnecessary N+1 queries.

NFR-008: Frequently filtered columns should be indexed.

NFR-009: Application must be responsive on desktop and mobile.

NFR-010: Production secrets must not be committed to source control.

# Architecture Decisions

## ADR-001: Use Laravel with Inertia and Vue

Decision:
Use Laravel 13 with Inertia.js and Vue 3 rather than a completely separate SPA and REST API.

Reason:
This represents an idiomatic modern Laravel architecture while still demonstrating Vue and TypeScript skills.

## ADR-002: Use MySQL

Decision:
Use MySQL 8 for development and production.

Reason:
The target role explicitly emphasizes MySQL experience and complex SQL/database models.

## ADR-003: Avoid a Repository Layer Initially

Decision:
Use Eloquent directly from services/query objects rather than creating repositories for every model.

Reason:
Eloquent already provides a persistence abstraction. Additional repository interfaces would add unnecessary ceremony unless a real abstraction need emerges.

## ADR-004: Use Application Services for Business Rules

Decision:
Business workflows that involve multiple models or state transitions will live in service classes.

Reason:
This keeps controllers thin and makes business behavior easier to test.

## ADR-005: Audit Work-Order Changes

Decision:
Important work-order changes will create immutable activity records.

Reason:
Auditability is common in business software and demonstrates transactional/domain-oriented design.

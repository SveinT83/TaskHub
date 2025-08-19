td-services

Purpose

The td-services module is the central service and product catalog for TaskHub.It defines and manages all services and products that can be offered to customers, regardless of contract or project.Other modules — such as td-contracts, td-cost-calculator, or td-servicedesk — can reference services in this catalog to ensure consistency in naming, pricing, and operational policies.

This module is designed with light dependencies on other TaskHub modules:

td-categories – optional category management

td-sla – optional SLA linking

td-policy – optional policy linking

td-cost-calculator – optional cost product linking and cost price ingestion

The module gracefully degrades if these dependencies are not installed.

Main Features

Service Catalog Management

Name, description, category (optional), SKU, status (draft|active|inactive)

Optional category link to td-categories (fallback to local field if not installed)

Pricing

Standard price and currency

Billing model: one-time, monthly, per user, or per unit

Billing interval: billing_interval_count + billing_interval_unit (day|month|year)

Included minutes/hours (optional)

Tier pricing: simple quantity-based discount or fixed unit price per interval

Cost Link & Margin (via td-cost-calculator)

Link one or more Products from td-cost-calculator to a Service

Each link may specify a quantity/multiplier (e.g., 0.5, 1, 3)

Service keeps a cached cost_price_cached (aggregation of linked products)

Manual cost items supported with: name, unit_cost, period_count, period_unit, quantity, notes

Define desired margin % on the Service and get a suggested sales price (ex. & inc. VAT)

suggested_price_ex_vat = cost_price_cached * (1 + margin_percent/100)

suggested_price_inc_vat = suggested_price_ex_vat * (1 + vat_rate)

Button “Use suggested price” to copy suggested_price_ex_vat → price_standard (and show inc. VAT for info)

SLA Defaults

Optional link to a pre-defined SLA profile from td-sla

Time Tracking Defaults

Default time code

Included time allocation

Billing policy:

All billable

Deduct from included bank first

Free if caused by provider fault

Rate profiles: standard pricing rules for service delivery (e.g., remote/onsite, normal/after-hours)

Policy Linking

Optional link to one or more policies via td-policy

Links stored via pivot (service_policy_links) using policy_uid

Policies are copied as snapshots into contracts when services are added

Meta Data Support

Extend any service with custom fields via TaskHub Meta Data System

Meta tab visible only if meta fields are defined for services

Role-Based Access Control

Uses Spatie Permissions (td-services.view, td-services.edit, td-services.admin)

Internationalization

All strings in Resources/lang/{locale}/messages.php

Ships with en locale; more can be added via TaskHub’s translation system

Menu Integration

Registers admin menu entries under Admin → Services

Menu visibility and actions gated by RBAC

What This Module Will NOT Do

To avoid GitHub Copilot or contributors adding unintended features, the following are explicitly out of scope:

❌ No automation engine — Policies are plain text, not executable rules.

❌ No hard foreign keys to other module tables — use light dependencies with nullable fields or policy_uid strings.

❌ No advanced pricing logic — No “X+Y=Z” bundles, campaign pricing, or region-specific prices in v1.

❌ No contract or bank management — Included hours are set as defaults; actual bank tracking is handled in td-contracts.

❌ No direct Tripletex sync — SKU is auto-generated and manually overrideable.

❌ No public/partner APIs — Only internal API endpoints with authentication/permissions.

❌ No creation or editing of SLA/policy content — Only linking to existing records if modules are installed.

❌ No cost calculation engine — Actual product cost math stays in td-cost-calculator. td-services only links to products and caches totals.

❌ No duplicate core tables — Use TaskHub core features (e.g., Meta Data System) where applicable.

❌ No unguarded routes — All web/API routes must be behind auth and permission checks.

Data Model (MVP)

services

id

name

description

sku (unique, auto-generated, overrideable)

status (enum: draft/active/inactive)

category_id (nullable, if td-categories installed)

category_text (fallback if td-categories not installed)

pricing_model (one_time/monthly/per_user/per_unit)

billing_interval_count (int)

billing_interval_unit (enum: day|month|year)

price_standard

currency

included_time_minutes (nullable)

default_timecode (nullable)

billing_policy (enum: bill_all | deduct_included_first | free_on_provider_fault)

sla_id (nullable, if td-sla installed)

cost_price_cached (decimal, nullable) – aggregated from linked and manual cost products

margin_percent (decimal, nullable)

suggested_price_ex_vat (decimal, computed/cached)

suggested_price_inc_vat (decimal, computed/cached)

meta_enabled (bool)

created_at / updated_at

service_price_tiers

id

service_id

min_qty

max_qty (nullable)

discount_percent (nullable)

unit_price (nullable)

service_rate_profiles

id

key

name

description

work_hours_rule (json)

base_rate

after_hours_rate

free_if_sla_breach (bool)

uses_contract_bank (bool)

service_rate_profile_service (pivot)

service_id

rate_profile_id

service_policy_links (pivot)

id

service_id

policy_uid (string)

is_default (bool)

display_order (int)

service_cost_products (pivot, light dependency to td-cost-calculator)

id

service_id

cost_product_uid (string)

quantity (decimal, default 1.0)

service_manual_costs

id

service_id

name (string)

unit_cost (decimal)

period_count (int)

period_unit (enum: day|month|year)

quantity (decimal, default 1.0)

notes (text, nullable)

Using *_uid strings keeps the dependency soft. When td-cost-calculator is not present, the UI hides cost linking and no lookups are attempted.

Cost Ingestion & Margin Logic

Auto price updates: When cost changes in td-cost-calculator, td-services updates cost_price_cached and re-computes suggested prices automatically. It does not overwrite the active sales price automatically.

Manual refresh: “Fetch Cost Price Now” button to re-pull latest product costs on demand.

Suggested price: Recompute suggested_price_ex_vat and suggested_price_inc_vat on changes to cost_price_cached or margin_percent.

Guardrails: If cost_price_cached is null, suggested price is not computed; UI shows a warning badge.

Action: “Use suggested price” copies the computed suggestion to price_standard (explicit user action).

Contracts: When a service is added to a contract, the price and cadence are snapshotted on the contract line and never auto-updated. Changes in service pricing require a manual update on the contract.

Integration with TaskHub Core

Menus: Use Admin menu conventions. Register under admin.services with optional children like admin.services.rate-profiles.

Translations: All strings in Resources/lang/{locale}/messages.php. The core Translation Editor can scan and edit module strings.

Permissions: Declare in module.json and enforce via Spatie can: in routes/controllers/views:

td-services.view, td-services.edit, td-services.admin

Meta Data System: Extend services without DB migrations; meta values stored in core meta_data table.

Optional modules: Feature flags show/hide tabs:

SLA tab visible only if td-sla installed

Policies tab visible only if td-policy installed

Costs & Margin panel visible only if td-cost-calculator installed

API

Internal-only API endpoints in routes/api.php

Protected with auth:sanctum and can: middleware

Naming convention: services.{resource}.{action}

Minimum endpoints:

GET /api/services – list with filters

POST /api/services – create

PUT /api/services/{id} – update

DELETE /api/services/{id} – soft delete/inactivate

POST /api/services/{id}/refresh-cost – manual cost pull (td-cost-calculator present)

GUI Structure

Tabs in Create/Edit Service view:

General Info

Pricing & Model

Costs & Margin

Cost lines table

Button: Add from Cost Calculator (only if module installed)

Button: Add Manual Cost Item (always available)

View aggregated Cost (cached)

Set Margin %

See Suggested Price (ex. & inc. VAT)

Button: Use suggested price

SLA (only if td-sla installed)

Time & Billing

Policies (only if td-policy installed)

Meta Data (only if meta fields exist)

Status & Validation Rules

Only active services can be used in contracts.

Activating a service requires: name, SKU, pricing model, billing interval, valid price (or tiers), and (if cost is linked) no missing cost product references.

Tier rows must not overlap. Each row uses either discount_percent or unit_price.

SKU & Tripletex

SKU is auto-generated (prefix + running number) on create.

SKU is unique and manually overrideable to match external systems (e.g., Tripletex). No external sync in v1.

Events

ServiceCreated, ServiceUpdated, ServiceActivated, ServiceDeactivated

ServiceCostRefreshed (payload: service_id, old_cost, new_cost, source='td-cost-calculator')

Anti-Scope Check (for Copilot)

No automation engine for policies

No hard FKs to other modules

No advanced promo/bundle pricing

No contract/bank ownership

No Tripletex integration

No public/partner APIs (internal only)

No cost computation engine (belongs to td-cost-calculator)

No core table duplication

No unguarded routes or hard-coded strings

© 2025 Trønder Data AS — td-services Module Documentation
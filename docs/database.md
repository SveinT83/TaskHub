
# TaskHub Database Structure Documentation

This document provides detailed information about the database structure and standard tables used in TaskHub.

## 🗃️ Core Tables

### 1. Users (`users`)
Stores user authentication details and profile information.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | string | User's full name |
| email | string | Unique user email address |
| password | string | Hashed password |
| email_verified_at | timestamp | Email verification timestamp |
| remember_token | string | Token for session persistence |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 2. Roles (`roles`)
Stores user roles for RBAC (Role-Based Access Control).

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | string | Role name |
| guard_name | string | Laravel guard context (usually 'web') |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 3. Permissions (`permissions`)
Stores permissions used by roles.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | string | Permission identifier |
| guard_name | string | Laravel guard context |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 4. Model-Role-Permission Pivot Tables
- `model_has_roles`: Links roles to users
- `role_has_permissions`: Links permissions to roles
- `model_has_permissions`: Links permissions directly to models (optional)

These tables are managed by Spatie Permissions.

### 5. Menus (`menus`)
Defines top-level menus within TaskHub GUI.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | string | Display name |
| slug | string | Unique slug for identification |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 6. Menu Items (`menu_items`)
Defines individual menu links and dropdown items.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| menu_id | bigint | Foreign key to menus |
| parent_id | bigint (nullable) | Self-referencing for nested menus |
| title | string | Display title |
| url | string | URL or route name |
| icon | string | Icon identifier |
| order | int | Display order |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 7. Widgets (`widgets`)
Stores widget configurations and metadata.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| key | string | Unique identifier |
| component | string | Component class reference |
| config | json | Widget configuration |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 8. Settings (`settings`)
Stores configurable settings.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| key | string | Setting key |
| value | json | Setting value |
| module | string | Module owning the setting |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 9. Cache (`cache`)
Stores cache entries.

| Column | Type | Description |
|--------|------|-------------|
| key | string | Cache key |
| value | json | Cached value |
| expiration | timestamp | Cache expiration |

### 10. Sessions (`sessions`)
Stores user session data.

| Column | Type | Description |
|--------|------|-------------|
| id | string | Session ID |
| user_id | bigint (nullable) | Foreign key to users |
| ip_address | string | IP address |
| user_agent | text | Browser user agent |
| payload | text | Session payload |
| last_activity | timestamp | Last activity timestamp |

### 11. Personal Access Tokens (`personal_access_tokens`)
Used by Laravel Sanctum for API tokens.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| tokenable_type | string | Model type (usually User) |
| tokenable_id | bigint | Model ID |
| name | string | Token name |
| token | string | Token hash |
| abilities | json | Permissions granted by token |
| last_used_at | timestamp | Last usage timestamp |
| expires_at | timestamp (nullable) | Expiration date |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

## 🌐 Global Shared Tables (planned)

### 12. Meta Data (`meta_data`)
For storing custom meta information across all entities.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| parent_type | string | Model type |
| parent_id | bigint | Model ID |
| key | string | Metadata key |
| value | json | Metadata value |
| module | string | Module responsible for data |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 13. Comments (`comments`)
Stores comments across various entities.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| commentable_type | string | Model type being commented |
| commentable_id | bigint | Model ID being commented |
| user_id | bigint | User who made the comment |
| content | text | Comment text |
| is_internal | bool | If comment is internal only |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 14. Notifications (`notifications`)
System notifications to users.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| user_id | bigint | User notified |
| type | string | Notification type |
| data | json | Notification data |
| read_at | timestamp (nullable) | When read |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

### 15. Activity Log (`activity_log`)
Tracks system and user activities.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| module | string | Module responsible |
| model_type | string | Model involved |
| model_id | bigint | Model ID |
| action | string | Action performed |
| user_id | bigint | User who performed action |
| description | text | Activity description |
| changes | json | Changes made |
| created_at | timestamp | Creation timestamp |

## 📖 Best Practices

- Always check existence with `Schema::hasTable()` and `Schema::hasColumn()` before migrations.
- Clearly define all table relations using Laravel relationships (Eloquent).
- Keep consistent naming conventions.

---

*© 2025 TrønderData AS – TaskHub Database Documentation*

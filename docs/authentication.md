
# Authentication & Authorization in TaskHub

This document outlines TaskHub's authentication and authorization methods, detailing integrations, best practices, and security mechanisms.

## 🔑 Authentication Methods

TaskHub supports multiple authentication methods:

### 1. Email & Password

Standard Laravel authentication is provided out of the box:

- **Registration:** Users can register via email/password combination.
- **Login:** Users authenticate via their registered email and password.
- **Password Reset:** Standard Laravel password reset functionality is available.

### 2. OAuth via Socialite

TaskHub supports OAuth authentication using Laravel Socialite. This allows users to authenticate via external providers (e.g., Google, Facebook, GitHub):

**Setup and usage:**

- Install Socialite:
  ```bash
  composer require laravel/socialite
  ```
- Configure providers in `.env`:
  ```env
  GOOGLE_CLIENT_ID=your-google-client-id
  GOOGLE_CLIENT_SECRET=your-google-secret
  ```
- Routes are provided for authentication:
  ```php
  Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect']);
  Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);
  ```

### 3. NextCloud Integration (Optional)

TaskHub offers optional NextCloud integration:

- Allows users to log in directly using their NextCloud credentials.
- Requires explicit setup and configuration via a dedicated TaskHub app.

## 🔐 API Token Management (Laravel Sanctum)

TaskHub utilizes Laravel Sanctum for secure API token management:

### Token Generation

Tokens are created via Laravel Sanctum API:

```php
$user = Auth::user();
$token = $user->createToken('token-name')->plainTextToken;
```

### Token Usage

API requests require the token to be sent as a Bearer token:

```
Authorization: Bearer <your-token>
```

### Token Management

- Tokens can be revoked or managed via the user profile in TaskHub GUI.
- Sanctum tokens respect user permissions and security context.

## 🛡️ Roles and Permissions (Spatie Permissions)

TaskHub uses Spatie Permissions for Role-Based Access Control (RBAC):

### Defining Roles & Permissions

Roles and permissions are defined explicitly in modules (`module.json`):

```json
"permissions": [
  {"key": "module.view", "label": "View Module", "description": "Allows viewing module content"}
]
```

### Middleware & Policy Usage

Use middleware in routes:

```php
Route::get('/module', [ModuleController::class, 'index'])->middleware('can:module.view');
```

In Blade templates:

```blade
@can('module.view')
    <!-- Content only visible with permission -->
@endcan
```

## 🔧 Middleware and Route Security

Routes in TaskHub must be secured appropriately using middleware:

- `web` middleware: session and CSRF protection.
- `auth` middleware: ensures authentication.
- `can:permission` middleware: checks permission via Spatie.

Example route definition:

```php
Route::middleware(['web', 'auth', 'can:module.access'])->group(function () {
    Route::get('module/', [ModuleController::class, 'index']);
});
```

## 🔒 Security Best Practices

### General Recommendations

- Always use HTTPS in production.
- Regularly update dependencies.
- Utilize Laravel built-in security measures: CSRF protection, password hashing, and secure cookies.

### Token Security

- Limit token lifespan (optional via configuration).
- Regularly audit tokens and revoke unused tokens.

## 📦 Optional Security Features (Recommended)

TaskHub recommends considering additional security features such as:

- **Two-Factor Authentication (2FA)**: Can be added via Laravel packages such as Laravel Fortify or custom implementation.
- **Rate Limiting**: Implement API throttling via Laravel middleware.

---

*© 2025 TrønderData AS – TaskHub Authentication Documentation*

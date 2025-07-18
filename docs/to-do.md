# 📝 TaskHub – To-Do & Design Roadmap

This document outlines planned features, best practices, and conventions for the TaskHub core and modules.

---

## ✅ Best Practices to Enforce

### Admin Menus
- Each module **must** register an admin menu under `admin.<module>` (e.g., `admin.equipment`)
- If modifying core configuration: use `admin.configuration.<module>`
- If affecting UI appearance: use `admin.appearance.<module>`
- If managing other modules: use `admin.modules.<module>`

### File & Folder Structure
- All modules should include:
  - `README.md`
  - `docs/` folder for any extended documentation
  - `module.json` and `composer.json` at root
  - `src/` for all source code
- Support for `docs/` browsing and links in GUI is planned

### Route Prefixes
- Avoid global route conflicts
- Always use a hardcoded prefix like `equipment/...`, not auto-generated

---

## 🧠 Architecture / Engine Features (To-Do)

### Module Installer/Uninstaller
- [ ] GUI: scan `modules/` and show unregistered modules
- [ ] On activation: run migrations, register in database, respect dependencies
- [ ] On deactivation:
  - Hide from menu
  - Remove widgets and routes
  - Keep data (but flag module as inactive)
- [ ] On uninstall:
  - Remove files, widgets, permissions, settings, database tables
  - Must perform dependency checks (used by other modules?)

### Dependency Management
- [ ] Implement support for declaring:
  - `depends_on` (required)
  - `compatible_with` (optional) in `module.json`
- [ ] GUI should show dependency tree before uninstall
- [ ] Optional: run composer commands to install GIT/Packagist packages

---

## ⚙️ Developer Tools (CLI)

- [ ] `taskhub:make-module` should:
  - Prompt for Livewire use (yes/no)
  - Create docs folder
  - Create example route, view, widget
  - Include uninstall/install hook stubs
- [ ] `taskhub:scan-modules`
  - Detect unregistered modules
  - Validate composer/module.json
  - Add to composer.json as "disabled"
- [ ] `taskhub:upgrade-module` (planned)
  - Git pull / composer update
  - Validate migrations
  - Show changes

---

## 🌐 Language Support

- [ ] Language files must default to Laravel's file-based structure
- [ ] GUI editor for scanning all modules and editing translations
- [ ] Allow upload/download of language file from GUI
- [ ] Avoid runtime DB language queries (performance)

---

## 📚 Meta Database

- [ ] Create a shared meta_data table to store:
  - Key-value pairs per model (e.g., for clients, tickets, etc.)
  - Track originating module for cleanup
- [ ] Allow use of custom attributes via meta (e.g., for custom fields)

---

## 🔐 Permissions

- [ ] Centralized permission registration (from module.json or boot)
- [ ] `can:` middleware still valid, but avoid hardcoding into views

---

## 🔁 Update Handling

- [ ] Update flow:
  - Sync files via Git/composer
  - Detect migration differences
  - Notify admin and allow one-click migration

---

## 🌱 Seeder / Asset Support

- [ ] Modules may include seeders
- [ ] Option to run seeders from GUI if available
- [ ] Assets to be published automatically if tagged

---

## 📦 Jobs, Events, Schedulers

- [ ] Support for event subscribers, scheduled jobs per module
- [ ] Planned registry and optional GUI overview

---

## 🧪 Testing

- [ ] Plan: `taskhub:test-module <name>` command
- [ ] Include test folder in module template

---

*This document is under continuous development.*
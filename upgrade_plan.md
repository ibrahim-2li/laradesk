# Laravel 8 to Laravel 11 + Livewire Migration Plan

## Overview

This plan covers migrating a Laravel 8 helpdesk/ticket management application from a Vue.js 2.6 SPA to Laravel 11 with Livewire. The application currently uses Vue Router for navigation, Vuex for state management, and 67+ Vue components that need to be converted to Livewire components.

**Note:** Laravel 12 does not exist yet. This plan assumes migration to Laravel 11 (latest stable version).

## Architecture Changes

### Current Architecture

- **Frontend:** Vue.js 2.6 SPA with Vue Router
- **State Management:** Vuex store
- **Routing:** Single route in `web.php` that serves `index.blade.php`, all navigation handled by Vue Router
- **API:** Separate API controllers serving JSON responses
- **Build:** Laravel Mix with webpack

### Target Architecture

- **Frontend:** Livewire components with Blade templates
- **State Management:** Livewire component properties and methods
- **Routing:** Traditional Laravel routes in `web.php`
- **API:** Converted to Livewire actions or kept as API endpoints where needed
- **Build:** Vite (Laravel 11 default) for minimal JavaScript assets

## Migration Phases

### Phase 1: Laravel Framework Upgrade

1. **Upgrade Laravel 8 → Laravel 11**

   - Update `composer.json` dependencies
   - Upgrade PHP requirement to ^8.2
   - Update framework dependencies (Sanctum, Tinker, etc.)
   - Run Laravel upgrade guide migrations
   - Update deprecated code patterns

2. **Update Configuration Files**

   - Migrate config files to Laravel 11 structure
   - Update `bootstrap/app.php` (new structure in Laravel 11)
   - Update service providers
   - Update middleware registration

3. **Update Dependencies**

   - Upgrade compatible packages or find alternatives
   - Update `tucker-eric/eloquentfilter` or replace
   - Update Sentry package
   - Update other third-party packages

### Phase 2: Install and Configure Livewire

1. **Install Livewire**

   - `composer require livewire/livewire`
   - Publish Livewire config and assets
   - Add `@livewireStyles` and `@livewireScripts` to main layout

2. **Set Up Vite**

   - Remove Laravel Mix configuration
   - Configure Vite for minimal JavaScript (Alpine.js if needed)
   - Update `package.json` scripts
   - Remove Vue.js dependencies

3. **Create Base Layouts**

   - Convert `resources/views/index.blade.php` to proper Blade layouts
   - Create `resources/views/layouts/app.blade.php` (main layout)
   - Create `resources/views/layouts/auth.blade.php` (auth layout)
   - Create `resources/views/layouts/dashboard.blade.php` (dashboard layout)
   - Create `resources/views/layouts/helpdesk.blade.php` (helpdesk layout)

### Phase 3: Convert Authentication System

1. **Auth Routes and Components**

   - Convert `resources/js/views/pages/auth/login.vue` → `app/Livewire/Auth/Login.php` + Blade view
   - Convert `resources/js/views/pages/auth/register.vue` → `app/Livewire/Auth/Register.php` + Blade view
   - Convert `resources/js/views/pages/auth/recover.vue` → `app/Livewire/Auth/Recover.php` + Blade view
   - Convert `resources/js/views/pages/auth/reset.vue` → `app/Livewire/Auth/Reset.php` + Blade view
   - Update routes in `routes/web.php` for auth pages
   - Replace Vuex auth state with Livewire session management

2. **Auth Controllers**

   - Keep or refactor `app/Http/Controllers/Api/Auth/AuthController.php`
   - Integrate auth logic into Livewire components

### Phase 4: Convert Dashboard Pages

1. **Dashboard Home**

   - Convert `resources/js/views/pages/dashboard/home.vue` → `app/Livewire/Dashboard/Home.php`
   - Convert widgets (stats, charts) to Livewire components
   - Update `app/Http/Controllers/Api/Dashboard/StatsController.php` or integrate into Livewire

2. **Tickets Management**

   - Convert `resources/js/views/pages/dashboard/tickets/list.vue` → `app/Livewire/Dashboard/Tickets/ListTickets.php`
   - Convert `resources/js/views/pages/dashboard/tickets/new.vue` → `app/Livewire/Dashboard/Tickets/CreateTicket.php`
   - Convert `resources/js/views/pages/dashboard/tickets/manage.vue` → `app/Livewire/Dashboard/Tickets/ManageTicket.php`
   - Convert filtering, pagination, and search functionality
   - Replace Vuex state with Livewire properties

3. **Orders Management**

   - Convert `resources/js/views/pages/dashboard/orders/list.vue` → `app/Livewire/Dashboard/Orders/ListOrders.php`
   - Convert `resources/js/views/pages/dashboard/orders/new.vue` → `app/Livewire/Dashboard/Orders/CreateOrder.php`
   - Convert `resources/js/views/pages/dashboard/orders/manage.vue` → `app/Livewire/Dashboard/Orders/ManageOrder.php`

4. **Canned Replies**

   - Convert `resources/js/views/pages/dashboard/canned-replies/list.vue` → `app/Livewire/Dashboard/CannedReplies/ListCannedReplies.php`
   - Convert `resources/js/views/pages/dashboard/canned-replies/new.vue` → `app/Livewire/Dashboard/CannedReplies/CreateCannedReply.php`
   - Convert `resources/js/views/pages/dashboard/canned-replies/edit.vue` → `app/Livewire/Dashboard/CannedReplies/EditCannedReply.php`

### Phase 5: Convert Admin Panel

1. **User Management**

   - Convert `resources/js/views/pages/dashboard/admin/users/list.vue` → `app/Livewire/Dashboard/Admin/Users/ListUsers.php`
   - Convert `resources/js/views/pages/dashboard/admin/users/new.vue` → `app/Livewire/Dashboard/Admin/Users/CreateUser.php`
   - Convert `resources/js/views/pages/dashboard/admin/users/edit.vue` → `app/Livewire/Dashboard/Admin/Users/EditUser.php`

2. **User Roles**

   - Convert user roles pages to Livewire components
   - Maintain permission system

3. **Departments, Branches, Labels, Brands, Stock**

   - Convert all admin CRUD pages to Livewire components
   - Follow pattern: List → `ListX.php`, New → `CreateX.php`, Edit → `EditX.php`

4. **Settings Pages**

   - Convert `resources/js/views/pages/dashboard/admin/settings/*.vue` to Livewire components
   - Each settings section becomes a Livewire component
   - Maintain settings API or integrate directly into Livewire

5. **Statuses, Priorities, Languages**

   - Convert remaining admin pages to Livewire components

### Phase 6: Convert Helpdesk (Public) Pages

1. **Public Tickets**

   - Convert `resources/js/views/pages/tickets/list.vue` → `app/Livewire/Tickets/ListTickets.php`
   - Convert `resources/js/views/pages/tickets/new.vue` → `app/Livewire/Tickets/CreateTicket.php`
   - Convert `resources/js/views/pages/tickets/manage.vue` → `app/Livewire/Tickets/ManageTicket.php`

2. **Public Orders**

   - Convert `resources/js/views/pages/orders/*.vue` to Livewire components
   - Maintain public-facing functionality

### Phase 7: Convert Reusable Components

1. **Form Components**

   - Convert `resources/js/components/forms/input-switch.vue` → Blade component or Livewire wire:model
   - Convert `resources/js/components/forms/input-select.vue` → Blade component
   - Convert `resources/js/components/forms/input-color.vue` → Blade component
   - Convert `resources/js/components/forms/image-input.vue` → Livewire file upload component
   - Convert `resources/js/components/forms/input-wysiwyg.vue` → Livewire component with TinyMCE integration

2. **Layout Components**

   - Convert `resources/js/components/layout/dashboard/sidebar.vue` → Blade component
   - Convert `resources/js/components/layout/dashboard/navbar.vue` → Blade component
   - Convert `resources/js/components/layout/dashboard/menu/*.vue` → Blade components
   - Convert `resources/js/components/layout/shared/navbar.vue` → Blade component
   - Convert `resources/js/components/layout/shared/logo.vue` → Blade component

3. **Widget Components**

   - Convert `resources/js/components/widgets/stats.vue` → Livewire component
   - Convert `resources/js/components/widgets/user-registrations.vue` → Livewire component
   - Convert `resources/js/components/widgets/opened-tickets.vue` → Livewire component

4. **Element Components**

   - Convert `resources/js/components/elements/loading.vue` → Livewire loading states
   - Convert `resources/js/components/elements/attachment.vue` → Blade/Livewire component

5. **Chart Components**

   - Convert `resources/js/components/charts/line-chart.vue` → Use Livewire with Chart.js via Alpine.js or Livewire events

### Phase 8: Replace Vue Features with Livewire/Alpine

1. **State Management**

   - Replace Vuex store with Livewire component properties
   - Move user state to session/auth
   - Move settings to Livewire properties or config cache

2. **Routing**

   - Convert Vue Router routes to Laravel routes in `routes/web.php`
   - Set up route groups for dashboard, helpdesk, admin
   - Implement middleware for authentication and authorization

3. **Notifications**

   - Replace `vue-notification` with Livewire flash messages
   - Use Laravel session flash or Livewire events

4. **i18n (Internationalization)**

   - Replace `vue-i18n` with Laravel's localization
   - Use `@lang()` and `trans()` helpers in Blade templates
   - Maintain language switching functionality

5. **File Uploads**

   - Replace Vue file upload with Livewire file uploads
   - Update `app/Http/Controllers/Api/File/FileController.php` or use Livewire's file upload

6. **Recaptcha**

   - Replace `vue-recaptcha-v3` with server-side recaptcha validation
   - Integrate into Livewire validation

7. **Charts**

   - Replace `vue-chartjs` with Chart.js directly via Alpine.js or Livewire events
   - Or use a Livewire-compatible chart package

### Phase 9: Update Routes and Navigation

1. **Web Routes**

   - Replace single catch-all route with proper route definitions
   - Organize routes by feature (auth, dashboard, admin, helpdesk)
   - Add proper middleware groups

2. **Navigation**

   - Convert Vue Router links to Laravel route helpers
   - Update all `router-link` to `route()` or `<a href>`
   - Maintain active route highlighting with Blade

3. **API Routes**

   - Keep API routes for external integrations if needed
   - Remove or consolidate API routes that are only used by Vue frontend
   - Some endpoints may become Livewire actions instead

### Phase 10: Styling and Assets

1. **CSS Framework**

   - Keep Tailwind CSS (already in use)
   - Update Tailwind config if needed for Laravel 11
   - Remove Vue-specific Tailwind plugins if any

2. **JavaScript Dependencies**

   - Remove all Vue.js dependencies from `package.json`
   - Keep only essential JS (Alpine.js if needed for interactivity)
   - Update build process to use Vite

3. **SVG Icons**

   - Convert `svg-vue` usage to inline SVG or Blade components
   - Maintain icon library structure

4. **TinyMCE**

   - Integrate TinyMCE with Livewire using wire:ignore
   - Update WYSIWYG component for Livewire

### Phase 11: Testing and Cleanup

1. **Remove Vue Files**

   - Delete `resources/js/` directory (or keep minimal JS for Alpine)
   - Remove `webpack.mix.js`
   - Clean up Vue-related config

2. **Update Tests**

   - Update feature tests to work with Livewire
   - Test Livewire components
   - Update API tests if endpoints changed

3. **Documentation**

   - Update README with new setup instructions
   - Document Livewire component structure

## Key Files to Modify

### Core Files

- `composer.json` - Update Laravel and dependencies
- `package.json` - Remove Vue, add Vite/Alpine
- `routes/web.php` - Complete rewrite with proper routes
- `routes/api.php` - Review and consolidate
- `resources/views/index.blade.php` - Convert to proper layouts
- `app/Http/Kernel.php` - Update middleware (Laravel 11 structure)
- `bootstrap/app.php` - Update to Laravel 11 structure

### New Livewire Components Structure

```
app/Livewire/
├── Auth/
│   ├── Login.php
│   ├── Register.php
│   ├── Recover.php
│   └── Reset.php
├── Dashboard/
│   ├── Home.php
│   ├── Tickets/
│   ├── Orders/
│   ├── CannedReplies/
│   └── Admin/
│       ├── Users/
│       ├── Departments/
│       ├── Branches/
│       ├── Stock/
│       ├── Labels/
│       ├── Brands/
│       ├── Statuses/
│       ├── Priorities/
│       ├── Languages/
│       ├── UserRoles/
│       └── Settings/
└── Tickets/
    ├── ListTickets.php
    ├── CreateTicket.php
    └── ManageTicket.php
```

### Blade Views Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── auth.blade.php
│   ├── dashboard.blade.php
│   └── helpdesk.blade.php
├── components/ (Blade components)
├── livewire/ (Livewire component views)
└── errors/
```

## Migration Considerations

1. **Data Persistence**: No database changes needed, only code migration
2. **Session Management**: Vuex state → Laravel session/auth
3. **Real-time Features**: Consider Livewire's polling or events for real-time updates
4. **File Uploads**: Use Livewire's file upload feature
5. **Form Validation**: Use Livewire's validation instead of Vue form validation
6. **Pagination**: Use Livewire's pagination or Laravel's paginator
7. **Search/Filtering**: Implement in Livewire component methods

## Estimated Complexity

- **67+ Vue components** to convert
- **Multiple API controllers** to review/refactor
- **Vue Router routes** to Laravel routes
- **Vuex store** to Livewire properties
- **Complex forms** with file uploads, WYSIWYG, etc.
- **Chart components** to replace
- **i18n system** to migrate

This is a substantial migration that will require careful planning and testing at each phase.

## Implementation Todos

1. **Upgrade Laravel 8 to Laravel 11**: Update composer.json, PHP requirements, framework dependencies, and run upgrade migrations
2. **Install and configure Livewire**: Add package, publish config/assets, set up base layouts (app, auth, dashboard, helpdesk)
3. **Replace Laravel Mix with Vite**: Configure Vite, update package.json, remove Vue dependencies, set up minimal JS build
4. **Convert authentication pages**: Login, Register, Recover, Reset from Vue to Livewire components with Blade views
5. **Convert dashboard home page**: Dashboard home page and widgets (stats, charts) to Livewire components
6. **Convert dashboard tickets management**: List, create, manage from Vue to Livewire with filtering and pagination
7. **Convert dashboard orders management**: List, create, manage from Vue to Livewire components
8. **Convert canned replies CRUD pages**: From Vue to Livewire components
9. **Convert admin user management pages**: List, create, edit from Vue to Livewire components
10. **Convert remaining admin pages**: Departments, branches, stock, labels, brands, statuses, priorities, languages, user roles, settings
11. **Convert public helpdesk pages**: Tickets, orders from Vue to Livewire components
12. **Convert reusable form components**: Input-switch, input-select, input-color, image-input, input-wysiwyg to Blade/Livewire components
13. **Convert layout components**: Sidebar, navbar, menu items from Vue to Blade components
14. **Replace Vue features**: Vuex → Livewire properties, Vue Router → Laravel routes, vue-notification → Livewire flash, vue-i18n → Laravel localization
15. **Rewrite routes/web.php**: Replace single catch-all route with proper route definitions organized by feature with middleware groups
16. **Update styling and assets**: Keep Tailwind, remove Vue-specific plugins, convert SVG icons, integrate TinyMCE with Livewire
17. **Cleanup and testing**: Remove Vue files, update tests for Livewire, verify all functionality works


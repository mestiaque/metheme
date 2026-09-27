# MASTER PROMPT: METHEME COMPLETE SECURITY, ARCHITECTURE, INSTALLATION, TESTING, DOCUMENTATION & CODECANYON PRODUCTION READINESS

## ROLE AND MISSION

Act as a Principal Laravel Package Architect, Senior PHP Security Engineer, Laravel Framework Specialist, QA Automation Engineer, Composer Package Maintainer, UI/UX Engineer, and CodeCanyon Product Preparation Specialist.

You have full access to my existing Metheme Laravel package repository.

Your mission is to transform the EXISTING Metheme package into a secure, professionally structured, installable, documented, maintainable, tested, and commercially presentable Laravel admin foundation suitable for preparing a CodeCanyon submission.

This is an implementation task, NOT an audit-only task.

You must inspect the existing project, understand its architecture, implement the necessary changes directly in the repository, run appropriate tests, fix discovered errors, create missing files, and provide a detailed completion report.

Do not merely tell me what needs to be done. Do the actual work.

Do not stop after producing a plan, identifying vulnerabilities, or fixing the first few issues. Continue through the entire scope, including automated tests, installation validation, documentation, and final verification.

If you encounter a genuine blocker, document it precisely, complete every independent task that can safely be completed, and provide an exact next action for the unresolved blocker.

---

# 1. PROJECT CONTEXT AND NON-NEGOTIABLE CONSTRAINTS

## 1.1 Existing project identity

The project is an existing Laravel Composer package:

* Package name: `mestiaque/metheme`
* Namespace: `ME\`
* Existing main service provider: `ME\MEServiceProvider`
* Existing package directory: `src/`
* Existing license metadata: MIT
* Existing repository: `https://github.com/mestiaque/metheme`
* Existing release tag: `v1.0.0`

The package is currently used inside my own Laravel application and has been observed working in a Laravel 12 environment.

The project is an admin module/package, not a standalone business application.

Its purpose is to provide reusable Laravel administration functionality, including:

* Authentication using email or phone.
* OTP-based registration and password recovery.
* User, role, and permission management.
* Config-driven sidebar navigation and menu search.
* Automatic activity logging.
* Settings and configuration management.
* SMTP configuration and mail logging.
* SMS gateway configuration, SMS logs, and balance checking.
* Profile management.
* Admin dashboard and reusable Blade layouts.
* English and Bangla localization.
* Light, dark, and glass-style authentication/error pages.
* Reusable Blade partials and UI helpers.

Use the existing source code as the authoritative reference for the exact functionality.

The above is the existing product scope, not permission to invent functionality that does not exist.

## 1.2 Preserve the existing package structure

My existing package structure is intentional.

I want to retain a Composer package architecture, approximately:

metheme/
├── composer.json
├── readme.md
└── src/
├── MEServiceProvider.php
├── Providers/
├── Config/
├── Http/
├── Models/
├── Services/
├── Mail/
├── Traits/
├── database/
│   └── migrations/
├── resources/
│   ├── views/
│   └── lang/
├── routes/
├── public/
└── other existing package directories

You may introduce additional directories and files when architecturally justified, such as:

* tests/
* docs/
* .github/workflows/
* bin/
* database/seeders/ or package-appropriate equivalents
* config/
* stubs/

However:

1. Preserve the package-based architecture.
2. Preserve the `src/` directory as the primary source directory.
3. Preserve the package identity `mestiaque/metheme`.
4. Preserve the `ME\` namespace wherever appropriate.
5. Preserve existing useful functionality and the existing visual identity.
6. Do not rebuild the project from scratch.
7. Do not turn the package into an unrelated standalone Laravel application.
8. Do not arbitrarily rename the package, provider, or all namespaces.
9. Do not delete functional features simply because they are difficult to maintain.
10. Avoid breaking changes unless required for security, framework compatibility, or a clearly documented architectural correction.

If a breaking change is unavoidable, provide a migration path and document it.

## 1.3 Primary technical environment

Use the existing codebase to determine exact requirements.

Known environment:

* PHP 8.4 in the development host.
* Laravel 12 in the development host.
* Laravel 13 support is currently claimed but has not been verified.
* Composer package installation through a local path repository currently works in the author's environment.
* MySQL is the primary existing database.
* The package uses Laravel Blade, Composer, migrations, service providers, middleware, controllers, and Eloquent models.

Target compatibility:

* PHP 8.2 or later, if the code can genuinely support it.
* Laravel 12 as the initial mandatory verified target.
* Laravel 13 only if it is genuinely compatible and can be tested.
* MySQL as the primary supported database.
* SQLite for automated testing where feasible.

Do not claim Laravel 13, PostgreSQL, SQLite production support, or any other compatibility unless the relevant tests have actually passed.

Before changing PHP syntax, Composer dependencies, framework APIs, or migrations, verify the supported version constraints.

---

# 2. WORKING RULES AND SAFETY

These rules apply to every phase.

## 2.1 Inspect before modifying

Start by inspecting:

* Git status.
* Current branch.
* Uncommitted changes.
* Composer metadata and dependencies.
* Directory structure.
* Existing provider registration and package discovery.
* Existing routes, middleware, migrations, configuration, models, views, helpers, assets, and translations.
* Existing README and release tags.
* Existing tests, if any.
* Any existing backup or developer notes.

Create a written implementation plan and save it as:

docs/IMPLEMENTATION_PLAN.md

Then execute the plan without waiting for additional approval for ordinary, reversible source-code changes.

Do not overwrite existing user work or discard uncommitted changes.

If the working tree contains unrelated changes, preserve them and avoid modifying those areas unless necessary.

## 2.2 Never perform destructive production operations

Do not:

* Connect to a production database.
* Run migrations against a real customer or production database.
* Truncate or delete existing application tables.
* Reset or force-push the public Git repository.
* Rewrite Git history.
* Delete remote repositories or tags.
* Deploy to a server.
* Publish a package to Packagist or CodeCanyon.
* Send actual SMS or emails to real customers.
* Print secrets, OTPs, passwords, API keys, SMTP credentials, or personal information in reports.

Use an isolated test environment and synthetic test data.

Never execute the package's existing Clear Data functionality during development.

Any schema testing must use a disposable database created specifically for testing.

## 2.3 Backups and Git

Before modifications:

1. Record the current Git status.
2. Identify the current branch and commit.
3. Create a new working branch if safe and possible.
4. Preserve all existing uncommitted changes.
5. Never overwrite the original branch or reset the repository.

Suggested branch:

`feature/codecanyon-production-readiness`

If the environment is not a Git repository or branch creation is not possible, continue safely without destructive Git operations.

Do not commit or push changes unless explicitly authorized.

## 2.4 Evidence-based implementation

Do not assume that a feature exists simply because the README claims it.

Use actual code to determine the correct implementation.

Do not invent successful test results.

If a command cannot run, record:

* The exact command.
* The reason it could not run.
* The environment or dependency needed.
* The next step required to complete validation.

Distinguish:

* Implemented and verified.
* Implemented but not verified.
* Partially implemented.
* Blocked.
* Not implemented.

---

# 3. PHASE 1 — COMPLETE SOURCE-CODE AND ARCHITECTURE AUDIT

Inspect the entire existing package before major modifications.

Read every PHP file, Composer configuration, service provider, route file, migration, config file, model, middleware, helper, and relevant Blade template.

Inspect JavaScript, CSS, SVGs, images, and third-party assets sufficiently to identify security issues, licensing concerns, broken references, and runtime dependencies.

Create:

`docs/ARCHITECTURE.md`

Document:

1. Current directory structure.
2. Service-provider lifecycle.
3. Composer package discovery.
4. Route registration and middleware.
5. Authentication flow.
6. Authorization and permission flow.
7. User and role model relationships.
8. Sidebar and menu-search architecture.
9. Settings and configuration loading.
10. Database tables and relationships.
11. Mail and SMS services.
12. Blade layout inheritance.
13. Public asset handling.
14. Localization.
15. Existing duplicated or unused implementations.

Identify every relevant code path before changing it.

The audit report previously identified the following high-priority issues. Treat them as initial investigation findings that must be independently verified against the current checkout.

---

# 4. PHASE 2 — CRITICAL SECURITY REMEDIATION

This phase has the highest priority.

Do not proceed to marketplace packaging while known critical vulnerabilities remain unresolved.

## 4.1 Remove the hard-coded super-admin backdoor

Investigate:

`src/database/migrations/0001_01_01_000001_create_users_and_roles_table.php`

And all associated code in:

* User models.
* Role models.
* Global scopes.
* Model boot methods.
* Authentication authorization.
* Gate registration.
* User and role controllers.
* Seeders and factories.
* Other migrations.

The current audit reports a hidden `encodex` role and a pre-seeded super-admin account containing a hard-coded password, personal email, and phone number.

Implement all of the following:

1. Remove hard-coded personal credentials from the source code and migrations.
2. Remove automatic creation of a known super-admin account.
3. Remove hard-coded passwords, email addresses, and phone numbers from all installation paths.
4. Remove hidden global scopes that conceal a privileged account or role.
5. Remove undeletable privileged accounts implemented through hidden model events or controller exceptions.
6. Remove any authentication bypass that grants unrestricted access solely because a user has the `encodex` role.
7. Do not preserve any undocumented developer backdoor.
8. Do not introduce a hidden support account, remote access mechanism, default credential, or master password.

A buyer must create their own initial administrator during installation.

Implement a secure first-administrator installation mechanism.

Recommended:

`php artisan metheme:install`

The installer should:

* Check that Metheme is installed correctly.
* Verify that the database is accessible.
* Verify that package migrations have been completed.
* Check whether an administrator already exists.
* Create the initial administrator only when explicitly requested.
* Prompt for the administrator's name, email, optional phone, and password.
* Confirm the password.
* Validate all inputs.
* Hash the password using Laravel's hashing system.
* Assign only the required initial administrative permissions.
* Prevent unauthorized or accidental recreation of a privileged administrator.
* Avoid exposing credentials in command output or logs.
* Avoid storing a plaintext default password anywhere.

If interactive installation is not available, provide a secure documented alternative.

Do not silently create an administrator during ordinary package discovery or migration.

The installer must not be publicly accessible through an unauthenticated HTTP route.

If the first-admin creation endpoint or command is rerun, it must not allow privilege escalation or takeover.

### Important credential remediation

The previous audit indicates that a password was published in a public Git repository.

Do not reproduce that password.

Do not attempt to use it.

Do not assume removing it from the latest code removes it from Git history.

Add a clearly documented security incident note for the package owner explaining that the exposed credential must be rotated anywhere it was reused.

Do not rewrite public Git history or force-push without explicit authorization.

Do not expose the credential in the generated documentation, changelog, tests, logs, or final report.

## 4.2 Fix OTP registration vulnerabilities

Inspect:

`src/Http/Controllers/Auth/AuthController.php`

And the OTP routes, mail service, SMS service, OTP storage, registration forms, and related configuration.

Remove any response field such as:

`otp_debug`

or any equivalent that returns a real verification code to the client.

OTP values must never appear in:

* HTTP responses.
* HTML source.
* Browser-visible JavaScript.
* Application logs.
* Exception messages.
* Debug API responses.
* Publicly accessible storage.

Implement secure OTP verification.

Requirements:

1. Use `random_int()` or an appropriate cryptographically secure generator.
2. Generate OTPs with sufficient entropy.
3. Store only a securely hashed OTP where practical.
4. Bind each OTP challenge to the exact intended identity, purpose, and verification session.
5. Bind registration OTPs to the email or phone address being verified.
6. Prevent an OTP issued for one identity from verifying another identity.
7. Prevent an OTP issued for registration from being reused for password recovery.
8. Set a configurable short expiry.
9. Enforce maximum verification attempts.
10. Enforce a resend cooldown.
11. Enforce per-identity, per-IP, and appropriate endpoint throttling.
12. Invalidate a challenge after successful verification.
13. Invalidate stale challenges when a new challenge is created.
14. Prevent replay.
15. Avoid user enumeration through inconsistent responses.
16. Use transactions or appropriate atomic operations to prevent race conditions.

Add feature tests proving that a registration OTP cannot be disclosed, reused, replayed, or applied to a different identity.

Use fake mail and SMS transports during tests.

## 4.3 Fix password-reset account takeover

The previous audit found a reset-verification flag that can remain true when the reset identity changes.

Do not patch only one line. Redesign the password-reset state so that verification is cryptographically and logically bound to the exact account and reset challenge.

Requirements:

1. A successful OTP verification must authorize resetting only the account whose identity was verified.
2. A new password-reset request must invalidate previous verification state.
3. Changing the reset identity must invalidate previous authorization.
4. The reset token/challenge must be one-time-use.
5. The token must expire.
6. Attempts must be limited.
7. Rate limiting must apply to all reset endpoints.
8. Reset tokens must not be exposed in logs or public responses.
9. Reset requests must not disclose whether an account exists.
10. Successful password resets must invalidate appropriate existing sessions and remember-me tokens.
11. The implementation must be compatible with Laravel's supported authentication mechanisms.

Consider using Laravel's password broker where appropriate, or a carefully designed package-specific reset challenge.

Do not preserve the vulnerable stateful flag design.

Create tests for:

* Resetting the user's own password.
* Attempting to change the identity after OTP verification.
* Requesting a second reset after verification.
* Replaying a consumed token.
* Expired tokens.
* Excessive attempts.
* Unauthorized reset requests.
* Concurrent reset requests.
* Session invalidation after a successful reset.

## 4.4 Add comprehensive OTP and authentication rate limiting

Apply Laravel throttling to:

* Login.
* Registration.
* Registration OTP sending.
* Registration OTP verification.
* Forgot password.
* Password-reset OTP sending.
* Password-reset OTP verification.
* Password reset submission.
* SMS test endpoints.
* Mail test endpoints where appropriate.
* Any public authentication or verification endpoints.

Use named, configurable rate limiters where suitable.

Do not rely only on frontend disabling or JavaScript cooldowns.

Use server-side validation and server-side throttling.

Ensure throttling behaves correctly behind trusted proxies and does not trust arbitrary client-supplied IP headers.

## 4.5 Enforce registration and password-recovery settings

The current audit reports that GET pages check feature toggles but POST handlers do not.

Inspect:

* `enable_registration`
* `enable_forget_password`
* Related authentication settings.

Enforce the settings inside every relevant server-side POST handler and service method.

If a feature is disabled, its submission endpoints must reject requests regardless of whether the user bypasses the UI.

Add tests for both enabled and disabled states.

Ensure settings cannot be bypassed by direct HTTP requests.

## 4.6 Fix activity-log authorization and privacy

Inspect all activity-related routes and controllers.

The current audit reports that activity records can be viewed, exported, and queried without adequate authorization.

Implement:

1. Explicit authorization for activity list pages.
2. Explicit authorization for detail pages.
3. Explicit authorization for CSV export.
4. Explicit authorization for statistics.
5. Explicit authorization for device/session management.
6. Appropriate permission declarations and middleware.
7. Query-level filtering and pagination.
8. Streaming or chunked CSV export for large datasets.
9. Protection against CSV formula injection.
10. Appropriate escaping and output encoding.
11. Protection of IP addresses, user agents, and sensitive request metadata.
12. A documented retention policy or configurable pruning mechanism.

Users must not be able to view other users' activity unless explicitly authorized.

Do not store passwords, OTPs, tokens, authorization headers, cookies, or secret form values in activity logs.

Add feature tests for unauthorized users, authorized administrators, export permissions, and data isolation.

## 4.7 Fix privilege escalation through role assignment

Inspect both user-management implementations:

* `UserController`
* `UsersController`
* `RoleController`
* `RolesController`

The current audit reports that user creation and editing may accept hidden privileged role IDs.

Implement:

1. Remove hidden role privileges.
2. Ensure all assignable roles are explicitly visible and authorized.
3. Scope role validation to roles the acting user is allowed to assign.
4. Prevent users from granting permissions they do not possess.
5. Prevent a non-super-administrator from granting unrestricted administrator privileges.
6. Prevent unauthorized self-escalation.
7. Protect the last active administrator from accidental removal or demotion, with a documented, safe recovery process.
8. Validate role and permission changes on the server, not just in the form.

Do not rely on a global role ID or positional permission index to establish privilege.

Create tests for horizontal and vertical privilege escalation.

## 4.8 Fix deactivated-user session handling

The current audit reports that `CheckUserActive` is not registered.

Implement a reliable solution that prevents deactivated, suspended, or deleted users from continuing to access protected routes.

Ensure:

* The middleware is registered correctly.
* It checks the authenticated account's current status.
* It handles remember-me sessions appropriately.
* It does not block guests or unrelated public routes.
* It logs out or rejects inactive users safely.
* It is tested across the supported Laravel versions.

If a user is deactivated, they must not retain access merely because their browser session remains valid.

## 4.9 Fix the destructive Clear Data feature

Inspect:

`DataController::clearData`

The current implementation reportedly truncates nearly every table in the connected database.

Remove this dangerous behavior from the buyer-facing package.

Do not implement an unrestricted database wipe.

If data-clearing functionality is retained for legitimate package maintenance, it must:

* Be restricted to an explicitly enumerated set of package-owned tables.
* Never truncate host application tables.
* Never remove host users, orders, financial data, or unrelated package data.
* Require explicit authorization.
* Require strong confirmation.
* Use transactions where possible.
* Clearly identify exactly which records will be affected.
* Have feature tests proving host tables are never touched.

Prefer removing the feature if it has no essential use case.

---

# 5. PHASE 3 — AUTHORIZATION AND ROLE/PERMISSION ARCHITECTURE

Create a consistent, documented, extensible RBAC system.

The audit found undeclared permission keys, duplicate user/role stacks, and permission inconsistencies.

## 5.1 Create a single source of truth

Inspect:

* `src/Config/permissions.php`
* User and role models.
* Authorization middleware.
* Gate registration.
* Permission forms.
* Sidebar definitions.
* Every controller using `can`, `hasPermission`, middleware, or manual permission checks.

Build a complete inventory of every permission referenced anywhere in the package.

Declare all required permission keys in the appropriate configuration.

At minimum, review and resolve every key identified in the audit, including:

* `me_user.*`
* `me_role.*`
* `user.*`
* `role.*`
* `me.dashboard`
* `me.theme`
* `me.clearData`
* `me.mailLayoutPreview`
* `me_menus.view`
* `me_activity.view`

Do not automatically grant every permission to every role.

Do not silently convert missing permissions into unrestricted access.

Undefined permissions should fail closed.

Provide a clear mechanism for package developers to extend the permission configuration.

## 5.2 Consolidate duplicate implementations

The audit identified duplicate implementations of:

* User versus Users.
* Role versus Roles.
* UserController versus UsersController.
* RoleController versus RolesController.

Inspect all dependencies and routes before consolidation.

Choose a canonical implementation for each domain.

Consolidate duplicated business logic while preserving compatibility where practical.

Do not delete a controller or model before identifying its references.

If backwards-compatible wrappers or aliases are required, implement and document them.

Ensure that all user-management routes use the same authorization rules and data access logic.

Ensure role-management behavior is consistent across every route.

## 5.3 Implement a secure permission service

Create or improve a dedicated permission service if appropriate.

Requirements:

* Consistent permission checking.
* Clear handling of roles and permissions.
* No implicit administrator backdoor.
* No privilege escalation through permission IDs.
* No positional permission identifiers that change when configuration ordering changes.
* Safe caching with invalidation after role/permission changes.
* Avoid unnecessary database queries.
* Support Laravel Gate and middleware integration.
* Support configurable permission definitions.

Keep permission evaluation testable.

Document how a host application can declare new permissions.

## 5.4 Fix route authorization comprehensively

Create an inventory mapping:

`Route → HTTP method → Controller/action → Middleware → Required permission`

Review every protected route.

Ensure authorization is applied at the server-side route/controller/policy/service level.

A hidden menu item is not an authorization mechanism.

A user must not access a protected URL simply by typing it manually.

Do not accidentally make package APIs, file endpoints, admin endpoints, or exports public.

Add tests that assert unauthorized requests receive the appropriate response.

---

# 6. PHASE 4 — FRESH LARAVEL INSTALLATION AND PACKAGE COMPATIBILITY

This phase is mandatory.

Metheme must be installable into a clean Laravel application without depending on the author's manually customized host application.

## 6.1 Composer architecture

Inspect and correct `composer.json`.

Ensure:

* Package name is `mestiaque/metheme`.
* Package type is appropriate for a Laravel package.
* PSR-4 mappings are correct.
* Laravel package auto-discovery works.
* Provider class paths are correct.
* Dependencies match actual framework usage.
* PHP constraints match actual syntax and APIs.
* The package can be installed using Composer.
* Composer optimized autoload generation does not produce PSR-4 warnings.
* Composer validation passes.

The audit reports the following possible PSR-4 problems:

* `src/Http/Helpers/PermissionHelper.php` declares `ME\Helpers`.
* `GeneratePackageController.php` declares `App\Http\Controllers`.
* `LoginRequestOld.php` declares a duplicate `LoginRequest`.

Verify each against the current source and fix every actual violation.

Remove obsolete duplicate classes after confirming they are not required.

Ensure package code never declares classes under the host application's `App\` namespace.

Remove dependencies used only by dead code, including `ext-zip` and `monolog/monolog`, if they are genuinely unnecessary.

Do not remove dependencies that are required at runtime.

The audit reports that `illuminate/*` dependencies are declared even though the package uses classes supplied by `laravel/framework`, including:

* `Illuminate\Foundation\Auth\User`
* `Illuminate\Foundation\Http\FormRequest`
* Framework-specific service providers.
* Host application's base controller classes.

Choose a correct dependency strategy.

If the package requires a complete Laravel framework, declare the appropriate dependency or otherwise demonstrate a valid supported dependency structure.

Do not claim that a set of Illuminate component packages provides classes that they do not contain.

Test dependency resolution in an isolated Composer project.

## 6.2 Fresh Laravel 12 installation

Create a disposable Laravel 12 test application.

Do not use my actual development application's database or environment file.

Install Metheme through the intended customer-facing installation procedure.

Test the full lifecycle:

1. Install Laravel.
2. Add Metheme as a Composer dependency.
3. Discover the package provider.
4. Publish package assets and configuration.
5. Run database migrations.
6. Create the first administrator.
7. Sign in.
8. Access the dashboard.
9. Use roles and permissions.
10. Access user-management pages.
11. Access settings.
12. Test mail configuration with a fake or local transport.
13. Test SMS functionality with a fake gateway.
14. Test logout.
15. Test password recovery.
16. Test deactivated users.
17. Run `php artisan route:list`.
18. Run `php artisan config:cache`.
19. Run `php artisan route:cache` where compatible.
20. Run `php artisan view:cache`.
21. Clear and rebuild caches.
22. Verify package assets after a fresh publish.
23. Verify no host application's unrelated functionality is broken.
24. Verify clean uninstall and upgrade guidance.

Fix every failure discovered.

Do not mark installation complete if the process has only been inspected but not actually executed.

## 6.3 Migration collision prevention

The audit reports that Metheme migrations create tables already created by a standard Laravel installation:

* `users`
* `password_reset_tokens`
* `sessions`
* `jobs`
* `failed_jobs`

Inspect all package migrations and their relationships.

Design an installation strategy that does not collide with standard Laravel migrations.

Do not blindly use `Schema::hasTable()` to skip arbitrary migrations if that would leave required columns or indexes missing.

Do not alter or replace the host application's default users table without an explicit, documented decision.

Prefer a package architecture that separates package-owned tables from host-owned tables.

Where the package genuinely needs to integrate with the host's users table, provide a documented and configurable approach.

Consider:

* Configurable package table names or prefixes.
* Dedicated package tables.
* Explicit user-model integration.
* Published migration stubs.
* A documented optional integration migration.

The correct choice must be based on the existing models, foreign keys, authentication architecture, and compatibility requirements.

Test migrations from a clean Laravel database.

Test repeated migration commands and upgrade paths.

Never drop or overwrite an existing host table.

## 6.4 Authentication model integration

The current audit reports that the package assumes its own `ME\Models\User` model and that publishing the package authentication config overwrites the host's entire `config/auth.php`.

Fix this architecture.

Do not require buyers to replace their entire authentication configuration merely to install Metheme.

Do not overwrite the host's auth configuration automatically.

Provide a configurable and documented authentication integration mechanism.

Possible approaches include:

* A configurable Metheme user model.
* A documented contract or interface.
* A trait that host user models can implement.
* A package-owned authentication guard where appropriate.
* An explicitly published, minimal package config.

Choose the approach that best fits the existing package while maintaining compatibility.

The host application must retain control over its own default authentication providers and guards.

Ensure that authorization does not fatally fail when the host uses `App\Models\User`.

If Metheme requires a custom user model, validate that requirement during installation and explain it clearly.

## 6.5 Safe route registration and namespacing

The audit identifies possible collisions with common Laravel authentication packages.

Review:

* `/login`
* `/register`
* `/logout`
* `/password`
* `/forget-password`
* `/menu-search`
* `/language/{locale}`
* `/admin/*`
* `/me/*`
* `/my/*`

Review route names including:

* `login`
* `register`
* `logout`
* `password.update`

Create a configurable route prefix and a documented route-registration strategy.

Preserve existing route names and paths when feasible, but provide a clean integration path for host applications that already use Breeze, Jetstream, Fortify, or their own authentication routes.

Avoid registering conflicting routes unnecessarily.

Support disabling package authentication routes if the host uses its own authentication system, provided the package can function securely in that configuration.

Ensure route names, redirects, middleware, and generated URLs remain correct when the route prefix is changed.

Test the default installation and a customized prefix.

## 6.6 Safe model and table integration

Review potential table conflicts involving:

* `roles`
* `settings`
* `menus`
* `sms_logs`
* Other package tables.

Do not assume these names are exclusive to Metheme.

Provide a reliable and documented strategy for package table naming or integration.

If table prefixes are configurable, ensure all models, migrations, relationships, foreign keys, queries, and services use the configured names consistently.

Do not implement a configuration that changes the table name in one model but leaves hard-coded names in raw queries or migrations.

Test custom table naming if implemented.

## 6.7 Safe asset publishing

The audit reports that the existing asset publishing process copies the whole package public directory into the host application's public directory, including:

* `index.php`
* `.htaccess`
* `robots.txt`

This is unacceptable.

Change asset publishing so Metheme assets are installed only under a dedicated directory, such as:

`public/vendor/metheme/`

Publish only intended static assets.

Do not publish package `index.php`, `.htaccess`, unrelated robots files, or unrelated application entry points.

Do not overwrite existing host assets.

Review and fix every asset reference in Blade templates and CSS/JavaScript.

The package must work when installed into a clean application and when its assets have not yet been published.

Do not allow `filemtime()` calls on missing files to crash the admin layout.

Use safe asset versioning and fallbacks.

If a package asset is missing, the page should produce a clear installation warning or use a safe fallback instead of throwing an exception.

Ensure asset URLs work with:

* A custom application URL.
* A subdirectory installation where supported.
* A configurable route prefix.
* Laravel's asset URL configuration.

Create tests or documented integration checks for asset publishing.

## 6.8 Fix redirects and route references

The audit reports that login redirects to `/admin/dashboard`, while the actual dashboard route is `/me`.

Find every hard-coded route, URL, redirect, menu target, and link that may not exist.

Use named routes or configurable route definitions wherever practical.

Ensure all login, logout, registration, password-reset, dashboard, and error redirects resolve correctly.

Do not hard-code the author's host application's route structure into the package.

---

# 7. PHASE 5 — SERVICE PROVIDER, CONFIGURATION, AND LARAVEL BEST PRACTICES

Review and improve `MEServiceProvider.php`.

Keep it focused on package bootstrapping and dependency registration.

## 7.1 Service provider responsibilities

Review:

* Route loading.
* Migration loading.
* View namespaces.
* Translation namespaces.
* Asset publishing.
* Configuration merging.
* Middleware aliases.
* Gate registration.
* Mail event listeners.
* Settings loading.
* SMS configuration.
* Database access during boot.

Remove unnecessary work from the application boot lifecycle.

Do not run database queries unconditionally during package discovery or when the database is unavailable.

Do not cause `php artisan package:discover`, `config:cache`, or `route:cache` to fail because package tables have not yet been migrated.

Use appropriate service classes, lazy initialization, events, and cached configuration.

Do not register duplicate listeners or middleware aliases.

Ensure package discovery is idempotent.

## 7.2 Configuration publishing

Provide clean, publishable configuration files for:

* General Metheme configuration.
* Routes and route prefixes.
* Authentication integration.
* Permissions.
* Sidebar.
* Table naming, if supported.
* Mail and SMS.
* Localization.
* Asset paths.
* Installation settings.

Use Laravel's standard configuration conventions.

The host application must be able to override package defaults without editing files inside `vendor/`.

Ensure published configuration does not contain real secrets.

Use safe placeholders and environment variables where appropriate.

Do not expose secret configuration values in public config endpoints or logs.

Do not automatically overwrite existing host configuration files.

## 7.3 Remove runtime env() misuse

Find every `env()` call outside configuration files.

The audit identified runtime usage in authentication OTP handling and Telegram-related code.

Move environment access into configuration files.

Use `config()` throughout application code.

Ensure functionality works after:

`php artisan config:cache`

Remove unused environment variables and obsolete services if their associated functionality is removed.

## 7.4 Optimize settings and permission queries

The current implementation reportedly queries settings repeatedly and checks role permissions with repeated database queries.

Implement appropriate caching and eager loading.

Requirements:

* Avoid N+1 permission queries.
* Cache settings where appropriate.
* Invalidate caches after changes.
* Ensure different users do not share unauthorized cached permissions.
* Avoid stale authorization after role changes.
* Handle cache failures safely.
* Support Laravel cache drivers.
* Avoid requiring Redis for basic functionality.

Add tests for permission cache invalidation and settings updates.

Do not sacrifice authorization correctness for performance.

---

# 8. PHASE 6 — MAIL, SMS, OTP DELIVERY, AND EXTERNAL SERVICES

## 8.1 Mail service

Review the database-stored SMTP configuration.

Maintain encryption of sensitive mail credentials.

Ensure:

* Credentials are never returned in plain text.
* Credentials are not included in logs.
* Configuration updates are validated.
* SMTP test messages are safe and authorized.
* Database values cannot unexpectedly override unrelated mail settings.
* Runtime configuration works with config caching.
* Mail failures do not expose secrets.
* Mail logs contain useful metadata without storing sensitive bodies.

Use Laravel's mail configuration and transport conventions where appropriate.

Provide a fake mail transport for automated tests.

## 8.2 SMS service abstraction

The audit reports that the SMS implementation is tightly coupled to one Bangladesh-specific gateway protocol.

Preserve existing Bangladesh gateway functionality, but isolate it behind a clean service or driver interface.

The architecture should allow future gateways to be added without rewriting authentication controllers.

Provide:

* A gateway contract or interface.
* A configurable default gateway.
* A properly validated gateway implementation.
* A clear error-handling strategy.
* Safe timeout and retry behavior.
* Secret encryption.
* Request validation.
* Phone-number normalization and configurable validation.
* Fake gateway support for tests.

Do not invent unsupported gateway APIs.

Do not send real SMS during automated tests.

Make country-specific phone validation configurable.

Preserve existing Bangladesh use cases.

If a global phone-number library is required, select a maintained compatible dependency and document it.

## 8.3 External HTTP request security

Review every outbound HTTP request.

In particular, inspect:

* SMS gateway URLs.
* Gateway balance checks.
* Mail tests.
* Any admin-configurable URL.
* Telegram-related code if retained.

Prevent server-side request forgery where administrators or lower-privileged users can configure URLs.

Validate allowed schemes and hosts where appropriate.

Block loopback, private, link-local, and cloud metadata addresses unless an explicitly safe and documented local testing configuration permits them.

Do not allow user-controlled URLs to reach arbitrary internal services.

Use timeouts and safe exception handling.

Never log credentials embedded in URLs.

---

# 9. PHASE 7 — FILE UPLOADS, XSS, CSRF, AND WEB SECURITY

Perform a systematic security review of all controllers, requests, Blade views, JavaScript, and file-serving routes.

## 9.1 Upload security

Inspect every upload endpoint, including:

* Profile photos.
* User photos.
* Application logos.
* Shop logos.
* Favicon/icon uploads.
* Attachments.
* Any other image or document upload.

Do not trust `getClientOriginalExtension()` or the client-provided MIME type.

Implement:

1. Server-side file validation.
2. File size limits.
3. Allowed extension and MIME combinations.
4. Server-side content validation where practical.
5. Randomized safe filenames.
6. Safe storage paths.
7. Prevention of executable file uploads.
8. Prevention of path traversal.
9. Protection against malicious SVG uploads.
10. Appropriate access controls for private files.
11. Safe download response headers.

Do not allow arbitrary SVG uploads into publicly executable or script-enabled contexts.

If SVG uploads are necessary, use a robust sanitizer or convert uploaded icons to safe raster formats.

Never trust a filename, extension, or MIME type supplied by the browser.

Add feature tests for malicious uploads and invalid content.

## 9.2 Stored and reflected XSS

Search for:

* `{!! ... !!}`
* `innerHTML`
* Dynamic URL assignment.
* Unescaped output.
* User-controlled attributes.
* Mail-template HTML.
* Configurable sidebar URLs.
* User-provided names and descriptions.
* Any stored content rendered into HTML or JavaScript.

The audit specifically identifies menu URL handling in `guestMaster.blade.php`.

Reject dangerous schemes such as `javascript:` in menu URLs.

Validate URL protocols and use safe URL handling.

Ensure all user-generated content is escaped unless there is a specific, sanitized HTML requirement.

Do not insert untrusted strings into JavaScript using unsafe Blade interpolation.

Use appropriate JSON encoding for JavaScript data.

Sanitize trusted HTML inputs using a maintained library where needed.

Do not remove legitimate formatting functionality without providing a safe replacement.

## 9.3 CSRF and request validation

Review every state-changing endpoint.

Ensure:

* CSRF protection is applied to web forms.
* State changes do not occur through GET requests.
* Controllers use validation requests or equivalent validation.
* Authorization happens before sensitive operations.
* Mass assignment is controlled.
* Sensitive attributes cannot be modified through untrusted requests.
* Destructive operations have explicit authorization.
* File endpoints cannot expose arbitrary files.

Do not bypass Laravel's CSRF protection to make tests or UI behavior easier.

## 9.4 Session and cookie security

Review session handling, remember-me behavior, logout, password updates, session invalidation, and device logout.

Ensure:

* Session identifiers are regenerated after login and privilege changes.
* Logout invalidates the session and regenerates the CSRF token.
* Password changes invalidate appropriate sessions.
* Device logout affects only the intended session/device.
* Cookie security is compatible with HTTPS deployments.
* Session configuration is not silently weakened by the package.

Do not override host session settings without a documented reason.

---

# 10. PHASE 8 — DATABASE, MODELS, AND DATA INTEGRITY

Inspect every migration, model, relationship, index, and query.

## 10.1 Database ownership

Clearly separate package-owned tables from host-owned tables.

Never modify or delete host tables as an installation side effect.

Document every table the package creates.

Document any required changes to a host user model or existing tables.

Use appropriate foreign keys, indexes, and deletion rules.

Avoid cascading deletion of unrelated customer data.

## 10.2 Migration quality

Review:

* Table names.
* Column types.
* Nullability.
* Defaults.
* Unique constraints.
* Foreign keys.
* Indexes.
* Rollback behavior.
* Upgrade behavior.

Ensure migration filenames and ordering are correct.

Do not assume that a migration has run just because its table exists.

Ensure repeated migration commands are safe.

If an upgrade requires a data migration, implement it carefully and document it.

Test migration rollback only in a disposable database.

Do not drop host-owned tables in rollback operations.

## 10.3 Model consistency

Review all Eloquent models for:

* Table naming.
* Primary keys.
* Fillable/guarded attributes.
* Relationships.
* Casts.
* Scopes.
* Soft deletes.
* Events.
* Authorization-related queries.

Remove duplicate model implementations where possible.

Use explicit relationships and correct foreign keys.

Ensure all package-owned database queries use the correct configurable table names if table prefixes are supported.

Prevent mass assignment of privileged fields.

Avoid N+1 queries in lists and dashboards.

---

# 11. PHASE 9 — CLEANUP AND ARCHITECTURAL REFACTORING

After the security-critical changes and tests are in place, clean the package without unnecessarily changing its public interface.

Investigate the following reported leftovers:

* `GeneratePackageController`
* `TelegramBotService`
* `PermissionMiddleware`
* `CheckUserActive`
* `HasPermissions`
* `LoginRequestOld`
* Duplicate controllers and models.
* Duplicate "copy" files.
* Unused service providers.
* Unused config files.
* Unused Composer dependencies.
* Loan Summary or unrelated business logic.
* Shop/POS-specific settings unrelated to Metheme's admin foundation.
* Hard-coded author branding.
* Unused API routes.
* Broken resource routes.
* Unused JavaScript and CSS.
* Unused assets.

For each item:

1. Search all references.
2. Determine whether it is used.
3. Determine whether another package depends on it.
4. Remove or refactor only when safe.
5. Document any removed public API.

Do not delete useful compatibility code merely because it looks old.

Do not include another developer's package or host application code.

Keep the package focused on reusable Laravel administration.

## 11.1 Menus CRUD

Inspect the database-backed Menus module.

The audit reports that resource routes exist for methods that may not be implemented.

Either:

* Implement the missing methods properly, including authorization and validation; or
* Restrict the resource routes to supported operations.

Validate menu URLs and labels.

Prevent stored XSS and unauthorized menu manipulation.

Clarify the relationship between the database menu table and the config-driven sidebar.

Do not pretend that the database menu table controls the sidebar if it does not.

If full dynamic sidebar support is implemented, make it optional, secure, and documented.

---

# 12. PHASE 10 — UI/UX AND PRODUCT PRESENTATION

Preserve Metheme's existing visual identity and useful components while improving consistency.

The existing UI uses AdminLTE, Bootstrap, Blade, and glass/dark layouts.

Do not redesign everything unnecessarily.

## 12.1 Dashboard

Replace personal branding and author-specific content with a neutral, brandable admin dashboard.

The dashboard must not contain:

* My personal name or email.
* My personal phone number.
* My private branding as a forced default.
* Links to unrelated projects.
* Personal promotional content that buyers cannot configure.

Create a clean dashboard with useful admin widgets based only on real package data.

Potential widgets:

* User count.
* Role count.
* Recent activity.
* Recent mail/SMS status.
* System or package information.

Do not create fake statistics.

Use efficient queries and permission checks.

If widgets are unavailable or there is no data, show a clean empty state.

Allow the host application to customize the dashboard or replace its content.

## 12.2 UI consistency

Review the entire admin interface for:

* Broken links.
* Incorrect routes.
* Missing translation keys.
* Inconsistent form validation.
* Inconsistent buttons and alerts.
* Missing empty states.
* Pagination problems.
* Mobile responsiveness.
* Accessibility.
* RTL behavior if claimed.
* Dark/light layout consistency.

Fix actual defects.

Avoid unnecessary cosmetic changes that could break existing layouts.

## 12.3 Bootstrap and frontend dependency cleanup

The audit reports that the package mixes Bootstrap 4 and Bootstrap 5 dependencies and uses older Chart.js and AdminLTE release-candidate assets.

Inventory all frontend dependencies.

Determine which Bootstrap version is actually used by each layout.

Choose a consistent strategy that minimizes breaking changes.

If upgrading is necessary:

1. Identify all affected templates and scripts.
2. Update incompatible markup and JavaScript.
3. Verify forms, dropdowns, modals, tables, alerts, and navigation.
4. Remove duplicate framework loading.
5. Verify dark and glass layouts.

Use stable production releases where possible.

Do not blindly upgrade major frontend frameworks without testing the affected pages.

Replace unpinned CDN dependencies such as `toastr/latest`.

Prefer properly bundled or version-pinned assets.

Where CDN dependencies remain, document them and use Subresource Integrity hashes when supported.

Do not include external scripts from untrusted domains.

---

# 13. PHASE 11 — LOCALIZATION AND BRANDING

## 13.1 English and Bangla localization

Inspect all translation files and translation keys.

The audit reports approximately 210 English keys missing from Bangla translations.

Verify the exact number and identify missing or inconsistent translations.

Complete the Bangla translations for the actual UI.

Ensure that:

* No user-facing strings are unnecessarily hard-coded.
* Validation messages are localized where feasible.
* Menu labels support translations.
* Dates and numbers are formatted consistently.
* Bangla numerals do not corrupt database values or machine-readable fields.
* English remains the default fallback.

Do not translate technical route names, database fields, permission keys, or API identifiers.

## 13.2 Branding and white-label customization

Remove forced personal branding from the default experience.

Make the following configurable where appropriate:

* Product name.
* Application name.
* Logo.
* Favicon.
* Footer text.
* Support URL.
* Brand colors.
* Login-page branding.
* Dashboard branding.

Use neutral defaults.

Do not include my private contact details in default metadata.

Ensure configuration and upload paths are safe.

---

# 14. PHASE 12 — THIRD-PARTY LICENSES AND INTELLECTUAL PROPERTY

The product is intended for commercial distribution.

Audit all bundled frontend libraries, icons, fonts, images, photos, logos, and other assets.

The previous audit identified potential issues involving:

* AdminLTE.
* Bootstrap.
* Font Awesome.
* jQuery.
* Chart.js.
* DataTables.
* Start Bootstrap Agency.
* Brand-logo SVGs.
* Unknown login background images.
* `hauntedHouse` assets.
* Map images.
* `Encodex_*` images.
* Favicon font licensing.
* Personal branding assets.

Do not assume an asset is commercially redistributable merely because it is publicly accessible.

For each third-party component, record:

* Name.
* Version.
* Original source.
* License.
* Required attribution.
* Commercial redistribution terms.
* Whether modifications were made.
* Whether the actual bundled copy has been verified.

Create:

* `THIRD_PARTY_LICENSES.md`
* `docs/THIRD_PARTY_ASSETS.md`

Where required, include the actual license texts or appropriate attribution files.

Remove assets with unverified provenance if their rights cannot be established.

Replace them with:

* Original assets created for the project.
* Properly licensed assets.
* Neutral placeholders that buyers can replace.

Remove trademarked logos that are not necessary or whose use is not authorized.

Do not claim that an unknown image is licensed.

Do not claim Envato approval or legal clearance.

Document remaining intellectual-property questions for the owner.

## 14.1 MIT and public GitHub distribution

The package is reportedly published under MIT on a public GitHub repository.

Investigate the actual current repository and license.

Do not change the license automatically.

Do not remove the public repository.

Do not rewrite Git history.

Do not falsely claim that MIT-licensed code can be made exclusive by selling a zip.

Prepare a clear report explaining the implications of keeping the package public under MIT while also selling it on CodeCanyon.

Present the available distribution options without making the business decision for me.

Possible options to document:

1. Continue MIT open source and sell documentation, support, convenience, or a distinct paid edition.
2. Keep the core open source and distribute additional genuinely original premium features separately.
3. Publish a distinct paid product with an appropriately reviewed license, only if the legal ownership and licensing of all included code permit it.
4. Keep selling the package under its existing applicable license, with honest disclosure.

Do not copy third-party code into a proprietary version without checking its license.

Do not remove required copyright notices.

Create a licensing decision document:

`docs/LICENSING_AND_DISTRIBUTION.md`

Clearly distinguish technical facts, license text, business choices, and questions requiring professional legal advice.

---

# 15. PHASE 13 — COMPREHENSIVE AUTOMATED TESTING

The existing audit reports no test suite.

Create a real automated test suite using Laravel's supported testing tools and PHPUnit or Pest, choosing the framework that best fits the current dependency structure.

Do not create empty tests that merely assert `true`.

Tests must execute real package behavior in an isolated environment.

## 15.1 Test infrastructure

Create:

* A proper test configuration.
* A disposable SQLite test database where supported.
* Factories or test helpers.
* Fake mail transport.
* Fake SMS gateway.
* Fake external HTTP responses.
* A clean Laravel test application or reliable Orchestra Testbench setup, depending on compatibility.

Do not require real SMTP credentials, SMS API credentials, production databases, or external services for the normal test suite.

## 15.2 Required feature tests

At minimum, cover:

### Installation and package discovery

* Service-provider discovery.
* Configuration merging.
* Route registration.
* View and translation loading.
* Migration behavior.
* Asset publishing.
* Fresh Laravel 12 installation.
* Existing host users table preservation.
* Repeated installation command behavior.

### Authentication

* Successful login.
* Invalid login.
* Login rate limiting.
* Inactive-user rejection.
* Logout.
* Session invalidation.
* Registration enabled/disabled.
* OTP sending.
* OTP verification.
* OTP expiration.
* OTP replay.
* OTP identity binding.
* OTP brute-force prevention.
* Password reset.
* Reset identity switching attack.
* Reset token replay.
* Reset token expiry.
* Reset throttling.
* Password changes.

### Authorization

* Permission allow/deny behavior.
* Role assignment.
* Unauthorized role assignment.
* Administrator privilege escalation.
* Hidden role removal.
* Undefined permission behavior.
* Activity-log access.
* Export authorization.
* Device/session management authorization.
* Direct URL access without permission.

### User and role management

* User CRUD.
* Role CRUD.
* Validation.
* Duplicate email/phone handling.
* Self-escalation prevention.
* Last-administrator protection.
* Role/permission cache invalidation.

### Uploads and web security

* Invalid file extensions.
* Invalid MIME types.
* Oversized uploads.
* Malicious SVG.
* Path traversal.
* Safe randomized filenames.
* Unsafe menu URL rejection.
* XSS payload escaping.
* CSRF behavior.

### Settings, mail, and SMS

* Settings read/update.
* Cache invalidation.
* SMTP settings encryption.
* SMTP secret redaction.
* Fake mail sending.
* Mail log privacy.
* SMS gateway validation.
* Fake SMS sending.
* SMS secret redaction.
* External URL validation.
* Failure and timeout handling.

### Data safety

* Clear Data cannot truncate host tables.
* Package migrations do not overwrite host tables.
* Rollback does not delete unrelated data.
* CSV exports are safe and properly authorized.
* CSV formula injection is mitigated.

## 15.3 Quality gates

Run the available relevant commands, including:

* `composer validate`
* Composer optimized autoload generation in an isolated environment.
* PHP syntax checks.
* PHPStan or an appropriate static-analysis tool.
* Laravel Pint or another appropriate code-style checker.
* PHPUnit/Pest.
* Security dependency audit.
* Fresh-install integration tests.
* Laravel route listing.
* Laravel config/view cache checks.

Use tools that are compatible with the project's supported PHP and Laravel versions.

Do not modify the host application's dependencies simply to make the package appear to pass.

If development dependencies are required, declare them appropriately in the package's development configuration.

Fix failures caused by Metheme.

If a third-party dependency has a vulnerability, determine whether it is a package dependency, a development dependency, or a host application's dependency.

Do not claim all dependencies are safe merely because `composer audit` returns a result for a particular environment.

Create:

`docs/TESTING.md`

Include the exact commands required to reproduce the test suite.

---

# 16. PHASE 14 — DOCUMENTATION AND INSTALLATION EXPERIENCE

Create complete, professional, English-language documentation suitable for an international buyer with beginner-to-intermediate Laravel knowledge.

Documentation must be written based on the actual final implementation, not on assumptions.

Create at least:

* `README.md` or update the existing README.
* `docs/INSTALLATION.md`
* `docs/CONFIGURATION.md`
* `docs/AUTHENTICATION.md`
* `docs/ROLES_AND_PERMISSIONS.md`
* `docs/SIDEBAR_AND_MENU.md`
* `docs/MAIL_CONFIGURATION.md`
* `docs/SMS_CONFIGURATION.md`
* `docs/CUSTOMIZATION.md`
* `docs/UPGRADE.md`
* `docs/UNINSTALLATION.md`
* `docs/TROUBLESHOOTING.md`
* `docs/TESTING.md`
* `docs/THIRD_PARTY_ASSETS.md`
* `docs/LICENSING_AND_DISTRIBUTION.md`
* `CHANGELOG.md`

## 16.1 Installation documentation

The installation guide must cover:

1. System requirements.
2. PHP and Laravel version compatibility.
3. Required PHP extensions.
4. Composer installation.
5. Package discovery.
6. Configuration publishing.
7. Asset publishing.
8. Database preparation.
9. Migration commands.
10. Initial administrator creation.
11. Storage and permissions.
12. Mail setup.
13. SMS setup.
14. Authentication configuration.
15. Role and permission setup.
16. Sidebar configuration.
17. Login instructions.
18. Cache commands.
19. Troubleshooting.
20. Upgrade instructions.
21. Uninstallation and cleanup.

Include complete working command examples.

Do not instruct buyers to publish the package's entire public directory into the application root.

Do not tell buyers to replace their complete `config/auth.php`.

Do not include credentials or production secrets.

## 16.2 Customization documentation

Document:

* Adding sidebar items.
* Adding nested sidebar groups.
* Declaring new permissions.
* Assigning permissions to roles.
* Using authorization middleware.
* Integrating the host application's User model.
* Extending or replacing dashboard content.
* Adding custom translations.
* Publishing and overriding views.
* Customizing logos and brand colors.
* Adding an SMS gateway.
* Configuring route prefixes.
* Configuring supported table names, if implemented.

Provide practical code examples based on the actual package API.

Do not document imaginary methods or configuration keys.

## 16.3 Public documentation

Prepare documentation that can be published publicly without exposing secrets or private information.

Do not publish a private page or upload documentation externally without authorization.

Create the files locally and explain how I can publish them.

---

# 17. PHASE 15 — CODECANYON PRODUCT PACKAGING

Prepare the technical product package for marketplace submission.

Do not upload or submit anything to Envato.

## 17.1 Customer-facing distribution

Create a clean distribution structure, such as:

release/metheme/

It should contain only files necessary for a buyer.

Potential structure:

release/metheme/
├── package/
├── documentation/
├── licenses/
├── changelog/
└── README.md

Adapt the structure to the actual product and Envato packaging requirements.

Do not include:

* `.env` files.
* API keys.
* SMTP credentials.
* SMS credentials.
* Personal passwords.
* Development database dumps.
* Customer data.
* Debug logs.
* Git history.
* IDE configuration.
* AI-agent worktrees.
* Temporary test files.
* Unrelated host-application files.
* Private development notes.
* Unlicensed third-party assets.

Do not package the full host application unless explicitly necessary and documented.

Ensure Composer installation instructions work with the actual distribution method.

If Packagist publication is not configured, provide an honest installation method using a downloadable package or a private repository.

Do not invent a published Composer package URL.

## 17.2 Package validation

Validate:

* Composer metadata.
* Autoloading.
* Provider discovery.
* Clean install.
* Documentation links.
* License files.
* Changelog.
* Asset paths.
* Default branding.
* Demo credentials, if any.
* Version number.
* Package archive contents.

Do not include a default administrator password in the archive.

If a demo is required, use isolated synthetic data and a clearly separated demo environment.

## 17.3 Marketplace listing preparation

Prepare draft listing materials in:

`docs/codecanyon/`

Include:

1. Product title suggestions.
2. A concise product description.
3. A detailed feature list.
4. System requirements.
5. Supported Laravel/PHP versions.
6. Installation summary.
7. Screenshot checklist.
8. Preview/demo checklist.
9. Changelog.
10. Third-party attribution checklist.
11. Licensing notes.
12. Support policy draft.
13. A list of claims that have actually been verified.
14. A list of claims that must not be made until further testing.

Do not fabricate:

* Sales numbers.
* Customer reviews.
* Marketplace approval.
* Performance benchmarks.
* Compatibility claims.
* Security certifications.
* Feature demonstrations.
* License rights.
* A live demo URL.

Do not promise Envato acceptance.

Use the latest official Envato/CodeCanyon requirements that can actually be verified.

If official pages cannot be accessed, record the exact limitation and provide links for manual review.

Do not rely solely on outdated search snippets.

---

# 18. PHASE 16 — FINAL INSTALLATION AND RELEASE VALIDATION

After implementation, repeat the fresh-install process from a clean, disposable Laravel 12 project.

Do not rely only on the existing host application.

Run the full test suite and quality checks.

Verify:

1. Composer installation works.
2. Service provider auto-discovery works.
3. Package routes load.
4. Package migrations work without colliding with Laravel defaults.
5. The host application's existing user table is preserved.
6. The initial administrator is created securely.
7. No hard-coded administrator exists.
8. No OTP is returned to clients.
9. Password reset cannot be redirected to another account.
10. OTPs expire and are rate-limited.
11. Unauthorized users cannot access activity logs.
12. Users cannot assign unauthorized roles.
13. Deactivated users lose access.
14. The Clear Data feature cannot damage host data.
15. Assets publish only to the dedicated Metheme directory.
16. The dashboard renders without missing-asset errors.
17. Login and redirects work.
18. Role/permission middleware works.
19. Configuration caching works.
20. Route caching works where compatible.
21. View caching works.
22. English and Bangla translations load.
23. Mail and SMS fake transports work.
24. Documentation matches the actual code.
25. The release archive contains no secrets or development artifacts.

Run the complete verification process more than once if necessary to ensure that installation is reproducible.

If Laravel 13 is available in the environment, test it separately.

Do not claim Laravel 13 compatibility if it has not been tested.

Do not claim PostgreSQL compatibility if it has not been tested.

Do not claim every security vulnerability is eliminated simply because automated tests pass.

---

# 19. REQUIRED EXECUTION STRATEGY

You must perform the work in logical phases, but continue autonomously through all phases.

Use this order:

**Stage 1 — Protect and inspect**

* Record Git status.
* Preserve uncommitted changes.
* Create a safe working branch if possible.
* Review the existing implementation.
* Save the implementation plan.

**Stage 2 — Fix critical security problems**

* Remove the hidden backdoor.
* Fix OTP leakage.
* Fix password-reset takeover.
* Add throttling and expiry.
* Fix authorization and role escalation.
* Protect activity logs.
* Remove dangerous database-clearing behavior.

**Stage 3 — Fix installation architecture**

* Fix Composer metadata and PSR-4.
* Fix authentication integration.
* Fix migration collisions.
* Fix routes and redirects.
* Fix asset publishing.
* Implement a safe install command.

**Stage 4 — Refactor and improve reliability**

* Consolidate duplicate implementations.
* Clean up dead code.
* Improve configuration and service-provider behavior.
* Improve permissions and caching.
* Fix uploads, external requests, and XSS.
* Improve database integrity.

**Stage 5 — Testing**

* Build test infrastructure.
* Implement meaningful feature tests.
* Run static analysis and syntax checks.
* Fix failures.
* Test on a clean Laravel application.

**Stage 6 — Product preparation**

* Improve branding and UI where needed.
* Complete translations.
* Document installation and customization.
* Audit third-party licenses.
* Prepare the release archive.
* Prepare draft CodeCanyon listing materials.

**Stage 7 — Final verification**

* Run all available tests.
* Validate the final archive.
* Write the final report.
* Identify anything still blocked or requiring owner decisions.

Do not stop after Stage 1 or Stage 2.

Do not stop to ask me for approval between ordinary implementation stages.

For decisions that can safely be implemented without changing the product's fundamental business model, choose the secure, maintainable, backward-compatible option and document the decision.

For decisions that require legal ownership, business model, public repository, or live credential choices, do not make irreversible changes. Implement safe reversible technical improvements and document the decision required from me.

---

# 20. REQUIRED FILES AND DELIVERABLES

At completion, create or update the following files as appropriate.

## Core project files

* Updated `composer.json`.
* Updated `src/MEServiceProvider.php`.
* Updated authentication controllers, middleware, models, routes, configuration, and migrations.
* Secure installation command.
* Proper configuration and publishing structure.
* Corrected assets and views.
* Automated tests.
* Test factories and fake services as required.

## Documentation

* `docs/IMPLEMENTATION_PLAN.md`
* `docs/ARCHITECTURE.md`
* `docs/INSTALLATION.md`
* `docs/CONFIGURATION.md`
* `docs/AUTHENTICATION.md`
* `docs/ROLES_AND_PERMISSIONS.md`
* `docs/SIDEBAR_AND_MENU.md`
* `docs/MAIL_CONFIGURATION.md`
* `docs/SMS_CONFIGURATION.md`
* `docs/CUSTOMIZATION.md`
* `docs/UPGRADE.md`
* `docs/UNINSTALLATION.md`
* `docs/TROUBLESHOOTING.md`
* `docs/TESTING.md`
* `docs/THIRD_PARTY_ASSETS.md`
* `docs/LICENSING_AND_DISTRIBUTION.md`
* `docs/codecanyon/` listing preparation materials.
* `THIRD_PARTY_LICENSES.md`
* `CHANGELOG.md`

Do not create empty placeholder documents merely to satisfy the list.

If a document is not applicable, explain why.

## Final report

Create:

`docs/FINAL_IMPLEMENTATION_REPORT.md`

The report must include:

1. Executive summary.
2. Files and subsystems changed.
3. Critical vulnerabilities fixed.
4. Installation problems fixed.
5. Architecture improvements.
6. Features preserved.
7. Features removed or deprecated, with reasons.
8. New features and commands.
9. Database and migration changes.
10. Backward-compatibility changes.
11. Documentation created.
12. Third-party licensing issues resolved.
13. Remaining licensing or business decisions.
14. Tests executed.
15. Exact test results.
16. Failed or blocked commands.
17. Laravel/PHP versions actually tested.
18. Fresh-install test results.
19. Release archive location.
20. Remaining manual steps.
21. Honest CodeCanyon readiness assessment based on completed evidence.

---

# 21. FINAL RESPONSE FORMAT

When all possible work is completed, provide a concise but detailed final response in the following format:

## A. Implementation Summary

Explain what was actually implemented.

## B. Critical Security Fixes

List each critical vulnerability and the concrete change that addresses it.

## C. Installation and Compatibility

State which Laravel and PHP versions were tested and whether a clean installation succeeded.

## D. Testing Results

For each test suite or quality gate, show:

* Command.
* Passed count.
* Failed count.
* Skipped count.
* Blocker, if any.

Never invent test counts or report a test as passed when it was not executed.

## E. Files Created or Updated

List the important files and documentation.

## F. Release Package

Provide the exact verified local path of the generated release archive, if one was successfully created.

Do not invent a download path.

## G. Remaining Issues

Clearly distinguish:

* Critical unresolved issues.
* Non-critical issues.
* Environmental blockers.
* Manual owner decisions.
* Legal/licensing decisions.
* Tests not performed.

## H. CodeCanyon Readiness

Provide a factual status for each category:

* Security.
* Installation.
* Compatibility.
* Documentation.
* Licensing.
* UI/UX.
* Demo and screenshots.
* Release packaging.
* Marketplace policy verification.

Do not guarantee marketplace approval.

## I. My Next Actions

Give me the exact manual steps I need to complete, with commands where applicable.

Prioritize actions involving exposed credentials, public repository history, licensing choices, and deployment.

## J. Final Safety Confirmation

Explicitly confirm whether you:

* Modified any production database.
* Deployed anything.
* Pushed to GitHub.
* Rewrote Git history.
* Published to Packagist.
* Submitted to CodeCanyon.
* Used any real SMTP or SMS credentials.

Be truthful.

---

# 22. FINAL INSTRUCTIONS

Start now.

First inspect the current repository and preserve its existing work.

Then implement the complete solution directly in the project.

Do not respond with only a proposed plan.

Do not ask me to manually fix issues that you can safely fix in the source code.

Do not make fake test results.

Do not invent compatibility or marketplace policy claims.

Do not remove working features without a documented reason.

Do not change the fundamental Composer package architecture.

Do not introduce hidden credentials or backdoors.

Do not expose secrets in output.

Do not perform destructive operations against production or remote repositories.

Continue until every feasible task is implemented, tested, documented, and reported.

The final goal is an actual improved Metheme repository and a reproducible release candidate—not just a report, code snippets, or recommendations.

Begin with repository inspection and execute the full workflow now.


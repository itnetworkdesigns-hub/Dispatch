# Dispatch — Trucker & Supplier Auth (PHP)

Small example app that provides user registration and login for two roles: `trucker` and `supplier`.

Contents (high level)
- `sql/create_users.sql` — creates `dispatch_db` and `users` table.
- `config.php` — DB config, PDO helper and autoloader.
- `classes/` — `User`, `Auth`, `Session` classes for user management and session handling.
- `auth/` — JSON-friendly endpoints (`register.php`, `login.php`, `logout.php`, `whoami.php`).
- `examples/` — small CLI examples for quick testing.

Quick start

1. Create the DB and table (run the migration):

```bash
mysql -u root -p < sql/create_users.sql
```

2. Configure DB credentials in `config.php`:

- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`

3. (Optional) Try the CLI examples:

```bash
php examples/register.php
php examples/login.php
```

Auth endpoints
- `auth/register.php` (POST)
  - Accepts JSON or form data: `name`, `email`, `password`, `role` (`trucker`|`supplier`)
  - Returns JSON: `{ "success": true, "user_id": <id> }` or `{ "success": false, "error": "..." }`

- `auth/login.php` (POST)
  - Accepts `email` and `password`, returns `{ success: true, user: { ... } }` and starts a session.

- `auth/logout.php` (POST)
  - Destroys the session and returns `{ success: true }`.

- `auth/whoami.php` (GET)
  - Returns the current session user or `null`.

Example: register with curl

```bash
curl -X POST -H "Content-Type: application/json" \
  -d '{"name":"Alice","email":"alice@example.com","password":"secret","role":"trucker"}' \
  http://localhost/dispatch/auth/register.php
```

Front-end notes
- The `index.php` page contains Bootstrap modals wired to the auth endpoints using `fetch()`.
- The nav shows the logged-in user's email with a dropdown to log out when authenticated.

Security & production
- Use HTTPS in production and set secure cookie flags on sessions.
- Add CSRF protection for state-changing requests from browsers.
- Rate-limit registration/login and enforce strong password rules.

Development
- Run quick tests with the PHP CLI examples or `curl` requests shown above.
- The project is small and intended as an example; feel free to extend with email verification, password reset flows, and RBAC.

License / Contact
- This repository is a work-in-progress example. Open an issue or PR on GitHub for changes: https://github.com/itnetworkdesigns-hub/Dispatch

---

If you want, I can also:
- Add `name` fields to the register modals and wire them to the endpoints.
- Add basic client-side validation and better UX after register/login.

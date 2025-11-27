# Dispatch (Trucker & Supplier) — PHP auth example

This project contains simple PHP classes and endpoints for registering and logging in two user roles: `trucker` and `supplier`.

Files added
- `sql/create_users.sql` — SQL migration to create `dispatch_db` and `users` table.
- `config.php` — PDO database connection helper and small autoloader.
- `classes/User.php` — `User` class with `register()`, `login()`, and lookup methods.
- `classes/Auth.php` — session helpers (`login`, `logout`, `user`, `check`).
- `auth/register.php` — JSON-friendly POST endpoint to register users.
- `examples/register.php`, `examples/login.php` — small CLI examples.

Quick setup

1. Create the database and table (run the SQL file):

```bash
mysql -u root -p < sql/create_users.sql
```

2. Update DB credentials in `config.php`:

- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`

3. Test registration (example):

```bash
php examples/register.php
```

Auth endpoints
- `auth/register.php` (POST) — accepts form-encoded or JSON body:
  - Required: `name`, `email`, `password`, `role` (`trucker` or `supplier`)
  - Returns JSON: `{ success: true, user_id: <id> }` or errors.

Examples: wiring your modals (AJAX)

Use `fetch` to POST JSON from your modal form. Example for registration:

```javascript
async function registerUser(data) {
  const res = await fetch('/auth/register.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
  return res.json();
}

// Example usage from a modal submit handler
document.querySelector('#registerForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const form = e.target;
  const data = {
    name: form.name.value,
    email: form.email.value,
    password: form.password.value,
    role: form.role.value, // "trucker" or "supplier"
  };
  const result = await registerUser(data);
  if (result.success) {
    // close modal, show success
  } else {
    // show errors
  }
});
```

Notes
- `auth/login.php` and `auth/logout.php` endpoints can be added similarly; `examples/login.php` shows a CLI usage of `User->login()` and `Auth::login()`.
- Sanitize and validate input on the client and server. Add CSRF protection for web forms.
- In production, secure sessions (cookie flags), use HTTPS, and consider email verification.

Git / push instructions

```bash
cd /var/www/html/dispatch
git remote add origin https://github.com/itnetworkdesigns-hub/Dispatch.git
git branch -M main
git add .
git commit -m "Initial commit: auth and user classes, DB migration and examples"
git push -u origin main
```

If you prefer SSH, set the remote to `git@github.com:itnetworkdesigns-hub/Dispatch.git` and ensure your SSH key is added to GitHub.

Want me to:
- Add `auth/login.php`/`auth/logout.php` endpoints? (I can implement them next.)
- Create simple HTML forms and wire JavaScript modals to the endpoints? (I can scaffold this.)

-- End

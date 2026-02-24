# PHP CRUD Application

A professional PHP CRUD application with MVC-inspired architecture.

 # PHP CRUD Application

A modern PHP CRUD app with authentication and a modal-driven, AJAX-powered dashboard.

**What's changed**
- Replaced separate add/edit pages with a reusable modal (AJAX form submits).
- Controllers centralized: [src/controllers/AuthController.php](src/controllers/AuthController.php) and [src/controllers/UserController.php](src/controllers/UserController.php).
- AJAX endpoint for create/update lives in [views/users/process-user.php](views/users/process-user.php).
- Dashboard UI updated with a custom color palette: `#C8D9E6`, `#F5EFEB`, `#FFFFFF`.

**Project layout**

```
crud_php/
├── public/                        # Web root (entry point)
│   └── index.php
├── src/
│   ├── config/
│   │   └── Database.php           # DB connection (update creds here)
│   └── controllers/
│       ├── AuthController.php     # Login / Register / Logout
│       └── UserController.php     # CRUD business logic
├── views/
│   ├── auth/
│   │   └── login.php
│   └── users/
│       ├── dashboard.php          # Main dashboard (HTML + inline CSS/JS)
│       └── process-user.php       # AJAX handler for add/edit
├── all.css
├── style.css
└── README.md
```

Quick links:
- Config: [src/config/Database.php](src/config/Database.php)
- Controllers: [src/controllers/AuthController.php](src/controllers/AuthController.php), [src/controllers/UserController.php](src/controllers/UserController.php)
- Dashboard: [views/users/dashboard.php](views/users/dashboard.php)
- AJAX processor: [views/users/process-user.php](views/users/process-user.php)

## Setup

1. Create the `users` table (run in phpMyAdmin or MySQL CLI):

```sql
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

2. Update DB credentials in [src/config/Database.php](src/config/Database.php).

3. Open the app in your browser:

```
http://localhost/crud_php/public/index.php
```

## How it works (high level)

- Authentication uses `password_hash()` / `password_verify()` and session keys (`logged_in`, `user_id`, `user_name`, `user_email`).
- The dashboard lists users and supports:
  - Add/Edit via a single modal form (client-side opens modal, fills values for edit).
  - AJAX POST to `process-user.php` for create/update (JS fetch API).
  - Row-level Edit/Delete and multi-select mass delete.
  - Client-side search that filters rows using `data-name` / `data-email` attributes.
- UI uses Bootstrap 4, SweetAlert for notifications, and a small amount of custom CSS (colors: `#C8D9E6`, `#F5EFEB`, `#FFFFFF`).

## Developer notes

- Modal & AJAX: [views/users/dashboard.php](views/users/dashboard.php) contains the modal markup plus the JS functions `openModal(mode, userData)`, form submit handler, `delUser()`, and `massDel()`.
- AJAX handler: [views/users/process-user.php](views/users/process-user.php) expects POST fields like `mode`, `name`, `email`, and `user_id` and returns JSON responses.
- Session keys: logged-in state is stored in session variables set by `AuthController`.

## Security (important)

- Current codebase still contains non-parameterized SQL in some places. Before using in production, convert all DB queries to prepared statements (mysqli->prepare / bind_param) or use an ORM.
- Add CSRF protection for form submissions (especially for AJAX endpoints).

## UX / Visual

- The dashboard was redesigned to use a soft pastel palette and more compact action buttons aligned above the "Actions" table column.
- If you'd like a different alignment or palette, edit the CSS variables at the top of [views/users/dashboard.php](views/users/dashboard.php).

## Future improvements

- Migrate queries to prepared statements
- Add role-based access control
- Add unit/integration tests and CI checks
- Extract inline CSS/JS from `dashboard.php` into separate assets

## Troubleshooting

- If you get "Table 'crud.users' doesn't exist": ensure you created the `users` table and the DB name matches the one in [src/config/Database.php](src/config/Database.php).
- Login problems: check the users table for correct `email` and hashed `password` values. Use `password_verify()` for checking.



# User-Management-System
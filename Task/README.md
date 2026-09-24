# LeadDesk Mini

A production-ready, full-stack lead management (mini-CRM) web application. Visitors submit enquiries through a modern landing page; administrators manage, search, and progress those leads through a secure dashboard.

**Stack:** PHP 8 · MySQL · Bootstrap 5 · Vanilla JavaScript (AJAX) · PDO (prepared statements everywhere)

---

## 1. Project Overview

LeadDesk Mini is built to demonstrate a complete, real-world CRUD workflow:

- A **public marketing landing page** that captures enquiries via a validated lead form.
- A **secure admin panel** (session-based auth) where staff can view, search, filter, paginate, and update the status of every lead — with AJAX so the page never has to reload.
- Security is treated as a first-class requirement, not an afterthought: hashed passwords, CSRF tokens, prepared statements, input sanitization, and session timeout are all implemented.

---

## 2. Features

### Public Website
- Sticky, scroll-aware navbar
- Animated hero section with a live "lead pipeline" visual
- 6 service cards with hover animations
- "Why Choose Us" section with stats
- Testimonials
- 3-tier pricing (Basic / Professional / Enterprise)
- Lead enquiry form with client + server-side validation, duplicate-submission prevention, honeypot spam trap, and toast-style success/error alerts
- Fully responsive footer

### Admin Panel
- Secure login (`password_hash` / `password_verify`, brute-force throttling)
- Dark sidebar navigation with active-state highlighting
- Topbar with profile dropdown and logout confirmation
- Dashboard stat cards: Total, New, Contacted, Closed leads
- Searchable, sortable (latest first), paginated leads table
- Change a lead's status inline via AJAX (no page reload)
- View full lead details in a modal
- Delete a lead via AJAX with confirmation
- Toast notifications and a loading overlay for all AJAX actions
- Session timeout after 30 minutes of inactivity

### Security
- **Prepared statements** (PDO, `ATTR_EMULATE_PREPARES => false`) on every query
- **Password hashing** with `password_hash()` / `password_verify()` (bcrypt)
- **CSRF tokens** on every form and AJAX POST request
- **Input sanitization** (`clean_input()`) and output escaping (`e()`) throughout
- **Session hardening**: `httponly` cookies, session regeneration on login, inactivity timeout
- **Honeypot field** + duplicate-submission guard on the public lead form

---

## 3. Folder Structure

```
LeadDeskMini/
│
├── index.php                  # Public landing page (hero, services, pricing, lead form)
├── README.md
│
├── css/
│   └── style.css              # All styling (landing page + admin panel)
│
├── js/
│   ├── main.js                # Landing page: scroll reveal, lead form AJAX
│   └── admin.js                # Admin panel: AJAX status updates, delete, toasts
│
├── images/                    # Static image assets
│
├── database/
│   └── leaddesk.sql            # Schema + seed data (admins, leads)
│
├── includes/
│   ├── db.php                  # PDO connection (edit credentials here)
│   ├── auth.php                # Session handling, login guard, timeout
│   ├── csrf.php                 # CSRF token generation/validation
│   └── functions.php            # Sanitization, escaping, formatting helpers
│
├── api/
│   ├── submit_lead.php          # POST — public lead form submission (JSON)
│   ├── update_status.php        # POST — admin-only, AJAX status change (JSON)
│   └── delete_lead.php          # POST — admin-only, AJAX lead delete (JSON)
│
└── admin/
    ├── login.php                # Admin login form
    ├── logout.php                # Destroys session, redirects to login
    ├── dashboard.php             # Stat cards + lead management table
    └── includes/
        ├── header.php             # Topbar + profile dropdown
        ├── sidebar.php            # Dark sidebar navigation
        └── footer.php             # Admin footer
```

---

## 4. Installation

### Requirements
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- A web server (Apache/Nginx) or PHP's built-in server for local testing

### Steps

1. **Copy the project** into your server's web root, e.g. `htdocs/LeadDeskMini` (XAMPP/WAMP) or your Nginx/Apache document root.

2. **Import the database** (see section 5 below).

3. **Configure the database connection** in `includes/db.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'leaddesk');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

4. **Run it locally** using PHP's built-in server (from the project root):
   ```bash
   php -S localhost:8000
   ```
   Then visit `http://localhost:8000`.

   Or, with XAMPP/WAMP, start Apache + MySQL and visit `http://localhost/LeadDeskMini`.

5. **Log in to the admin panel** at `/admin/login.php` using the default credentials below.

---

## 5. Database Import

Using the MySQL CLI:
```bash
mysql -u root -p < database/leaddesk.sql
```

Or via **phpMyAdmin**:
1. Create a database named `leaddesk` (or let the script create it — it includes `CREATE DATABASE IF NOT EXISTS`).
2. Go to the **Import** tab, choose `database/leaddesk.sql`, and click **Go**.

The script creates two tables (`admins`, `leads`), inserts a default admin account, and seeds three sample leads for testing.

---

## 6. Admin Credentials (Default / Demo)

| Field    | Value       |
|----------|-------------|
| Username | `admin`     |
| Password | `Admin@123` |

> ⚠️ **Change this password (or add a new admin and delete this row) before deploying to production.** The password hash in `database/leaddesk.sql` is a real bcrypt hash generated for `Admin@123` — do not reuse it in production.

To generate a new hash for a different password, run:
```php
<?php echo password_hash('YourNewPassword', PASSWORD_DEFAULT);
```

---

## 7. Deployment Guide

1. **Upload files** to your host (shared hosting, VPS, or a platform like Render/InfinityFree/GCP).
2. **Create the MySQL database** through your host's control panel and import `database/leaddesk.sql`.
3. **Update `includes/db.php`** with your production database host, name, user, and password.
4. **Enable HTTPS** and uncomment `'secure' => true` in `includes/auth.php`'s session cookie params once served over HTTPS.
5. **Set `display_errors = Off`** in production `php.ini` (or via `.htaccess`) so raw errors are never shown to visitors — errors are already logged via `error_log()` in this codebase.
6. **Change the default admin password** immediately after your first login.
7. Point your domain at the project root (where `index.php` lives) and verify the lead form and admin dashboard both work end-to-end.

---

## 8. Future Improvements

- Email notifications to admins when a new lead arrives
- Role-based access (multiple admins with different permission levels)
- CSV/Excel export of leads
- Lead source tagging (which page/campaign a lead came from)
- Rich activity log / audit trail per lead
- Two-factor authentication for admin accounts
- Rate limiting at the infrastructure level (in addition to the app-level throttling already included)

---

## Credits

Built for **Digital Heroes Training Task** — [digitalheroesco.com](https://digitalheroesco.com)

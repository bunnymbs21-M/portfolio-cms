# Dynamic Developer Portfolio & CMS

A minimal, performant developer portfolio built with native web standards and a custom PHP/MariaDB administrative content management system. Features asynchronous contact handling, dynamic project rendering, and full CRUD operations protected by session-based authentication.

---

## Features

- **Public Portfolio Interface:** Fast, clean presentation focused on content hierarchy, responsive typography, and semantic HTML5.
- **Dynamic Content Rendering:** Projects and metadata are retrieved directly from a MariaDB relational database.
- **Asynchronous Inquiries:** Contact form transmits messages via Fetch API (`POST` JSON) without page reloads.
- **Full CRUD Management:** Authenticated dashboard to Create, Read, Update, and Delete project entries.
- **Inquiry Tracker:** Admin message inbox to inspect and delete visitor inquiries.
- **Security & Data Integrity:**
  - Password hashing via native PHP `password_hash()` (Bcrypt).
  - Parameterized database operations using PHP Data Objects (PDO) to prevent SQL injection.
  - Server-side input sanitization (`htmlspecialchars`) mitigating Cross-Site Scripting (XSS).
  - Session regeneration and cookie protection for administrative access control.

---

## Tech Stack

- **Frontend:** Semantic HTML5, CSS3 (CSS Grid & Flexbox, CSS Variables), Vanilla JavaScript (ES6+ Fetch API)
- **Backend:** PHP 8.x
- **Database:** MariaDB / MySQL
- **Web Server:** PHP Built-in Server / Apache

---

## Project Structure

```text
portfolio/
├── admin/
│   ├── dashboard.php       # Authenticated CRUD management interface
│   ├── login.php           # Admin authentication & session setup
│   └── logout.php          # Session termination & cookie invalidation
├── api/
│   └── contact.php         # Asynchronous JSON endpoint for visitor inquiries
├── assets/
│   ├── css/
│   │   └── style.css       # Unified minimalist stylesheet & CSS variables
│   └── js/
│       └── main.js         # Client-side form validation & AJAX dispatch
├── config/
│   └── db.php              # PDO database connection & security helpers
├── database.sql            # Database schema and initial seed data
├── index.php               # Public dynamic portfolio page
└── README.md               # Documentation

# Simple Blog API (PHP)

A simple RESTful API built with PHP and PDO, following an MVC-like structure.

## 🚀 Features
- JWT Authentication (Login/Register).
- CRUD operations for Blog posts.
- Automatic Database Migration.

## 🛠️ Requirements
- PHP 8.0+
- MySQL/MariaDB
- XAMPP / WAMP / Laragon (Recommended)

## 📦 Installation & Setup

1. **Clone the repository** into your web server directory (e.g., `C:\xampp\htdocs\project\simple-blog`).
2. **Configure Database**:
   - Open `config/database.php`.
   - Update the `host`, `database`, `username`, and `password` to match your local MySQL settings.
3. **Run Migrations**:
   - Open your terminal in the project root.
   - Run the following command to create the database and tables:
     ```bash
     php migrate.php
     ```
4. **Configure Virtual Host (Optional but Recommended)**:
   - Ensure your Apache server is configured to allow `.htaccess` overrides.
   - The project is set up to route all requests through `public/index.php`.

## ⚙️ Settings
- **Database Settings**: Located in `config/database.php`.
- **App Settings**: Located in `config/app.php` (includes JWT secret and expiration).

## 🔗 Dependency
This API is designed to work with the [React Simple Blog Frontend](https://github.com/MoAjabali/react-simpleApi-blog).
Ensure the API is running before starting the frontend.

---
Developed by [MoAjabali](https://github.com/MoAjabali)

# Damar Wulan AC and Radiator E-Commerce Platform

A web-based automotive spare parts e-commerce and inventory management system specialized in automotive air conditioning (AC) and radiator components. The application provides an end-to-end shopping experience for customers and a comprehensive administrative portal for store managers and business owners.

## Table of Contents

- Overview
- Architecture and Tech Stack
- Key Features
- System Requirements
- Database Schema
- Installation and Setup
- Configuration Guide
- Default User Accounts
- Directory Structure
- Webhook and Payment Flow
- Security Practices
- License

---

## Overview

Damar Wulan AC & Radiator is designed to digitize sales and operational workflows for automotive spare parts retail. The platform provides real-time stock monitoring, weight-based regional shipping calculations, automated invoice generation via the Xendit payment gateway, and transactional notifications via SMTP email and WhatsApp.

---

## Architecture and Tech Stack

- **Backend Runtime:** PHP 8.0+ (Native procedural architecture with modular components)
- **Database Engine:** MySQL / MariaDB (Relational schema with InnoDB engine and foreign key constraints)
- **Dependency Management:** Composer (PHP PSR autoloader)
- **Frontend Framework:** Bootstrap 5, jQuery, DataTables, Chart.js, SweetAlert2, Animate.css
- **Payment Gateway:** Xendit Payment API (Hosted Checkout Invoice, QRIS, Virtual Accounts, E-Wallets)
- **Email Service:** PHPMailer via Google SMTP / TLS
- **Messaging Service:** Fonnte WhatsApp API Gateway
- **Web Server:** Apache (XAMPP / LAMP / standalone Apache with mod_rewrite)

---

## Key Features

### 1. Customer Storefront
- **Product Catalog:** Categorized catalog with live search, brand filtering, pricing, and stock visibility.
- **Cart Management:** Session-based shopping cart supporting dynamic item quantity updates and subtotal calculations.
- **Dynamic Shipping Tariff:** Automatic shipping cost calculation based on the customer destination zone and cumulative product weight (in grams).
- **Checkout and Payment:** Integrated checkout redirecting directly to Xendit hosted payment pages.
- **Order Tracking:** Detailed order history view with real-time status indicators (pending, dibayar/paid, dikirim/shipped, selesai/completed, dibatalkan/canceled) and tracking numbers.
- **Delivery Confirmation:** Customer self-confirmation button to complete orders upon delivery, triggering automated customer satisfaction emails.
- **Account Management:** Profile viewing and password updates with secure hashing (`password_hash`).

### 2. Administrator Panel
- **Product Management:** Complete CRUD operations for products including name, part number, brand, category, price, stock, weight in grams, description, and image uploads.
- **Category Management:** Create, read, update, and delete vehicle system product categories.
- **Shipping Zone Management:** Configurable destination zones (`ongkir_area`) with base cost, weight thresholds, and incremental rates per extra kilogram.
- **Order Processing:** Order workflow management including payment verification, invoice inspection, courier receipt number input, and shipping status updates.
- **Customer Notification Trigger:** Automated email notification sent to customers when orders are marked as shipped.

### 3. Business Owner Panel
- **Executive Dashboard:** High-level metrics displaying total revenue, completed transaction count, product inventory count, and active customer count.
- **Interactive Visual Analytics:** Integrated Chart.js visualizations for daily sales trends and top-performing product categories.
- **Comprehensive Reporting Suite:** Printable transaction reports with date-range filters across four report types:
  1. Product Sales Summary
  2. Shipping Volume by Destination Area
  3. Sales Performance by Category
  4. Detailed Order and Transaction Status Log
- **User Account Governance:** Administrative user management for creating and modifying staff and customer accounts.

---

## System Requirements

- **PHP:** Version 8.0 or higher
- **PHP Extensions:**
  - `mysqli` (Database connectivity)
  - `openssl` (Secure communication and mail encryption)
  - `curl` (API communication with Xendit and Fonnte)
  - `json` (Payload parsing)
  - `mbstring` (Multibyte string manipulation)
- **Database:** MySQL 5.7+ or MariaDB 10.4+
- **Composer:** Version 2.0 or higher
- **Web Server:** Apache 2.4+ (XAMPP recommended on Windows environments)

---

## Database Schema

The database `damar` consists of seven relational tables:

1. `pengguna`: Stores user accounts, profile details, delivery addresses, and role assignments.
2. `peran`: Defines authorization levels (1: Pemilik/Owner, 2: Admin, 3: Pelanggan/Customer).
3. `kategori`: Categorizes spare parts (e.g., Compressor, Condenser, Evaporator, Expansion Valve).
4. `produk`: Contains product listings, specifications, part numbers, stock, and item weight.
5. `ongkir_area`: Defines geographical delivery regions, base freight fees, and incremental per-kg charges.
6. `transaksi`: Stores order header records, payment references, shipping addresses, tracking numbers, and Xendit invoice URLs.
7. `detail_transaksi`: Line-item breakdown of products, quantities, and unit prices for each transaction.

The database initialization script is available in `damar.sql`. An Entity Relationship Diagram is documented in `akmal_erd.drawio.png`.

---

## Installation and Setup

### Step 1: Clone the Repository
Clone this repository to your local web server root directory:

```bash
cd D:/Xampp/htdocs
git clone https://github.com/LESOTOLE/damarwulan.git damar
cd damar
```

### Step 2: Install Dependencies
Run Composer to install required vendor libraries (PHPMailer):

```bash
composer install
```

### Step 3: Database Setup
1. Start Apache and MySQL through your XAMPP Control Panel.
2. Open phpMyAdmin (`http://localhost/phpmyadmin`) or your MySQL CLI client.
3. Create a new database named `damar`:
   ```sql
   CREATE DATABASE damar CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```
4. Import the `damar.sql` file into the newly created database:
   ```bash
   mysql -u root -p damar < damar.sql
   ```

### Step 4: Configure the Application
Review and update configuration parameters as detailed in the Configuration Guide below.

### Step 5: Access the Application
Open your web browser and navigate to:
- Customer Storefront: `http://localhost/damar/`
- Administrative Login: `http://localhost/damar/login.php`

---

## Configuration Guide

### 1. Database Connection (`config/koneksi.php`)
Verify that the database credentials match your local MySQL configuration:

```php
$host     = "localhost";
$username = "root";
$port     = 3306;
$password = ""; // Empty by default on XAMPP
$database = "damar";
```

### 2. Payment Gateway (`config/koneksi.php` and `webhook.php`)
Set your Xendit API keys in `config/koneksi.php`:

```php
define('XENDIT_SECRET_KEY', 'your-xendit-secret-key');
define('XENDIT_CALLBACK_TOKEN', 'your-xendit-callback-token');
```

In `webhook.php`, configure the callback verification token:

```php
$xenditCallbackToken = 'your-xendit-callback-token';
```

### 3. SMTP Email Configuration (`config/mail_sender.php`)
Configure your mail server parameters to enable transactional emails:

```php
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'your-email@gmail.com';
$mail->Password   = 'your-app-password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;
$mail->setFrom('no-reply@damarwulan.com', 'Damar Wulan AC Radiator');
```

### 4. WhatsApp Notification Gateway (`webhook.php`)
Configure your Fonnte API authorization token for automated WhatsApp alerts:

```php
'Authorization: YOUR_FONNTE_TOKEN'
```

---

## Default User Accounts

The database seed provides predefined accounts for testing:

| Role | Email | Password | Access Area |
|---|---|---|---|
| Business Owner | `owner@damar.com` | `owner123` | Storefront, Admin Panel, Financial Reports |
| Administrator | `admin@damar.com` | `admin123` | Storefront, Inventory, Orders, Shipping |
| Customer | `okeegayn@gmail.com` | `admin123` | Customer Storefront, Cart, Orders |

*Note: It is strongly recommended to change all default passwords in production environments.*

---

## Directory Structure

```
damar/
|-- .gitignore                  # Git exclusion rules
|-- README.md                   # Project documentation
|-- akmal_erd.drawio.png        # Entity Relationship Diagram (ERD)
|-- aksi_keranjang.php          # Shopping cart actions (add, update, delete)
|-- aksi_login.php              # Authentication handler
|-- aksi_password_pembeli.php   # Customer password change handler
|-- aksi_pesanan_user.php       # Order completion handler
|-- aksi_register.php           # Customer registration handler
|-- alamat.php                  # Customer delivery address editor
|-- checkout.php                # Order review and shipping fee calculator
|-- composer.json               # Composer dependency declarations
|-- composer.lock               # Composer locked versions
|-- damar.sql                   # MySQL database schema and seed data
|-- detail_pesanan.php          # Detailed invoice and order status page
|-- detail_produk.php           # Individual product view page
|-- get_produk.php              # Async endpoint for dynamic product data
|-- index.php                   # Main catalog storefront
|-- keranjang.php               # Shopping cart interface
|-- login.php                   # User sign-in page
|-- logo.png                    # Brand logo asset
|-- logout.php                  # Session destruction handler
|-- pembayaran_sukses.php       # Post-payment confirmation landing page
|-- proses_checkout.php         # Order persistence and Xendit invoice creation
|-- register.php                # Customer sign-up page
|-- riwayat.php                 # Customer order history list
|-- ubah_password.php           # Customer password change interface
|-- webhook.php                 # Xendit callback and notification dispatcher
|
|-- admin/                      # Administrative and Owner portal
|   |-- aksi_kategori.php       # Category CRUD handler
|   |-- aksi_ongkir.php         # Shipping tariff CRUD handler
|   |-- aksi_password.php       # Administrative password update handler
|   |-- aksi_pengguna.php       # User account management handler
|   |-- aksi_pesanan.php        # Order status and tracking number handler
|   |-- aksi_produk.php         # Product management handler (with upload)
|   |-- cetak_laporan.php       # Printable reports engine
|   |-- detail_transaksi.php    # Admin order inspect and update view
|   |-- footer_admin.php        # Shared admin layout footer
|   |-- header_admin.php        # Shared admin layout header
|   |-- index.php               # Admin and owner analytics dashboard
|   |-- kategori.php            # Category management interface
|   |-- laporan.php             # Report generator interface
|   |-- ongkir.php              # Shipping rates management interface
|   |-- pengguna.php            # User administration interface
|   |-- pesanan.php             # Orders queue management interface
|   |-- produk.php              # Product catalog inventory interface
|   |-- ubah_password.php       # Admin password update form
|   |-- update_status.php       # Status modification endpoint
|
|-- assets/                     # Static media and design assets
|   |-- img/                    # Product imagery and category graphics
|
|-- config/                     # Core system configuration files
|   |-- .htaccess               # Apache directory security configuration
|   |-- koneksi.php             # Database connection and global helper functions
|   |-- mail_sender.php         # PHPMailer SMTP client setup
|
|-- includes/                   # Shared view components
|   |-- .htaccess               # Component directory access prevention
|   |-- footer.php              # Public storefront footer
|   |-- header.php              # Public storefront HTML head and styles
|   |-- mail_helper.php         # Transactional email helper functions
|   |-- navbar.php              # Storefront navigation bar and cart count
|
`-- vendor/                     # Third-party packages managed by Composer
```

---

## Webhook and Payment Flow

1. **Order Placement:** Customer verifies cart and submits checkout in `checkout.php`.
2. **Invoice Generation:** `proses_checkout.php` creates an order record with status `pending`, computes total weight, calculates zone-based shipping, and calls the Xendit Invoices API via cURL.
3. **Payment Completion:** Customer is redirected to the Xendit payment link and completes the transaction via QRIS, Virtual Account, or supported payment channels.
4. **Callback Notification:** Xendit delivers a webhook payload via HTTP POST to `webhook.php`.
5. **Validation:** `webhook.php` verifies the `x-callback-token` header against `XENDIT_CALLBACK_TOKEN`.
6. **State Mutation:** If payment status is `PAID`, `transaksi.status_transaksi` is updated to `dibayar`.
7. **Customer Dispatch:** The webhook triggers an HTML receipt via PHPMailer and an automated WhatsApp notification via Fonnte.
8. **Fulfillment:** Store administrators prepare parts, update order status to `dikirim`, and enter shipping tracking numbers.

---

## Security Practices

- **SQL Injection Prevention:** Input sanitization via `mysqli_real_escape_string` and parameterized statements via `mysqli_prepare` on critical authentication routes.
- **Password Security:** Password hashing implemented via PHP native `password_hash()` utilizing the `PASSWORD_DEFAULT` (Bcrypt) algorithm.
- **Directory Protection:** `.htaccess` rules prevent direct web execution of files residing in `config/` and `includes/`.
- **Role Isolation:** Session verification on administrative pages ensures customers cannot access internal dashboards or operational actions.

---

## License

This project is licensed under the MIT License. See the repository for details.

# 🧸 Fuggler Shop

A dynamic PHP and MySQL e-commerce web application built for browsing and purchasing collectible Fuggler plushies, keychains, and mini-monsters 👾.

## ✨ Features

- **🛍️ Dynamic Product Catalog**: Displays featured products on the homepage and filters items by category (`Plushie Fugglers`, `Classic Keychain Fuggler`, `Rainbow Keychain Fuggler`, and `Mini-baby Fugglers`).
- **🎨 Database-Driven Branding**: Dynamically loads the site logo (`site_header`) and promotional hero banner (`banner`) directly from MySQL.
- **🛒 Session-Based Shopping Cart**:
  - Add items to the cart from the homepage or category pages.
  - Increment/decrement item quantities (`+`/`−`) or remove items completely.
  - Select specific items via checkboxes for partial or full cart checkout.
- **💳 Order Checkout & Confirmation**:
  - Calculates item subtotals, applies a flat shipping fee (`₱60.00`), and displays the grand total.
  - Collects customer delivery details (Full Name, Email, Address) and generates an order summary confirmation receipt.
- **📱 Responsive Design**: Styled with custom CSS and Google Fonts (`Poppins`) for desktop and mobile viewing.

## 🛠️ Tech Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript 
- **Backend**: PHP 8.2+ (Session management, MySQLi)
- **Database**: MySQL / MariaDB
- **Local Server**: XAMPP (Apache + MySQL + phpMyAdmin)

## 📁 Project Structure

```text
fuggler-shop/
├── 📄 index.php          # Homepage with dynamic banner and featured products
├── 🏷️ products.php       # Category-filtered product catalog
├── 🛒 cart.php           # Session-based shopping cart with item selection
├── 💳 checkout.php       # Order summary, shipping calculation, and customer form
├── ✅ confirmation.php   # Final order receipt and delivery details
├── 🔌 db.php             # MySQL database connection configuration
├── 🎨 style.css          # Global stylesheet and responsive layout rules
└── 🗄️ fuggler_db.sql     # Database dump containing product and branding seed data

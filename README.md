# ✈️ Airport Lost & Found Management System

A complete, production-ready web application designed to help airports manage lost and found items efficiently. The system allows passengers to report lost items or browse found items, while providing staff with a secure dashboard to manage reports, track statuses, and utilize matching algorithms to return items to their rightful owners.

---

## 🌟 Key Features

### 👤 Passenger Portal
- **Smart Reporting**: Interactive form to report lost items with detailed descriptions and image uploads.
- **Instant Tracking**: Check the status of your claim in real-time using a unique Claim Code.
- **Claim Passes**: Generate a digital claim pass once an item is matched for quick verification at the office.

### 🛡️ Staff Management System
- **Comprehensive Dashboard**: Real-time stats on lost vs. found items and recovery rates.
- **Log Found Items**: Easy logging for staff to record items found across terminal gates and lounges.
- **Advanced Match System**: Intelligent filtering to match reported lost items with found inventory.
- **Secure Returns**: Digital workflow to process item returns and maintain history.

### 🎨 Premium User Experience
- **Responsive Design**: Optimized for Desktop, Tablet, and Mobile devices.
- **Modern UI**: Elegant color palette inspired by Ethiopian Airlines branding (Deep Forest Green and Gold).
---
## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Server Environment:** XAMPP

## 🚀 Installation & Setup

Follow these steps to run the project locally on your machine:

1. **Install XAMPP:**
   Download and install [XAMPP](https://www.apachefriends.org/index.html).

2. **Clone the Repository:**
   Clone this repository into your XAMPP `htdocs` directory:

   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/YOUR-USERNAME/YOUR-REPO-NAME.git lost-found
   ```

3. **Database Setup:**
   - Open the XAMPP Control Panel and start **Apache** and **MySQL**.
   - _Note: This project is configured to connect to MySQL on port `3308`. If your XAMPP uses the default port `3306`, update the port in `config.php`._
   - Open phpMyAdmin in your browser (usually `http://localhost/phpmyadmin` or `http://localhost:8080/phpmyadmin`).
   - Create a new database named `airport_lost_found` (or the name specified in your config).
   - Import the `database.sql` file provided in the root directory to set up the necessary tables and schemas.

4. **Run the Application:**
   Open your web browser and navigate to:
   ```
   http://localhost/airport-lost-and-found-main/](http://localhost/airport-lost-and-found-main/)
   
   ```

## 📂 Project Structure

- `/passenger` - Pages and logic for passenger-facing interfaces.
- `/staff` - Secure dashboard and logic for airport staff.
- `/uploads` - Directory where uploaded images of items are stored.
- `config.php` - Database connection settings.
- `database.sql` - SQL script to generate the database schema.
- `index.php` - Main landing page of the application.
- `script.js` - Global JavaScript logic.
- `style.css` - Global styling and design system.




| Home Page | 
| :---: |
| ![Home](home_screenshot.png) |

---



| Staff Portal | 
| :---: |
| ![Portal](staff_portal.png) |

---

## 🔐 Staff Access (Demo)
- **Username**: `staff`
- **Password**: `staff123`


## 📄 License

This project is licensed under the MIT License.

# ACDIP – Adaptive Community Disaster Intelligence Platform

## 📌 Project Description

ACDIP is a web-based disaster management platform designed to support disaster preparedness, emergency response, rescue coordination, and relief management in Bangladesh.

The platform helps citizens access disaster alerts, submit rescue requests, locate emergency shelters, find safety guidelines, and access important emergency information. Administrators can manage disaster information, rescue operations, volunteers, shelters, and relief resources.

## 🛠️ Technologies Used

- Programming Language: PHP, JavaScript, HTML5, CSS3
- Backend Framework: Laravel
- Frontend: Blade Templates, HTML, CSS, JavaScript
- CSS Framework: Bootstrap
- Database: MySQL / SQLite
- ORM: Laravel Eloquent ORM
- Development Tools: VS Code, Git, GitHub, Composer, Node.js, NPM
- Local Server: XAMPP

## ✨ Main Features

- Disaster News and Alerts
- Affected Area Information
- Disaster Safety Guidelines
- Emergency Helpline Directory
- Rescue Request Submission and Tracking
- Volunteer Registration and Approval
- Volunteer Task Assignment
- Missing Person Reports
- Shelter Locator and Capacity Management
- Shelter Facilities and Evacuation Information
- Relief Resource Inventory
- Donation Management
- NGO and Medical Camp Directory
- Disaster History Dashboard
- Interactive Map of Bangladesh
- Disaster Impact Assessment

## 📋 Prerequisites

Install the following software before running the project:

1. PHP
2. Composer
3. Node.js and NPM
4. XAMPP (if using MySQL locally)
5. Git
6. Visual Studio Code (recommended)

Make sure your PHP version is compatible with the project's Laravel version.

## 📥 Installation and Setup

### Step 1: Clone the Repository

Open your terminal and run:

```bash
git clone https://github.com/PronoyPramanick/disaster-management-system.git
```

Navigate to the project directory:

```bash
cd disaster-management-system
```

### Step 2: Install PHP Dependencies

Run:

```bash
composer install
```

### Step 3: Configure the Environment

Create your local environment file.

For Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

**Important:** Never upload your `.env` file to GitHub because it may contain sensitive information.

### Step 4: Configure the Database

You can use MySQL or SQLite.

#### Option A: ( Only if we use MySQL)

1. Open XAMPP Control Panel.
2. Start Apache and MySQL.
3. Open phpMyAdmin.
4. Create a database named:

```text
disaster_management
```

5. Update the database settings in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=disaster_management
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the username and password if your MySQL configuration is different.

#### Option B: SQLite

If the project is configured to use SQLite, follow the project's existing SQLite configuration instead. You do not need to create a MySQL database when using SQLite.

### Step 5: Run Database Migrations

Run:

```bash
php artisan migrate
```

This creates the database tables defined by the project's migrations.

### Step 6: Install Frontend Dependencies

Run:

```bash
npm install
```

For development, run:

```bash
npm run dev
```

Keep this terminal running while developing.

### Step 7: Start the Laravel Server

Open another terminal in the project directory and run:

```bash
php artisan serve
```

### Step 8: Open the Website

Open your browser and visit:

http://127.0.0.1:8000

The Laravel application should now be accessible locally.

## 🔄 Useful Commands

Check the PHP version:

```bash
php --version
```

Check the Composer version:

```bash
composer --version
```

Check the Laravel application:

```bash
php artisan --version
```

List application routes:

```bash
php artisan route:list
```

Clear the application cache:

```bash
php artisan optimize:clear
```

Run database migrations:

```bash
php artisan migrate
```

Start the local server:

```bash
php artisan serve
```

## 👥 Working with Group Members

Each group member should clone the repository and install the required dependencies locally.

Every member must configure their own `.env` file and database.

Do not share `.env` files containing passwords or secret keys through GitHub.

Before pushing changes, run:

```bash
git pull origin main
```

After making changes, use:

```bash
git add .
git commit -m "Describe your changes"
git push origin main
```

Collaborators must have the appropriate GitHub repository permissions to push changes.

## 🎯 Project Goal

The goal of ACDIP is to improve disaster preparedness, emergency communication, rescue coordination, and resource management through a centralized platform for disaster management in Bangladesh.

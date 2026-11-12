# SURAKSHA – Adaptive Community Disaster Intelligence Platform

## 📌 Project Description

SURAKSHA is a web-based disaster management platform designed to support disaster preparedness, emergency response, rescue coordination, and relief management in Bangladesh.

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

1. PHP 8.2 or newer, with the `mbstring`, `openssl`, `pdo_sqlite`, `fileinfo`, and `zip` extensions enabled
2. Composer
3. Node.js LTS (includes npm)
4. Git
5. Visual Studio Code (recommended)

XAMPP is only needed if you choose MySQL. The default configuration uses SQLite, so you do not need to start Apache or MySQL to run the Laravel development server.

On Windows, install Node.js LTS from [nodejs.org](https://nodejs.org/en/download) or run `winget install --id OpenJS.NodeJS.LTS --exact`. Reopen your terminal after installation so the updated PATH is available.

The application supports disaster alerts, emergency response workflows, shelter and resource management, volunteer coordination, and public information access for citizens and administrators.

### Step 1: Open the project

If you do not already have the project, clone the correct repository:

```powershell
git clone https://github.com/PronoyPramanick/suraksha-disaster-management-system.git
Set-Location suraksha-disaster-management-system
```

If you already cloned it, open a terminal in the project folder instead.

Before running the project, install the following:

1. PHP 8.2 or newer with the `mbstring`, `openssl`, `pdo_sqlite`, `fileinfo`, and `zip` extensions enabled
2. Composer
3. Node.js LTS (includes npm)
4. Git
5. Optional: XAMPP if you plan to use MySQL instead of SQLite

On Windows, install Node.js LTS from [nodejs.org](https://nodejs.org/en/download) or run:

```powershell
winget install --id OpenJS.NodeJS.LTS --exact
```

Then reopen the terminal so the updated PATH is available.

Create your local environment file and generate the application key:

### 1. Clone the repository

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

### 3. Configure the environment

Create the local environment file and generate the app key:

SQLite is the default in `.env.example` and is the simplest option for local development. Create its database file and run the migrations:

```powershell
New-Item -ItemType File -Path database\database.sqlite -Force
php artisan migrate
```

You do not need to install or start a separate database server for SQLite.

#### Optional: Use MySQL instead

1. Start MySQL from the XAMPP Control Panel.
2. Create a database named `disaster_management` in phpMyAdmin.
3. Update the database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=disaster_management
DB_USERNAME=root
DB_PASSWORD=
```

Use the username and password configured for your MySQL installation, then run `php artisan migrate`.

### Step 5: Install frontend dependencies

```powershell
npm install
```

### Step 6: Start the app

Open two terminals in the project folder. Run the Vite development server in the first:

```powershell
npm run dev
```

Run the Laravel server in the second:

```powershell
php artisan serve
```

Open http://127.0.0.1:8000 in your browser. Keep both terminals running while developing.

## Useful Commands

### Incident map and JSON API

The public incident map is available at `/map` and uses Leaflet with OpenStreetMap tiles. To place an alert on the map, enter both latitude and longitude when creating or editing it. Only active alerts with coordinates are shown as markers.

The versioned JSON API is public and read-only:

```text
GET /api/v1/alerts
GET /api/v1/alerts/{id}
```

The list endpoint supports `category`, `severity`, `status`, `q`, `has_coordinates`, `per_page` (up to 100), and pagination via `page`. Example: `/api/v1/alerts?status=active&has_coordinates=1&per_page=100`.

Check installed tool versions:

```bash
php --version
composer --version
node --version
npm --version
git --version
```

Check the Laravel version:

```bash
php artisan --version
```

List routes:

```bash
php artisan route:list
```

Clear cached files:

```bash
php artisan optimize:clear
```

Run database migrations:

```bash
php artisan migrate --seed
```

The seeder creates the local administrator account:

```text
Email: admin@gmail.com
Password: 12345
```

Each collaborator should run `php artisan migrate --seed` after configuring their own `.env`. The seeded account is for local development; change its password for any deployed environment.

Start the local development server:

```bash
php artisan serve
```

## 👥 Working with Group Members

Each group member should clone the repository and install the dependencies locally. Keep `.env` and other machine-specific files local; never commit secrets.

Start by updating `main`, then create a separate branch for your work:

```powershell
git switch main
git pull --ff-only origin main
git switch -c feature/your-name-task
```

After making and reviewing your changes:

```powershell
git status
git add <files-you-changed>
git commit -m "Describe your changes"
git push -u origin feature/your-name-task
```

Open a pull request on GitHub to merge the branch into `main`. Do not push directly to `main`. If you are continuing work on an existing branch, switch to that branch first, then use `git pull --ff-only` and `git push` to sync it with its upstream.

Collaborators need GitHub repository access to push branches.

## Project Goal

The goal of SURAKSHA is to improve disaster preparedness, emergency communication, rescue coordination, and resource management through a centralized digital platform for disaster response in Bangladesh.    

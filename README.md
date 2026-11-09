# SURAKSHA – Adaptive Community Disaster Intelligence Platform

## Overview

SURAKSHA is a Laravel-based disaster management platform designed to help communities in Bangladesh prepare for, respond to, and recover from emergencies.

The application supports disaster alerts, emergency response workflows, shelter and resource management, volunteer coordination, and public information access for citizens and administrators.

## Tech Stack

- Backend: Laravel 12
- Frontend: Blade templates, Vite, Bootstrap, Tailwind CSS
- Database: SQLite by default, MySQL optional
- Language: PHP, JavaScript, HTML, CSS
- Dev tools: Composer, npm, Git, GitHub, VS Code

## Key Features

- Disaster alerts and news updates
- Affected area information
- Safety guidelines and emergency instructions
- Emergency helpline directory
- Rescue request submission and tracking
- Volunteer registration and assignment
- Missing person reporting
- Shelter locator and capacity tracking
- Relief resource inventory
- Donation and NGO coordination
- Medical camp directory
- Disaster history dashboard
- Interactive map support for Bangladesh
- Impact assessment and reporting

## Prerequisites

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

## Installation and Setup

### 1. Clone the repository

```powershell
git clone https://github.com/PronoyPramanick/suraksha-disaster-management-system.git
Set-Location suraksha-disaster-management-system
```

### 2. Install PHP dependencies

```powershell
composer install
```

### 3. Configure the environment

Create the local environment file and generate the app key:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Important: do not commit your `.env` file because it may contain sensitive local configuration.

### 4. Set up the database

SQLite is the default database in `.env.example` and is the recommended option for local development.

Create the SQLite database file and run migrations:

```powershell
New-Item -ItemType File -Path database\database.sqlite -Force
php artisan migrate
```

You do not need to start a separate database server for SQLite.

#### Optional: Use MySQL

If you prefer MySQL:

1. Start MySQL from XAMPP or your local MySQL server.
2. Create a database named `disaster_management`.
3. Update the database configuration in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=disaster_management
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```powershell
php artisan migrate
```

### 5. Install frontend dependencies

```powershell
npm install
```

### 6. Start the application

Open two terminals in the project folder.

In the first terminal, start the Vite frontend:

```powershell
npm run dev
```

In the second terminal, run the Laravel app:

```powershell
php artisan serve
```

Then open http://127.0.0.1:8000 in your browser.

For a production build, run:

```powershell
npm run build
```

## Useful Commands

Check installed tooling versions:

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
php artisan migrate
```

Start the local development server:

```bash
php artisan serve
```

## Team Workflow

Each team member should clone the repository and install dependencies locally. Keep `.env` and other machine-specific files local and never commit secrets.

Start from the main branch, then create a feature branch:

```powershell
git switch main
git pull --ff-only origin main
git switch -c feature/your-name-task
```

After finishing work:

```powershell
git status
git add <files-you-changed>
git commit -m "Describe your changes"
git push -u origin feature/your-name-task
```

Open a pull request to merge the branch into `main`.

## Project Goal

The goal of SURAKSHA is to improve disaster preparedness, emergency communication, rescue coordination, and resource management through a centralized digital platform for disaster response in Bangladesh.hhh

# Sprint 1 contribution: two features

## Feature 10: Missing Person Report
Authenticated citizens can submit missing-person reports with name, optional age/photo, last known location, date, contact method and details. Users can search by name/location. Case owners and administrators can change status to Missing, Located or Closed. Uploaded photos use Laravel's public disk.

## Feature 8: Volunteer Registration & Approval
Authenticated users submit one volunteer application with contact, skills, availability and experience. Administrators view all applications and approve/reject pending ones. Approval promotes the account role to `volunteer`. To prevent bypassing approval, new signups always start as citizens even if the legacy registration form requests volunteer.

## Setup
After merging, run `php artisan migrate`, `php artisan storage:link`, `npm install`, `npm run dev`, and `php artisan serve` (with Composer dependencies and `.env` already configured as described in README). Test at `/missing-persons` and `/volunteers` using citizen and admin accounts.

## Limitations
Photo uploads require the storage link and writable public disk. Reports are visible to signed-in users, including contact methods, as the original project requires searchable reports. Use a safe contact method instead of sensitive private details. Existing volunteer accounts are not retroactively reviewed. No email notifications are included.

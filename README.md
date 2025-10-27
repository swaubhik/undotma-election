# UN Brahma College - Student Election Web App

A Laravel-based web application for conducting student elections with OTP-based voter authentication and live results.

## Features

### Public Interface

-   **Candidate Listing**: Browse all active candidates with their profiles
-   **Candidate Detail Pages**: View detailed information including bio, manifesto, department, and year
-   **OTP-Based Voting**: Secure voting system using mobile number verification
-   **Live Results**: Real-time vote counting with dynamic progress bars (auto-updates every 5 seconds)
-   **One Vote Per Mobile**: Ensures each mobile number can vote only once

### Admin Panel (`/admin`)

-   **Dashboard**: Overview with total candidates, votes, voters, and live results chart
-   **Candidate Management**: Full CRUD operations for candidates
    -   Add/Edit/Delete candidates
    -   Upload candidate photos
    -   Set candidate status (active/inactive)
    -   Manage candidate details: name, position, department, year, bio, manifesto
-   **Voter List**: View all voters with their mobile numbers (partially masked), verification status, and vote history

## Technology Stack

-   **Framework**: Laravel 12
-   **Authentication**: Laravel Breeze (Blade)
-   **Frontend**: Blade Templates + Tailwind CSS
-   **Real-time Updates**: AJAX Polling (5-second intervals)
-   **Database**: SQLite (default) / MySQL

## Installation

### Prerequisites

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   SQLite or MySQL

### Setup Steps

1. **Clone and Install Dependencies**

    ```bash
    composer install
    npm install
    ```

2. **Environment Configuration**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3. **Database Setup**

    ```bash
    php artisan migrate:fresh --seed
    ```

4. **Create Storage Link**

    ```bash
    php artisan storage:link
    ```

5. **Build Frontend Assets**

    ```bash
    npm run build
    # OR for development
    npm run dev
    ```

6. **Start Development Server**
    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` to access the application.

## Default Login Credentials

**Admin Account:**

-   Email: `admin@unbrahma.edu`
-   Password: `password`

## Database Structure

### Tables

1. **users**: Admin authentication

    - `id`, `name`, `email`, `password`, `role` (admin/voter), timestamps

2. **candidates**: Candidate information

    - `id`, `name`, `bio`, `position`, `photo`, `department`, `year`, `manifesto`, `is_active`, timestamps

3. **votes**: Voting records

    - `id`, `candidate_id`, `voter_mobile` (unique), `verified`, timestamps

4. **otp_verifications**: OTP storage
    - `id`, `mobile`, `otp`, `expires_at`, `verified`, timestamps

## Key Routes

### Public Routes

-   `/` - Candidate listing (home)
-   `/candidates` - All candidates
-   `/candidates/{id}` - Candidate detail & voting
-   `/results` - Live election results
-   `/api/live-results` - JSON API for live results

### Admin Routes (Protected)

-   `/admin` - Admin dashboard
-   `/admin/candidates` - Manage candidates
-   `/admin/voters` - View voter list

### Authentication Routes

-   `/login` - Admin login
-   `/register` - Admin registration

## OTP Implementation

The OTP system includes a placeholder function for SMS integration:

```php
protected function sendOTPMessage(string $mobile, string $otp): void
{
    // Placeholder function for sending OTP via SMS
    // Implement this with your preferred SMS service (Twilio, MSG91, etc.)
}
```

**To integrate SMS service:**

1. Install your preferred SMS package (e.g., `composer require twilio/sdk`)
2. Add SMS credentials to `.env`
3. Implement the `sendOTPMessage()` method in `app/Http/Controllers/VoteController.php`

## Live Results Implementation

The results page automatically updates every 5 seconds using AJAX polling. The implementation:

-   Fetches data from `/api/live-results`
-   Updates vote counts and progress bars without page reload
-   Shows real-time voting progress

## File Upload

Candidate photos are stored in `storage/app/public/candidates/` and accessed via the public link at `public/storage`.

## Security Features

-   **Admin Middleware**: Protects admin routes from unauthorized access
-   **CSRF Protection**: All forms include CSRF tokens
-   **OTP Expiration**: OTPs expire after 10 minutes
-   **One-Vote Validation**: Database unique constraint on `voter_mobile`
-   **Form Request Validation**: Dedicated validation classes for all forms

## Development Notes

-   Code formatting is maintained using **Laravel Pint**
-   Run `vendor/bin/pint` before committing changes
-   All models use proper type declarations and relationships
-   Follow existing code conventions in the project

## Future Enhancements

Potential improvements for production:

1. Implement actual SMS service for OTP delivery
2. Add email notifications for voters and admins
3. Implement Laravel Echo + Pusher for WebSocket-based real-time updates
4. Add multi-position election support
5. Implement voting time window restrictions
6. Add election results export (PDF/Excel)
7. Implement voter registration system
8. Add candidate comparison feature
9. Mobile app integration via API

## Support

For issues or questions, please contact the development team.

---

Built with ❤️ for UN Brahma College

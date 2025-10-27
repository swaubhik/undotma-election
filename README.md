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

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

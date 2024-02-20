## UN1Q - Laravel Test: Calendar Event Management System

**1. Project Overview**

This Laravel application, named UN1Q - Laravel Test, implements a simple calendar event management system. It allows users to create, update, list, and delete events through REST APIs. Currently, authentication and authorization are not included. This project emphasizes applying Domain-Driven Design principles across different layers and demonstrates proficiency in Laravel best practices.

**2. Installation**

**Prerequisites:**

- PHP 8.1 or later
- Composer

**Steps:**

1. Clone the repository: `git clone git@github.com:glaucojrcarvalho/UN1Q.git`
2. Navigate to the project directory: `cd UN1Q/src`
3. Install dependencies: `composer install`
4. Generate application key: `php artisan key:generate`
5. Configure database connection in `.env` file
6. Run database migrations: `php artisan migrate`

**3. Usage:**

You can interact with the event management system through API endpoints, using tools like Postman or cURL.

**Supported operations:**

- Create event: `POST /api/events`
- Update event: `PUT /api/events/{uuid}`
- List events: `GET /api/events` (supports optional query parameters for filtering by start and end dates)
- Delete event: `DELETE /api/events/{uuid}`


**4. Additional Information:**

- Unit tests are included and can be executed with `phpunit`.
- Feel free to reach out with any questions or suggestions.

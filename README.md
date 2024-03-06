# Project 

User Management System API using Laravel. This API is responsible for handling user profiles within an application, including operations such as creating, updating, viewing, and deleting users.

## Setup Instructions

Follow these steps to set up the project on your local machine.

### Prerequisites

- PHP >= 7.4
- Composer
- MySQL or any other supported database

### Installation

1. Clone the repository:

```shell
   git clone https://github.com/phonixcode/usermgt.git
```
2. Navigate to the project directory:

```shell
   cd usermgt
```
3. Install dependencies:
```shell
   composer install
```
4. Copy the `.env.example` file to `.env`:
```shell
   cp .env.example .env
```
Update the .env file with your database credentials and other settings. `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

5. Run migrations to create the database tables and seed data:

```shell
   php artisan migrate:fresh --seed
```
6. Passport Installation

```shell
  php artisan passport:install
````

7. Start the development server:

```shell
   php artisan serve
```
You can now access the application at <http://localhost:8000>.

### Running Tests

To run the PHPUnit tests: 
```shell 
  php artisan test
```

```shell

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\AuthenticationTest
  ✓ user registration success
  ✓ user registration failure
  ✓ user login success
  ✓ user login failure

   PASS  Tests\Feature\UserControllerTest
  ✓ user index
  ✓ user store success
  ✓ user show success
  ✓ user update success
  ✓ user destroy success

  Tests:  10 passed
  Time:   0.36s
```
### Troubleshooting

If you encounter any issues during the setup process, you can refer to the <a href="https://laravel.com/docs/">Laravel documentation</a> for more information and troubleshooting tips.

## Additional Configuration (Optional)

Replace placeholders like `Project Name`, `your-username`, and `project-name` with actual details relevant to your project. This README.md file provides instructions for setting up the project, running migrations, seeding the database, starting the server, and running tests. You can add any additional sections or information as needed.


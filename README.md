# <img src="./resources/icons/logo.png" alt="Logo"> Filez.io

Filez.io is a simple personal file cloud storage solution built using Laravel Sail, Jetstream, Inertia.js, Vue 3, MySQL, MinIO, and Nginx. It provides an easy-to-use interface for managing and storing files securely.

## Stack

The project uses the following technologies:

- Laravel Sail: A lightweight development environment for running Laravel applications using Docker.
- Jetstream: A beautifully designed application scaffolding for Laravel, providing a robust starting point for building your application.
- Inertia.js: A modern approach to building single-page applications using server-side routing and Vue.js.
- MySQL: A popular open-source relational database management system.
- MinIO: A high-performance object storage system, used as a local bucket storage.
- Nginx: A web server and reverse proxy server used to handle routing and serve the application.
- Mailpit: A fake SMTP server for testing emails.

## Getting Started

### Prerequisites

- Docker and Docker Compose
- bash for running the `install.sh` script

Follow the instructions below to set up and run the Filez.io project:

1. Copy the `./env.example` file to `./.env` and update the environment variables if needed (note that you can run the application with de provided example).

2. Run the `./install.sh` script. This script will pull a Docker image to install the project dependencies using `composer install` and build the application.
The script will attempt to run the sudo command at the end of the process to reset the project permissions.

3. Start the application by running `./vendor/bin/sail up`. This command will start all the containers and create the MinIO bucket for file storage.

4. Run the database migrations by executing `./vendor/bin/sail artisan migrate`. This will set up the necessary database tables.

5. Optionally, you can run the database seeder to create a test user by running `./vendor/bin/sail artisan db:seed`.
It will create a user with the following credentials:

    - Email: `test@example.com`
    - Password: `password`

6. Access the project by opening your web browser and visiting [http://localhost:80](http://localhost:80).

Please note that the application will be accessible at `http://localhost`, which is configured through Nginx.
Additionally, you can access the MinIO storage at `http://static.localhost`, which is also handled by Nginx.

You can view the sent emails in Mailpit by visiting http://localhost:8025. Note that it is used only for the password recovery feature.

# Testing

The project uses PHPUnit for testing. You can run the tests by executing `./vendor/bin/sail artisan test`.
And you can run the tests with coverage by running `./vendor/bin/sail artisan test --coverage-html ./coverage`.

# Screenshots

## Landing page
<img src="./screenshots/landing-page.png" alt="Landing page">

## Login screen
<img src="./screenshots/login-page.png" alt="Login screen">

## User register screen
<img src="./screenshots/register.png" alt="User register screen">

## Password recovery screen
<img src="./screenshots/password-recovery-page.png" alt="Password recovery screen">

---
<img src="./screenshots/password-recovery-email.png" alt="Password recovery email">

## User's profile
<img src="./screenshots/profile.png" alt="Profile">

## User's dashboard
<img src="./screenshots/dashboard.png" alt="User's dashboard">

---
<img src="./screenshots/file-listing.png" alt="User's dashboard files">

---
<img src="./screenshots/file-upload.png" alt="File upload">

---
<img src="./screenshots/renaming.png" alt="File renaming">

---
<img src="./screenshots/new-folder.png" alt="New folder">

---
<img src="./screenshots/exclusion.png" alt="File exclusion">

---
<img src="./screenshots/file-controls.png" alt="File controls">

---
<img src="./screenshots/nested-path.png" alt="Nest path">

# Storage
<img src="./screenshots/storage.png" alt="Storage files">


## License

This project is licensed under the [MIT License](https://opensource.org/license/mit/).

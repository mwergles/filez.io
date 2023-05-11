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

## Getting Started

Follow the instructions below to set up and run the Filez.io project:

1. Copy the `./env.example` file to `./.env` and update the environment variables if needed (note that you can run the application with de providex example).

2. Run the `./install.sh` script. This script will pull a Docker image to install the project dependencies using `composer install` and build the application.

3. Start the application by running `./vendor/bin/sail up`. This command will start all the containers and create the MinIO bucket for file storage.

4. Run the database migrations by executing `./vendor/bin/sail artisan migrate`. This will set up the necessary database tables.

5. Access the project by opening your web browser and visiting [http://localhost:80](http://localhost:80).

Please note that the application will be accessible at `http://localhost`, which is configured through Nginx. Additionally, you can access the MinIO storage at `http://static.localhost`, which is also handled by Nginx.

Feel free to explore and utilize Filez.io for your personal file storage needs. Enjoy the convenience and simplicity it offers!

## License

This project is licensed under the [MIT License](https://opensource.org/license/mit/).

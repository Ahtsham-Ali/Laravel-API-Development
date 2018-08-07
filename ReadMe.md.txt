Installation
Install Git on your operating system.
Clone the project from the GitHub repository by running the following command in the terminal
Git clone url
Install the Composer dependencies by running inside the fo;der:
composer install

Copy the file .env.example as .env and use it as a starting point for your local development environment. You have to set it up yourself based on the environment you are using.
You can run the following command to copy the .env.example to .env:
cp .env.example .env
Run the following command to generate an application key:
Php artisan key:generate


You could also use the PHP built-in server which Laravel has a command by running:
php artisan serve

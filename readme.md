# Backend Laravel Test - Leroy Merlin

### Cloning

These commands will download the repository and prepare it for you.

```ssh
git clone https://github.com/cristianopacheco/test-leroy-merlin.git
cd test-leroy-merlin
```

### Setup

2. This is a Laravel 5.4 project
	* With Terminal:
        * Navigate to **test-leroy-merlin** folder and then:
        * `composer install` to install Laravel and third party packages
        * `touch database/database.sqlite` to create an empty database file
        * `cp .env.example .env` to configure installation
        * `php artisan key:generate` to generate unique key for the project
        * `php artisan migrate` to create all the tables
        * `php artisan serve` to serve application on localhost:8000
        * `php artisan queue:work` to start a work for processing the jobs

## Testing

Navigate to **test-leroy-merlin** folder and run the composer test script

``` bash
$ composer test
```

## Check Style

Navigate to **test-leroy-merlin** folder and run the composer test script

``` bash
$ composer check-style
```

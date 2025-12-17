## Init project
``composer install``
## Generate key
``php artisan key:generate``
## Create table
``php artisan migrate``
## Create seeder
``php artisan make:seed UserTableSeeder``
## Run seeder
``php artisan db:seed --class=UserTableSeeder``
## Create migrate
``php artisan make:migration add_fields_status_for_users_table --table=users``
## Create Request
``php artisan make:request UserRequest``
## Create Model
``php artisan make:model User -m``
## Create Command
``php artisan make:command NewsletterCommand``
## Run schedule
``php artisan newsletter:push``
## Make controller
``php artisan make:controller Api/UserController --resource``
## Rollback migrate
``php artisan migrate:rollback``






# Lumen Extended Starter Pack

This package is an extended version of the default Lumen framework with added various features by pre-configured 3rd party packages so it might be hard to catch up things if you are new to PHP and/or Lumen(Laravel) world.

My approach is to create a bootstrap framework to build "real life" applications so this package mostly shaped for my needs. You may need less or more features depending on your project size.

The package is fully covered with unit, integration and acceptance tests and also includes reports for phpunit, phpmd, phploc and phpcpd reports in `tests` folder.

> Note: If you haven'I used any included package before, i strongly suggest to check out it's site to get details about usage.

## Installation
* Install the package via git:
``` bash
git clone https://github.com/turkeryildirim/lumen-extended-starter-pack.git
```
* Run composer to install dependencies in the root folder of the package: 
 ``` bash
composer install
```
* Create your own environment file:
 ``` bash
php -r "copy('.env.example', '.env');"
```
* Make proper configurations in your `.env` file. 
* Check configurations for each file in the `config` folder.
* Run migrations and seed demo data:
``` bash
php artisan migrate:fresh --seed
```
If all above steps are successful then these demo users should be created in database:
Admin user: admin@test.com/123456
Regular user: user@test.com/123456

## How To Fire Up
* If you use `env.example` file, you need to be sure each activated service is installed and configured in your local system.
* If you use `env.extended` file, you will need to run docker images and to accomplish that you need to install docker and docker-compose. After installation you just need to run below command in the root folder of the project:
``` bash
docker-compose up
```
* You could check API documentation and use/test API endpoints from: `http://localhost/api/documantation/` 

## How To Use
> Note: If you haven'I used any included package before, i strongly suggest to check out it's site to get details about usage. Then below details could be more "clear" for you.
* You can define constants in `app/Constants` folder. It's very useful for text comparisons and also prevents typo.
* You can add more migration, factory and seeder files for your needs. Everything is same with regular Lumen. Unless application environment is set to "production", development seeders (files in `database/seeds/development` folder) will be loaded to database.
* You need to extend  `app/Models/BaseModel.php` to create new model and you need to set `transformer` method with proper definition if needed. `BaseModel` has also several methods to make your life easier.
* If `transformer` method defined in your model, you need to create a transformation file in `app/Transformers` folder. This file is also a good place to put swagger model definitions.
* You need to extend `app/Http/Controllers/Controller.php` to create new controller. This file also has two methods to create success or error response. You'd add swagger definitions too.
* If needed, you need to create policy file for the controller in `app/Policies`folder. Same with Lumen/Laravel.
* If needed, you need to create validation folder in  `app/Http/Validations` with the name of the new controller and validation rules file for each *POST* and *PUT* type request method of the controller. 
* If needed, you need to extend `app/Events/BaseEvent.php` to create new event. There are 5 types of event by default which are sms, email, push, database(which is used for on site notifications) and all. You could set it via `$type` variable while calling the event.
* If needed, you need to extend `app/Mails/BaseMail.php` to create new mail. 
* If needed, you need to extend `app/Notifications/BaseNotification.php` to create new notification. There are 5 types of notifications by default which are sms, email, push, database and all. These types are passed by an event.
* If needed, you need to create new listener in `app/Listeners/` folder.  When an event occurs, defined listener (or subscriber) calls notification class. Then that notification class also makes another call(s) (mail, sms etc.) with given event type.
* If needed, you need to extend `app/Jobs/Job.php` to create new job. Same with Lumen/Laravel.
* There 4 custom classes defined in `app/Services`
  *  *ImageStorageService*: helps you to upload, resize, crop and make thumbs operations.
  * *EmailService*: helps you to implement 3rd party mass mailing provider.
  * *PushNotificationService*: helps you to implement remote push notification service.
  * *SmsService*: helps you to implement remote sms service.
* You can use 3  route middleware in `routes/api.php`
  * *valid*: automatically run validation for the request
  * *auth*: check authentication for the request 
  * *can*: check user ability for the request (same with Lumen/Laravel)


## What Is Included Into This Package

    laravel/lumen-framework
    illuminate/notifications
    illuminate/mail
    illuminate/redis
    vlucas/phpdotenv
    guzzlehttp/guzzle
    zircote/swagger-php
    darkaonline/swagger-lume
    barryvdh/laravel-cors
    pda/pheanstalk
    flipbox/lumen-generator
    flugger/laravel-responder
    intervention/image
    turkeryildirim/lumen-config-autoloader
    ramsey/uuid
    fzaninotto/faker
    phpunit/phpunit
    mockery/mockery
    squizlabs/php_codesniffer
    barryvdh/laravel-ide-helper
    phpmd/phpmd
    phploc/phploc
    sebastian/phpcpd
    laravelista/lumen-vendor-publish
    symfony/thanks

## License
Lumen Extended Starter Pack is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

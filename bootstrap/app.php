<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades(true, [
    \Illuminate\Support\Facades\Storage::class => 'Storage',
    \Illuminate\Support\Facades\Notification::class => 'Notification',
    \Illuminate\Support\Facades\Mail::class => 'Mail',
    \Flugg\Responder\Facades\Responder::class => 'Responder',
    \Flugg\Responder\Facades\Transformation::class => 'Transformation',
    \Intervention\Image\Facades\Image::class => 'Image'
]);

$app->withEloquent();

$app->singleton(
    \Illuminate\Contracts\Debug\ExceptionHandler::class,
    \App\Exceptions\Handler::class
);

$app->singleton(
    \Illuminate\Contracts\Console\Kernel::class,
    \App\Console\Kernel::class
);

$app->routeMiddleware([
    'auth' => \App\Http\Middleware\AuthenticateMiddleware::class,
    'valid' => \App\Http\Middleware\ValidationMiddleware::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
]);

$app->middleware([
    \App\Http\Middleware\TrimStringsMiddleware::class,
    \App\Http\Middleware\ConvertEmptyStringsToNullMiddleware::class,
    \Barryvdh\Cors\HandleCors::class,
]);

// turkeryildirim/lumen-config-autoloader
$app->register(\Turker\ConfigAutoloader\ServiceProvider::class);

$app->register(\App\Providers\AppServiceProvider::class);
$app->register(\App\Providers\AuthServiceProvider::class);
$app->register(\App\Providers\EventServiceProvider::class);
$app->register(\App\Providers\EmailServiceProvider::class);
$app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);

// intervention/image
$app->register(\Intervention\Image\ImageServiceProvider::class);

// illuminate/notifications
$app->register(\Illuminate\Notifications\NotificationServiceProvider::class);

// illuminate/mail
$app->register(\Illuminate\Mail\MailServiceProvider::class);

// illuminate/redis
$app->register(\Illuminate\Redis\RedisServiceProvider::class);

// barryvdh/laravel-cors
$app->register(\Barryvdh\Cors\ServiceProvider::class);

// darkaonline/swagger-lume
$app->register(\SwaggerLume\ServiceProvider::class);

// flugger/laravel-responder
$app->register(\Flugg\Responder\ResponderServiceProvider::class);

if ($app->environment() !== 'production') {
    // barryvdh/laravel-ide-helper
    $app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

    // flipboxstudio/lumen-generator
    $app->register(\Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
}

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/api.php';
});

return $app;

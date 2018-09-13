# Lumen Extended Starter Pack

This package is NOT a simple starter kit. 3rd party packages are included to add more features and/or extend default Lumen functionality.

With default Lumen installation, you have to add a line into `bootstrap/app.php` file with `$app->configure('...');` to load package's config file for each composer or custom package.  At the beginning may be you don't know that but i mostly forgot to do that.

## Installation
* Install the package via composer
``` bash
composer require turkeryildirim/lumen-config-autoloader
```

* Create a folder named `config` in your application's root.

## Usage
You just need to copy 3rd party packages config files into **your** config folder or you can use [Lumen vendor publish](https://github.com/laravelista/lumen-vendor-publish) package.

## License
Lumen Extended Starter Pack is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

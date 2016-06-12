Laravel Bitbucket
=================

Laravel Bitbucket was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and is a [PHP Bitbucket API](https://github.com/gentlero/bitbucket-api) bridge for [Laravel 5](http://laravel.com). It utilises my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Bitbucket/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md).

![Laravel Bitbucket](https://cloud.githubusercontent.com/assets/2829600/15991648/9c381138-30b0-11e6-87e1-ad698c2dfe97.png)

<p align="center">
<a href="https://styleci.io/repos/60779513"><img src="https://styleci.io/repos/60779513/shield" alt="StyleCI Status"></img></a>
<a href="https://travis-ci.org/GrahamCampbell/Laravel-Bitbucket"><img src="https://img.shields.io/travis/GrahamCampbell/Laravel-Bitbucket/master.svg?style=flat-square" alt="Build Status"></img></a>
<a href="https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Bitbucket/code-structure"><img src="https://img.shields.io/scrutinizer/coverage/g/GrahamCampbell/Laravel-Bitbucket.svg?style=flat-square" alt="Coverage Status"></img></a>
<a href="https://scrutinizer-ci.com/g/GrahamCampbell/Laravel-Bitbucket"><img src="https://img.shields.io/scrutinizer/g/GrahamCampbell/Laravel-Bitbucket.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/GrahamCampbell/Laravel-Bitbucket/releases"><img src="https://img.shields.io/github/release/GrahamCampbell/Laravel-Bitbucket.svg?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

Either [PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+ are required.

To get the latest version of Laravel Bitbucket, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require graham-campbell/bitbucket
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "graham-campbell/bitbucket": "^1.0"
    }
}
```

Once Laravel Bitbucket is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'GrahamCampbell\Bitbucket\BitbucketServiceProvider'`

You can register the Bitbucket facade in the `aliases` key of your `config/app.php` file if you like.

* `'Bitbucket' => 'GrahamCampbell\Bitbucket\Facades\Bitbucket'`


## Configuration

Laravel Bitbucket requires connection configuration.

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish
```

This will create a `config/bitbucket.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

There are two config options:

##### Default Connection Name

This option (`'default'`) is where you may specify which of the connections below you wish to use as your default connection for all work. Of course, you may use many connections at once using the manager class. The default value for this setting is `'main'`.

##### Bitbucket Connections

This option (`'connections'`) is where each of the connections are setup for your application. Example configuration has been included, but you may add as many connections as you would like. Note that the two supported authentication methods are: `"basic"` and `"token"`.


## Usage

##### BitbucketManager

This is the class of most interest. It is bound to the ioc container as `'bitbucket'` and can be accessed using the `Facades\Bitbucket` facade. This class implements the `ManagerInterface` by extending `AbstractManager`. The interface and abstract class are both part of my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package, so you may want to go and checkout the docs for how to use the manager class over at [that repo](https://github.com/GrahamCampbell/Laravel-Manager#usage). Note that the connection class returned will always be an instance of `\Bitbucket\API\Api`.

##### Facades\Bitbucket

This facade will dynamically pass static method calls to the `'bitbucket'` object in the ioc container which by default is the `BitbucketManager` class.

##### BitbucketServiceProvider

This class contains no public methods of interest. This class should be added to the providers array in `config/app.php`. This class will setup ioc bindings.

##### Real Examples

Here you can see an example of just how simple this package is to use. Out of the box, the default adapter is `main`. After you enter your authentication details in the config file, it will just work:

```php
use GrahamCampbell\Bitbucket\Facades\Bitbucket;
// you can alias this in config/app.php if you like

Bitbucket::api('Teams')->all();
// we're done here - how easy was that, it just works!

Bitbucket::api('Repositories\Repository')->get('gentlero', 'bitbucket-api');
// this example is simple, and there are far more methods available
```

The bitbucket manager will behave like it is a `\Bitbucket\API\Api` class. If you want to call specific connections, you can do with the `connection` method:

```php
use GrahamCampbell\Bitbucket\Facades\Bitbucket;

// the alternative connection is the other example provided in the default config
Bitbucket::connection('alternative')->api('User')->emails();
```

With that in mind, note that:

```php
use GrahamCampbell\Bitbucket\Facades\Bitbucket;

// writing this:
Bitbucket::connection('main')->api('Repositories\Issues\Comments')->all('gentlero', 'bitbucket-api', 2);

// is identical to writing this:
Bitbucket::api('Repositories\Issues\Comments')->all('gentlero', 'bitbucket-api', 2);

// and is also identical to writing this:
Bitbucket::connection()->api('Repositories\Issues\Comments')->all('gentlero', 'bitbucket-api', 2);

// this is because the main connection is configured to be the default
Bitbucket::getDefaultConnection(); // this will return main

// we can change the default connection
Bitbucket::setDefaultConnection('alternative'); // the default is now alternative
```

If you prefer to use dependency injection over facades like me, then you can easily inject the manager like so:

```php
use GrahamCampbell\Bitbucket\BitbucketManager;
use Illuminate\Support\Facades\App; // you probably have this aliased already

class Foo
{
    protected $bitbucket;

    public function __construct(BitbucketManager $bitbucket)
    {
        $this->bitbucket = $bitbucket;
    }

    public function bar()
    {
        $this->bitbucket->api('Repositories\Issues\Comments')->all('gentlero', 'bitbucket-api', 2);
    }
}

App::make('Foo')->bar();
```

For more information on how to use the `\Bitbucket\API\Api` class we are calling behind the scenes here, check out the docs at http://gentlero.bitbucket.org/bitbucket-api/, and the manager class at https://github.com/GrahamCampbell/Laravel-Manager#usage.

##### Further Information

There are other classes in this package that are not documented here. This is because they are not intended for public use and are used internally by this package.


## Security

If you discover a security vulnerability within this package, please send an e-mail to Graham Campbell at graham@alt-three.com. All security vulnerabilities will be promptly addressed.


## License

Laravel Bitbucket is licensed under [The MIT License (MIT)](LICENSE).

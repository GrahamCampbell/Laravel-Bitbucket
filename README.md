Laravel Bitbucket
=================

Laravel Bitbucket was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and is a [Bitbucket PHP API Client](https://github.com/BitbucketPHP/Client) bridge for [Laravel](https://laravel.com/). It utilises my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-Bitbucket/releases), [security policy](https://github.com/GrahamCampbell/Laravel-Bitbucket/security/policy), [license](LICENSE), [code of conduct](.github/CODE_OF_CONDUCT.md), and [contribution guidelines](.github/CONTRIBUTING.md).

![Banner](https://user-images.githubusercontent.com/2829600/71477343-60993680-27e1-11ea-9e70-e6b9e459c31f.png)

<p align="center">
<a href="https://github.com/GrahamCampbell/Laravel-Bitbucket/actions?query=workflow%3ATests"><img src="https://img.shields.io/github/actions/workflow/status/GrahamCampbell/Laravel-Bitbucket/tests.yml?label=Tests&style=flat-square" alt="Build Status"></img></a>
<a href="https://github.styleci.io/repos/60779513"><img src="https://github.styleci.io/repos/60779513/shield" alt="StyleCI Status"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="Software License"></img></a>
<a href="https://packagist.org/packages/graham-campbell/bitbucket"><img src="https://img.shields.io/packagist/dt/graham-campbell/bitbucket?style=flat-square" alt="Packagist Downloads"></img></a>
<a href="https://github.com/GrahamCampbell/Laravel-Bitbucket/releases"><img src="https://img.shields.io/github/release/GrahamCampbell/Laravel-Bitbucket?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

This version requires [PHP](https://www.php.net/) 7.4-8.2 and supports [Laravel](https://laravel.com/) 8-10.

| Bitbucket | L5.5               | L5.6               | L5.7               | L5.8               | L6                 | L7                 | L8                 | L9                 | L10                |
|-----------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|
| 3.0       | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                |
| 4.3       | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                | :x:                | :x:                |
| 5.5       | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                |
| 6.0       | :x:                | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                |
| 7.3       | :x:                | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                |
| 8.1       | :x:                | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                |
| 9.0       | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :x:                |
| 10.0      | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :white_check_mark: |

To get the latest version, simply require the project using [Composer](https://getcomposer.org/):

```bash
$ composer require "graham-campbell/bitbucket:^10.0"
```

Once installed, if you are not using automatic package discovery, then you need to register the `GrahamCampbell\Bitbucket\BitbucketServiceProvider` service provider in your `config/app.php`.

You can also optionally alias our facade:

```php
        'Bitbucket' => GrahamCampbell\Bitbucket\Facades\Bitbucket::class,
```


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

This option (`'connections'`) is where each of the connections are setup for your application. Example configuration has been included, but you may add as many connections as you would like. Note that the 5 supported authentication methods are: `"jwt"`, `"none"`, `"oauth"`, `"private"` and `"password"`.

##### HTTP Cache

This option (`'cache'`) is where each of the cache configurations setup for your application. Only the "illuminate" driver is provided out of the box. Example configuration has been included.


## Usage

##### BitbucketManager

This is the class of most interest. It is bound to the ioc container as `'bitbucket'` and can be accessed using the `Facades\Bitbucket` facade. This class implements the `ManagerInterface` by extending `AbstractManager`. The interface and abstract class are both part of my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package, so you may want to go and checkout the docs for how to use the manager class over at [that repo](https://github.com/GrahamCampbell/Laravel-Manager#usage). Note that the connection class returned will always be an instance of `Bitbucket\Client`.

##### Facades\Bitbucket

This facade will dynamically pass static method calls to the `'bitbucket'` object in the ioc container which by default is the `BitbucketManager` class.

##### BitbucketServiceProvider

This class contains no public methods of interest. This class should be added to the providers array in `config/app.php`. This class will setup ioc bindings.

##### Real Examples

Here you can see an example of just how simple this package is to use. Out of the box, the default adapter is `main`. After you enter your authentication details in the config file, it will just work:

```php
use GrahamCampbell\Bitbucket\Facades\Bitbucket;
// you can alias this in config/app.php if you like

Bitbucket::currentUser()->show();
// we're done here - how easy was that, it just works!
```

The bitbucket manager will behave like it is a `Bitbucket\Client` class. If you want to call specific connections, you can do with the `connection` method:

```php
use GrahamCampbell\Bitbucket\Facades\Bitbucket;

// writing this:
Bitbucket::connection('main')->currentUser()->show();

// is identical to writing this:
Bitbucket::currentUser()->show();

// and is also identical to writing this:
Bitbucket::connection()->currentUser()->show();

// this is because the main connection is configured to be the default
Bitbucket::getDefaultConnection(); // this will return main

// we can change the default connection
Bitbucket::setDefaultConnection('alternative'); // the default is now alternative

// Get all the repositories info
Bitbucket::repositories()->list();

// Get all the repositories info filtered by workspace
Bitbucket::repositories()->workspaces('example')->list();
```

If you prefer to use dependency injection over facades like me, then you can easily inject the manager like so:

```php
use GrahamCampbell\Bitbucket\BitbucketManager;

class Foo
{
    private BitbucketManager $bitbucket;

    public function __construct(BitbucketManager $bitbucket)
    {
        $this->bitbucket = $bitbucket;
    }

    public function bar()
    {
        $this->bitbucket->currentUser()->show();
    }
}

app(Foo::class)->bar();
```

For more information on how to use the `Bitbucket\Client` class we are calling behind the scenes here, check out the docs at https://github.com/BitbucketPHP/Client/tree/v5.0.0#usage, and the manager class at https://github.com/GrahamCampbell/Laravel-Manager#usage.

##### Further Information

There are other classes in this package that are not documented here. This is because they are not intended for public use and are used internally by this package.


## Security

If you discover a security vulnerability within this package, please send an email to security@tidelift.com. All security vulnerabilities will be promptly addressed. You may view our full security policy [here](https://github.com/GrahamCampbell/Laravel-Bitbucket/security/policy).


## License

Laravel Bitbucket is licensed under [The MIT License (MIT)](LICENSE).



## For Enterprise

Available as part of the Tidelift Subscription

The maintainers of `graham-campbell/bitbucket` and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.](https://tidelift.com/subscription/pkg/packagist-graham-campbell-bitbucket?utm_source=packagist-graham-campbell-bitbucket&utm_medium=referral&utm_campaign=enterprise&utm_term=repo)

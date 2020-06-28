# CakePHP 3 REST API

A skeleton for creating a REST API using CakePHP 3 with the awesome plugins JWT Authentication and CRUD.
## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally got to project folder, run

```bash
composer update
```

You must create a database and set it on config/app.php file. After that, must run the migrations, that will create the example tables:

```sh
$ bin/cake migrations migrate
```

After that, if all goes well, the API can now be tested through a REST Client as the [Postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop), for Chrome.

You can access or retrieve data in two ways: by API or browser request. By the way, in this project, only the API requires some authorization (by JWT, but I'll explain later), if you try to access the data by browser request, you will get it. For example:

```sh
http://localhost/cake_api/cocktails
```

## Update

Since this skeleton is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades, so you have to do any updates manually.

## Configuration:

Setup `AuthComponent`:

```php
    // In your controller, for e.g. src/Api/AppController.php
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'ADmad/JwtAuth.Jwt' => [
                    'userModel' => 'Users',
                    'fields' => [
                        'username' => 'id'
                    ],

                    'parameter' => 'token',

                    // Boolean indicating whether the "sub" claim of JWT payload
                    // should be used to query the Users model and get user info.
                    // If set to `false` JWT's payload is directly returned.
                    'queryDatasource' => true,
                ]
            ],

            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize',

            // If you don't have a login action in your application set
            // 'loginAction' to false to prevent getting a MissingRouteException.
            'loginAction' => false
        ]);
    }
```
## Working

The authentication class checks for the token in two locations:

- `HTTP_AUTHORIZATION` environment variable:

  It first checks if token is passed using `Authorization` request header.
  The value should be of form `Bearer <token>`. The `Authorization` header name
  and token prefix `Bearer` can be customized using options `header` and `prefix`
  respectively.

- The query string variable specified using `parameter` config:

  Next it checks if the token is present in query string. The default variable
  name is `token` and can be customzied by using the `parameter` config shown
  above.

### Known Issue
  Some servers don't populate `$_SERVER['HTTP_AUTHORIZATION']` when
  `Authorization` header is set. So it's up to you to ensure that either
  `$_SERVER['HTTP_AUTHORIZATION']` or `$_ENV['HTTP_AUTHORIZATION']` is set.

  For e.g. for apache you could use the following:

  ```
  RewriteEngine On
  RewriteCond %{HTTP:Authorization} ^(.*)
  RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
  ```

## Token Generation

You can use `\Firebase\JWT\JWT::encode()` of the [firebase/php-jwt](https://github.com/firebase/php-jwt)
lib, which this plugin depends on, to generate tokens.

**The payload should have the "sub" (subject) claim whos value is used to query the
Users model and find record matching the "id" field.**

You can set the `queryDatasource` option to `false` to directly return the token's
payload as user info without querying datasource for matching user record.

## Further reading

For an end to end usage example check out [this](http://www.bravo-kernel.com/2015/04/how-to-add-jwt-authentication-to-a-cakephp-3-rest-api/) blog post by Bravo Kernel.

## Layout

The app skeleton uses a subset of [Foundation](http://foundation.zurb.com/) (v5) CSS
framework by default. You can, however, replace it with any other library or
custom styles.

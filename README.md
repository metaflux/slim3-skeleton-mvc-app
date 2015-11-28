# SlimPHP 3 Skeleton MVC App

This is a template web-application (powered by SlimPHP 3), that can be extended to build more complex web applications.

It adds the Model-View-Controller structure to a SlimPHP3 web-application.

It ships with the Foundation 5 template (http://foundation.zurb.com/).

## Requirements

* PHP 5.5+
* Pdo sqlite (3) extension for Authentication in non-production environments
* Composer (https://getcomposer.org)

## Installation (Creating a Project)

* Get the most recent development version (creates the project from the master branch in the repo)

  **`$ composer create-project -n -s dev rotexsoft/slim3-skeleton-mvc-app my-app`**

* Get the most stable version (creates the project from the most recent tagged release in the repo)

  **`$ composer create-project -n rotexsoft/slim3-skeleton-mvc-app my-app`**

### Testing the Installation

1. **`$ cd my-app`**

2. **`$ php -S 0.0.0.0:8888 -t public`**

3. Browse to [http://localhost:8888](http://localhost:8888)

  * Below are the default links that are available upon installation:

    * [http://localhost:8888/base-controller/action-index/](http://localhost:8888/base-controller/action-index/) same as [http://localhost:8888/base-controller/](http://localhost:8888/base-controller/)
    * [http://localhost:8888/base-controller/action-login/](http://localhost:8888/base-controller/action-login/) comes with 2 default accounts **admin:admin** and **root:root**
    * [http://localhost:8888/base-controller/action-logout/0](http://localhost:8888/base-controller/action-logout/0)
    * [http://localhost:8888/base-controller/action-logout/1](http://localhost:8888/base-controller/action-logout/1)
    * [http://localhost:8888/base-controller/action-login-status/](http://localhost:8888/base-controller/action-login-status/)
    * [http://localhost:8888/hello/action-index/](http://localhost:8888/hello/action-index/) same as [http://localhost:8888/hello/](http://localhost:8888/hello/)
    * [http://localhost:8888/hello/action-login/](http://localhost:8888/hello/action-login/) comes with 2 default accounts **admin:admin** and **root:root**
    * [http://localhost:8888/hello/action-logout/0](http://localhost:8888/hello/action-logout/0)
    * [http://localhost:8888/hello/action-logout/1](http://localhost:8888/hello/action-logout/1)
    * [http://localhost:8888/hello/action-login-status/](http://localhost:8888/hello/action-login-status/)
    * `http://localhost:8888/hello/action-world/{name}/{another_parameter}`
      * you can do stuff like [http://localhost:8888/hello/action-world/john/doe](http://localhost:8888/hello/action-world/john/doe)

  * The `action-` prefix can be omitted from the links above if `S3MVC_APP_AUTO_PREPEND_ACTION_TO_ACTION_METHOD_NAMES` is set to `true`
    * For example [http://localhost:8888/hello/action-login/](http://localhost:8888/hello/action-login/) will become [http://localhost:8888/hello/login/](http://localhost:8888/hello/login/)

* You may need to modify the **`RewriteBase`** directive in the **`public/.htaccess`** file, if you are using aliases in your apache web server and are getting 404 errors

## Key directories 
* **`config`:** Contains files for configuring the application
 
* **`logs`:** Log files

* **`public`:** Webserver root

* **`public/css`:** Your application's css files should be placed here 

* **`public/image`:** Your application's image files should be placed here

* **`public/js`:** Your application's javascript files should be placed here

* **`src/controllers`:** Your application's controller classes should be placed here

* **`src/layout-templates`:** The layout template(s) for your application should be placed here

* **`src/models`:** Your application's model classes should be placed here

* **`src/views`:** Should contain view files (associated with each of your controller classes' action) that should be rendered into your application's layout template(s)

* **`tests`:** Place files for testing your application (eg. PHPUnit test cases) here

* **`tmp`:** Temporary files generated by your application (like session files) should be placed here

* **`vendor`:** Composer dependencies


## Key files 
* **`composer.json`:** contains your application's composer dependencies

* **`README.md`:** Add documentation for your application here.

* **`config/dependencies.php`:** Add dependencies to SlimPHP3's dependency injection container (ie. Pimple) here.

    * Below are the objects that are registered in the container:

        * **`errorHandler:`** An anonymous function that handles all uncaught PHP exceptions in your application. See http://www.slimframework.com/docs/handlers/error.html for more details.

        * **`notFoundHandler:`** An anonymous function that handles all request urls that do not match any of the routes defined in your application (ie. in `public/index.php` or `config/routes.php`). See http://www.slimframework.com/docs/handlers/not-found.html for more details.

        * **`notAllowedHandler:`** An anonymous function that handles all requests whose **HTTP Request Method** does not match any of the **HTTP Request Methods** associated with the routes defined in your application (ie. in `public/index.php` or `config/routes.php`). See http://www.slimframework.com/docs/handlers/not-allowed.html for more details.

        * **`logger:`** A PSR-3 compliant logger, that can be used for logging in your application. See https://bitbucket.org/jelofson/vespula.log for more details on how to configure this logger to suit your application's needs.

            ```php
            <?php
                //You can access the logger from within your controller like so:
                $this->app->getContainer()->get('logger');
            ?>
            ```

        * **`namespaces_for_controllers:`** An array containing a list of the namespaces that your application's controller classes belong to. If all your controllers are in the global namespace (like the **`Hello`** controller that ships with this package), then you don't need to update **`namespaces_for_controllers`**. The default namespace that ships with this package is `'\\Slim3MvcTools\\Controllers\\'` (the namespace where `BaseController` belongs).  

        * **`new_layout_renderer:`** An object used for rendering layout-template(s) for your application (see the `renderLayout` method in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`). See https://github.com/rotexsoft/file-renderer for more details on how to configure this object.

        * **`new_view_renderer:`** An object used for rendering view file(s) associated with each action method in the controller(s) for your application (see the `renderView` method in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`). See https://github.com/rotexsoft/file-renderer for more details on how to configure this object.

        * **`vespula_auth:`** An object used by the `BaseController` to implement authentication functionality (see the `isLoggedIn`, `actionLogin`, `actionLogout` and `actionLoginStatus` methods in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`). See https://bitbucket.org/jelofson/vespula.auth for more details on how to configure this object.

            ```php
            <?php
                //You can access the auth object from within your controller like so:
                $this->app->getContainer()->get('vespula_auth');
            ?>
            ```

* **`config/env.php`:** Edit it to define your application's environment. It should return one of **S3MVC_APP_ENV_DEV**, **S3MVC_APP_ENV_PRODUCTION**, **S3MVC_APP_ENV_STAGING** or **S3MVC_APP_ENV_TESTING** relevant to the environment you are installing your web-app.

* **`config/ini-settings.php`:** Modify ini settings via `ini_set(..)` here. Remember to update `date.timezone` in this file to match your timezone.

* **`config/routes.php`:** Add additional routes for your application here (if needed). You can decide to define all the routes for your application here (in this case set the **S3MVC_APP_USE_MVC_ROUTES** constant in `public/index.php` to false). A default `/` route is defined in this file and will be active if **S3MVC_APP_USE_MVC_ROUTES** has a value of false.

* **`public/.htaccess`:** Apache web-server settings.

* **`public/index.php`:** Entry point to application.

* **`src/controllers/Hello.php`:** Example Controller class.

* **`src/layout-templates/main-template.php`:** Default site template based on Foundation 5.

* **`src/views/base/index.php`:** View file associated with the `actionIndex` method in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`.

* **`src/views/base/login.php`:** View file associated with the `actionLogin` method in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`.

* **`src/views/base/login-status.php`:** View file associated with the `actionLoginStatus` method in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`.

* **`src/views/hello/world.php`:** View file associated with the `world` method in `src/controllers/Hello.php`.

## Configuration
* To use LDAP autehntication, you will need to update the values for the *$server*, *'basedn'*, *'bindpw'*, *'searchfilter'* and '*$dnformat*' in the *'aura_auth_adapter_object'* entry in the dependency injection container.
> You can optionally change the default *'successful_login_callback'* callback.

## MVC Functionality
* `s3MVC_CreateController(\Slim\App $app, $controller_name_from_url, $action_name_from_url, \Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)`
* `s3MVC_DumpVar($v)`
* `s3MVC_GetBaseUrlPath()`
* `s3MVC_GetCurrentAppEnvironment()`
* `s3MVC_GetSuperGlobal($global_name='', $key='', $default_val='')`
* S3MVC_APP_PUBLIC_PATH
* S3MVC_APP_ROOT_PATH
* S3MVC_APP_USE_MVC_ROUTES
* S3MVC_APP_DEFAULT_CONTROLLER_CLASS_NAME
* S3MVC_APP_DEFAULT_ACTION_NAME

* Helper script for creating controller classes and a default index view:

    * `php ./vendor/rotexsoft/slim3-skeleton-mvc-tools/src/scripts/create-controller.php`

* Controller classes must extend `\Slim3MvcTools\BaseController`
    
    //default route with default controller and default action
    `/`

    //controller with no action and params route handler

    `/{controller}[/]`

    //controller with action and optional params route handler

    `/{controller}/{action}[/{parameters:.+}]`

    `/{controller}/{action}/` **//handle trailing slash**




Action methods in Controller classes MUST either return a string (i.e. containing the output to display to the client)
or an instance of Psr\Http\Message\ResponseInterface (e.g. $response, that has the output to be displayed to the client, 
injected into it via $response->getBody()->write($data) );

### Security Considerations

* Make sure to validate / sanitize the password value posted to `\Slim3MvcTools\BaseController::actionLogin()` in your Controller(s). It is deliberately left un-sanitized and un-validated because each application should define which characters are allowed in passwords and validation / sanitization should be based on the allowed characters.

## Documentation for Components Used
* SlimPHP 3 http://www.slimframework.com/docs/
* Slim3 Skeleton MVC Tools https://github.com/rotexsoft/slim3-skeleton-mvc-tools contains BaseController.php and other Slim3 Skeleton MVC specific classes and functions 
* Vespula.Log https://bitbucket.org/jelofson/vespula.log (a PSR-3 compliant logger)
* Vespula.Auth for Authentication https://bitbucket.org/jelofson/vespula.auth
* See http://pimple.sensiolabs.org/ for more information on how the dependency injection container used by *SlimPHP 3* works
* File-Renderer https://github.com/rotexsoft/file-renderer for rendering the template and view files


## References
* https://getcomposer.org/doc/articles/scripts.md
* https://devedge.wordpress.com/2014/11/05/building-better-project-skeletons-with-composer-2/
* http://www.binpress.com/tutorial/better-project-skeletons-with-composer/157


## SlimPHP 3's Implementation of PSR-7

![Class Diagram of SlimPHP 3's Implementation of PSR-7](slim3-psr7.png)

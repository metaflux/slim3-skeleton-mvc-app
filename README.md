# SlimPHP 3 Skeleton MVC App

This is a template web-application (powered by SlimPHP 3), that can be extended to build more complex web applications.

It adds the Model-View-Controller structure to a SlimPHP3 web-application.

It ships with the Foundation 5 template (http://foundation.zurb.com/)

## Requirements

* PHP 5.5+
* Pdo sqlite (3) extension for Authentication in non-production environments
* Composer (https://getcomposer.org)

## Installation (Creating a Project)

  $ composer create-project -n -s dev rotexsoft/slim3-skeleton-mvc-app my-app

### Testing the Installation

1. `$ cd my-app` 

2. `$ php -S 0.0.0.0:8888 -t public` 

3. Browse to http://localhost:8888

  * Below are the default links that are available upon installation

    * `http://localhost:8888/base-controller/action-index/` same as `http://localhost:8888/base-controller/`
    * `http://localhost:8888/base-controller/action-login/` comes with 2 default accounts **admin:admin** and **root:root**
    * `http://localhost:8888/base-controller/action-logout/0`
    * `http://localhost:8888/base-controller/action-logout/1`
    * `http://localhost:8888/base-controller/action-login-status/`
    * `http://localhost:8888/hello/action-index/` same as `http://localhost:8888/hello/`
    * `http://localhost:8888/hello/action-login/` comes with 2 default accounts **admin:admin** and **root:root**
    * `http://localhost:8888/hello/action-logout/0`
    * `http://localhost:8888/hello/action-logout/1`
    * `http://localhost:8888/hello/action-login-status/`
    * `http://localhost:8888/hello/world/{name}/{another_parameter}`
      * you can do stuff like http://localhost:8888/hello/world/john/doe

* You may need to modify the `RewriteBase` directive in the `public/.htaccess` file, if you are using aliases in your apache web server and are getting 404 errors

## Key directories 
* `config`: Contains files for configuring the application 
* `logs`: Log files
* `public`: Webserver root
* `public/css`: Your application's css files should be placed here 
* `public/image`: Your application's image files should be placed here 
* `public/js`: Your application's javascript files should be placed here
* `src/controllers`: Your application's controller classes should be placed here
* `src/layout-templates`:The layout template(s) for your application should be placed here
* `src/models`: Your application's model classes should be placed here
* `src/views`: Should contain view files (associated with each of your controller classes' action) that should be rendered into your application's layout template(s)
* `tests`: Place files for testing your application (eg. PHPUnit test cases) here
* `tmp`: Temporary files generated by your application (like session files) should be placed here
* `vendor`: Composer dependencies


## Key files 
* `composer.json`: contains your application's composer dependencies
* `README.md`: Add documentation for your application here
* `config/dependencies.php`: Add dependencies to SlimPHP3's dependency injection container (ie. Pimple) here
* `config/env.php`: Edit it to define your application's environment. It should return one of **S3MVC_APP_ENV_DEV**, **S3MVC_APP_ENV_PRODUCTION**, **S3MVC_APP_ENV_STAGING** or **S3MVC_APP_ENV_TESTING** relevant to the environment you are installing your web-app.
* `config/ini-settings.php`: Modify ini settings via `ini_set(..)` here
* `config/routes.php`: Add additional routes for your application here (if needed). You can decide to define all the routes for your application here (in this case set the **use_mvc_routes** entry in `config/dependencies.php` to false).
* `public/.htaccess`: Apache web-server settings
* `public/index.php`: Entry point to application
* `src/controllers/Hello.php`: Example Controller class
* `src/layout-templates/main-template.php`: Default site template based on Foundation 5
* `src/views/base/index.php`: View file associated with the `actionIndex` method in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`
* `src/views/base/login.php`: View file associated with the `actionLogin` method in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`
* `src/views/base/login-status.php`: View file associated with the `actionLoginStatus` method in `vendor/rotexsoft/slim3-skeleton-mvc-tools/src/BaseController.php`
* `src/views/hello/world.php`: View file associated with the `world` method in `src/controllers/Hello.php`

## Configuration
* To use LDAP autehntication, you will need to update the values for the *$server*, *'basedn'*, *'bindpw'*, *'searchfilter'* and '*$dnformat*' in the *'aura_auth_adapter_object'* entry in the dependency injection container.
> You can optionally change the default *'successful_login_callback'* callback.

## MVC Functionality
* `s3MVC_CreateController(\Slim\App $app, $controller_name_from_url, $action_name_from_url, \Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)`
* `s3MVC_DumpVar($v)`
* `s3MVC_GetBaseUrlPath()`
* `s3MVC_GetCurrentAppEnvironment()`
* `s3MVC_GetSuperGlobal($global_name='', $key='', $default_val='')`

* Controller classes must extend `\Slim3MvcTools\BaseController`

    //controller with no action and params route handler
    `/{controller}[/]`

    //controller with action and optional params route handler
    `/{controller}/{action}[/{parameters:.+}]`
    `/{controller}/{action}/` **//handle trailing slash**

### Registered Objects / items in the Container

* **`use_mvc_routes:`**
* **`logger:`**
* **`namespaces_for_controllers:`**
* **`default_controller_class_name:`**
* **`default_action_name:`**
* **`new_layout_renderer:`**
* **`new_view_renderer:`**
* **`aura_auth_factory:`**
* **`aura_auth_object:`**
* **`aura_auth_adapter_object:`**
* **`aura_login_service:`**
* **`aura_logout_service:`**
* **`aura_resume_service:`**

Action methods in Controller classes MUST either return a string (i.e. containing the output to display to the client)
or an instance of Psr\Http\Message\ResponseInterface (e.g. $response, that has the output to be displayed to the client, 
injected into it via $response->getBody()->write($data) );


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

# MVC Functionality

```php
<?php
namespace Slim3SkeletonMvcApp\Controllers;

class Hello extends \Slim3MvcTools\Controllers\BaseController
{
    public function __construct(
        \Interop\Container\ContainerInterface $container, 
        $controller_name_from_uri, $action_name_from_uri, 
        \Psr\Http\Message\ServerRequestInterface $req, 
        \Psr\Http\Message\ResponseInterface $res     
    ) {
        parent::__construct($container, $controller_name_from_uri, $action_name_from_uri, $req, $res);
    }
    
    public function actionIndex() {

        return 'in Hello::actionIndex()<br>';
    }
    
    public function actionThere($first_name, $last_name) {

        return "Hello There $first_name, $last_name";
    }
}
?>
```
**Figure 2: an Hello Controller class**

* There are four routes that are defined in the **`./public/index.php`** file to handle MVC requests
* These four routes will be enabled, if and only if **S3MVC_APP_USE_MVC_ROUTES** is set to **`true`**
* Below are the four routes:
    * **`/`**: the **default route**. It creates an instance of the default controller (configurable via **S3MVC_APP_DEFAULT_CONTROLLER_CLASS_NAME**) and executes the default action method (configurable via **S3MVC_APP_DEFAULT_ACTION_NAME**) on the default controller. This route should be used for the home-page of your app.
        * For example, given **S3MVC_APP_DEFAULT_CONTROLLER_CLASS_NAME** with a value of **`'\\Slim3SkeletonMvcApp\\Controllers\\Hello'`** and **S3MVC_APP_DEFAULT_ACTION_NAME** with a value of **`'actionIndex()'`**, the **default route** will lead to the execution of **`'\\Slim3SkeletonMvcApp\\Controllers\\Hello::actionIndex()'`**
    * **`/{controller}[/]`**: the **controller only route**. It creates an instance of the controller specified in the url and executes the default action method (configurable via **S3MVC_APP_DEFAULT_ACTION_NAME**) on the specified controller.
        * For example, given a route with a value of **`/hello`** and **S3MVC_APP_DEFAULT_ACTION_NAME** with a value of **`'actionIndex()'`**, the **controller only route** will lead to the execution of **`'\\Slim3SkeletonMvcApp\\Controllers\\Hello::actionIndex()'`**
    * **`/{controller}/{action}/`**: the **controller and action only route**. It creates an instance of the controller specified in the url and executes the action method specified in the url on the specified controller. The specified method in the specified controller should not accept any parameters / arguments.
        * For example, given a route with a value of **`/hello/index/`**, the **controller and action only route** will lead to the execution of **`'\\Slim3SkeletonMvcApp\\Controllers\\Hello::actionIndex()'`**
    * **`/{controller}/{action}[/{parameters:.+}]`**: the **controller, action and optional parameters route**. It creates an instance of the controller specified in the url and executes the action method specified in the url (with the parameters specified in the url, if any) on the specified controller.
        * For example, given a route with a value of **`/hello/there/john/Doe`**, the **controller, action and optional parameters route** will lead to the execution of **`'\\Slim3SkeletonMvcApp\\Controllers\\Hello::actionThere('john', 'Doe')'`**
        * This route also responds to **`/{controller}/{action}`** (without a trailing slash). In this case the controller method specified via **`{action}`** part of the url must not accept any arguments or parameters.

### **More on Controllers and MVC Routes** 
* Controller classes must extend `\Slim3MvcTools\Controllers\BaseController`. These classes must be named using studly case / caps e.g. **StaticPages**, **MobileDataProviders** and must be referenced in the controller segment of the url in all lowercases with dashes preceding capital case characters (except for the first capital case character). For example, `http://localhost:8888/mobile-data-providers/` will be responded to by the default action (defined via **`S3MVC_APP_DEFAULT_ACTION_NAME`**; default value is **`actionIndex()`** ) method in the controller named **MobileDataProviders**, `http://localhost:8888/mobile-data-providers/list-providers` or `http://localhost:8888/mobile-data-providers/action-list-providers` (if **`S3MVC_APP_AUTO_PREPEND_ACTION_TO_ACTION_METHOD_NAMES`** is set to **`false`**) will be responded to by the **`actionListProviders()`** method in the controller named **`MobileDataProviders`**, etc.
    * Controller action methods should be named using camel case (e.g. **`listProviders()`** ). In addition, they must be prefixed with the word **`action`** if **`S3MVC_APP_AUTO_PREPEND_ACTION_TO_ACTION_METHOD_NAMES`** is set to `true`; in this case **`actionListProviders()`**

* Action methods in Controller classes MUST either return a string (ie. containing the output to display to the client) or an instance of **Psr\Http\Message\ResponseInterface** (e.g. $response, that has the output to be displayed to the client, injected into it via `$response->getBody()->write($data)` );

* **`The default route with default controller and default action route handler:`** The default route handler responds to the **`/`** route by creating an instance of the default controller (defined via **`S3MVC_APP_DEFAULT_CONTROLLER_CLASS_NAME:`**) and calling the default action method (defined via **`S3MVC_APP_DEFAULT_ACTION_NAME:`**) on the controller object and returning the result as a response object (if the method returns a string the default route handler will write the string into a response object and return that object). 

* **`The controller with action and optional params route handler:`** The mvc route handler responds to the **`/{controller}/{action}[/{parameters:.+}]`** and **`/{controller}/{action}/`** routes by going through the steps below:
    * extracting the **`{controller}`**, **`{action}`** and **`{parameters}`** segments of a request uri. Eg. http://localhost:8888/hello/action-world/john/doe will lead to `hello` being extracted as the value of the **`{controller}`** segment, `action-world` being extracted as the value of the **`{action}`** segment and `['john', 'doe']` as the value of the **`{parameters}`** segment. It then converts the value of the **`{action}`** segment to camel case; in this case from `action-world` to `actionWorld`. If **`S3MVC_APP_AUTO_PREPEND_ACTION_TO_ACTION_METHOD_NAMES`** is set to `true` then the handler will try to prepend the string `'action'` to the camel-cased value of the **`{action}`** segment; however in this case it will not prepend the string `'action'` to `actionWorld` since it already starts with the string `action`. It then goes on to validate that `actionWorld` is a valid name for a php class' method name, if it's an invalid name it will invoke the **`notFoundHandler`** which will lead to a 404 not found response. If it's avalid method name it tries to create an instance of a controller class by first converting the value of the **`{controller}`** segment, in this case `hello`, to studly case which will lead to `hello` being converted to `Hello` and it then goes on to validate that `Hello` is a valid name for a php class, if it's an invalid name it will invoke the **`notFoundHandler`** which will lead to a 404 not found response. If it's a valid class name, it then goes on to check if the class exists in the gloabal namespace first, and if not, then it continues checking in the namespaces registered in the container (**`namespaces_for_controllers`**). If the class does not exist, it will invoke the **`notFoundHandler`** which will lead to a 404 not found response. Else if the class exists, an instance will be created. The handler then goes on to check if the method named `actionWorld` exists in the instance of the controller class just created. If the method doesn't exist, the handler will invoke the **`notFoundHandler`** which will lead to a 404 not found response. Else if the method exists it will be called on the created controller object with the values of the **`{parameters}`** segment (in this case `['john', 'doe']`) as arguments (ie. $instance_of_hello_controller->actionWorld('john', 'doe')) and the result will be returned as a response object (if the method returns a string the handler will write the string into a response object and return that object). Note that if there are no values supplied for the **`{parameters}`** segment, the action method will be called on the controller with no parameter ($instance_of_hello_controller->actionWorld()) this happens when the **`/{controller}/{action}/`** route is matched. 
    
* **`The controller with no action and no params route handler:`** `/{controller}[/]`: works in a similar manner that the handler that handles the **`/{controller}/{action}[/{parameters:.+}]`** and **`/{controller}/{action}/`** routes. Except that the value of **`S3MVC_APP_DEFAULT_ACTION_NAME`** is used for the method name and the method will always be invoke with no parameters.

### **Controller Execution Flow** 
Middlewares added to your app, like the one in **Figure 6**, will be executed for all routes (MVC ones above included) in your app.
You can also use the **`preAction()`** and  **`postAction(\Psr\Http\Message\ResponseInterface $response)`** methods 
in any of your controllers to inject code you want to be executed before and after each action method is run during 
a request to an action in a specific controller. You can create a BaseController 
(which extends `\Slim3MvcTools\Controllers\BaseController`) in your app which all 
your app's controllers will extend. This BaseController's 
**`preAction()`** and  **`postAction(\Psr\Http\Message\ResponseInterface $response)`** methods
can then be used to implement common logic which you want to be executed before and after any 
action method is run in any of your controllers.

In a nutshell; for each request, middleware code is first executed 
(in Slim 3 the handler associated with the route matched for the current request
is also executed as a middleware). When the route handler for any of the MVC routes mentioned above is executed,
the **`preAction()`** method for the current controller is executed first, followed by the current action method
and then followed by the **`postAction(\Psr\Http\Message\ResponseInterface $response)`** method for the current 
controller. Finally, other middleware code (if any) is executed after the route handler for the current request 
has been executed. 

Given the code in **figures 5** and **6** below, executing **`http://localhost:8888/hello/`** will generate the output in Figure 3 below and executing **`http://localhost:8888/hello/action-there/john/Doe`** (or **`http://localhost:8888/hello/there/john/Doe`** if **S3MVC_APP_AUTO_PREPEND_ACTION_TO_ACTION_METHOD_NAMES** is set to **`true`**) will generate the output in Figure 4 below:

```
in Middleware before current route handler
in Hello::__construct(...)
in Hello::preAction()
in Hello::actionIndex()
in Hello::postAction(\Psr\Http\Message\ResponseInterface $response)
in Middleware after current route handler
```
**Figure 3: Output of executing** `http://localhost:8888/hello/`

```
in Middleware before current route handler
in Hello::__construct(...)
in Hello::preAction()
Hello There john, Doe
in Hello::postAction(\Psr\Http\Message\ResponseInterface $response)
in Middleware after current route handler
```
**Figure 4: Output of executing** `http://localhost:8888/hello/action-there/john/Doe`

```php
<?php
namespace Slim3SkeletonMvcApp\Controllers;

class Hello extends \Slim3MvcTools\Controllers\BaseController
{
    public function __construct(
        \Interop\Container\ContainerInterface $container, 
        $controller_name_from_uri, $action_name_from_uri, 
        \Psr\Http\Message\ServerRequestInterface $req, 
        \Psr\Http\Message\ResponseInterface $res     
    ) {
        parent::__construct($container, $controller_name_from_uri, $action_name_from_uri, $req, $res);
        $this->response->getBody()->write('in Hello::__construct(...)<br>'); 
    }
    
    public function actionIndex() {

        return 'in Hello::actionIndex()<br>';
    }
    
    public function actionThere($first_name, $last_name) {

        return "Hello There $first_name, $last_name<br>";
    }
    
    public function preAction() {
        
        // add code that you need to be executed before each controller action method is executed
        $response = parent::preAction();
        $response->getBody()->write('in Hello::preAction()<br>'); 
        
        return $response;
    }
    
    public function postAction(\Psr\Http\Message\ResponseInterface $response) {
        
        // add code that you need to be executed after each controller action method is executed
        $response = parent::postAction($response);
        $response->getBody()->write('in Hello::postAction(\Psr\Http\Message\ResponseInterface $response)<br>');
        
        return $response;
    }
}
```
**Figure 5: Example Hello Controller Class**

```php
<?php
$app->add(function (\Psr\Http\Message\ServerRequestInterface $req, \Psr\Http\Message\ResponseInterface $res, $next) {

    $res->getBody()->write('in Middleware before current route handler<br>');
    
    $new_response = $next($req, $res); // this will eventually execute the route handler
                                       // for the matched route for the current request.
                                       // This is where the controller is instantiated 
                                       // and the appropriate controller method is 
                                       // invoked with / without parameters.
                                                
    $new_response->getBody()->write('in Middleware after current route handler<br>');
    
    return $new_response;
});
?>
```
**Figure 6: Sample middleware that should be placed in `./config/routes-and-middlewares.php`**

### **Using the File Renderer for Rendering Views and Layouts inside Controller Action Methods** 
????????????
Talk about cascading view system in renderView( $file_name, array $data = [] ).

### **Creating Controller Classes via the Commnadline** 
????????????
????????????
* Helper script for creating controller classes and a default index view:

    * On *nix-like Oses
        * `./vendor/bin/s3mvc-create-controller`

    * On Windows
        * `.\vendor\bin\s3mvc-create-controller.bat`


### S3MVC Helper Functions
* **`s3MVC_CreateController(\Interop\Container\ContainerInterface $container, $controller_name_from_url, $action_name_from_url, \Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)`:** used by the route handlers to create controllers to handle mvc routes. You should not really need to call this function.
* **`s3MVC_DumpVar($v)`:** for dumping variables during development for debugging purposes.
* **`s3MVC_GetBaseUrlPath()`:** performs the same function as \Slim\Http\Uri::getBasePath()
* **`s3MVC_MakeLink($path)`:** prepends **s3MVC_GetBaseUrlPath()** followed by **/** to $path and returns the prepended string. Use this for generating links in your application.
* **`s3MVC_GetSuperGlobal($global_name='', $key='', $default_val='')`:** a helper function for accessing super globals.
* **`s3MVC_UriToString(\Psr\Http\Message\UriInterface $uri)`:** a helper function for converting PSR-7 uri objects to a string.
* **`s3MVC_addQueryStrParamToUri(\Psr\Http\Message\UriInterface $uri, $param_name, $param_value)`:** a helper function for adding query string parameters to PSR-7 uri objects.
* **`s3MVC_psr7RequestObjToString(\Psr\Http\Message\ServerRequestInterface $req,...)`:** a helper function for dumping PSR-7 request objects for debugging purposes.
* **`s3MVC_psr7UploadedFileToString(\Psr\Http\Message\UploadedFileInterface $file)`:** a helper function for dumping PSR-7 file objects for debugging purposes.


## Security Considerations
* Make sure to validate / sanitize the password value posted to `\Slim3MvcTools\Controllers\BaseController::actionLogin()` in your Controller(s). It is deliberately left un-sanitized and un-validated because each application should define which characters are allowed in passwords and validation / sanitization should be based on the allowed characters.
* When configuring your webserver, make sure you set the document root to your site to the **`./public`** folder so that attackers won't be able to browse directly to sensitive folders like the **`config`** or **`src`** folders of your application. For apache HTTP webservers, **`.htaccess`** files (each with a directive to prevent browsing directly to folders) are provided in the **`config`**, **`logs`**, **`src`**, **`tests`** and **`tmp`** folders out of the box. For NGINX users, you should look into configuring something similar to prevent direct browsing to sensitive folders of your application.

## Documentation for Components Used
* SlimPHP 3 http://www.slimframework.com/docs/
* Slim3 Skeleton MVC Tools https://github.com/rotexsoft/slim3-skeleton-mvc-tools contains BaseController.php and other Slim3 Skeleton MVC specific classes and functions 
* Vespula.Log https://bitbucket.org/jelofson/vespula.log (a PSR-3 compliant logger)
* Vespula.Auth for Authentication https://bitbucket.org/jelofson/vespula.auth
* File-Renderer https://github.com/rotexsoft/file-renderer for rendering the template and view files
* See http://pimple.sensiolabs.org/ for more information on how the dependency injection container used by *SlimPHP 3* works


## References
* https://getcomposer.org/doc/articles/scripts.md
* https://devedge.wordpress.com/2014/11/05/building-better-project-skeletons-with-composer-2/
* http://www.binpress.com/tutorial/better-project-skeletons-with-composer/157


## SlimPHP 3's Implementation of PSR-7

![Class Diagram of SlimPHP 3's Implementation of PSR-7](../slim3-psr7.png)

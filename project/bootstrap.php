<?php
/**
 * Bootstrap the project.
 *
 * PHP version 5.4
 *
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    0.0.1
 */
namespace Project;

use Yadeo\Core\AutoLoader,
    Yadeo\Auth\OAuth\Factory as OAuthFactory,
    Yadeo\Storage\Medium\Database,
    Yadeo\Format\Formatter\Markdown,
    Yadeo\Http\Request,
    Project\Core\Factory as CoreFactory,
    Yadeo\Router\RewriteEngine,
    Yadeo\Router\RoutesParser\ArrayParser,
    Yadeo\Router\Route\Route,
    Project\Core\Delegator;

/**
 * Setup error reporting
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

/**
 * Bootstrap the library
 */
ini_set('date.timezone', 'Europe/Amsterdam');

/**
 * Bootstrap the library
 */
require_once '/../lib/Yadeo/bootstrap.php';

/**
 * Bootstrap the oAuth library
 */
require_once '/../PHPoAuthLib/src/OAuth/bootstrap.php';

/**
 * Setup library autoloader
 */
$projectAutoloader = new AutoLoader(__NAMESPACE__, dirname(__DIR__));
$projectAutoloader->register();

/**
 * Create an instance of the OAuth factory
 */
require_once '/oauth.php';
$storageMedium = new OAuthFactory($oauthServiceCredentials);

/**
 * Create an instance of the storage medium of choice
 */
$storageMedium = new Database('pgsql:host=127.0.0.1;dbname=yadeo', 'user', 'pass');

/**
 * Create an instance of the article and comment parser of choice
 */
$articleFormatter = new Markdown();
$commentFormatter = new Markdown(false, false);

/**
 * Create an instance of the HTTP request
 */
$request = new Request(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE
);

/**
 * Create an instance of the factory class
 */
$factory = new CoreFactory($storageMedium, $articleFormatter, $commentFormatter, $request);

/**
 * Create an instance of the Rewrite engine
 */
$rewriteEngine = new RewriteEngine($request);

/**
 * Register the routes in the rewrite engine
 */
require_once '/routes.php';
$routeParser = new ArrayParser($routes);
foreach($routeParser as $key => $route) {
    $rewriteEngine->registerRoute(
        new Route($routeParser)
    );
}

/**
 * Get the current route
 */
$route = $rewriteEngine->getRouteOfRequest();

/**
 * Get the base page template
 */
$pageTemplate = '';
if ($route->getResponseType() == 'html') {
    ob_start();
    require_once '/templates/index.phtml';
    $pageTemplate = ob_get_contents();
    ob_end_clean();
}

/**
 * Get the content of the page
 */
$delegator = new Delegator($storageMedium, $factory, $route->getVariables($request), $request);
$content = $delegator->{$route->getDelegationMethod()}();

/**
 * Render the view
 */
/*
$view = new \Yadeo\Core\View($pageTemplate, $content);
print $view->render();
*/

print str_replace('{{content}}', $content, $pageTemplate);
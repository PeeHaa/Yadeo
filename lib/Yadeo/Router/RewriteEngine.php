<?php
/**
 * Rewrite engine. Matches a request with a route.
 *
 * PHP version 5.3
 *
 * @category   Yadeo
 * @package    Router
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    0.0.1
 */
namespace Yadeo\Router;

/**
 * Rewrite engine. Matches a request with a route.
 *
 * @category   Yadeo
 * @package    Router
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class RewriteEngine implements \Yadeo\Router\Engine
{
    /**
     * @var \Yadeo\Http\Request The request object
     */
    protected $request;

    /**
     * @var array An array with routes of type \Yadeo\Router\Route
     */
    protected $routes = array();

    /**
     * Creates the instance
     *
     * @param \Yadeo\Http\Request $request The request object to match the routes against
     */
    public function __construct(\Yadeo\Http\Request $request)
    {
        $this->request = $request;
    }

    /**
     * Registers routes
     *
     * @param \Yadeo\Router\Route $route The route to register
     */
    public function registerRoute(\Yadeo\Router\Route $route)
    {
        $this->routes[$route->getName()] = $route;
    }

    /**
     * Gets the route which matches with the request
     *
     * @param \Yadeo\Router\Route $route The route which matches the request or the 404 route when
     *                                   no match has been found
     */
    public function getRouteOfRequest()
    {
        foreach($this->routes as $route) {
            if ($route->doesRequestMatchRoute($this->request)) {
                return $route;
            }
        }

        return $this->routes[404];
    }
}
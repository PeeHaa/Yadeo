<?php
/**
 * Router engine interface. All routers should implement this interface.
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
 * Router engine interface. All routers should implement this interface.
 *
 * @category   Yadeo
 * @package    Router
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
interface Engine
{
    /**
     * Adds an route to the routes array
     *
     * @param \Yadeo\Router\Route $route The route to add
     */
    public function registerRoute(\Yadeo\Router\Route $route);

    /**
     * Gets the route which matches the request or returns the 404 route when no match is found
     *
     * @return \Yadeo\Router\Route $route The matching route or the 404 route
     */
    public function getRouteOfRequest();
}
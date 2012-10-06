<?php
/**
 * The array parser for routes in an array.
 *
 * PHP version 5.3
 *
 * @category   Yadeo
 * @package    Router
 * @subpackage RoutesParser
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    0.0.1
 */
namespace Yadeo\Router\RoutesParser;

/**
 * The array parser for routes in an array.
 *
 * @category   Yadeo
 * @package    Router
 * @subpackege RoutesParser
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class ArrayParser implements \Yadeo\Router\RoutesParser
{
    /**
     * @var array The array of routes
     */
    protected $routes = array();

    /**
     * Creates the instance
     *
     * @param array $routes The routes to parse
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Gets the current route
     *
     * @return array The current route
     */
    public function current()
    {
        return current($this->routes);
    }

    /**
     * Gets the current route key
     *
     * @return array The current route key
     */
    public function key()
    {
        return key($this->routes);
    }

    /**
     * Gets the next route
     *
     * @return array The next route
     */
    public function next()
    {
        return next($this->routes);
    }

    /**
     * Reset the pointer of the array
     *
     * @return array The first route
     */
    public function rewind()
    {
        return reset($this->routes);
    }

    /**
     * Validates the current pointer
     *
     * @return boolean Whether the current pointer is valid
     */
    public function valid()
    {
        return current($this->routes) !== false;
    }

    /**
     * Gets the target of the current route
     *
     * @return string The target
     */
    public function getTarget()
    {
        return $this->key();
    }

    /**
     * Gets the URI of the current route
     *
     * @return string The URI
     */
    public function getUri()
    {
        $currentRoute = $this->current();

        return $currentRoute['uri'];
    }

    /**
     * Gets the requirements of the current route
     *
     * @return string The requirements
     */
    public function getRequirements()
    {
        $currentRoute = $this->current();

        if (array_key_exists('requirements', $currentRoute)) {
            return $currentRoute['requirements'];
        }

        return array();
    }

    /**
     * Gets the method of the current route
     *
     * @return string The route
     */
    public function getMethod()
    {
        $currentRoute = $this->current();

        return $currentRoute['method'];
    }

    /**
     * Gets the response type of the current route
     *
     * @return string The response type
     */
    public function getResponse()
    {
        $currentRoute = $this->current();

        return $currentRoute['response'];
    }

    /**
     * Gets the template of the current route
     *
     * @return string The template
     */
    public function getDelegationMethod()
    {
        $currentRoute = $this->current();

        return $currentRoute['delegationMethod'];
    }
}
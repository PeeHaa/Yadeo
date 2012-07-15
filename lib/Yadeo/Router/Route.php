<?php
/**
 * Route interface. All routes should implement this interface.
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
 * Route interface. All routes should implement this interface.
 *
 * @category   Yadeo
 * @package    Router
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
interface Route
{
    /**
     * Gets the name of the current route
     *
     * @return string The name
     */
    public function getName();

    /**
     * Gets the target of the current route
     *
     * @return string The target
     */
    public function getTarget();

    /**
     * Gets the URI of the current route
     *
     * @return string The URI
     */
    public function getUri();

    /**
     * Gets the method of the current route
     *
     * @return string The route
     */
    public function getMethod();

    /**
     * Gets the response type of the current route
     *
     * @return string The response type
     */
    public function getResponseType();

    /**
     * Gets the template of the current route
     *
     * @return string The template
     */
    public function getTemplate();

    /**
     * Verifies whether the route matches the request
     *
     * @param \Yadeo\Http\Request $request The current request object
     *
     * @return boolean Whether the route matches the current request
     */
    public function doesRequestMatchRoute(\Yadeo\Http\Request $request);
}
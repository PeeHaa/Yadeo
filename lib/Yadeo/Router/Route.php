<?php
/**
 * Route interface. All routes should implement this interface.
 *
 * PHP version 5.3
 *
 * @category   Yadeo
 * @package    Router
 * @subpackage Route
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    0.0.1
 */
namespace Yadoe\Router;

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
     * Verifies whether the route matches the request
     *
     * @param \Yadeo\Http\Request $request The current request object
     *
     * @return boolean Whether the route matches the current request
     */
    public function doesRequestMatchRoute(\Yadeo\Http\Request $request);
}
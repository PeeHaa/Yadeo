<?php
/**
 * The Parser interface. All classes which can parse routes should implement this interface.
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
namespace Yadeo\Router\Route;

/**
 * The Parser interface. All classes which can parse routes should implement this interface.
 *
 * @category   Yadeo
 * @package    Storage
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
interface Parser extends \Iterator
{
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
    public function getResponse();
}
<?php
/**
 * Route class.
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
 * Route class.
 *
 * @category   Yadeo
 * @package    Router
 * @subpackage Route
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class Route implements \Yadeo\Router\Route
{
    /**
     * @var string The name of the route
     */
    protected $name;

    /**
     * @var string The URI of the route
     */
    protected $uri;

    /**
     * @var string The requirements of the route
     */
    protected $requirements = array();

    /**
     * @var string The HTTP method of the route
     */
    protected $method;

    /**
     * @var string The response type of the route
     */
    protected $responseType;

    /**
     * @var string The template
     */
    protected $delegationMethod;

    protected $variables = array();

    /**
     * Creates the instance
     *
     * @param \Yadeo\Router\RoutesParser $routeParser The route supplied by the routeparser
     */
    public function __construct(\Yadeo\Router\RoutesParser $routeParser)
    {
        $this->name             = $routeParser->key();
        $this->uri              = $routeParser->getUri();
        $this->requirements     = $routeParser->getRequirements();
        $this->method           = $routeParser->getMethod();
        $this->responseType     = $routeParser->getResponse();
        $this->delegationMethod = $routeParser->getDelegationMethod();
    }

    /**
     * Gets the name of the current route
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the target of the current route
     *
     * @return string The target
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Gets the URI of the current route
     *
     * @return string The URI
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Gets the requirements of the current route
     *
     * @return string The requirements
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * Gets the method of the current route
     *
     * @return string The route
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the response type of the current route
     *
     * @return string The response type
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * Gets the template
     *
     * @return string The template
     */
    public function getDelegationMethod()
    {
        return $this->delegationMethod;
    }

    /**
     * Verifies whether the route matches the request
     *
     * @param \Yadeo\Http\Request $request The current request object
     *
     * @return boolean Whether the route matches the current request
     */
    public function doesRequestMatchRoute(\Yadeo\Http\Request $request)
    {
        if (!$this->doesHttpMethodMatch($request->getMethod())) {
            return false;
        }

        if (!$this->doesUriPartsCountMatch($request->getPath())) {
            return false;
        }

        if (!$this->doRequirementsMatch($request)) {
            return false;
        }

        return $this->doesUriMatch($request->getPath());
    }

    /**
     * Verifies whether the HTTP method of the route matches the HTTP method of the request
     *
     * @param string $method The HTTP method of the current request
     *
     * @return boolean Whether the HTTP method of the route matches the current request
     */
    protected function doesHttpMethodMatch($method)
    {
        if ($this->getMethod() == '*' || $this->getMethod() == $method) {
            return true;
        }

        return false;
    }

    /**
     * Verifies whether the number of URI parts matches the route
     *
     * @param array $uriParts The URI parts
     *
     * @return boolean Whether the count of the URI parts of the route matches the current request
     */
    protected function doesUriPartsCountMatch(array $uriParts)
    {
        if (count($this->getUriParts()) == count($uriParts)) {
            return true;
        }

        return false;
    }

    /**
     * Verifies whether the URI matches with the route
     *
     * @param array $uriParts The URI parts
     *
     * @return boolean Whether the URI of the route matches the current request
     */
    protected function doesUriMatch(array $uriParts)
    {
        $routeUriParts = $this->getUriParts();
        foreach($routeUriParts as $index => $routePart) {
            if ($this->isUriPartVariable($routePart)) {
                continue;
            }

            if ($routePart != $uriParts[$index]) {
                return false;
            }
        }

        return true;
    }

    protected function getUriParts()
    {
        return explode('/', trim($this->getUri(), '/'));
    }

    protected function isUriPartVariable($uriPart)
    {
        if (strpos($uriPart, ':') === 0) {
            return true;
        }

        return false;
    }

    protected function doRequirementsMatch(\Yadeo\Http\Request $request)
    {
        $requestVariables = $this->getVariables($request);

        foreach($this->getRequirements() as $uriVariable => $pattern) {
            if (array_key_exists($uriVariable, $requestVariables)) {
                if (!preg_match($pattern, $requestVariables[$uriVariable])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Gets the variables defined in the URI
     *
     * @param \Yadeo\Http\Request $request The current request object
     *
     * @return array The variables defined in the URI
     */
    public function getVariables(\Yadeo\Http\Request $request)
    {
        $requestUriParts = $request->getPath();
        foreach($this->getUriParts() as $index => $uriPart) {
            if ($this->isUriPartVariable($uriPart)) {
                $this->variables[substr($uriPart, 1)] = $requestUriParts[$index];
            }
        }

        return $this->variables;
    }
}
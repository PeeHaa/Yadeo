<?php
/**
 * HTTP request class.
 *
 * PHP version 5.3
 *
 * @category   Yadeo
 * @package    Http
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    0.0.1
 */
namespace Yadeo\Http;

/**
 * HTTP request class.
 *
 * @category   Yadeo
 * @package    Http
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class Request
{
    /**
     * @var string The URI
     */
    protected $uri;

    /**
     * @var array The request parts
     */
    protected $uriParts;

    /**
     * @var null|string The request scheme
     */
    protected $scheme = null;

    /**
     * @var null|string The request host
     */
    protected $host = null;

    /**
     * @var null|string The request port
     */
    protected $port = null;

    /**
     * @var null|string The request user
     */
    protected $user = null;

    /**
     * @var null|string The request pass
     */
    protected $password = null;

    /**
     * @var array The request path
     */
    protected $path = array();

    /**
     * @var array The request query
     */
    protected $query = array();

    /**
     * @var null|string The request fragment
     */
    protected $fragment = null;

    /**
     * @var string The request method
     */
    protected $method = null;

    /**
     * Creates the instance
     *
     * @param string $uri The URI
     * @param string $method The HTTP method
     */
    public function __construct($uri, $method)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->uriParts = parse_url($this->uri);

        $this->parseUri();
    }

    /**
     * Parse the current request
     */
    protected function parseUri()
    {
        foreach($this->uriParts as $name => $value) {
            switch($name) {
                case 'scheme':
                case 'host':
                case 'port':
                case 'user':
                case 'pass':
                case 'fragment':
                    $this->$name = $value;
                    break;


                case 'path':
                    $this->$name = explode('/', rtrim($value, '/'));
                    break;

                case 'query':
                    $this->$name = explode('&', $this->trimQueryString($value));
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Trim query string from an url
     *
     * @param string $url The url with (possibly) a query string
     *
     * @return string The url without a querystring
     */
    protected function trimQueryString($url)
    {
        return strtok($url, '?');
    }

    /**
     * Gets the HTTP method used
     *
     * @return string The HTTP method used
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the HTTP scheme
     *
     * @return string The HTTP scheme
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Gets the hostname
     *
     * @return string The hostname
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Gets the port
     *
     * @return string The port
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Gets the username
     *
     * @return string The username
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Gets the password
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Gets the path
     *
     * @return array The path parts
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Gets the query
     *
     * @return array The querystring parameters
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Gets the fragment
     *
     * @return string The fragment
     */
    public function getFragment()
    {
        return $this->fragment;
    }
}
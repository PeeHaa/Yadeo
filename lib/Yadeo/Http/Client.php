<?php
/**
 * Implementation of an pure PHP HTTP client
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
 * Implementation of an pure PHP HTTP client
 *
 * @category   Yadeo
 * @package    Http
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class Client
{
    /**
     * @var string The possible HTTP methods
     */
    const GET     = 'GET';
    const POST    = 'POST';
    const PUT     = 'PUT';
    const HEAD    = 'HEAD';
    const DELETE  = 'DELETE';
    const TRACE   = 'TRACE';
    const OPTIONS = 'OPTIONS';
    const CONNECT = 'CONNECT';
    const MERGE   = 'MERGE';

    /**
     * @var string The possible HTTP versions
     */
    const HTTP_0 = '1.0';
    const HTTP_1 = '1.1';

    /**
     * @var string The possible form request methods
     */
    const ENC_URLENCODED = 'application/x-www-form-urlencoded';
    const ENC_FORMDATA   = 'multipart/form-data';

    /**
     * @var array The HTTP client options
     */
    protected $options = array(
        'maxredirects'    => 5,
        'strictredirects' => false,
        'useragent'       => 'Yadeo_Http_Client',
        'referer'         => null,
        'timeout'         => 10,
        'httpversion'     => self::HTTP_1,
        'keepalive'       => false,
        'storeresponse'   => true,
        'strict'          => true,
        'output_stream'   => false,
        'encodecookies'   => true,
        'verifypeer'      => 1,
    );

    /**
     * @var string The URI to make the request to
     */
    protected $uri = null;

    /**
     * @var \Yadeo\Core\Factory The factory
     */
    protected $factory;

    /**
     * @var string The headers of the request
     */
    protected $headers = array();

    /**
     * @var string The request method
     */
    protected $method = self::GET;

    /**
     * @var array The get parameters
     */
    protected $paramsGet = array();

    /**
     * @var string The post parameters
     */
    protected $paramsPost = array();

    /**
     * @var null|string The encoding type
     */
    protected $encType = null;

    /**
     * @var array The responses of the requests
     */
    protected $responses = array();

    /**
     * Creates the instance
     *
     * @param string $uri The URI to send the request to
     * @param array $options The options to send with the request
     * @return void
     */
    public function __construct($uri, \Yadeo\Core\Factory $factory, array $options = array())
    {
        if ($uri !== null) {
            $this->setUri($uri);
        }

        if ($options) {
            $this->setOptions($options);
        }

        $this->factory = $factory;
    }

    /**
     * Sets the URI used to make the request
     *
     * @param string $uri The URI to send the request to
     * @return void
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Gets the URI used to make the request
     *
     * @return string $uri The URI to send the request to
     */
    protected function getUri()
    {
        return $this->uri;
    }

    /**
     * Sets the options of the client
     *
     * @param array $options The options
     * @return void
     */
    public function setOptions($options)
    {
        foreach($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Sets the request method
     *
     * @todo Validate the method used
     * @param string $method The method to use for the request
     * @return void
     */
    public function setMethod($method = self::GET)
    {
        if ($method == self::POST && $this->encType === null) {
            $this->setEncType(self::ENC_URLENCODED);
        }

        $this->method = $method;
    }

    /**
     * Sets the header used in the request
     *
     * @param string $name The name of the header
     * @param null|string $value The value of the header
     * @return void
     */
    public function setHeaders($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                if (is_string($key)) {
                    $this->setHeaders($key, $value);
                } else {
                    $this->setHeaders($value, null);
                }
            }
        } else {
            $normalized_name = strtolower($name);

            if ($value === null || $value === false) {
                unset($this->headers[$normalized_name]);
            } else {
                if (is_string($value)) {
                    $value = trim($value);
                }
                $this->headers[$normalized_name] = $value;
            }
        }
    }

    protected function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sets the encoding type of the request
     *
     * @param string $encType The encoding of the request
     * @return void
     */
    public function setEncType($encType = self::ENC_URLENCODED)
    {
        $this->encType = $encType;
    }

    /**
     * Makes an HTTP request
     *
     * @todo Add error checking for pages not returning within timeout etc
     * @todo Make CURLOPT_HEADER optional by introducing setter and getter
     * @param null|string $uri The URI to make the request to
     * @param int $redirects The numbers of redirects followed
     * @return void
     */
    public function request($uri = null, $redirects = 0)
    {
        if ($uri === null) {
            $uri = $this->uri;
        }

        if ($redirects == $this->options['maxredirects']) {
            return null;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);

        if ($this->options['referer'] !== null) {
            curl_setopt($ch, CURLOPT_REFERER, $this->options['referer']);
        }

//        if ($uri->getScheme() == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->options['verifypeer']);
//        }

        $headers = $this->getHeaders();
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_USERAGENT, $this->options['useragent']);
        curl_setopt($ch, CURLOPT_HEADER, 1); // should be an option / getter, setter
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->options['timeout']);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        $http_response = $this->factory->buildClientResponse($response);

        $this->addResponse($http_response, $info);

        if (floor($info['http_code'] / 100) != 3) {
            return null;
        }

        $redirects++;

        $http_response->parseResponse();
        $headers = $http_response->getHeaders();

        if (!array_key_exists('Location', $headers)) {
            return null;
        }

        $uri = $headers['Location'];

        $this->request($uri, $redirects);
    }

    /**
     * Adds a response to the list of responses
     *
     * @param \Yadeo\Http\ClientResponse $response The response
     * @param array $info The info about of the response
     * @return void
     */
    protected function addResponse(\Yadeo\Http\ClientResponse $response, $info = array())
    {
        $response->parseResponse();

        $this->responses[] = array('headers'=>$response->getHeaders(),
                                   'body'=>$response->getBody(),
                                   'info'=>$info);
    }

    /**
     * Gets all the responses
     *
     * @return array The responses
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Gets the last response
     *
     * @return array The last response
     */
    public function getLastResponse()
    {
        $responses = $this->getResponses();

        return end($responses);
    }
}
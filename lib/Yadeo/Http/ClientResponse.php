<?php
/**
 * Retrieves information about an HTTP response
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
 * Retrieves information about an HTTP response
 *
 * @category   Yadeo
 * @package    Http
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class ClientResponse
{
    /**
     * @var string The raw response
     */
    protected $rawResponse;

    /**
     * @var string The raw headers
     */
    protected $rawHeader;

    /**
     * @var array The headers of the response
     */
    protected $headers = array();

    /**
     * @var null|string The body of the response
     */
    protected $body = null;

    /**
     * Creates the instance
     *
     * @param null|string $response The raw response
     * @return void
     */
    public function __construct($response = null)
    {
        if ($response !== null) {
            $this->setRawResponse($response);
        }
    }

    /**
     * Sets the raw response
     *
     * @param string $response The raw response
     * @return void
     */
    public function setRawResponse($response)
    {
        $this->rawResponse = $response;
    }

    /**
     * Gets the raw response
     *
     * @return string $response The raw response
     */
    protected function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * Parses the raw response
     *
     * @return void
     */
    public function parseResponse()
    {
        $this->separateHeadersAndBody();

        $this->parseRawHeader();
    }

    /**
     * Separates the headers from the body
     *
     * @return void
     */
    protected function separateHeadersAndBody()
    {
        $response_parts = explode("\r\n\r\n", $this->getRawResponse());

        $this->setRawHeader($response_parts[0]);
        if (isset($response_parts[1])) {
            $this->setBody($response_parts[1]);
        }
    }

    /**
     * Sets the raw header
     *
     * @param string $header The raw header
     * @return void
     */
    protected function setRawHeader($header)
    {
        $this->rawHeader = $header;
    }

    /**
     * Gets the raw header
     *
     * @return string The raw header
     */
    protected function getRawHeader()
    {
        return $this->rawHeader;
    }

    /**
     * Parses the raw header
     *
     * @return void
     */
    protected function parseRawHeader()
    {
        $header_parts = explode("\r\n", $this->getRawHeader());

        foreach($header_parts as $part) {
            if (strpos($part, ':') !== False) {
                $keyvaluepair = explode(':', $part, 2);

                $this->headers[trim($keyvaluepair[0])] = trim($keyvaluepair[1]);
            } else {
                $this->headers['http_status'] = trim($part);
            }
        }
    }

    /**
     * Gets the headers
     *
     * @return array The header
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sets the body
     *
     * @param string $body The body
     * @return void
     */
    protected function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Gets the body
     *
     * @return string The body
     */
    public function getBody()
    {
        return $this->body;
    }
}
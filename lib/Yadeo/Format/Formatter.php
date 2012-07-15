<?php
/**
 * The Formatter interface. Formatting engines (e.g. markdown class) should implement this interface
 *
 * PHP version 5.3
 *
 * @category   Yadeo
 * @package    Format
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    1.0.0
 */
namespace Yadeo\Format;

/**
 * The Formatter interface. Formatting engines (e.g. markdown class) should implement this interface
 *
 * @category   Yadeo
 * @package    Format
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
interface Formatter
{
    /**
     * Parses text
     *
     * @param string $data The data to be parsed
     *
     * @return string The parsed data
     */
    public function parse($data);
}
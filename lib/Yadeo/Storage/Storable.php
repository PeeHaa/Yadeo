<?php
/**
 * The Storable interface. All classes which are storing data somewhere should implement this interface.
 *
 * PHP version 5.3
 *
 * @category   Yadeo
 * @package    Storage
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    0.0.1
 */
namespace Yadeo\Storage;

/**
 * The Storable interface. All classes which are storing data somewhere should implement this interface.
 *
 * @category   Yadeo
 * @package    Storage
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
interface Storable
{
    /**
     * Saves the object in our storage medium
     *
     * @return void
     */
    public function save();
}
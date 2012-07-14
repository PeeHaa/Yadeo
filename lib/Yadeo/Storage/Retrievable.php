<?php
/**
 * The Retrievable interface. All classes which are storing data should implement this interface.
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
 * The Retrievable interface. All classes which are storing data should implement this interface.
 *
 * @category   Yadeo
 * @package    Storage
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
interface Retrievable
{
    /**
     * Retrieves an item from the storage medium
     *
     * @param mixed $identifier The unique identifier of the item we are going to retrieve
     */
    public function get($identifier);

    /**
     * Retrieves an item from the storage medium
     *
     * @param mixed $searchCriteria The search criteria used for fniding items
     * @param \Yadeo\Paginator $pagniator A paginator instance
     * @param string $sortField The name of the field on which we are going to sort
     */
    public function search($searchCriteria, \Yadeo\Paginator $paginator, $sortField = 'id');
}
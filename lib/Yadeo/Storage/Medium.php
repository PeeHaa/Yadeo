<?php
/**
 * The storage medium interface. All classes which represent a storage medium should implemenent this interface
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
 * The storage medium interface. All classes which represent a storage medium should implemenent this interface
 *
 * @category   Yadeo
 * @package    Storage
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
interface Medium
{
    /**
     * Gets the total number of articles
     *
     * @return int The total number of articles
     */
    public function getNumberOfArticles();

    /**
     * Gets an article
     *
     * @param mixed $identifier The unique identifier of the article
     *
     * @return array|null The articledata when found match with identifier
     */
    public function getArticle($identifier);

    /**
     * Gets articles
     *
     * @param \Yadeo\Paginator $paginator An paginator instance
     *
     * @return array An empty array or an array filled with \Yadeo\Core\Article articles
     */
    public function getArticles(\Yadeo\Paginator $paginator);

    /**
     * Saves an article
     * When the article object contains an identifier it is updated otherwise it is added
     *
     * @param \Yadeo\Storage\Storable $article The article to store
     *
     * @return bool Whether the article is saved successfully
     */
    public function saveArticle(\Yadeo\Core\Article $article);

    /**
     * Gets a comment
     *
     * @param mixed $identifier The unique identifier of the comment
     *
     * @return array|null The comment data when found match with identifier
     */
    public function getComment($identifier);

    /**
     * Saves a comment
     * When the comment object contains an identifier it is updated otherwise it is added
     *
     * @param \Yadeo\Storage\Storable $data The comment to store
     *
     * @return bool Whether the comment is saved successfully
     */
    public function saveComment(\Yadeo\Core\Comment $comment);
}
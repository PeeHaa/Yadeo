<?php

namespace YadeoTest\Mocks\Storage\Medium;

class StorageObject implements \Yadeo\Storage\Medium
{
    /**
     * Gets the total number of articles
     *
     * @return int The total number of articles
     */
    public function getNumberOfArticles()
    {
        return 10;
    }

    /**
     * Gets an article
     *
     * @param mixed $identifiers The unique identifier of the article
     *
     * @return array|null The articledata when found match with identifier
     */
    public function getArticle($identifiers)
    {
        return array(
            // need to fill the array with data
        );
    }

    /**
     * Gets articles
     *
     * @param \Yadeo\Paginator $paginator An paginator instance
     *
     * @return array An empty array or an array filled with \Yadeo\Core\Article articles
     */
    public function getArticles(\Yadeo\Paginator $paginator)
    {
        return array(
            // need to fill the array with data
        );
    }

    /**
     * Saves an article
     * When the article object contains an identifier it is updated otherwise it is added
     *
     * @param \Yadeo\Storage\Storable $article The article to store
     *
     * @return bool Whether the article is saved successfully
     */
    public function saveArticle(\Yadeo\Storage\Storable $article)
    {
    }

    /**
     * Gets a comment
     *
     * @param mixed $identifier The unique identifier of the comment
     *
     * @return array|null The comment data when found match with identifier
     */
    public function getComment($identifier)
    {
    }

    /**
     * Saves a comment
     * When the comment object contains an identifier it is updated otherwise it is added
     *
     * @param \Yadeo\Storage\Storable $data The comment to store
     *
     * @return bool Whether the comment is saved successfully
     */
    public function saveComment(\Yadeo\Storage\Storable $data)
    {
    }
}
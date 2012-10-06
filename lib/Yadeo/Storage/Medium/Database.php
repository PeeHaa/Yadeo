<?php

namespace Yadeo\Storage\Medium;

class Database implements \Yadeo\Storage\Medium
{
    protected $connection;

    /**
     * Creates an instance of the class
     *
     * @param string $dsn The connections string
     * @param string $username The username used to connect to the database
     * @param string $password The password used to connect to the database
     */
    public function __construct($dsn, $username, $password)
    {
        $this->connection = new \PDO($dsn, $username, $password);
        $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Gets the total number of articles
     *
     * @return int The total number of articles
     */
    public function getNumberOfArticles()
    {
        $query = "SELECT count(id) FROM article";

        $stm = $this->connection->query($query);

        return $stm->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Gets an article
     *
     * @param mixed $identifiers The unique identifier of the article
     *
     * @todo Note that postgresql uses EXTRACT to get the month/year from a timestamp
     *       but mysql uses MONTH() and YEAR() functions
     * @todo Setup better query: http://stackoverflow.com/questions/11658191/getting-data-from-three-tables-in-one-query#comment15449922_11658191
     *
     * @return array|null The articledata when found match with identifier
     */
    public function getArticle($identifiers)
    {
        $query = 'SELECT DISTINCT tags.tag,';
        $query.= ' article.id, article.title, article.intro, article.message, article.timestamp, article.slug';
        $query.= ' FROM tags';
        $query.= ' RIGHT JOIN';
        $query.= '  (SELECT id, title, intro, message, timestamp, slug';
        $query.= '  FROM article';
        $query.= '  WHERE slug = :slug';
        $query.= '  AND EXTRACT(YEAR FROM timestamp) = :year';
        $query.= '  AND EXTRACT(MONTH FROM timestamp) = :month) article';
        $query.= ' ON article.id = tags.article';

        $stm = $this->connection->prepare($query);
        $stm->execute(array(
            ':slug'     => $identifiers['slug'],
            ':year'     => $identifiers['year'],
            ':month'    => $identifiers['month'],
        ));

        return $stm->fetchAll(\PDO::FETCH_OBJ);
    }

    // note mysql uses isnull() instead of coalesce
    // note that we expect the comments to be in the right order for us to later be able to build a tree
    public function getCommentsOfArticle($identifier)
    {
        $query = 'SELECT comment.id, comment.userid, comment.article, comment.parentid, comment.ip, comment.message,';
        $query.= ' comment.timestamp, coalesce(SUM(vote.points), 0) as points'; // comment user
        $query.= ' FROM comment';
        $query.= ' LEFT JOIN vote ON comment.id = vote.comment';
        $query.= ' WHERE comment.article = :articleid';
        $query.= ' GROUP BY comment.id, comment.userid, comment.article, comment.parentid, comment.ip, comment.message,';
        $query.= ' comment.timestamp';
        $query.= ' ORDER BY comment.id DESC';

        $stm = $this->connection->prepare($query);
        $stm->execute(array(
            ':articleid' => $identifier,
        ));

        return $stm->fetchAll(\PDO::FETCH_OBJ);
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
        $query = 'SELECT tags.tag,';
        $query.= ' article.id, article.title, article.intro, article.message, article.timestamp, article.slug,';
        $query.= ' comment.count';
        $query.= ' FROM tags';
        $query.= ' RIGHT JOIN';
        $query.= '  (SELECT id, title, intro, message, timestamp, slug';
        $query.= '  FROM article';
        $query.= '  ORDER BY article.id';
        $query.= '  DESC LIMIT :limit';
        $query.= '  OFFSET :offset) article';
        $query.= ' ON article.id = tags.article';
        $query.= ' LEFT JOIN (SELECT article, count(id) FROM comment GROUP BY article) comment';
        $query.= ' ON article.id = comment.article';
        $query.= ' ORDER BY article.id DESC';

        $stm = $this->connection->prepare($query);
        $stm->execute(array(
            ':limit'    => $paginator->getPageSize(),
            ':offset'   => (($paginator->getCurrentPage() - 1) * $paginator->getPageSize()),
        ));

        return $stm->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Saves an article
     * When the article object contains an identifier it is updated otherwise it is added
     *
     * @param \Yadeo\Storage\Storable $article The article to store
     *
     * @return bool Whether the article is saved successfully
     */
    public function saveArticle(\Yadeo\Core\Article $article)
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
    public function saveComment(\Yadeo\Core\Comment $comment)
    {
        if (!$comment->getIdentifier()) {
            return $this->insertComment($comment);
        } else {
            return $this->updateComment($comment);
        }
    }

    protected function insertComment(\Yadeo\Core\Comment $comment)
    {
        $query = 'INSERT INTO comment';
        $query.= ' (article, ip, parentid, message, userid) VALUES';
        $query.= ' (:articleid, :ip, :parentid, :message, :user)';

        $stm = $this->connection->prepare($query);
        $stm->execute(array(
            ':articleid'    => $comment->getArticleId(),
            ':user'         => $comment->getUser(),
            ':parentid'     => $comment->getParentId(),
            ':ip'           => $comment->getIp(),
            ':message'      => $comment->getMessage(),
        ));

        return $this->connection->lastInsertId('comment_id_seq');
    }

    // need to add user
    protected function updateComment(\Yadeo\Core\Comment $comment)
    {
        $query = 'UPDATE comment';
        $query.= ' SET article = :articleid,';
        $query.= ' ip = :ip,';
        $query.= ' message = :message';
        $query.= ' WHERE id = :id';

        $stm = $this->connection->prepare($query);
        return $stm->execute(array(
            ':articleid'    => $comment->getArticleId(),
            ':ip'           => $comment->getIp(),
            ':message'      => $comment->getMessage(),
            ':id'           => $comment->getIdentifier(),
        ));
    }

    public function saveVote($commentId, $points)
    {
        $query = 'INSERT INTO vote';
        $query.= ' (comment, points) VALUES';
        $query.= ' (:commentid, :points)';

        $stm = $this->connection->prepare($query);
        $stm->execute(array(
            ':commentid' => $commentId,
            ':points'    => $points,
        ));

        $query = 'SELECT sum(points)';
        $query.= ' FROM vote';
        $query.= ' WHERE comment = :commentid';

        $stm = $this->connection->prepare($query);
        $stm->execute(array(
            ':commentid' => $commentId,
        ));

        $result = $stm->fetch(\PDO::FETCH_OBJ);
        return $result->sum;
    }
}
<?php

namespace Yadeo\Core;

class Comment implements \Yadeo\Storage\Storable, \Yadeo\Storage\Retrievable
{
    protected $storageMedium;

    protected $contentParser;

    protected $identifier = null;
    protected $articleId = null;
    protected $userId = null;
    protected $ip = null;
    protected $parentId = null;
    protected $message = null;
    protected $timestamp = null;
    protected $children = array();
    protected $points = 0;

    public function __construct(\Yadeo\Storage\Medium $storageMedium, \Yadeo\Format\Formatter $contentParser)
    {
        $this->storageMedium = $storageMedium;
        $this->contentParser = $contentParser;
    }

    public function get($identifier)
    {
        $this->setData($this->storageMedium->getArticle());
    }

    public function setData($articleData)
    {
        foreach($articleData as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getArticleId()
    {
        return $this->articleId;
    }

    public function getUser()
    {
        return $this->userId;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getParsedMessage()
    {
        return $this->contentParser->parse($this->message);
    }

    public function getTimestamp($format = 'Y-m-d H:i:s')
    {
        $timestamp = new \DateTime($this->timestamp);

        return $timestamp->format($format);
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function search($searchCriteria, \Yadeo\Paginator $paginator, $sortField = 'id')
    {
        return array();
    }

    public function save()
    {
        return $this->storageMedium->saveComment($this);
    }

    public function addChild(\Yadeo\Core\Comment $comment)
    {
        $this->children[$comment->getIdentifier()] = $comment;
    }

    public function getChildren()
    {
        return $this->children;
    }
}
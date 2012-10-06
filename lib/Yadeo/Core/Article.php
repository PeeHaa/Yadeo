<?php

namespace Yadeo\Core;

class Article implements \Yadeo\Storage\Storable, \Yadeo\Storage\Retrievable
{
    protected $storageMedium;

    protected $contentParser;

    protected $factory;

    protected $identifier = null;
    protected $title = null;
    protected $slug = null;
    protected $intro = null;
    protected $message = null;
    protected $timestamp = null;
    protected $tags = array();
    protected $commentCount = 0;
    protected $comments = array();

    public function __construct(\Yadeo\Storage\Medium $storageMedium, \Yadeo\Format\Formatter $contentParser, \Yadeo\Core\Factory $factory)
    {
        $this->storageMedium = $storageMedium;
        $this->contentParser = $contentParser;
        $this->factory = $factory;
    }

    public function get($identifiers)
    {
        $articleRecordset = $this->storageMedium->getArticle($identifiers);
        $this->parseRecordset($articleRecordset);

        $commentRecordset = $this->storageMedium->getCommentsOfArticle($this->identifier);
        $this->parseCommentRecordset($commentRecordset);
    }

    protected function parseRecordset(array $recordset)
    {
        $this->identifier = $recordset[0]->id;
        $this->title = $recordset[0]->title;
        $this->slug = $recordset[0]->slug;
        $this->intro = $recordset[0]->intro;
        $this->message = $recordset[0]->message;
        $this->timestamp = $recordset[0]->timestamp;
        $this->tags = array();
        $this->commentCount = 0;
        $this->comments = array();

        foreach($recordset as $record) {
            if ($record->tag && !in_array($record->tag, $this->tags)) {
                $this->tags[] = $record->tag;
            }
        }
    }

    protected function parseCommentRecordset(array $commentRecordset)
    {
        $comments = array();
        foreach($commentRecordset as $record) {
            $commentObj = $this->factory->buildComment();

            $commentObj->setData(array(
                'identifier' => $record->id,
                'parentId' => $record->parentid,
                'articleId' => $record->article,
                'userId' => $record->userid,
                'ip' => $record->ip,
                'message' => $record->message,
                'timestamp' => $record->timestamp,
                'points' => $record->points,
            ));

            $comments[$commentObj->getIdentifier()] = $commentObj;
        }

        $this->commentCount = count($comments);
        $this->comments = $this->createCommentTree($comments);
    }

    protected function createCommentTree($comments)
    {
        $commentTree = array();
        foreach($comments as $commentId => $comment) {
            if (!$comment->getParentid()) {
                continue;
            }

            if (array_key_exists($comment->getParentId(), $comments)) {
                $comments[$comment->getParentId()]->addChild($comment);
            }

            unset($comments[$commentId]);
        }

        return $this->sortCommentTree($comments);
    }

    protected function sortCommentTree(array $comments)
    {
        foreach($comments as $comment) {
            $children = $comment->getChildren();
            if ($children > 0) {
                $comment->setData(array('children' => $this->sortCommentTree($children)));
            }
        }

        return array_reverse($comments, true);
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

    public function getTitle()
    {
        return $this->title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getIntro()
    {
        return $this->intro;
    }

    public function getParsedIntro()
    {
        return $this->contentParser->parse($this->intro);
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

    public function getTags()
    {
        return $this->tags;
    }

    public function getCommentCount()
    {
        return $this->commentCount;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getUri()
    {
        $uriParts = array(
            $this->getTimestamp('Y'),
            $this->getTimestamp('m'),
            $this->getSlug(),
        );

        return '/' . implode('/', $uriParts);
    }

    public function search($searchCriteria, \Yadeo\Paginator $paginator, $sortField = 'id')
    {
        return array();
    }

    public function save()
    {
        return $this->storageMedium->saveArticle($this);
    }
}
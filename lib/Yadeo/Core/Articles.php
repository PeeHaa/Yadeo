<?php

namespace Yadeo\Core;

class Articles
{
    protected $storageMedium;

    protected $factory;

    protected $articles = array();

    public function __construct(\Yadeo\Storage\Medium $storageMedium, \Yadeo\Core\Factory $factory)
    {
        $this->storageMedium = $storageMedium;
        $this->factory = $factory;
    }

    public function getNumberOfArticles()
    {
        return $this->storageMedium->getNumberOfArticles();
    }

    public function getArticles(\Yadeo\Paginator $paginator)
    {
        $recordset = $this->storageMedium->getArticles($paginator);

        $articles = $this->parseArticlesRecordset($recordset);
        foreach($articles as $article) {
            $this->addArticle($article);
        }

        return $this->articles;
    }

    protected function parseArticlesRecordset(array $recordset)
    {
        $articles = array();
        foreach($recordset as $record) {
            if (!array_key_exists($record->id, $articles)) {
                $articles[$record->id] = new \StdClass();
                $articles[$record->id]->id = $record->id;
                $articles[$record->id]->title = $record->title;
                $articles[$record->id]->slug = $record->slug;
                $articles[$record->id]->intro = $record->intro;
                $articles[$record->id]->message = $record->message;
                $articles[$record->id]->timestamp = $record->timestamp;
                $articles[$record->id]->tags = array();
                $articles[$record->id]->commentCount = 0;
                if ($record->count !== null) {
                    $articles[$record->id]->commentCount = $record->count;
                }
            }

            $articles[$record->id]->tags[] = $record->tag;
        }

        return $articles;
    }

    protected function addArticle($article)
    {
        $articleObj = $this->factory->buildArticle();
        $articleObj->setData($article);

        $this->articles[] = $articleObj;
    }
}
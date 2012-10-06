<?php

namespace Project\Core;

class Factory implements \Yadeo\Core\Factory
{
    protected $storageMedium;
    protected $articleFormatter;
    protected $commentFormatter;
    protected $request;

    protected $cache = array();

    public function __construct(
        \Yadeo\Storage\Medium $storageMedium,
        \Yadeo\Format\Formatter $articleFormatter,
        \Yadeo\Format\Formatter $commentFormatter,
        $request
    )
    {
        $this->storageMedium = $storageMedium;
        $this->articleFormatter = $articleFormatter;
        $this->commentFormatter = $commentFormatter;
        $this->request = $request;
    }

    public function buildArticle()
    {
        return new \Yadeo\Core\Article($this->storageMedium, $this->articleFormatter, $this);
    }

    public function buildComment()
    {
        return new \Yadeo\Core\Comment($this->storageMedium, $this->commentFormatter);
    }

    public function buildArticles()
    {
        return new \Yadeo\Core\Articles($this->storageMedium, $this);
    }

    public function buildVote()
    {
        return new \Yadeo\Core\Vote($this->storageMedium, $this->request);
    }

    public function buildPaginator($itemCount, $currentPage = 1, $pageSize = null, $windowSize = null)
    {
        return new \Yadeo\Paginator($itemCount, $currentPage, $pageSize, $windowSize);
    }

    public function buildHttpClient($uri, array $options = array())
    {
        return new \Yadeo\Http\Client($uri, $this, $options = array());
    }

    public function buildClientResponse($response)
    {
        return new \Yadeo\Http\ClientResponse($response);
    }

    public function buildOauthSignature($consumerKey)
    {
        if (!isset($this->cache['Oauth1Signature'])) {
            $this->cache['Oauth1Signature'] = new \Yadeo\Auth\Oauth1\Signature($consumerKey);
        }

        return $this->cache['Oauth1Signature'];
    }
}
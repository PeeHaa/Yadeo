<?php

namespace Yadeo\Core;

interface Factory
{
    public function buildArticle();

    public function buildArticles();

    public function buildPaginator($itemCount, $currentPage = 1, $pageSize = null, $windowSize = null);
}
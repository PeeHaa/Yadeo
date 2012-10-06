<?php

$routes = array(
    'login' => array(
        'uri'               => '/login/:service',
        'requirements'      => array('service' => '/^(twitter|github|google|microsoft)$/'),
        'method'            => 'POST',
        'response'          => 'html',
        'delegationMethod'  => 'login',
    ),
    'articles' => array(
        'uri'               => '/',
        'method'            => 'GET',
        'response'          => 'html',
        'delegationMethod'  => 'articles',
    ),
    'articles/paginated' => array(
        'uri'               => '/page/:page',
        'requirements'      => array('page' => '/^(\d+)$/'),
        'method'            => 'GET',
        'response'          => 'html',
        'delegationMethod'  => 'articles',
    ),
    'article' => array(
        'uri'               => '/:year/:month/:slug',
        'method'            => 'GET',
        'response'          => 'html',
        'delegationMethod'  => 'article',
    ),
    'comment/post' => array(
        'uri'               => '/article/:id/:slug/comment/post',
        'method'            => 'POST',
        'response'          => 'html',
        'delegationMethod'  => 'addComment',
    ),
    'comment/reply/html' => array(
        'uri'               => '/article/:articleid/:slug/comment/:commentid/reply',
        'method'            => 'GET',
        'response'          => 'phtml',
        'delegationMethod'  => 'replyCommentHtml',
    ),
    'comment/reply' => array(
        'uri'               => '/article/:articleid/:slug/comment/:commentid/reply',
        'method'            => 'POST',
        'response'          => 'html',
        'delegationMethod'  => 'replyComment',
    ),
    'comment/vote' => array(
        'uri'               => '/article/:articleid/:slug/comment/:commentid/vote/:direction',
        'requirements'      => array('direction' => '/^(up|down)$/'),
        'method'            => 'GET',
        'response'          => 'json',
        'delegationMethod'  => 'voteComment',
    ),
    'feed' => array(
        'uri'               => '/feed',
        'method'            => 'GET',
        'response'          => 'atom',
        'delegationMethod'  => 'feed',
    ),
    '404' => array(
        'uri'               => '/not-found',
        'method'            => '*',
        'response'          => 'html',
        'delegationMethod'  => 'notFound',
    ),
);
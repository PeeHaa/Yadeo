<?php

namespace Yadeo\Http;

class Response
{
    protected $view;

    protected $type;

    protected $router;

    public function __construct(\Yadeo\View $view, \Yadeo\Router\Route $route)
    {
        $this->view = $view;

        $this->route = $route;
    }

    public function setResponseType($type)
    {
        $this->type = $type;
    }

    public function buildResponse()
    {
        return $this->view->render();
    }
}
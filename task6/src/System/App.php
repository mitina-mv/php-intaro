<?php

namespace App\System;

use Exception;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class App
{
    private $request;
    private $router;
    private $routes;

    private $requestContext;
    private $controller;
    private $args;
    public $orm;
    
    public static $instance = null;

    public static function getInctance()
    {
        if(is_null(static::$instance))
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->setRequest();
        $this->setRequestContext();
        $this->setRouter();
        $this->routes = $this->router->getRouteCollection();
        $this->orm = new ORM([
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'host' => $_ENV['DB_HOST'],
            'driver' => $_ENV['DB_DRIVER'],
        ]);
    }

    private function setRequest()
    {
        $this->request = Request::createFromGlobals();
    }

    public function getRequest()
    {
        return $this->request;
    }

    private function setRequestContext()
    {
        $this->requestContext = new RequestContext();
        $this->requestContext->fromRequest($this->request);
    }

    public function getRequestContext()
    {
        return $this->requestContext;
    }

    private function setRouter()
    {
        $fileLocator = new FileLocator([__DIR__]);

        $this->router = new Router(
            new YamlFileLoader($fileLocator),
            $_SERVER['DOCUMENT_ROOT'] . '/config/routes/routes.yaml'
        );
    }

    public function getController()
    {
        return (new ControllerResolver())->getController($this->request);
    }

    public function getArguments()
    {
        return (new ArgumentResolver())->getArguments($this->request, $this->controller);
    }

    public function run()
    {
        $matcher = new UrlMatcher($this->routes, $this->requestContext);

        try {
            $this->request->attributes->add($matcher->match($this->request->getPathInfo()));

            $this->controller = $this->getController();
            $this->args = $this->getArguments();

            $response = call_user_func($this->controller, $this->args);

        } catch (Exception $e) {
            exit($e->getMessage());
        }

        $response->send();
    }
}
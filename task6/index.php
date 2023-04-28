<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use App\System\App;

// переменные из .env
$dotenv = new Dotenv();
$dotenv->load($_SERVER['DOCUMENT_ROOT']. $DIR .'/.env');

$app = App::getInctance();

$app->run();

// use Symfony\Component\EventDispatcher\EventDispatcher;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\RequestStack;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
// use Symfony\Component\HttpKernel\Controller\ControllerResolver;
// use Symfony\Component\HttpKernel\EventListener\RouterListener;
// use Symfony\Component\HttpKernel\HttpKernel;
// use Symfony\Component\Routing\Matcher\UrlMatcher;
// use Symfony\Component\Routing\RequestContext;
// use Symfony\Component\Routing\Route;
// use Symfony\Component\Routing\RouteCollection;
// use App\Controller\HelloController;

// $routes = new RouteCollection();
// $routes->add('hello', new Route('/hello/{name}', [
//     'controller' => 'HelloController',
//     'method' => 'print'
//     ]
// ));

// $request = Request::createFromGlobals();

// $matcher = new UrlMatcher($routes, new RequestContext());

// $dispatcher = new EventDispatcher();
// $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

// $controllerResolver = new ControllerResolver();
// $argumentResolver = new ArgumentResolver();

// $kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

// $response = $kernel->handle($request);
// $response->send();

// $kernel->terminate($request, $response);
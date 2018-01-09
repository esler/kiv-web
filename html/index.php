<?php
namespace Esler\KivWeb;

use GuzzleHttp\Psr7\ServerRequest as Request;
use Twig_Loader_Filesystem;
use Twig_Environment;

// use default composer autoload
$docroot = realpath(__DIR__ . '/..');
if (file_exists($filename = __DIR__ . '/../vendor/autoload.php')) {
    require_once $filename;
} else {
    trigger_error('Composer deps not installed, run composer install!', E_USER_ERROR);
}

$loader = new Twig_Loader_Filesystem(realpath(__DIR__ . '/../templates'));
$twig = new Twig_Environment($loader, [
    'autoescape' => 'html',
    // 'cache' => realpath(__DIR__ . '/../cache/twig'),
]);

session_start();

$container = require $docroot . '/config/container.php';

$view = new View($twig);
$front = new FrontController($container, $view);

$router = new Router;
$router->addRoute('/:control/:id/:action~read', $front);
$router->addRoute('/:control~home/:action~list', $front);

$router->dispatch(false);

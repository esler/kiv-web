<?php
namespace Esler\KivWeb;

use GuzzleHttp\Psr7\ServerRequest as Request;

// use default composer autoload
if (file_exists($filename = __DIR__ . '/../vendor/autoload.php')) {
    require_once $filename;
} else {
    trigger_error('Composer deps not installed, run composer install!', E_USER_ERROR);
}

$router = new Router;
$router->addRoute('/:control/:id/:action~read', [FrontController::class, 'dispatch']);
$router->addRoute('/:control/:action~list', [FrontController::class, 'dispatch']);

// routes
// /                        /index.php
// /users                   /index.php?page=users
// /users/{id:int}          /index.php?page=users&id={:int}
// /users/{id:int}/edit     /index.php?page=users&id={:int}&action=update GET/POST
// /matches                 /index.php?page=matches
// /{page:home}/{id}/{action:list}



// $this->add('/', ); // return callback or null
// $this->add('');
// $this->add('');
// $this->add('', )

// var_dump($_SERVER);

$router->dispatch(false);

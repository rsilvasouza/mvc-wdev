<?php
require __DIR__.'/vendor/autoload.php';

use App\Http\Router;
use App\Utils\View;

define('URL','http://localhost/mvc');

View::init([
    'URL' => URL
]);

//inicia o router
$obRouter = new Router(URL);

//Inclui as rotas de páginas
include __DIR__.'/routes/pages.php';

//Imprime response da Rota
$obRouter->run()->sendResponse();


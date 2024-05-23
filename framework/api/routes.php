<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

//Altere o caminho da API conforme o seu sistema
$caminhoAPI = '/adiantiApp/adianti-fork-framework/framework/api/';
$app->get($caminhoAPI, function (Request $request, Response $response, $args) {
    $msg = "Hello world!";
    $msgJson = json_encode($msg);
    $response->getBody()->write( $msgJson );
    $result = $response->withHeader('Content-Type', 'application/json');
    return $result;
});

$app->run();
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy as RouteCollectorProxy;
use Slim\Factory\AppFactory;

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$displayErrorDetails = getenv('DISPLAY_ERRORS_DETAILS');
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

//Altere o caminho da API conforme o seu sistema
$urlraizAPI = '/adiantiApp/adianti-fork-framework/framework/api/';
$app->get($urlraizAPI, function (Request $request, Response $response, $args) {
    $msg = "Hello world!";
    $msgJson = json_encode($msg);
    $response->getBody()->write( $msgJson );
    $result = $response->withHeader('Content-Type', 'application/json');
    return $result;
});

//--------------------------------------------------------------------
//  Exemplo HTML
//--------------------------------------------------------------------
$urlGrupo = $urlraizAPI.'html';
$app->get($urlGrupo, function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

//--------------------------------------------------------------------
//  Exemplo JSON
//--------------------------------------------------------------------
$urlGrupo = $urlraizAPI.'json';
$app->get($urlGrupo, function (Request $request, Response $response, $args) {
    $msg = "Hello world!";
    $msgJson = json_encode($msg);
    $response->getBody()->write( $msgJson );
    $result = $response->withHeader('Content-Type', 'application/json');
    return $result;
});

$app->run();
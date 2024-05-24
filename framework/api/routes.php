<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy as RouteCollectorProxy;
use Slim\Factory\AppFactory;


use api_controllers\SysinfoAPI;
use api_controllers\DbExemploAPI;

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
$displayErrorDetails = API_DISPLAY_ERRORS_DETAILS;
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

//Altere o caminho da API conforme o seu sistema
//$urlraizAPI = '/adiantiApp/adianti-fork-framework/framework/api/';
$urlraizAPI = ServerHelper::getRequestUri(true);
$urlraizAPI = explode('api/', $urlraizAPI);
$urlraizAPI = $urlraizAPI[0];
$urlraizAPI = $urlraizAPI.'api/';


$app->get($urlraizAPI, function (Request $request, Response $response, $args) use ($app) {
    $url = \ServerHelper::getFullServerName();
    $routes = $app->getRouteCollector()->getRoutes();
    $routesArray = array();
    foreach ($routes as $route) {
        $routeArray = array();
        $routeArray['id']  = $route->getIdentifier();
        $routeArray['name']= $route->getName();
        $routeArray['methods']= $route->getMethods()[0];
        $routeArray['url'] = $url.$route->getPattern();
        $routesArray[] = $routeArray;
    }

    $msg = array( 'info'=> SysinfoAPI::info()
                , 'endpoints'=>array( 'qtd'=> \CountHelper::count($routesArray)
                                    ,'result'=>$routesArray
                                    )
                );

    $msgJson = json_encode($msg);
    $response->getBody()->write( $msgJson );
    $result = $response->withHeader('Content-Type', 'application/json');
    return $result;
})->setName('index');


//Entrar na classe Authentication para pegar usuário e senha
//Descomentar as linhas que precisam ser autenticadas
//$controllerAuthentication = new Authentication($urlraizAPI);
//$controllerAuthentication->addPath('sqlite_sequence');
//$app->add($controllerAuthentication->basicAuth());


$app->get($urlraizAPI.'sysinfo', SysinfoAPI::class . ':getInfo')->setName('sysinfo');
//$app->get($urlraizAPI.'auth', SysinfoAPI::class . ':getInfo');


//--------------------------------------------------------------------
//  Exemplo HTML
//--------------------------------------------------------------------
$urlGrupo = $urlraizAPI.'html';
$app->get($urlGrupo, function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
})->setName('html-hello world');

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
})->setName('json-hello world');

//--------------------------------------------------------------------
//  TABLE: exemplo
//--------------------------------------------------------------------
$urlGrupo = $urlraizAPI.'dbexemplo';
$app->group($urlGrupo, function(RouteCollectorProxy $group) use ($app,$urlGrupo) {
    $app->get($urlGrupo.'', DbExemploAPI::class . ':selectAll');
    $app->get($urlGrupo.'/{id:[0-9]+}', DbExemploAPI::class . ':selectById')->setName('selectById');


    $app->post($urlGrupo.'', DbExemploAPI::class . ':save');
    $app->put($urlGrupo.'/{id:[0-9]+}', DbExemploAPI::class . ':save');
    $app->delete($urlGrupo.'/{id:[0-9]+}', DbExemploAPI::class . ':delete');
});

$app->run();
<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../vendor/autoload.php';
require '../src/Server/config.php';
require '../src/Server/Database.php';
require '../src/Server/db_User.php';

$app = new \Slim\App;
$db = new db_User();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->get('/api/alluser/{id}', function (Request $request, Response $response) {
    global $db;

    $id = $request->getAttribute('id');
    $data = $db->get_all_user($id);
    $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($data);
    return $response;
});

$app->get('/api/emails/{type}/{id}', function (Request $request, Response $response) {
    global $db;

    $type = $request->getAttribute('type');
    $id   = $request->getAttribute('id');
    switch ($type) {
        case 'received':
            $data = $db->get_received_emails($id);
            break;

        case 'sent':
            $data = $db->get_sent_emails($id);
            break;

        default:
            return false;
            break;
    }
    $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($data);
    return $response;
});
$app->run();
?>

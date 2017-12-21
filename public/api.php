<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../vendor/autoload.php';
require '../src/Server/config.php';
require '../src/Server/Database.php';
require '../src/Server/db_User.php';
// require './guzzleRequest.php';

$app = new \Slim\App;
$db = new db_User();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'https://jeemail.com')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With,
                          Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
});

$app->get('/hi', function (Request $request, Response $response) {
    $response->getBody()->write('Hey there!');
    return $response;
});

$app->post('/api/alluser/{id}', function (Request $request,
                                          Response $response) {
    global $db;
    $id   = $request->getAttribute('id');
    $data = $db->get_all_user($id);
    $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($data);
    return $response;
});



$app->post('/emails/{type}', function (Request $request, Response $response) {
    global $db;
    $id = $request->getQueryParams();
    if(count($id) > 1 || count($id) === 0) {
        return $response;
    }

    $id   = $id['id'];
    $type = $request->getAttribute('type');
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

    // $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    // return $response->withStatus(200)
    //                 ->withHeader('Content-Type', 'application/json')
    //                 ->getBody()->write(json_encode($data));
    // withJson is the newer way to do the above. It wraps around the native
    // json_encode as well.
    return $response->withJson($data, 200,
                               JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
});

$app->post('/login', function (Request $request, Response $response) {
    global $db;
    // First, get the Authorization header from the request
    // $auth = $request->getHeaders();
    $auth = $request->getHeaders()['HTTP_AUTHORIZATION'][0];
    // The header is in the form of 'Basic xxxxxxxx'. This explode is done
    // to allow us to decode everything after the 'Basic' scheme.
    $removedAuthScheme = explode(" ", $auth)[1];
    $decoded = base64_decode($removedAuthScheme);
    // Then, separate provided username from password
    $split   = explode(":", $decoded);
    $package = ["username" => $split[0], "pass" => $split[1]];
    $data    = $db->login($package);
    $response->getBody()->write($data);
    return $response;
});

$app->run();
?>

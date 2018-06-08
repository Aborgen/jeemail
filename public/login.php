<?php
require '../vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$url    = 'https://api.jeemail.com/login';
$client = new Client();
$req    = new Request('GET', $url, [
    'headers' => [
        'content-type' => 'application/json'
    ]
]);
$res        = $client->send($req);
$statusCode = $res->getStatusCode();
if ($statusCode === 200) {
    $body    = $res->getBody()->getContents();
    $decoded = json_decode($body, true);
    echo $decoded['template'];
}
?>

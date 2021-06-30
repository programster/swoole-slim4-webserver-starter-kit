<?php

require __DIR__ . '/vendor/autoload.php';

$psr17Factory = new Nyholm\Psr7\Factory\Psr17Factory();

$serverRequestFactory = new \Ilex\SwoolePsr7\SwooleServerRequestConverter(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$app = new \Slim\App($psr17Factory);

$app->any('/', function (Psr\Http\Message\RequestInterface $request, Psr\Http\Message\ResponseInterface $response, array $args) {
    $responseData = [
        'args' => $args,
        'body' => (string) $request->getBody(),
        'headers' => $request->getHeaders(),
    ];

    $responseBody = json_encode($responseData);
    $response->getBody()->write($responseBody);
    $newResponse = $response->withHeader('Content-Type', 'application/json');
    return $newResponse;
});

# Define the logic for swoole to run on startup.
$swooleStartupCallback = function ($server) {
    echo "Server started at http://127.0.0.1:9000\n";
};

# Define the callback to execute whenever swoole recieves a request.
# This will convert the swoole request to a psr7 request, run it through slim, then convert it back to swoole response.
$requestCallback = function (
    \Swoole\Http\Request $swooleRequest, 
    Swoole\Http\Response $swooleResponse
) use($serverRequestFactory, $app) {
    $psr7Request = $serverRequestFactory->createFromSwoole($swooleRequest);
    $psr7Response = $app->handle($psr7Request);
    $converter = new \Ilex\SwoolePsr7\SwooleResponseConverter($swooleResponse);
    $converter->send($psr7Response);
};


$server = new Swoole\Http\Server('127.0.0.1', 9000);
$server->on('start', $swooleStartupCallback);
$server->on('request', $requestCallback);
$server->start();
<?php
// TODO: remove this $app...
//$app = "";
require_once 'Leaderboard.php';

$Leaderboard = new Leaderboard();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world from routes.php!");
    return $response;
});
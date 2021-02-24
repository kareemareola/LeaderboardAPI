<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL); // TODO: remove all error reporting

$app = AppFactory::create();

$app->setBasePath((function () {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $uri = (string)parse_url('http://a' . $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if (stripos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
        return $_SERVER['SCRIPT_NAME'];
    }
    if ($scriptDir !== '/' && stripos($uri, $scriptDir) === 0) {
        return $scriptDir;
    }
    return '';
})());

$app->addBodyParsingMiddleware();

// TODO: Get rid of an requests from here and add them to routes.php
require_once 'Leaderboard.php';
$Leaderboard = new Leaderboard();

// TODO: remove this generic route
// Welcome route for base URL
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Welcome to the leaderboard API<br> Please POST to the valid endpoints.");
    return $response;
});

// Lists all players in the Leaderboard
$app->get('/list', function (Request $request, Response $response, $args) {
    $Leaderboard = new Leaderboard();
    $list = $Leaderboard->listAllPlayers();
    $response->getBody()->write($list);
    return $response;
});

// Decreases the score of a player by one point
$app->post('/players/downscore/', function (Request $request, Response $response, $args) {
    try {
        $data = $request->getParsedBody();
        if (!is_null($data)) {
            if (array_key_exists("playerId", $data)) {
                $playerId = $data['playerId'];
            }
            else {
                throw new Exception("playerId not found.");
            }
            $Leaderboard = new Leaderboard();
            $decreaseRequestResponse = $Leaderboard->decreasePlayerScore($playerId);
            $response->getBody()->write($decreaseRequestResponse);
            return $response;
        }
        else {
            throw new Exception("Invalid or malformed data.");
        }
    } catch (Exception $exception) {
        $response->getBody()->write("{'error': '" . $exception->getMessage() . " '}");
        return $response->withStatus(400);
    }
});

// Increases the score of a player by one point
$app->post('/players/upscore/', function (Request $request, Response $response, $args) {
    try {
        $data = $request->getParsedBody();
        if (!is_null($data)) {
            if (array_key_exists("playerId", $data)) {
                $playerId = $data['playerId'];
            }
            else {
                throw new Exception("playerId not found.");
            }
            $Leaderboard = new Leaderboard();
            $increaseRequestResponse = $Leaderboard->increasePlayerScore($playerId);
            $response->getBody()->write($increaseRequestResponse);
            return $response;
        }
        else{
            throw new Exception("Invalid or malformed data");
        }
    } catch (Exception $exception) {
        $response->getBody()->write("{'error': '" . $exception->getMessage() . " '}");
        return $response->withStatus(400);
    }
});

// Adds a player to the system, given the playerName, playerAge and playerAddress
$app->post('/players/add/', function (Request $request, Response $response, $args) {
    try {
        $data = $request->getParsedBody();

        if (array_key_exists("playerName", $data)) {
            $playerName = $data['playerName'];
        }
        else {
            throw new Exception("playerName not found.");
        }
        if (array_key_exists("playerName", $data)) {
            $playerAge = $data['playerAge'];
        }
        else {
            throw new Exception("playerAge not found.");
        }
        if (array_key_exists("playerAddress", $data)) {
            $playerAddress = $data['playerAddress'];
        }
        else {
            throw new Exception("playerAddress not found.");

        }
        $playerInfoArray = [
            'playerName' => $playerName,
            'playerAge' => $playerAge,
            'playerAddress' => $playerAddress
        ];
        $Leaderboard = new Leaderboard();
        $increaseRequestResponse = $Leaderboard->addNewPlayer($playerInfoArray);
        $response->getBody()->write($increaseRequestResponse);
        return $response;
    } catch (Exception $exception) {
        $response->getBody()->write("{'error': '" . $exception->getMessage() . " '}");
        return $response->withStatus(400);
    }
});

// Removes a given player from the system based on playerId
$app->post('/players/remove/', function (Request $request, Response $response, $args) {
    try {
        $data = $request->getParsedBody();
        if (!is_null($data)) {
            if (array_key_exists("playerId", $data)) {
                $playerId = $data['playerId'];
            }
            else {
                throw new Exception("playerId not found.");
            }
            $Leaderboard = new Leaderboard();
            $increaseRequestResponse = $Leaderboard->deletePlayer($playerId);
            $response->getBody()->write($increaseRequestResponse);
            return $response;
        }
        else {
            throw new Exception("Invalid or malformed request");
        }
    } catch (Exception $exception) {
        $response->getBody()->write("{'error': '" . $exception->getMessage() . " '}");
        return $response->withStatus(400);
    }
});

$app->run();
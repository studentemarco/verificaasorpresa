<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use App\Database;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

function jsonResponse($response, $data) {
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
}

// var_dump(class_exists(\App\Database::class));
// exit;

//1. Trovare i pnome dei pezzi per cui esiste un qualche fornitore
$app->get('/1', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT DISTINCT p.pnome
        FROM Pezzi p
        JOIN Catalogo c ON p.pid = c.pid
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});


$app->run();
<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use App\Database;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

function jsonResponse($response, $data) {
    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
}

// var_dump(class_exists(\App\Database::class));
// exit;

$app->get('/', function ($request, $response) {
    return jsonResponse($response, [
        'message' => 'Benvenuto nella API di esercizio!',
        'endpoints' => [
            [
                'method' => 'GET',
                'path' => '/1',
                'description' => 'Pezzi con almeno un fornitore'
            ],
            [
                'method' => 'GET',
                'path' => '/2',
                'description' => 'Fornitori che forniscono ogni pezzo'
            ],
            [
                'method' => 'GET',
                'path' => '/3',
                'description' => 'Fornitori che forniscono tutti i pezzi rossi'
            ],
            [
                'method' => 'GET',
                'path' => '/4',
                'description' => 'Pezzi forniti solo da Acme'
            ],
            [
                'method' => 'GET',
                'path' => '/5',
                'description' => 'Fornitori che ricaricano sopra media'
            ],
            [
                'method' => 'GET',
                'path' => '/6',
                'description' => 'Fornitore con ricarico massimo per ogni pezzo'
            ],
            [
                'method' => 'GET',
                'path' => '/7',
                'description' => 'Fornitori che forniscono solo pezzi rossi'
            ],
            [
                'method' => 'GET',
                'path' => '/8',
                'description' => 'Fornitori che forniscono rosso e verde'
            ],
            [
                'method' => 'GET',
                'path' => '/9',
                'description' => 'Fornitori che forniscono rosso o verde'
            ],
            [
                'method' => 'GET',
                'path' => '/10',
                'description' => 'Pezzi forniti da almeno 2 fornitori'
            ]
        ]
    ]);
});

//1. Pezzi con almeno un fornitore
$app->get('/1', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT DISTINCT p.pnome
        FROM Pezzi p
        JOIN Catalogo c ON p.pid = c.pid
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//2. Fornitori che forniscono ogni pezzo
$app->get('/2', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT f.fnome
        FROM Fornitori f
        WHERE NOT EXISTS (
            SELECT *
            FROM Pezzi p
            WHERE NOT EXISTS (
                SELECT *
                FROM Catalogo c
                WHERE c.fid = f.fid AND c.pid = p.pid
            )
        )
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//3. Fornitori che forniscono tutti i pezzi rossi
$app->get('/3', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT f.fnome
        FROM Fornitori f
        WHERE NOT EXISTS (
            SELECT *
            FROM Pezzi p
            WHERE p.colore = 'rosso'
            AND NOT EXISTS (
                SELECT *
                FROM Catalogo c
                WHERE c.fid = f.fid AND c.pid = p.pid
            )
        )
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//4. Pezzi forniti solo da Acme
$app->get('/4', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT p.pnome
        FROM Pezzi p
        JOIN Catalogo c ON p.pid = c.pid
        JOIN Fornitori f ON c.fid = f.fid
        WHERE f.fnome = 'Acme'
        AND NOT EXISTS (
            SELECT *
            FROM Catalogo c2
            WHERE c2.pid = p.pid
            AND c2.fid <> f.fid
        )
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//5. Fornitori che ricaricano sopra media
$app->get('/5', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT DISTINCT c.fid
        FROM Catalogo c
        WHERE c.costo >
            (SELECT AVG(c2.costo)
             FROM Catalogo c2
             WHERE c2.pid = c.pid)
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//6. Fornitore con ricarico massimo per ogni pezzo
$app->get('/6', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT p.pnome, f.fnome
        FROM Catalogo c
        JOIN Pezzi p ON c.pid = p.pid
        JOIN Fornitori f ON c.fid = f.fid
        WHERE c.costo = (
            SELECT MAX(c2.costo)
            FROM Catalogo c2
            WHERE c2.pid = c.pid
        )
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//7. Fornitori che forniscono solo pezzi rossi
$app->get('/7', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT f.fid
        FROM Fornitori f
        WHERE NOT EXISTS (
            SELECT *
            FROM Catalogo c
            JOIN Pezzi p ON c.pid = p.pid
            WHERE c.fid = f.fid AND p.colore <> 'rosso'
        )
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//8. Fornitori che forniscono rosso e verde
$app->get('/8', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT f.fid
        FROM Fornitori f
        WHERE EXISTS (
            SELECT *
            FROM Catalogo c
            JOIN Pezzi p ON c.pid = p.pid
            WHERE f.fid = c.fid AND p.colore = 'rosso'
        )
        AND EXISTS (
            SELECT *
            FROM Catalogo c
            JOIN Pezzi p ON c.pid = p.pid
            WHERE f.fid = c.fid AND p.colore = 'verde'
        )
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//9. Fornitori che forniscono rosso o verde
$app->get('/9', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT DISTINCT f.fid
        FROM Fornitori f
        JOIN Catalogo c ON f.fid = c.fid
        JOIN Pezzi p ON c.pid = p.pid
        WHERE p.colore IN ('rosso','verde')
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

//10. Pezzi forniti da almeno 2 fornitori
$app->get('/10', function ($request, $response) {
    $db = Database::connect();
    $stmt = $db->query("
        SELECT pid
        FROM Catalogo
        GROUP BY pid
        HAVING COUNT(DISTINCT fid) >= 2
    ");
    return jsonResponse($response, $stmt->fetchAll(PDO::FETCH_ASSOC));
});

// 404 per endpoint non esistenti
$app->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], '/{routes:.+}', function ($request, $response) {
    return jsonResponse($response, [
        'error' => 'Not Found'
    ])->withStatus(404);
});

$app->run();
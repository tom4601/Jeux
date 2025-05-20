<?php

$envPath = __DIR__ . '/.env';

if (! file_exists($envPath)) {
    exit('Erreur : fichier .env introuvable.');
}

$env = parse_ini_file($envPath);

if (! isset($env['DB_HOST'], $env['DB_USER'], $env['DB_PASSWORD'], $env['DB_NAME'])) {
    exit('Erreur : paramètres de connexion manquants dans .env');
}

$conn = @new mysqli(
    $env['DB_HOST'],
    $env['DB_USER'],
    $env['DB_PASSWORD'],
    $env['DB_NAME']
);

if ($conn->connect_error) {
    exit('Erreur de connexion à la base de données.');
}

$conn->set_charset('utf8mb4');

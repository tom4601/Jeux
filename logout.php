<?php
session_start();

function log_event($message)
{
    $logFile = __DIR__ . '/../logs/logs.log';
    $date    = date('Y-m-d H:i:s');
    $ip      = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $entry   = "[$date] [$ip] $message" . PHP_EOL;

    if (! file_exists($logFile)) {
        file_put_contents($logFile, '');
        chmod($logFile, 0644);
    }

    file_put_contents($logFile, $entry, FILE_APPEND);
}

$username = $_SESSION['username'] ?? 'invité';
log_event("Déconnexion de l'utilisateur '$username'");

$_SESSION = [];
session_destroy();

header("Location: ../index.html");
exit;

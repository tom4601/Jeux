<?php
    session_start();
    require '../db.php';

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

    log_event("Accès au formulaire d'assistance");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire d'assistance</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container w3-light-grey w3-padding-32">

<div class="w3-card-4 w3-white w3-padding">

    <h2 class="w3-center">Soumettre un Ticket</h2>

    <form action="traitement_tickets.php" method="post" class="w3-container" autocomplete="off">
        <p>
            <label class="w3-text-grey">Nom :</label>
            <input class="w3-input w3-border" type="text" name="nom" required pattern="[A-Za-zÀ-ÿ '-]{2,50}">
        </p>

        <p>
            <label class="w3-text-grey">Email :</label>
            <input class="w3-input w3-border" type="email" name="email" required>
        </p>

        <p>
            <label class="w3-text-grey">Sujet :</label>
            <input class="w3-input w3-border" type="text" name="sujet" required maxlength="100">
        </p>

        <p>
            <label class="w3-text-grey">Catégorie du problème :</label>
            <select class="w3-select w3-border" name="categorie" required>
                <option value="" disabled selected>Choisissez une catégorie</option>
                <option value="Connexion">Connexion</option>
                <option value="Erreur système">Erreur système</option>
                <option value="Demande de fonctionnalités">Demande de fonctionnalités</option>
                <option value="OS">OS</option>
                <option value="Autre">Autre</option>
            </select>
        </p>

        <p>
            <label class="w3-text-grey">Message :</label>
            <textarea class="w3-input w3-border" name="message" rows="6" required></textarea>
        </p>

        <p>
            <input class="w3-button w3-blue w3-round" type="submit" value="Envoyer le ticket">
        </p>
    </form>

</div>

</body>
</html>

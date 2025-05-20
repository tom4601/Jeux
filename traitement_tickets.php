<?php
    session_start();
    require '../db.php';

    // 📄 Fonction de log
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traitement du ticket</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container w3-light-grey w3-padding-32">
<div class="w3-card-4 w3-white w3-padding">

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom       = trim($_POST['nom'] ?? '');
        $email     = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $sujet     = trim($_POST['sujet'] ?? '');
        $message   = trim($_POST['message'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');

        if ($nom && $email && $sujet && $message && $categorie) {
            $stmt = $conn->prepare("INSERT INTO tickets (nom, email, sujet, message, categorie, statut, created_at) VALUES (?, ?, ?, ?, ?, 'ouvert', NOW())");
            $stmt->bind_param("sssss", $nom, $email, $sujet, $message, $categorie);

            if ($stmt->execute()) {
                $ticket_id = $stmt->insert_id;
                echo '<div class="w3-panel w3-green w3-round w3-padding">';
                echo "✅ Ticket soumis avec succès.<br>ID du ticket : <strong>{$ticket_id}</strong>";
                echo '</div>';

                log_event("Création du ticket #$ticket_id par '$nom <$email>' — Sujet: '$sujet' | Catégorie: '$categorie'");
            } else {
                echo '<div class="w3-panel w3-red w3-round w3-padding">';
                echo "❌ Erreur : " . htmlspecialchars($stmt->error);
                echo '</div>';

                log_event("Erreur MySQL lors de la création de ticket par '$nom <$email>' — Sujet: '$sujet' | Erreur : " . $stmt->error);
            }

            $stmt->close();
        } else {
            echo '<div class="w3-panel w3-red w3-round w3-padding">';
            echo "❌ Tous les champs doivent être remplis correctement.";
            echo '</div>';

            log_event("Soumission invalide de ticket par '$nom <$email>' — Données incomplètes.");
        }
    } else {
        http_response_code(405);
        exit("Méthode non autorisée.");
    }

    $conn->close();
?>

<a href="../index.html" class="w3-button w3-gray w3-round w3-margin-top">Retour à l'accueil</a>

</div>
</body>
</html>

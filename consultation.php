<?php
    session_start();
    require_once '../db.php';

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

    $message = "";
    $ticket  = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['ticket_id']);

        $stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $ticket = $res->fetch_assoc();
            log_event("Consultation du ticket #$id");
        } else {
            $message = "Ticket introuvable.";
            log_event("Tentative de consultation du ticket inexistant #$id");
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter un ticket</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container w3-light-grey w3-padding-32">

    <div class="w3-card-4 w3-white w3-padding">

        <h2 class="w3-center">Consulter un ticket</h2>

        <form method="POST" class="w3-container w3-margin-bottom">
            <label class="w3-text-grey">ID du ticket :</label>
            <input class="w3-input w3-border" type="number" name="ticket_id" required>
            <br>
            <input class="w3-button w3-blue w3-round" type="submit" value="Voir">
        </form>

        <?php if ($message): ?>
            <div class="w3-panel w3-pale-red w3-border w3-text-red">
                <?php echo htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($ticket): ?>
            <hr>
            <h3 class="w3-text-black">Ticket #<?php echo htmlspecialchars($ticket['id']) ?></h3>
            <p><strong>Sujet :</strong>                                         <?php echo htmlspecialchars($ticket['sujet']) ?></p>
            <p><strong>Description :</strong><br><?php echo nl2br(htmlspecialchars($ticket['message'])) ?></p>
            <p><strong>Statut :</strong>                                           <?php echo htmlspecialchars($ticket['statut']) ?></p>
        <?php endif; ?>

        <hr>
        <a href="../index.html" class="w3-button w3-gray w3-round">Retour à l'accueil</a>
    </div>

</body>
</html>

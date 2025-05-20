<?php
    session_start();
    require_once '../db.php';

    if (! isset($_SESSION['user_logged_in']) || ! in_array($_SESSION['role'], ['admin', 'technicien'])) {
        http_response_code(403);
        exit("Accès interdit.");
    }

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

    if (! isset($_GET['id']) || ! is_numeric($_GET['id'])) {
        die("ID manquant ou invalide.");
    }

    $id = (int) $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (! $result || $result->num_rows === 0) {
        die("Ticket introuvable.");
    }

    $ticket  = $result->fetch_assoc();
    $updated = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sujet   = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $statut  = trim($_POST['statut'] ?? '');

        if ($sujet === '' || $message === '' || ! in_array($statut, ['ouvert', 'en cours', 'fermé'])) {
            die("Entrées invalides.");
        }

        $stmt = $conn->prepare("UPDATE tickets SET sujet = ?, message = ?, statut = ? WHERE id = ?");
        $stmt->bind_param("sssi", $sujet, $message, $statut, $id);
        if ($stmt->execute()) {
            $updated = true;
            log_event("Mise à jour du ticket #$id par '{$_SESSION['username']}' (Statut: $statut)");
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail Ticket                                                    <?php echo htmlspecialchars($ticket['id']) ?></title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container w3-light-grey w3-padding-32">

<div class="w3-card-4 w3-white w3-padding">
    <h2 class="w3-center">Détail du ticket #<?php echo htmlspecialchars($ticket['id']) ?></h2>

    <?php if ($updated): ?>
        <div class="w3-panel w3-green w3-round w3-padding">✅ Ticket mis à jour avec succès.</div>
    <?php endif; ?>

    <form method="post" class="w3-container">
        <p>
            <label>Sujet :</label>
            <input class="w3-input w3-border" type="text" name="sujet" value="<?php echo htmlspecialchars($ticket['sujet']) ?>" required>
        </p>

        <p>
            <label>Message :</label>
            <textarea class="w3-input w3-border" name="message" required><?php echo htmlspecialchars($ticket['message']) ?></textarea>
        </p>

        <p>
            <label>Statut :</label>
            <select class="w3-select w3-border" name="statut" required>
                <option value="ouvert"                                                                             <?php echo $ticket['statut'] === 'ouvert' ? 'selected' : '' ?>>Ouvert</option>
                <option value="en cours"                                                                                 <?php echo $ticket['statut'] === 'en cours' ? 'selected' : '' ?>>En cours</option>
                <option value="fermé"                                                                              <?php echo $ticket['statut'] === 'fermé' ? 'selected' : '' ?>>Fermé</option>
            </select>
        </p>

        <p>
            <button type="submit" class="w3-button w3-blue w3-round">Enregistrer</button>
            <a href="gestion_tickets.php" class="w3-button w3-gray w3-round">← Retour</a>
        </p>
    </form>
</div>

</body>
</html>

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

    log_event("Consultation de la liste des tickets par '{$_SESSION['username']}' (rôle: {$_SESSION['role']})");

    $query  = "SELECT * FROM tickets ORDER BY statut ASC, created_at ASC";
    $result = $conn->query($query);

    function getColorClass($status)
    {
        return match ($status) {
            'ouvert' => 'w3-green',
            'en cours' => 'w3-yellow',
            'fermé' => 'w3-red',
            default => 'w3-light-grey'
        };
    }

    $ticketsParStatut = [];

    while ($ticket = $result->fetch_assoc()) {
        $statut                      = $ticket['statut'] ?: 'Inconnu';
        $ticketsParStatut[$statut][] = $ticket;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des tickets</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container w3-light-grey w3-padding-32">

<div class="w3-card-4 w3-white w3-padding">
    <h2 class="w3-center">Liste des tickets par statut</h2>

    <?php foreach ($ticketsParStatut as $statut => $tickets): ?>
        <h3 class="w3-border-bottom w3-padding-small"><?php echo ucfirst(htmlspecialchars($statut)) ?></h3>

        <?php foreach ($tickets as $ticket): ?>
            <div class="w3-margin-bottom w3-border w3-round-large">
                <div class="w3-padding-small
                <?php echo getColorClass($ticket['statut']); ?> w3-round-top">
                    <strong class="w3-text-white"><?php echo strtoupper(htmlspecialchars($ticket['statut'])) ?></strong>
                </div>
                <a href="consultations_tickets.php?id=<?php echo $ticket['id']; ?>" class="w3-button w3-white w3-hover-light-grey w3-block w3-left-align w3-round-bottom">
                    <?php echo htmlspecialchars($ticket['sujet']); ?>
                </a>
            </div>
        <?php endforeach; ?>
<?php endforeach; ?>

    <div class="w3-margin-top">
        <a href="logout.php" class="w3-button w3-red w3-round">Se déconnecter</a>
        <a href="../index.html" class="w3-button w3-gray w3-round">Retour à l'accueil</a>
    </div>
</div>

</body>
</html>

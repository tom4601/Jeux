<?php
    session_start();
    require_once '../db.php';

    function log_event($message)
    {
        $logFile = __DIR__ . '/../logs/logs.log';
        $date    = date('Y-m-d H:i:s');
        $ip      = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $entry   = "[$date] [$ip] $message" . PHP_EOL;
        file_put_contents($logFile, $entry, FILE_APPEND);
    }

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $error = "Veuillez remplir tous les champs.";
            log_event("Tentative de connexion avec champs vides.");
        } else {
            $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows === 1) {
                $user = $res->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['username']       = $user['username'];
                    $_SESSION['role']           = $user['role'];

                    log_event("Connexion réussie pour l'utilisateur '$username' avec rôle '{$user['role']}'");
                    header("Location: gestion_tickets.php");
                    exit;
                } else {
                    $error = "Identifiants incorrects.";
                    log_event("Échec de connexion pour '$username' (mauvais mot de passe)");
                }
            } else {
                $error = "Identifiants incorrects.";
                log_event("Échec de connexion pour '$username' (utilisateur non trouvé)");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-light-grey">

<div class="w3-container w3-display-middle w3-card-4 w3-white w3-padding" style="max-width:400px;">
    <h2 class="w3-center">Connexion</h2>

    <?php if ($error): ?>
        <div class="w3-panel w3-red w3-round">
            <p><?php echo htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>

    <form method="post" class="w3-container">
        <p>
            <label>Nom d'utilisateur</label>
            <input class="w3-input w3-border" type="text" name="username" required>
        </p>
        <p>
            <label>Mot de passe</label>
            <input class="w3-input w3-border" type="password" name="password" required>
        </p>
        <p>
            <button class="w3-button w3-blue w3-round w3-block" type="submit">Se connecter</button>
        </p>
    </form>
</div>

</body>
</html>

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

    $message = '';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role     = trim($_POST['role'] ?? 'technicien');

        if ($username === '' || $password === '') {
            $message = "❌ Tous les champs sont requis.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO utilisateurs (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hash, $role);

            if ($stmt->execute()) {
                $message = " Utilisateur <b>$username</b> ajouté avec succès avec le rôle <b>$role</b>.";
                log_event("Ajout de l'utilisateur '$username' avec le rôle '$role' par " . ($_SESSION['username'] ?? 'inconnu'));
            } else {
                $message = " Erreur : " . $conn->error;
                log_event("Erreur à l'ajout de '$username' : " . $conn->error);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Utilisateur</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-light-grey">

<div class="w3-container w3-display-middle w3-card-4 w3-white w3-padding" style="max-width:400px;">
    <h2 class="w3-center">Créer un nouvel utilisateur</h2>

    <?php if ($message): ?>
        <div class="w3-panel<?php echo(strpos($message, '✅') === 0) ? 'w3-green' : 'w3-red'; ?> w3-round">
            <p><?php echo $message; ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="w3-container">
        <p>
            <label>Nom d'utilisateur</label>
            <input class="w3-input w3-border" type="text" name="username" required>
        </p>
        <p>
            <label>Mot de passe</label>
            <input class="w3-input w3-border" type="password" name="password" required>
        </p>
        <p>
            <label>Rôle</label>
            <select class="w3-select w3-border" name="role" required>
                <option value="technicien" selected>Technicien</option>
                <option value="admin">Administrateur</option>
            </select>
        </p>
        <p>
            <button class="w3-button w3-blue w3-round w3-block" type="submit">Ajouter</button>
        </p>
    </form>
     <a href="../index.html" class="w3-button w3-gray w3-round">Retour à l'accueil</a>
</div>

</body>
</html>

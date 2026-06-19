<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.gc_maxlifetime', 1800);
session_start();
require 'db.php';

function logConnexion($pdo, $nom, $statut)
{
    try {

        $stmt = $pdo->prepare(
            "INSERT INTO log_connexion (nom_utilisateur, ip, statut, date_connexion)
             VALUES (:nom, :ip, :statut, NOW())"
        );
        $stmt->execute([
            'nom' => $nom,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'statut' => $statut
        ]);
    } catch (PDOException $e) {
        // On ignore silencieusement si le log échoue
    }
}



$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $password = $_POST['password'];


    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom = :nom");
    $stmt->execute(['nom' => $nom]);
    $user = $stmt->fetch();



    if ($user && password_verify($password, $user['mot_de_passe'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['nom'] = $user['nom'];

        logConnexion($pdo, $nom, 'succes');

        header('Location: collections.php');
        exit();
    } else {
        logConnexion($pdo, $nom, 'echec');
        $erreur = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>

    <?php require 'menu.php'; ?>

    <h1>Connexion</h1>

    <?php if (isset($erreur)): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" action="connexion.php">
        <label for="nom">Nom d'utilisateur :</label>
        <input type="text" name="nom" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>



        <button type="submit">Se connecter</button>
    </form>

</body>

</html>

<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.gc_maxlifetime', 1800);
session_start();
require 'db.php';

function logConnexion($pdo, $nom, $statut) {
    $stmt = $pdo->prepare(
        "INSERT INTO log_connexion (nom, ip, statut, date_connexion)
         VALUES (:nom, :ip, :statut, NOW())"
    );

    $stmt->execute([
        'nom' => $nom,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'statut' => $statut
    ]);
}

$nom = $_POST['nom'];
$password = $_POST['password'];

$stmt = $pdo->prepare(
    "SELECT * FROM users WHERE nom = :nom"
);

$stmt->execute([
    'nom' => $nom
]);

$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];

    logConnexion($pdo, $nom, 'succes');
    echo "Connexion réussie";
} else {
    logConnexion($pdo, $nom, 'echec');
    echo "Identifiants incorrects";
}

?>


<?php require 'menu.php'; ?>

<h1>Connexion</h1>
<?php if(isset($erreur)): ?>
    <p><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>
<form method="post" action="connexion.php">

    <label for="nom">Nom :</label>
    <input type="text" name="nom" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required><br>

    <button type="submit">Se connecter</button>
</form>
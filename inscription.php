<?php
session_start();
require 'db.php';

$message = "";
$message_color = "red";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (!isset($_POST['consentement_rgpd'])) {
        die("Vous devez accepter la politique de confidentialité pour vous inscrire.");
    }

    $nom = $_POST['nom'];
    $mail = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W]/', $password)) {
        $message = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (:nom, :email, :mot_de_passe)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                'nom' => $nom,
                'email' => $mail,
                'mot_de_passe' => $hashed_password
            ]);
            $message = "Inscription réussie ! <a href='connexion.php'>Cliquez ici pour vous connecter</a>.";
            $message_color = "green";
        } catch (PDOException $e) {

            $message = "Erreur : Ce nom d'utilisateur ou cet email est déjà pris.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>

    <?php require 'menu.php'; ?>

    <h1>Créer un compte sécurisé</h1>

    <?php if (!empty($message)): ?>
        <div
            style="color: <?= $message_color ?>; font-weight: bold; border: 1px solid <?= $message_color ?>; padding: 10px; margin-bottom: 15px;">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="post" action="inscription.php">
        <label for="nom">Nom d'utilisateur :</label>
        <input type="text" name="nom" required><br>

        <label for="email">Email :</label>
        <input type="email" name="email" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required
            title="8 caractères min, 1 majuscule, 1 chiffre, 1 caractère spécial"><br>

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" name="confirm_password" required><br>
        <div class="rgpd-consentement">

            <label>
                <input type="checkbox" name="consentement_rgpd" required>
                J'accepte que mes données personnelles soient collectées et traitées
                conformément à la <a href="politique-confidentialite.html" target="_blank">politique de
                    confidentialité</a>,
                dans le but de gérer mon inscription et l'accès à mes collections.
                Ces données ne seront ni vendues ni partagées avec des tiers.
            </label>
        </div>

        <button type="submit">S'inscrire</button>
    </form>

</body>

</html>
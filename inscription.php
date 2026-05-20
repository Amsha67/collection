
<?php
session_start();
require 'db.php';

$nom = $_POST['nom'];
$mail = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users
        WHERE nom = '$nom'
        AND password = '$password'";

$result = $pdo->query($sql);
$user = $result->fetch();

if ($user) {
    $_SESSION['user_id'] = $user['id'];
    echo "Connexion réussie";
} else {
    echo "Identifiants incorrects";
}
?>



<?php require 'menu.php'; ?>    


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire d'inscription</title>
    <link rel="stylesheet" href="./assets/css/style.css">   

</head>
<body>


<h1>Inscription</h1>
<form method="post" action="connexion.php">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="nom" required><br>

<!-- Dans le formulaire -->
<label for="email">Email :</label>
    <input type="email" name="email" required><br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required><br>

    <button type="submit">S'inscrire</button>
</form>
</body>
</html>


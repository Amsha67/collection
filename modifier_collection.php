<?php
require 'auth_check.php';
require 'menu.php';
?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';

// L'id arrive maintenant en POST (depuis le lien-formulaire de collections.php)
if (!isset($_POST['id'])) {
    die("Accès invalide.");
}
$id = $_POST['id'];

// On vérifie que la collection existe ET qu'elle appartient à l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM collections WHERE id_collection = ? AND id_utilisateur = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$collection = $stmt->fetch();

if (!$collection) {
    die("Vous n'avez pas accès à cette collection.");
}

// Si le formulaire de modification est soumis
if (isset($_POST['nom_collection'])) {
    $stmt = $pdo->prepare("UPDATE collections SET nom_collection = ? WHERE id_collection = ? AND id_utilisateur = ?");
    $stmt->execute([$_POST['nom_collection'], $id, $_SESSION['user_id']]);
    header("Location: collections.php");
    exit();
}
?>
<h1>Modifier la collection</h1>
<form method="post" action="modifier_collection.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
    <input type="text" name="nom_collection" value="<?= htmlspecialchars($collection['nom_collection']) ?>">
    <button type="submit">Modifier</button>
</form>
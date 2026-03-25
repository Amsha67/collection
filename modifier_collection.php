<?php
require 'connexion.php';

$id = $_GET['id'];

// Récupérer la collection à modifier
$stmt = $pdo->prepare("SELECT * FROM collections WHERE id_collection = ?");
$stmt->execute([$id]);
$collection = $stmt->fetch();

// Si le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("UPDATE collections SET nom_collection = ? WHERE id_collection = ?");
    $stmt->execute([$_POST['nom_collection'], $id]);
    header("Location: collections.php");
}
?>

<?php require 'menu.php'; ?>

<h1>Modifier la collection</h1>

<form method="post">
    <input type="text" name="nom_collection" value="<?= htmlspecialchars($collection['nom_collection']) ?>">
    <button type="submit">Modifier</button>
</form>
<?php require 'auth_check.php'; ?>
<?php require 'menu.php'; ?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $type = $_POST['type'];

    $sql = "INSERT INTO collections (nom_collection, id_type, id_utilisateur) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $type, $_SESSION['user_id']]);

    echo "Collection ajoutée";
}

$types = $pdo->query("SELECT * FROM types_collection");
?>

<form method="post">
    <p>Nom : <input type="text" name="nom" /></p>
    <p>Type :
        <select name="type">
                <?php foreach ($types as $t): ?>
                <option value="<?= $t['id_type'] ?>">
                        <?= htmlspecialchars($t['nom_type']) ?>
                </option>
                <?php endforeach; ?>
        </select>
    </p>
    <button type="submit">Ajouter</button>
</form>
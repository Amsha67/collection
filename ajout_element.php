<?php require 'auth_check.php'; ?>
<?php require 'menu.php'; ?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';

$stmt = $pdo->prepare("SELECT * FROM collections WHERE id_utilisateur = ?");
$stmt->execute([$_SESSION['user_id']]);
$collections = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'];
    $numero = ($_POST['numero'] === '') ? null : $_POST['numero'];
    $collection_id = $_POST['collection'];
    $possede = $_POST['possede'];

    $check = $pdo->prepare("SELECT * FROM collections WHERE id_collection = ? AND id_utilisateur = ?");
    $check->execute([$collection_id, $_SESSION['user_id']]);
    if (!$check->fetch()) {
        die("Vous n'avez pas accès à cette collection.");
    }

    $sql = "INSERT INTO elements_collection (titre_element, numero, id_collection, possede) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titre, $numero, $collection_id, $possede]);
    echo "Élément ajouté";
}
?>
<!-- FORMULAIRE HTML -->
<form method="post">
    <p>Titre : <input type="text" name="titre" required /></p>
    <p>Numéro : <input type="number" name="numero" /></p>
    <p>Possédé :
        <select name="possede">
            <option value="1">Oui</option>
            <option value="0">Non</option>
        </select>
    </p>
    <p>Collection :
        <select name="collection">
                <?php foreach ($collections as $c): ?>
                <option value="<?= $c['id_collection'] ?>">
                        <?= htmlspecialchars($c['nom_collection']) ?>
                </option>
                <?php endforeach; ?>
        </select><br>
        <button type="submit">Ajouter</button>
</form>
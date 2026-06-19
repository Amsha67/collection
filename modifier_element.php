<?php
require 'auth_check.php';
require 'menu.php';
?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';

if (!isset($_POST['id'])) {
    die("Accès invalide.");
}
$id = $_POST['id'];

$stmt = $pdo->prepare(
    "SELECT e.* FROM elements_collection e
     JOIN collections c ON e.id_collection = c.id_collection
     WHERE e.id_element = ? AND c.id_utilisateur = ?"
);
$stmt->execute([$id, $_SESSION['user_id']]);
$element = $stmt->fetch();

if (!$element) {
    die("Vous n'avez pas accès à cet élément.");
}

if (isset($_POST['titre_element'])) {
    $stmt = $pdo->prepare(
        "UPDATE elements_collection SET titre_element = ?, numero = ?, possede = ? WHERE id_element = ?"
    );
    $stmt->execute([$_POST['titre_element'], $_POST['numero'], $_POST['possede'], $id]);
    header("Location: elements.php");
    exit();
}
?>
<h1>Modifier l'élément</h1>
<form method="post" action="modifier_element.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
    <p>Titre : <input type="text" name="titre_element" value="<?= htmlspecialchars($element['titre_element']) ?>"></p>
    <p>Numéro : <input type="number" name="numero" value="<?= $element['numero'] ?>"></p>
    <p>Possédé :
        <select name="possede">
            <option value="1" <?= $element['possede'] ? 'selected' : '' ?>>Oui</option>
            <option value="0" <?= !$element['possede'] ? 'selected' : '' ?>>Non</option>
        </select>
    </p>
    <button type="submit">Modifier</button>
</form>
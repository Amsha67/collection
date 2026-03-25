<?php
require 'connexion.php';

$id = $_GET['id'];

// Récupérer l'élément à modifier
$stmt = $pdo->prepare("SELECT * FROM elements_collection WHERE id_element = ?");
$stmt->execute([$id]);
$element = $stmt->fetch();

// Si le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("UPDATE elements_collection SET titre_element = ?, numero = ?, possede = ? WHERE id_element = ?");
    $stmt->execute([$_POST['titre_element'], $_POST['numero'], $_POST['possede'], $id]);
    header("Location: elements.php");
}
?>

<?php require 'menu.php'; ?>

<h1>Modifier l'élément</h1>

<form method="post">
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
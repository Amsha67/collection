

<?php require 'menu.php'; ?> 

<?php
require 'connexion.php';


if($_SERVER["REQUEST_METHOD"] === "POST") {

$titre = $_POST['titre'];
$numero = $_POST['numero'];
$collection = $_POST['collection'];
$possede = $_POST['possede'];

$sql = "INSERT INTO elements_collection (titre_element, numero, id_collection, possede) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$titre, $numero, $collection, $possede]);

echo "Élément ajouté";
}

$collections = $pdo->query("SELECT * FROM collections");
?>

<!-- FORMULAIRE HTML -->

<form method="post">
    
    <p>Titre : <input type="text" name="titre"/><p>
    <p>Numéro : <input type="number" name="numero"/><p>
    <p>Possédé :
    <select name="possede">
        <option value="1">Oui</option>
        <option value="0">Non</option>
    </select>
    </p>

    <p>Collection :<p>
    <select name="collection">
        <?php foreach($collections as $c): ?>
            <option value="<?= $c['id_collection'] ?>">
                <?= htmlspecialchars($c['nom_collection']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Ajouter</button>

</form>

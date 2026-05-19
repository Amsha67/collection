<?php require 'menu.php'; ?>
<link rel="stylesheet" href="./assets/css/style.css">


<?php
require 'connexion.php';
$id_utilisateur = 1; // à remplacer par la session

// Retirer un favori
if(isset($_GET['retirer'])) {
    $stmt = $pdo->prepare("DELETE FROM favoris WHERE id_utilisateur = ? AND id_element = ?");
    $stmt->execute([$id_utilisateur, $_GET['retirer']]);
}

$stmt = $pdo->prepare("
    SELECT e.id_element, e.titre_element, e.numero, c.nom_collection, t.nom_type
    FROM favoris f
    JOIN elements_collection e ON f.id_element = e.id_element
    JOIN collections c ON e.id_collection = c.id_collection
    JOIN types_collection t ON c.id_type = t.id_type
    WHERE f.id_utilisateur = ?
");
$stmt->execute([$id_utilisateur]);
$favoris = $stmt->fetchAll();
?>


<h1>Mes Favoris</h1>

<table border="1">
<tr>
    <th>Titre</th>
    <th>Numéro</th>
    <th>Collection</th>
    <th>Type</th>
    <th>Action</th>
</tr>
<?php foreach($favoris as $f): ?>
<tr>
    <td><?= htmlspecialchars($f['titre_element']) ?></td>
    <td><?= htmlspecialchars($f['numero']) ?></td>
    <td><?= htmlspecialchars($f['nom_collection']) ?></td>
    <td><?= htmlspecialchars($f['nom_type']) ?></td>
    <td>
        <a href="favoris.php?retirer=<?= $f['id_element'] ?>">Retirer des favoris</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
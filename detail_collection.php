<link rel="stylesheet" href="./assets/css/style.css">

<?php require 'menu.php'; ?>
<?php
require 'db.php';

$id = $_GET['id'];

// Récupérer les infos de la collection
$stmt = $pdo->prepare("SELECT c.nom_collection, t.nom_type 
    FROM collections c
    JOIN types_collection t ON c.id_type = t.id_type
    WHERE c.id_collection = ?");
$stmt->execute([$id]);
$collection = $stmt->fetch();

// Récupérer les éléments de la collection
$stmt = $pdo->prepare("SELECT * FROM elements_collection WHERE id_collection = ?");
$stmt->execute([$id]);
$elements = $stmt->fetchAll();
?>


<h1>Collection : <?= htmlspecialchars($collection['nom_collection']) ?></h1>
<p>Type : <?= htmlspecialchars($collection['nom_type']) ?></p>
<div style="overflow-x:auto;">
<table border="3">
<tr>
    <th>Titre</th>
    <th>Numéro</th>
    <th>Possédé</th>
</tr>

<?php foreach($elements as $e): ?>
<tr>
    <td><?= htmlspecialchars($e['titre_element']) ?></td>
    <td><?= htmlspecialchars($e['numero']) ?></td>
    <td><?= $e['possede'] ? "Oui" : "Non" ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>
<a href="collections.php">← Retour aux collections</a>

<?php require 'menu.php'; ?> 
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'connexion.php';


$sql = "SELECT 
        e.id_element,
        e.titre_element,
        e.numero,
        c.nom_collection,
        e.possede
    FROM elements_collection e
    JOIN collections c ON e.id_collection = c.id_collection";

 

$resultat = $pdo->query($sql);
?>

<h1>Éléments</h1>

<table border="1">
<tr>
    <th>Titre</th>
    <th>Numéro</th>
    <th>Collection</th>
    <th>Possédé</th>
</tr>

<?php foreach($resultat as $row): ?>
<tr>
    
    <td><?= htmlspecialchars($row['titre_element']) ?></td>
    <td><?= $row['numero'] ?></td>
    <td><?= htmlspecialchars($row['nom_collection']) ?></td>
    <td><?= $row['possede'] ? "Oui" : "Non" ?></td>
    <td>
        
    <a href="modifier_element.php?id=<?= $row['id_element'] ?>">Modifier</a>
    <a href="supprimer_element.php?id=<?= $row['id_element'] ?>">Supprimer</a>
</td>
<td>
    <a href="ajout_favori.php?id=<?= $row['id_element'] ?>">⭐ Ajouter aux favoris</a>
</td>
</tr>
<?php endforeach; ?>

</table>

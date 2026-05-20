<?php require 'menu.php'; ?> 
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';

//sélectionner les collections avec leur type
$sql = "SELECT c.id_collection, c.nom_collection, t.nom_type
       FROM collections c
       JOIN types_collection t ON c.id_type = t.id_type";

$resultat = $pdo->query($sql);
?>

<h1>Mes collections</h1>
   

   <table border="1">
      <tr>
         <th>Collection</th>
         <th>Type</th>
      </tr>

      <?php foreach($resultat as $row): ?>
      <tr>
         <td><?= htmlspecialchars($row['nom_collection']) ?></td>
         <td><?= htmlspecialchars($row['nom_type']) ?></td>
         <td>
            <a href="detail_collection.php?id=<?= $row['id_collection'] ?>">Détail</a>
            <a href="modifier_collection.php?id=<?= $row['id_collection'] ?>">Modifier</a>
            <a href="supprimer_collection.php?id=<?= $row['id_collection'] ?>">Supprimer</a>
         </td>
      </tr>
      <?php endforeach; ?>

   </table>


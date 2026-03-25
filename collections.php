<?php
require 'connexion.php';

$sql = "SELECT c.nom_collection, t.nom_type
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
</tr>
<?php endforeach; ?>

</table>

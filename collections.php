<?php require 'auth_check.php'; ?>
<?php require 'menu.php'; ?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';
$sql = "SELECT c.id_collection, c.nom_collection, t.nom_type, c.id_utilisateur, u.nom AS proprietaire
        FROM collections c
        JOIN types_collection t ON c.id_type = t.id_type
        JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur";
$resultat = $pdo->query($sql);
?>
<h1>Collections</h1>
<table border="1">
   <tr>
      <th>Collection</th>
      <th>Type</th>
      <th>Propriétaire</th>
      <th>Actions</th>
   </tr>
   <?php foreach ($resultat as $c): ?>
      <tr>
         <td><?= htmlspecialchars($c['nom_collection']) ?></td>
         <td><?= htmlspecialchars($c['nom_type']) ?></td>
         <td><?= htmlspecialchars($c['proprietaire']) ?></td>
         <td>
            <form method="post" action="detail_collection.php" style="display:inline;">
               <input type="hidden" name="id" value="<?= $c['id_collection'] ?>">
               <button type="submit">Détail</button>
            </form>
            <?php if ($c['id_utilisateur'] == $_SESSION['user_id']): ?>
               <form method="post" action="modifier_collection.php" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $c['id_collection'] ?>">
                  <button type="submit">Modifier</button>
               </form>
               <form method="post" action="supprimer_collection.php" style="display:inline;"
                  onsubmit="return confirm('Supprimer cette collection et tous ses éléments ?');">
                  <input type="hidden" name="id" value="<?= $c['id_collection'] ?>">
                  <button type="submit">Supprimer</button>
               </form>
            <?php endif; ?>
         </td>
      </tr>
   <?php endforeach; ?>
</table>
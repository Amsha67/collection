<?php
require 'connexion.php';

if($_SERVER["REQUEST_METHOD"] === "POST") {

   $nom = $_POST['nom'];
   $type = $_POST['type'];

   $sql = "INSERT INTO collections (nom_collection, id_type) VALUES (?, ?)";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$nom, $type]);

   echo "Collection ajoutée";
}

$types = $pdo->query("SELECT * FROM types_collection");
?>

<form method="post">
   Nom : <input type="text" name="nom"><br>

   Type :
   <select name="type">
       <?php foreach($types as $t): ?>
           <option value="<?= $t['id_type'] ?>">
               <?= htmlspecialchars($t['nom_type']) ?>
           </option>
       <?php endforeach; ?>
   </select>

   <button type="submit">Ajouter</button>
</form>

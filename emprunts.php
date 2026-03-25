<?php require 'menu.php'; ?> 

<?php
require 'connexion.php';


// Récupérer les éléments avec leurs emprunts
if($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom_emprunteur'];
    $date = $_POST['date_emprunt'];
    $id_element = $_POST['id_element'];

     // On vérifie si l'élément est déjà emprunté
    $check = $pdo->prepare("SELECT * FROM emprunts WHERE id_element = ?");
    $check->execute([$id_element]);

    if($check->rowCount() == 0) {
        // Pas d'emprunt existant → on insère
        $stmt = $pdo->prepare("INSERT INTO emprunts (nom_emprunteur, date_emprunt, id_element) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $date, $id_element]);
        echo "Emprunt ajouté !";
    
    }
}


// Récupérer les éléments avec leurs emprunts
$sql = "SELECT 
        e.id_element,
        e.titre_element,
        e.numero,
        c.nom_collection,
        t.nom_type,
        e.possede,
        emp.nom_emprunteur,
        emp.date_emprunt
    FROM elements_collection e
    JOIN collections c ON e.id_collection = c.id_collection
    JOIN types_collection t ON c.id_type = t.id_type
    LEFT JOIN emprunts emp ON e.id_element = emp.id_element";

$resultat = $pdo->query($sql);
?>

 <!-- TABLEAU  HTML-->

<h1>Gestion des emprunts</h1>

<table border="1">
<tr>
    <th>Type</th>
    <th>Titre</th>
    <th>Numéro</th>
    <th>Collection</th>
    <th>Possédé</th>
    <th>Emprunté par</th>
    <th>Date emprunt</th>
    <th>Statut/emprunt</th>
</tr>


<!-- TABLEAU COTEE PHP QUI RECUPERE TOUT  -->
<?php foreach($resultat as $row): ?>
<tr>
    <td><?= htmlspecialchars($row['nom_type']) ?></td> 
    <td><?= htmlspecialchars($row['titre_element']) ?></td>
    <td><?= $row['numero'] ?></td>
    <td><?= htmlspecialchars($row['nom_collection']) ?></td>
    <td><?= $row['possede'] ? "Oui" : "Non" ?></td>
    <td><?= $row['nom_emprunteur'] ?? "Personne" ?></td>
    <td><?= $row['date_emprunt'] ?? "-" ?></td>
    <td>
        <?php if(!$row['nom_emprunteur'] && $row['possede']): ?>
            <p>Disponible<p>
            <!-- Formulaire pour prêter l'élément -->
             <h2>Prêter cet élément :</h2>

                 <form method="post">
                        <input type="hidden" name="id_element" value="<?= $row['id_element'] ?>">
                        <input type="text" name="nom_emprunteur" placeholder="Prénom">
                        <input type="date" name="date_emprunt">
                         <button type="submit">Prêter</button>
                 </form>

        <?php elseif(!$row['possede']): ?>
            <p>Indisponible</p>
        <?php else: ?>
            <p>Déjà emprunté<p>
        <?php endif; ?>
        
    </td>
</tr>

<?php endforeach; ?>

</table>
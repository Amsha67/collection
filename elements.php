<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<?php require 'auth_check.php'; ?>
<?php require 'menu.php'; ?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';
$sql = "SELECT 
        e.id_element,
        e.titre_element,
        e.numero,
        c.nom_collection,
        e.possede,
        c.id_utilisateur,
        u.nom AS proprietaire
    FROM elements_collection e
    JOIN collections c ON e.id_collection = c.id_collection
    JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur";
$resultat = $pdo->query($sql);
?>
<h1>Éléments</h1>
<table border="1">
    <tr>
        <th>Titre</th>
        <th>Numéro</th>
        <th>Collection</th>
        <th>Possédé</th>
        <th>Propriétaire</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($resultat as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['titre_element']) ?></td>
            <td><?= $row['numero'] ?></td>
            <td><?= htmlspecialchars($row['nom_collection']) ?></td>
            <td><?= $row['possede'] ? "Oui" : "Non" ?></td>
            <td><?= htmlspecialchars($row['proprietaire']) ?></td>
            <td>
                <?php if ($row['id_utilisateur'] == $_SESSION['user_id']): ?>
                    <form method="post" action="modifier_element.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id_element'] ?>">
                        <button type="submit">Modifier</button>
                    </form>
                    <form method="post" action="supprimer_element.php" style="display:inline;"
                        onsubmit="return confirm('Supprimer cet élément ?');">
                        <input type="hidden" name="id" value="<?= $row['id_element'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                <?php endif; ?>
                <form method="post" action="ajout_favori.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id_element'] ?>">
                    <button type="submit">★ Favori</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
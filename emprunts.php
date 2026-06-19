<?php require 'auth_check.php'; ?>
<?php require 'menu.php'; ?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom_emprunteur'];
    $date = $_POST['date_emprunt'];
    $id_element = $_POST['id_element'];

    // Seul le propriétaire de l'élément peut enregistrer un prêt dessus
    $owner_check = $pdo->prepare(
        "SELECT e.* FROM elements_collection e
         JOIN collections c ON e.id_collection = c.id_collection
         WHERE e.id_element = ? AND c.id_utilisateur = ?"
    );
    $owner_check->execute([$id_element, $_SESSION['user_id']]);
    if (!$owner_check->fetch()) {
        die("Vous n'avez pas accès à cet élément.");
    }

    $check = $pdo->prepare("SELECT * FROM emprunts WHERE id_element = ?");
    $check->execute([$id_element]);
    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO emprunts (nom_emprunteur, date_emprunt, id_element) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $date, $id_element]);
        echo "Emprunt ajouté !";
    }
}

// On affiche TOUS les éléments, avec leur propriétaire
$sql = "SELECT 
        e.id_element,
        e.titre_element,
        e.numero,
        c.nom_collection,
        t.nom_type,
        e.possede,
        c.id_utilisateur,
        u.nom AS proprietaire,
        emp.nom_emprunteur,
        emp.date_emprunt
    FROM elements_collection e
    JOIN collections c ON e.id_collection = c.id_collection
    JOIN types_collection t ON c.id_type = t.id_type
    JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
    LEFT JOIN emprunts emp ON e.id_element = emp.id_element";
$resultat = $pdo->query($sql);
?>
<h1>Gestion des emprunts</h1>
<table border="1">
    <tr>
        <th>Type</th>
        <th>Titre</th>
        <th>Numéro</th>
        <th>Collection</th>
        <th>Propriétaire</th>
        <th>Possédé</th>
        <th>Emprunté par</th>
        <th>Date emprunt</th>
        <th>Statut/emprunt</th>
    </tr>
    <?php foreach ($resultat as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['nom_type']) ?></td>
            <td><?= htmlspecialchars($row['titre_element']) ?></td>
            <td><?= $row['numero'] ?></td>
            <td><?= htmlspecialchars($row['nom_collection']) ?></td>
            <td><?= htmlspecialchars($row['proprietaire']) ?></td>
            <td><?= $row['possede'] ? "Oui" : "Non" ?></td>
            <td><?= htmlspecialchars($row['nom_emprunteur'] ?? "Personne") ?></td>
            <td><?= htmlspecialchars($row['date_emprunt'] ?? "-") ?></td>
            <td>
                <?php if (!$row['nom_emprunteur'] && $row['possede']): ?>
                    <p>Disponible</p>
                    <?php if ($row['id_utilisateur'] == $_SESSION['user_id']): ?>
                        <form method="post">
                            <input type="hidden" name="id_element" value="<?= $row['id_element'] ?>">
                            <input type="text" name="nom_emprunteur" placeholder="Prénom" required>
                            <input type="date" name="date_emprunt" required>
                            <button type="submit">Prêter</button>
                        </form>
                    <?php endif; ?>
                <?php elseif (!$row['possede']): ?>
                    <p>Indisponible</p>
                <?php else: ?>
                    <p>Déjà emprunté</p>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
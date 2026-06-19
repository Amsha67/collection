<?php require 'auth_check.php'; ?>
<?php require 'menu.php'; ?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php
require 'db.php';

// Prêter un élément
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'preter') {
    $nom = $_POST['nom_emprunteur'];
    $date = $_POST['date_emprunt'];
    $id_element = $_POST['id_element'];

    $owner_check = $pdo->prepare(
        "SELECT e.* FROM elements_collection e
         JOIN collections c ON e.id_collection = c.id_collection
         WHERE e.id_element = ? AND c.id_utilisateur = ?"
    );
    $owner_check->execute([$id_element, $_SESSION['user_id']]);
    if (!$owner_check->fetch()) {
        die("Vous n'avez pas accès à cet élément.");
    }

    $check = $pdo->prepare("SELECT * FROM emprunts WHERE id_element = ? AND date_retour IS NULL");
    $check->execute([$id_element]);
    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO emprunts (nom_emprunteur, date_emprunt, id_element) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $date, $id_element]);
    }
}

// Marquer un emprunt comme rendu
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'retourner') {
    $id_emprunt = $_POST['id_emprunt'];
    $id_element = $_POST['id_element'];

    $owner_check = $pdo->prepare(
        "SELECT e.* FROM elements_collection e
         JOIN collections c ON e.id_collection = c.id_collection
         WHERE e.id_element = ? AND c.id_utilisateur = ?"
    );
    $owner_check->execute([$id_element, $_SESSION['user_id']]);
    if (!$owner_check->fetch()) {
        die("Vous n'avez pas accès à cet élément.");
    }

    $stmt = $pdo->prepare("UPDATE emprunts SET date_retour = CURDATE() WHERE id_emprunt = ?");
    $stmt->execute([$id_emprunt]);
}

// Affichage : tous les éléments, avec l'emprunt en cours s'il y en a un
$sql = "SELECT 
        e.id_element, e.titre_element, e.numero, e.possede,
        c.nom_collection, t.nom_type, c.id_utilisateur, u.nom AS proprietaire,
        emp.id_emprunt, emp.nom_emprunteur, emp.date_emprunt
    FROM elements_collection e
    JOIN collections c ON e.id_collection = c.id_collection
    JOIN types_collection t ON c.id_type = t.id_type
    JOIN utilisateurs u ON c.id_utilisateur = u.id_utilisateur
    LEFT JOIN emprunts emp ON e.id_element = emp.id_element AND emp.date_retour IS NULL";
$resultat = $pdo->query($sql);
?>
<h1>Gestion des emprunts</h1>
<table border="1">
    <tr>
        <th>Titre</th>
        <th>Collection</th>
        <th>Propriétaire</th>
        <th>Possédé</th>
        <th>Statut</th>
        <th>Action</th>
    </tr>
    <?php foreach ($resultat as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['titre_element']) ?></td>
            <td><?= htmlspecialchars($row['nom_collection']) ?></td>
            <td><?= htmlspecialchars($row['proprietaire']) ?></td>
            <td><?= $row['possede'] ? "Oui" : "Non" ?></td>
            <td>
                <?php if (!$row['possede']): ?>
                    Indisponible (non possédé)
                <?php elseif ($row['nom_emprunteur']): ?>
                    Emprunté par <strong><?= htmlspecialchars($row['nom_emprunteur']) ?></strong> depuis le
                    <?= htmlspecialchars($row['date_emprunt']) ?>
                <?php else: ?>
                    Disponible
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['id_utilisateur'] == $_SESSION['user_id']): ?>
                    <?php if ($row['possede'] && !$row['nom_emprunteur']): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="action" value="preter">
                            <input type="hidden" name="id_element" value="<?= $row['id_element'] ?>">
                            <input type="text" name="nom_emprunteur" placeholder="Prénom" required>
                            <input type="date" name="date_emprunt" required>
                            <button type="submit">Prêter</button>
                        </form>
                    <?php elseif ($row['nom_emprunteur']): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="action" value="retourner">
                            <input type="hidden" name="id_emprunt" value="<?= $row['id_emprunt'] ?>">
                            <input type="hidden" name="id_element" value="<?= $row['id_element'] ?>">
                            <button type="submit">Marquer comme rendu</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
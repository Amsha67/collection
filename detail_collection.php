<?php
require 'auth_check.php';
require 'db.php';

if (!isset($_POST['id'])) {
    die("Accès invalide.");
}
$id = $_POST['id'];

// Récupérer les infos de la collection, en vérifiant qu'elle appartient à l'utilisateur connecté
$stmt = $pdo->prepare("SELECT c.nom_collection, t.nom_type 
    FROM collections c
    JOIN types_collection t ON c.id_type = t.id_type
    WHERE c.id_collection = ? AND c.id_utilisateur = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$collection = $stmt->fetch();

if (!$collection) {
    die("Vous n'avez pas accès à cette collection.");
}

// Récupérer les éléments de la collection
$stmt = $pdo->prepare("SELECT * FROM elements_collection WHERE id_collection = ?");
$stmt->execute([$id]);
$elements = $stmt->fetchAll();
?>
<link rel="stylesheet" href="./assets/css/style.css">
<?php require 'menu.php'; ?>
<h1>Collection : <?= htmlspecialchars($collection['nom_collection']) ?></h1>
<p>Type : <?= htmlspecialchars($collection['nom_type']) ?></p>
<div style="overflow-x:auto;">
    <table border="3">
        <tr>
            <th>Titre</th>
            <th>Numéro</th>
            <th>Possédé</th>
        </tr>
        <?php foreach ($elements as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['titre_element']) ?></td>
                <td><?= htmlspecialchars($e['numero']) ?></td>
                <td><?= $e['possede'] ? "Oui" : "Non" ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<a href="collections.php">← Retour aux collections</a>
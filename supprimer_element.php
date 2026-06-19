<?php
require 'auth_check.php';
require 'db.php';

if (!isset($_POST['id'])) {
    die("Accès invalide.");
}
$id = $_POST['id'];

// Vérifier que l'élément appartient bien à une collection de l'utilisateur connecté
$stmt = $pdo->prepare(
    "SELECT e.* FROM elements_collection e
     JOIN collections c ON e.id_collection = c.id_collection
     WHERE e.id_element = ? AND c.id_utilisateur = ?"
);
$stmt->execute([$id, $_SESSION['user_id']]);
$element = $stmt->fetch();

if (!$element) {
    die("Vous n'avez pas accès à cet élément.");
}

// Supprimer d'abord les emprunts liés
$stmt = $pdo->prepare("DELETE FROM emprunts WHERE id_element = ?");
$stmt->execute([$id]);

// Puis supprimer l'élément
$stmt = $pdo->prepare("DELETE FROM elements_collection WHERE id_element = ?");
$stmt->execute([$id]);

header("Location: elements.php");
exit();
?>
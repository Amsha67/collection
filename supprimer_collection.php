<?php
require 'auth_check.php';
require 'db.php';

if (!isset($_POST['id'])) {
    die("Accès invalide.");
}
$id = $_POST['id'];

// On vérifie que la collection appartient bien à l'utilisateur connecté avant de supprimer
$stmt = $pdo->prepare("SELECT * FROM collections WHERE id_collection = ? AND id_utilisateur = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$collection = $stmt->fetch();

if (!$collection) {
    die("Vous n'avez pas accès à cette collection.");
}

// 1. Supprimer d'abord les emprunts liés aux éléments
$stmt = $pdo->prepare("DELETE FROM emprunts WHERE id_element IN (SELECT id_element FROM elements_collection WHERE id_collection = ?)");
$stmt->execute([$id]);

// 2. Supprimer les éléments liés
$stmt = $pdo->prepare("DELETE FROM elements_collection WHERE id_collection = ?");
$stmt->execute([$id]);

// 3. Supprimer la collection
$stmt = $pdo->prepare("DELETE FROM collections WHERE id_collection = ?");
$stmt->execute([$id]);

header("Location: collections.php");
exit();
?>
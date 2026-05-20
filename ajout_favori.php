<?php
require 'db.php';
$id_utilisateur = 1; // à remplacer par la session
$id_element = $_GET['id'];

// Vérifier si déjà en favori
$check = $pdo->prepare("SELECT * FROM favoris WHERE id_utilisateur = ? AND id_element = ?");
$check->execute([$id_utilisateur, $id_element]);

if($check->rowCount() == 0) {
    $stmt = $pdo->prepare("INSERT INTO favoris (id_utilisateur, id_element) VALUES (?, ?)");
    $stmt->execute([$id_utilisateur, $id_element]);
}

header("Location: elements.php");
?>
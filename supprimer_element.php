<?php
require 'connexion.php';

$id = $_GET['id'];

// Supprimer d'abord les emprunts liés
$stmt = $pdo->prepare("DELETE FROM emprunts WHERE id_element = ?");
$stmt->execute([$id]);

// Puis supprimer l'élément
$stmt = $pdo->prepare("DELETE FROM elements_collection WHERE id_element = ?");
$stmt->execute([$id]);

header("Location: elements.php");
?>
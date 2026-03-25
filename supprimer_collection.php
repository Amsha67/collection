<?php
require 'connexion.php';

$id = $_GET['id'];

// Supprimer d'abord les éléments liés
$stmt = $pdo->prepare("DELETE FROM elements_collection WHERE id_collection = ?");
$stmt->execute([$id]);

// Puis supprimer la collection
$stmt = $pdo->prepare("DELETE FROM collections WHERE id_collection = ?");
$stmt->execute([$id]);

header("Location: collections.php");
?>
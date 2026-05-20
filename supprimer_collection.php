<?php
require 'db.php';

$id = $_GET['id'];

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
?>
```


<?php
require 'auth_check.php';
require 'db.php';

if (!isset($_POST['id'])) {
    die("Accès invalide.");
}
$id_utilisateur = $_SESSION['user_id'];
$id_element = $_POST['id'];

// Vérifier si déjà en favori
$check = $pdo->prepare("SELECT * FROM favoris WHERE id_utilisateur = ? AND id_element = ?");
$check->execute([$id_utilisateur, $id_element]);
if ($check->rowCount() == 0) {
    $stmt = $pdo->prepare("INSERT INTO favoris (id_utilisateur, id_element) VALUES (?, ?)");
    $stmt->execute([$id_utilisateur, $id_element]);
}
header("Location: elements.php");
exit();
?>
<?php
try {
   $pdo = new PDO("mysql:host=127.0.0.1;port=8889;dbname=gestion_collections;charset=utf8", "root", "root");
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
   die("Erreur : " . $e->getMessage());
}
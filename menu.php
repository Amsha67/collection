<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <a href="collections.php">Collections</a> |
    <a href="ajout_collection.php">Ajouter une collection</a> |
    <a href="elements.php">Éléments</a> |
    <a href="ajout_element.php">Ajouter un élément</a> |
    <a href="emprunts.php">Emprunts</a> |
    <a href="favoris.php">Mes favoris</a> |
    <?php if (isset($_SESSION['user_id'])): ?>
        Connecté en tant que <strong style="color: white;"><?= htmlspecialchars($_SESSION['nom']) ?></strong> |
        <a href="deconnexion.php">Se déconnecter</a>
    <?php else: ?>
        <a href="connexion.php">Se connecter</a> |
        <a href="inscription.php">S'inscrire</a>
    <?php endif; ?>
</nav>
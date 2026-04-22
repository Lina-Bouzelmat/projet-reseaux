<?php
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<header>
    <div class="navbar">
        <div class="logo">CeriFAI</div>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="mesure_debit.php">Débit</a></li>
            <li><a href="forum.php">Forum</a></li>
            <li><a href="mails.php">Mail</a></li>

            <?php if($isAdmin): ?>
                <li><a href="addhost.php">DNS</a></li>
                <li><a href="ip_form.php">IP</a></li>
                <li><a href="controle.php">Contrôle</a></li>
                <li><a href="rapport_sites.php">Rapport</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="login.php">Connexion admin</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>

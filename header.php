<?php
if(!isset($page_title)){
    $page_title = "LinaFAI";
}
if(!isset($active_page)){
    $active_page = "";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($page_title) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="navbar">
        <div class="logo">CERIFAI</div>
        <ul>
            <li><a href="index.php" class="<?= $active_page === 'accueil' ? 'active' : '' ?>">Accueil</a></li>
            <li><a href="mesure_debit.php" class="<?= $active_page === 'debit' ? 'active' : '' ?>">Débit</a></li>
            <li><a href="forum.php" class="<?= $active_page === 'forum' ? 'active' : '' ?>">Forum</a></li>
            <li><a href="mails.php" class="<?= $active_page === 'mail' ? 'active' : '' ?>">Mail</a></li>
            <li><a href="addhost.php" class="<?= $active_page === 'dns' ? 'active' : '' ?>">DNS</a></li>
            <li><a href="ip_form.php" class="<?= $active_page === 'ip' ? 'active' : '' ?>">IP</a></li>
            <li><a href="controle.php" class="<?= $active_page === 'controle' ? 'active' : '' ?>">Contrôle</a></li>
            <li><a href="rapport_sites.php" class="<?= $active_page === 'rapport' ? 'active' : '' ?>">Rapport</a></li>
        </ul>
    </div>
</header>

<div class="container">

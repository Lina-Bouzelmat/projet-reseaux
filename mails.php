<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mails – LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1 class="page-title"> Boîte mail – stud</h1>

    <div class="card terminal">

        <div class="terminal-header">
            <span class="dot red"></span>
            <span class="dot yellow"></span>
            <span class="dot green"></span>
            <span class="terminal-title">/var/mail/stud</span>
        </div>

        <pre class="terminal-body">
<?php
$path = "mails.txt";
if (file_exists($path)) {
    echo htmlspecialchars(file_get_contents($path));
} else {
    echo "Fichier mails.txt introuvable.";
}
?>
        </pre>

    </div>

</div>

<div class="footer">
    LinaFAI – Service Mail Postfix
</div>

</body>
</html>

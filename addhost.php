<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['hostname'])) {
        $host = escapeshellarg($_POST['hostname']);

        $cmd = "ssh stud@192.168.1.12 \"echo '$host IN A 192.168.1.13' | sudo tee -a /etc/bind/db.ceri.com && sudo systemctl restart bind9\"";

        $output = shell_exec($cmd);
        $message = "<p style='color:green;'>Domaine $host.ceri.com ajouté avec succès.</p><pre>$output</pre>";
    } else {
        $message = "<p style='color:red;'>Erreur : aucune saisie détectée.</p>";
    }
}
?>
    
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion DNS – LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1 class="page-title">Gestion DNS</h1>

    <div class="card">
        <h2>Ajout d’un sous-domaine <span style="color:#6cf;">.ceri.com</span></h2>

        <p class="text-muted">
            Cette interface permet d’ajouter dynamiquement un sous-domaine DNS
            sur le serveur BIND distant.
        </p>

        <form method="post">
            <label for="hostname">Nom du sous-domaine :</label>
            <input type="text" name="hostname" id="hostname" placeholder="ex: box-client" required>
            <button type="submit">Ajouter le sous-domaine</button>
        </form>

        <?php echo $message; ?>
    </div>

</div>

<div class="footer">
    LinaFAI – Gestion DNS avancée
</div>

</body>
</html>

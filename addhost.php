<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['hostname'])) {

        // Nettoyage du nom
        $hostname = preg_replace('/[^a-zA-Z0-9\-]/', '', $_POST['hostname']);

        // IP DU CLIENT À ASSOCIER AU SOUS-DOMAINE
        // (ici client = 192.168.1.14 comme sur tes captures)
        $client_ip = "192.168.1.14";

        // COMMANDE SSH VERS DNSP (DNS PRINCIPAL)
        $cmd = "ssh stud@192.168.1.1 \"echo '$hostname IN A $client_ip' | sudo tee -a /etc/bind/db.ceri.com && sudo systemctl restart bind9\"";

        $output = shell_exec($cmd);

        $message = "<p style='color:green;'>Sous-domaine <strong>$hostname.ceri.com</strong> ajouté avec succès.</p>";

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

    <h1>Gestion DNS</h1>

    <div class="card">
        <h2>Ajout d’un sous-domaine <span style="color:#3ddc84;">.ceri.com</span></h2>

        <p>
            Cette interface permet d’ajouter dynamiquement un sous-domaine DNS
            sur le serveur DNS principal (DNSP).
        </p>

        <form method="post">
            <label for="hostname">Nom du sous-domaine :</label>
            <input type="text" name="hostname" id="hostname" placeholder="ex: toto" required>
            <button type="submit">Ajouter le sous-domaine</button>
        </form>

        <?= $message ?>

    </div>

</div>

</body>
</html>

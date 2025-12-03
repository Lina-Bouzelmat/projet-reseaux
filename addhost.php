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
<h2>Ajout d’un sous-domaine .ceri.com</h2>
<form method="post">
    <label for="hostname">Nom du sous-domaine :</label>
    <input type="text" name="hostname" id="hostname" required>
    <input type="submit" value="Ajouter">
</form>

<?php echo $message; ?>

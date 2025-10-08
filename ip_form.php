<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Changer d'adresse IP</title>
</head>
<body>
<h2>Changement d'adresse IP (mode automatique)</h2>
<form method="POST" action="">
    <label for="numero">Nombre d'appareils :</label>
    <input type="number" id="numero" name="numero" min="1" required>
    <button type="submit" name="auto">Appliquer</button>
</form>

<?php
if(isset($_POST["auto"])) {
    $num = intval($_POST["numero"]);
    echo "<p>Mode automatique : $num appareils</p>";
    $output = shell_exec("sudo /home/stud/generation_ip.sh $num 2>&1");
    echo "<pre>$output</pre>";
}
?>

<hr>

<h2>Configuration manuelle (mode avancé)</h2>
<form method="POST" action="">
    <label>Adresse réseau :</label>
    <input type="text" name="reseau" placeholder="192.168.1.0" required><br>
    <label>Masque :</label>
    <input type="text" name="masque" placeholder="255.255.255.0" required><br>
    <label>Début plage :</label>
    <input type="text" name="debut" placeholder="192.168.1.10" required><br>
    <label>Fin plage :</label>
    <input type="text" name="fin" placeholder="192.168.1.20" required><br>
    <label>Passerelle :</label>
    <input type="text" name="gateway" placeholder="192.168.1.1" required><br>
    <button type="submit" name="manuel">Appliquer</button>
</form>

<?php
if(isset($_POST["manuel"])) {
    $reseau = escapeshellarg($_POST["reseau"]);
    $masque = escapeshellarg($_POST["masque"]);
    $debut = escapeshellarg($_POST["debut"]);
    $fin = escapeshellarg($_POST["fin"]);
    $gateway = escapeshellarg($_POST["gateway"]);

    echo "<p>Mode manuel : configuration appliquée</p>";
    $output = shell_exec("sudo /home/stud/manual_dhcp.sh $reseau $masque $debut $fin $gateway 2>&1");
    echo "<pre>$output</pre>";
}
?>
</body>
</html>

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
    <label>Début plage :</label>
    <input type="text" name="debut" placeholder="192.168.1.10" required><br>
    <label>Fin plage :</label>
    <input type="text" name="fin" placeholder="192.168.1.20" required><br>
    <button type="submit" name="manuel">Appliquer</button>
</form>

<?php
if(isset($_POST["manuel"])) {
    $reseau = escapeshellarg($_POST["reseau"] ?? "192.168.1.0");
    $masque = escapeshellarg($_POST["masque"] ?? "255.255.255.0");
    $debut = escapeshellarg($_POST["debut"]);
    $fin = escapeshellarg($_POST["fin"]);
    $gateway = escapeshellarg($_POST["gateway"] ?? "192.168.1.1");

    echo "<p>Mode manuel : configuration appliquée</p>";
    $output = shell_exec("sudo /home/stud/manual_dhcp.sh $reseau $masque $debut $fin $gateway 2>&1");
    echo "<pre>$output</pre>";
}
?>

<hr>

<h2>Modifier uniquement le dernier octet de l'adresse IP actuelle</h2>
<form method="POST" action="">
    <label for="dernier_octet">Dernier octet (ex: 15 pour 192.168.1.15) :</label>
    <input type="number" id="dernier_octet" name="dernier_octet" min="2" max="254" required>
    <button type="submit" name="changer_ip">Changer IP</button>
</form>

<?php
if(isset($_POST["changer_ip"])) {
    $octet = intval($_POST["dernier_octet"]);
    if($octet < 2 || $octet > 254){
        echo "<p>Erreur : valeur invalide (2-254)</p>";
    } else {
        $nouvelle_ip = "192.168.1." . $octet;
        echo "<p>Changement d'adresse IP vers : $nouvelle_ip</p>";
        $output = shell_exec("sudo /home/stud/change_ip.sh $nouvelle_ip 2>&1");
        echo "<pre>$output</pre>";
    }
}
?>
</body>
</html>

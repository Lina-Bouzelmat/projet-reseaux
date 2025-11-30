<!DOCTYPE html>
<html>
<head><title>Ajouter un hôte</title></head>
<body>
<h2>Ajout d’un sous-domaine .ceri.com</h2>
<form method="post">
  Nom du sous-domaine: <input type="text" name="host" required>
  <input type="submit" value="Ajouter">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = htmlspecialchars($_POST["host"]);
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Commande SSH vers DNSP
    $cmd = "ssh stud@192.168.1.12 '/home/stud/add_record.sh $hostname $ip'";
    echo "<pre>";
    system($cmd);
    echo "</pre>";
}
?>
</body>
</html>

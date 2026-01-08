<?php
$message = "";

$dns_ip = "192.168.1.1";     // IP de DNSp
$dns_user = "stud";
$zone_file = "/etc/bind/db.ceri.com";
$zone_name = "ceri.com";

$target_ip = "192.168.1.14";// IP de ma machine

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $hostname = $_POST["hostname"] ?? "";
    $hostname = trim($hostname);

    if($hostname === "" || !preg_match("/^[a-zA-Z0-9-]+$/", $hostname)) {
        $message = "<p style='color:#ff6b6b;'>Erreur : nom invalide (lettres/chiffres/tiret uniquement).</p>";
    } else {
        $hostname = strtolower($hostname);

        $line = $hostname." IN A ".$target_ip;

        $remote = "echo ".escapeshellarg($line)." | sudo tee -a ".escapeshellarg($zone_file)." >/dev/null"
                ." && sudo named-checkzone ".escapeshellarg($zone_name)." ".escapeshellarg($zone_file)
                ." && sudo systemctl restart bind9";

        $cmd = "ssh -o BatchMode=yes -o StrictHostKeyChecking=no ".$dns_user."@".$dns_ip." ".escapeshellarg($remote)." 2>&1";

        $output = [];
        $ret = 0;
        exec($cmd, $output, $ret);
        $outText = htmlspecialchars(implode("\n", $output));

        if($ret === 0) {
            $message = "<p style='color:#3ddc84;'>Sous-domaine <b>".$hostname.".".$zone_name."</b> ajouté (A → ".$target_ip.").</p>";
        } else {
            $message = "<p style='color:#ff6b6b;'><b>Erreur :</b> l'ajout a échoué (SSH/sudo/BIND).</p><pre>".$outText."</pre>";
        }
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

<?php include "menu.php"; ?>

<div class="container">
    <h1>Gestion DNS</h1>

    <div class="card">
        <h2>Ajout d’un sous-domaine <span style="color:#6cf;">.ceri.com</span></h2>

        <p style="color:#ccc;line-height:1.6;">
            Ajout automatique d’une entrée DNS sur le serveur DNS principal (DNSp).
        </p>

        <form method="post">
            <label for="hostname">Nom du sous-domaine :</label>
            <input type="text" name="hostname" id="hostname" placeholder="ex: toto" required>
            <button type="submit">Ajouter le sous-domaine</button>
        </form>

        <?php echo $message; ?>
    </div>
</div>

</body>
</html>

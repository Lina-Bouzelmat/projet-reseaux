<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Configuration IP – LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1>Gestion de l’adresse IP</h1>

    <div class="warning">
        <strong>Attention :</strong><br>
        Les modifications effectuées sur cette page impactent directement la
        configuration réseau de votre infrastructure.<br>
        Si vous ne maîtrisez pas les notions d’adressage IP, DHCP et réseau,
        veuillez ne pas appliquer de changements sans supervision.
    </div>

    <!-- MODE AUTOMATIQUE -->
    <div class="card">
        <h2>Changement d’adresse IP (mode automatique)</h2>

        <form method="POST">
            <label>Nombre d'appareils :</label>
            <input type="number" name="numero" min="1" required>
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
    </div>

    <!-- MODE MANUEL -->
    <div class="card">
        <h2>Configuration manuelle (mode avancé)</h2>

        <form method="POST">
            <label>Début de plage :</label>
            <input type="text" name="debut" placeholder="192.168.1.10" required>

            <label>Fin de plage :</label>
            <input type="text" name="fin" placeholder="192.168.1.20" required>

            <button type="submit" name="manuel">Appliquer</button>
        </form>

        <?php
        if(isset($_POST["manuel"])) {
            $reseau = escapeshellarg($_POST["reseau"] ?? "192.168.1.0");
            $masque = escapeshellarg($_POST["masque"] ?? "255.255.255.0");
            $debut  = escapeshellarg($_POST["debut"]);
            $fin    = escapeshellarg($_POST["fin"]);
            $gateway = escapeshellarg($_POST["gateway"] ?? "192.168.1.1");

            echo "<p>Mode manuel : configuration appliquée</p>";
            $output = shell_exec("sudo /home/stud/manual_dhcp.sh $reseau $masque $debut $fin $gateway 2>&1");
            echo "<pre>$output</pre>";
        }
        ?>
    </div>

    <!-- MODIFICATION DERNIER OCTET -->
    <div class="card">
        <h2>Modifier uniquement le dernier octet</h2>

        <form method="POST">
            <label>Dernier octet (ex : 15 pour 192.168.1.15)</label>
            <input type="number" name="dernier_octet" min="2" max="254" required>
            <button type="submit" name="changer_ip">Changer IP</button>
        </form>

        <?php
        if(isset($_POST["changer_ip"])) {
            $octet = intval($_POST["dernier_octet"]);
            if($octet < 2 || $octet > 254){
                echo "<p style='color:red;'>Erreur : valeur invalide (2-254)</p>";
            } else {
                $nouvelle_ip = "192.168.1." . $octet;
                echo "<p>Nouvelle adresse IP : <strong>$nouvelle_ip</strong></p>";
                $output = shell_exec("sudo /home/stud/change_ip.sh $nouvelle_ip 2>&1");
                echo "<pre>$output</pre>";
            }
        }
        ?>
    </div>

</div>

<footer>
    LinaFAI – Gestion réseau & infrastructure
</footer>

</body>
</html>

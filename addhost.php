<?php
if (isset($_POST['hostname'])) {
    $hostname = escapeshellarg($_POST['hostname']);
    $ip = $_SERVER['REMOTE_ADDR'];  // ou une IP spécifique

    $update = <<<EOD
server 192.168.1.1
zone ceri.com.
update add $hostname.ceri.com. 3600 A $ip
send
EOD;

    file_put_contents("/tmp/nsupdate.txt", $update);
    system("nsupdate -k /etc/bind/dynupdate.key /tmp/nsupdate.txt");

    echo "Domaine $hostname.ceri.com ajouté pour l'IP $ip.";
} else {
?>
<form method="POST">
    Nom de sous-domaine souhaité: <input name="hostname" />
    <input type="submit" value="Créer">
</form>
<?php
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mesure de débit AMS</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <h1>AMS - Supervision réseau</h1>
    <nav>
        <a href="index.html">Accueil</a>
        <a href="mesure_debit.php">Débit</a>
        <a href="forum.php">Forum</a>
        <a href="mail.php">Mail</a>
    </nav>
</header>

<div class="container">

<h2>Mesures de débit réseau</h2>

<table>
<tr>
    <th>Date</th>
    <th>Débit (MB/s)</th>
</tr>

<?php
$file="debit.csv";
$lines=[];

if(file_exists($file)){
    $lines=file($file);
    foreach($lines as $line){
        list($date,$speed)=explode(";",trim($line));
        echo "<tr><td>$date</td><td>$speed</td></tr>";
    }
}
?>
</table>

<h2>Graphique du débit</h2>

<div class="graph">
<?php
$max=30; // débit max attendu

if(!empty($lines)){
    foreach($lines as $line){
        list($date,$speed)=explode(";",trim($line));
        $height=($speed/$max)*100;
        echo "<div class='bar'>
                <div class='value' style='height:{$height}%'></div>
                <span>$speed</span>
              </div>";
    }
}
?>
</div>

</div>

</body>
</html>

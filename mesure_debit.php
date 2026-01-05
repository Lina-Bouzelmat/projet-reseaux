<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mesure de débit AMS</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <nav>
        <a href="index.html">Accueil</a>
        <a href="mesure_debit.php">Débit</a>
        <a href="forum.php">Forum</a>
        <a href="mail.php">Mail</a>
    </nav>
</header>

<div class="container">

<h1>Mesures de débit réseau</h1>

<table>
<tr>
    <th>Date</th>
    <th>Débit (MB/s)</th>
</tr>

<?php
$file="debit.csv";
$speeds=[];

if(file_exists($file)){
    $lines=file($file);
    foreach($lines as $line){
        if(trim($line)=="") continue;
        list($date,$speed)=explode(",",$line);
        $speeds[]=$speed;
        echo "<tr><td>$date</td><td>$speed</td></tr>";
    }
}
$max=max($speeds);
?>
</table>

<h2>Graphique du débit</h2>

<div class="graph-wrapper">

<!-- ECHELLE -->
<div class="y-axis">
    <div><?=round($max)?> MB/s</div>
    <div><?=round($max*0.75)?></div>
    <div><?=round($max*0.5)?></div>
    <div><?=round($max*0.25)?></div>
    <div>0</div>
</div>

<!-- GRAPH -->
<div class="chart">
<?php
foreach($speeds as $speed){
    $height=($speed/$max)*240;
    echo "<div class='bar' style='height:{$height}px'>
            <span>$speed</span>
          </div>";
}
?>
</div>
</div>

<div class="legend">
    <span></span> Débit réseau en MB/s
</div>

</div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mesure de débit AMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("menu.php"); ?>

    
<div class="container">

<h1>Mesures de débit réseau</h1>

<table>
<tr>
    <th>Date</th>
    <th>Débit (MB/s)</th>
</tr>

<?php
$file="debit.csv";
$dates=[];
$speeds=[];

if(file_exists($file)){
    $lines=file($file);
    foreach($lines as $line){
        list($date,$speed)=explode(",",trim($line));
        $dates[]=$date;
        $speeds[]=(float)$speed;
        echo "<tr><td>$date</td><td>$speed</td></tr>";
    }
}
?>
</table>

<h2>Graphique du débit</h2>

<div class="chart-container">

    <div class="y-axis">
        <div>35</div>
        <div>30</div>
        <div>25</div>
        <div>20</div>
        <div>15</div>
        <div>10</div>
        <div>5</div>
        <div>0</div>
    </div>

    <div class="chart">
        <?php
        $max=35; // échelle FIXE
        foreach($speeds as $s){
            $height=($s/$max)*100;
            echo "<div class='bar' style='height:$height%'><span>$s</span></div>";
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

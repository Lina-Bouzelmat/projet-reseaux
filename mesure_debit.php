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
$file = "debit.csv";

if(file_exists($file)){
    $lines = file($file);
    foreach($lines as $line){
        $line = trim($line);
        if($line == "") continue;

        list($date,$speed) = explode(",", $line);

        echo "<tr>
                <td>$date</td>
                <td>$speed</td>
              </tr>";
    }
}
?>
</table>

<h2>Graphique du débit</h2>

<div class="chart">
<?php
if(file_exists($file)){
    $lines = file($file);
    foreach($lines as $line){
        $line = trim($line);
        if($line == "") continue;

        list($date,$speed) = explode(",", $line);
        $height = $speed * 4; // échelle graphique

        echo "<div class='bar' title='$date : $speed MB/s' style='height:".$height."px'>
                <span>$speed</span>
              </div>";
    }
}
?>
</div>

</div>

</body>
</html>

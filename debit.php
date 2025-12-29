<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mesure de débit AMS</title>
<style>
body{font-family:Arial;background:#111;color:#eee;padding:20px;}
table{width:60%;border-collapse:collapse;}
td,th{border:1px solid #444;padding:8px;text-align:center;}
th{background:#222;}
</style>
</head>
<body>

<h1>Mesure de débit réseau</h1>

<table>
<tr>
    <th>Date</th>
    <th>Débit (MB/s)</th>
</tr>

<?php
$file = "debit.csv";
if (file_exists($file)) {
    $lines = file($file);
    foreach ($lines as $line) {
        list($date,$speed) = explode(",",trim($line));
        echo "<tr><td>$date</td><td>$speed</td></tr>";
    }
}
?>
</table>

</body>
</html>

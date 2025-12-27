<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mails - serveurAMS</title>
    <style>
        body{font-family:monospace;background:#111;color:#0f0;padding:20px;}
        h1{color:#6cf;}
        pre{background:#000;padding:15px;border-radius:5px;}
    </style>
</head>
<body>

<h1>📬 Mails de stud</h1>

<pre>
<?php
$path = "mails.txt";
if (file_exists($path)) {
    echo htmlspecialchars(file_get_contents($path));
} else {
    echo "Fichier mails.txt introuvable.";
}
?>
</pre>

</body>
</html>

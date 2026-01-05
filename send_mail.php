<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Envoyer un mail</title>
</head>
<body>

<h2>Envoyer un mail</h2>

<form method="post">
    <label>À :</label><br>
    <input type="text" name="to" value="stud" required><br><br>

    <label>Sujet :</label><br>
    <input type="text" name="subject" required><br><br>

    <label>Message :</label><br>
    <textarea name="message" rows="5" cols="40" required></textarea><br><br>

    <input type="submit" value="Envoyer">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $to = $_POST["to"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $headers = "From: web@serveurAMS\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "<p>Mail envoyé avec succès.</p>";
    } else {
        echo "<p>Erreur lors de l'envoi.</p>";
    }
}
?>

</body>
</html>

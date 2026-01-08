<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Envoyer un mail – LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1 class="page-title"> Envoyer un mail</h1>

    <div class="card">
        <p class="text-muted">
            Cette interface permet d’envoyer un mail via le serveur Postfix local.
            Le message sera traité et stocké comme les autres mails reçus.
        </p>

        <form method="post">
            <label>Destinataire :</label>
            <input type="text" name="to" value="stud" required>

            <label>Sujet :</label>
            <input type="text" name="subject" required>

            <label>Message :</label>
            <textarea name="message" rows="6" required></textarea>

            <button type="submit">Envoyer le mail</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $to = $_POST["to"];
            $subject = $_POST["subject"];
            $message = $_POST["message"];

            $headers = "From: web@serveurAMS\n";

            if (mail($to, $subject, $message, $headers)) {
		shell_exec("sudo /home/stud/collect_mails.sh");
		 echo "<div class='info'>Mail envoyé avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de l’envoi du mail.</div>";
            }
        }
        ?>
    </div>

</div>

<div class="footer">
    LinaFAI – Envoi de mails via Postfix
</div>

</body>
</html>

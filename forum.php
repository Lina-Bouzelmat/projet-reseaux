<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Forum AMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
    <div class="card">
        <h1>Forum serveurAMS</h1>

        <form method="post">
            <input type="text" name="pseudo" placeholder="Votre pseudo" required>
            <textarea name="message" placeholder="Votre message" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>

    <!-- messages -->
    <?php
    if(file_exists("forum.txt")){
        $lines = array_reverse(file("forum.txt"));
        foreach($lines as $l){
            echo "<div class='card'>$l</div>";
        }
    }
    ?>
</div>

</body>
</html>

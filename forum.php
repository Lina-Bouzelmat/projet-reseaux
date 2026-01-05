<?php
$host="localhost";
$dbname="forum_db";
$user="root";
$pass="root";

try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$user,$pass);
}catch(PDOException $e){
    die("Erreur connexion base");
}

/* Envoi message */
if(isset($_POST['pseudo'], $_POST['message'])){
    if(!empty($_POST['pseudo']) && !empty($_POST['message'])){
        $stmt=$pdo->prepare("INSERT INTO messages(pseudo,message) VALUES(?,?)");
        $stmt->execute([$_POST['pseudo'], $_POST['message']]);
    }
}

/* Récupération messages */
$messages=$pdo->query("SELECT * FROM messages ORDER BY date_post DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Forum - LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">

    <!-- FORMULAIRE -->
    <div class="card">
        <h1>Forum serveurAMS</h1>

        <form method="post">
            <input type="text" name="pseudo" placeholder="Votre pseudo" required>
            <textarea name="message" placeholder="Votre message" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>

    <!-- MESSAGES -->
    <?php foreach($messages as $msg){ ?>
        <div class="card">
            <strong><?=htmlspecialchars($msg['pseudo'])?></strong>
            <p class="date"><?=htmlspecialchars($msg['date_post'])?></p>
            <p><?=nl2br(htmlspecialchars($msg['message']))?></p>
        </div>
    <?php } ?>

</div>

</body>
</html>

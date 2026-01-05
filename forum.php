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

if(isset($_POST['pseudo']) && isset($_POST['message'])){
    if(!empty($_POST['pseudo']) && !empty($_POST['message'])){
        $stmt=$pdo->prepare("INSERT INTO messages(pseudo,message) VALUES(?,?)");
        $stmt->execute([$_POST['pseudo'],$_POST['message']]);
    }
}

$messages=$pdo->query("SELECT * FROM messages ORDER BY date_post DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Forum – LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1 class="page-title">Forum LinaFAI</h1>

    <div class="card">
        <h2>Poster un message</h2>

        <form method="post">
            <input type="text" name="pseudo" placeholder="Votre pseudo" required>
            <textarea name="message" placeholder="Votre message" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>

    <div class="card">
        <h2>Derniers messages</h2>

        <?php foreach($messages as $msg){ ?>
            <div class="forum-message">
                <div class="forum-header">
                    <strong><?=htmlspecialchars($msg['pseudo'])?></strong>
                    <span class="forum-date"><?=$msg['date_post']?></span>
                </div>
                <div class="forum-content">
                    <?=nl2br(htmlspecialchars($msg['message']))?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="footer">
    LinaFAI – Forum interne
</div>

</body>
</html>

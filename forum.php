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
<html>
<head>
<meta charset="UTF-8">
<title>Forum - serveurAMS</title>
<style>
body{font-family:Arial;background:#111;color:#eee;padding:20px;}
h1{color:#6cf;}
form{background:#222;padding:15px;border-radius:5px;margin-bottom:20px;}
input,textarea{width:100%;margin-bottom:10px;padding:8px;}
button{padding:8px 15px;}
.message{background:#000;padding:10px;margin-bottom:10px;border-radius:5px;}
.date{color:#999;font-size:12px;}
</style>
</head>

<body>

<h1>Forum serveurAMS</h1>

<form method="post">
<input type="text" name="pseudo" placeholder="Votre pseudo" required>
<textarea name="message" placeholder="Votre message" required></textarea>
<button type="submit">Envoyer</button>
</form>

<?php foreach($messages as $msg){ ?>
<div class="message">
<strong><?=htmlspecialchars($msg['pseudo'])?></strong>
<div class="date"><?=$msg['date_post']?></div>
<p><?=nl2br(htmlspecialchars($msg['message']))?></p>
</div>
<?php } ?>

</body>
</html>

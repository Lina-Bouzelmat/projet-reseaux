<?php
$host="127.0.0.1";
$dbname="forum";
$user="root";
$pass="root";

$conn = new mysqli($host, $user, $pass, $dbname);

if($conn->connect_error){
    die("Erreur connexion base : " . $conn->connect_error);
}

if(isset($_POST['pseudo']) && isset($_POST['message'])){
    $pseudo = $conn->real_escape_string($_POST['pseudo']);
    $message = $conn->real_escape_string($_POST['message']);

    if(!empty($pseudo) && !empty($message)){
        $sql = "INSERT INTO messages (pseudo, message) VALUES ('$pseudo','$message')";
        $conn->query($sql);
    }
}

$result = $conn->query("SELECT * FROM messages ORDER BY date_post DESC LIMIT 10");
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

<form method="post">
  <input type="text" name="pseudo" placeholder="Votre pseudo" required>
  <textarea name="message" placeholder="Votre message" required></textarea>
  <button type="submit">Envoyer</button>
</form>

<?php
while ($row = $result->fetch_assoc()){
    echo "<div class='message'>";
    echo "<div class='pseudo'>" . htmlspecialchars($row['pseudo']) . "</div>";
    echo "<div>" . htmlspecialchars($row['message']) . "</div>";
    echo "<div class='date'>" . $row['date_post'] . "</div>";
    echo "</div>";
}

$conn->close();

?>
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

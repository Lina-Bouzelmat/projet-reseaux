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

</body>
</html>

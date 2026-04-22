<?php
require_once("auth.php");

if(is_admin()){
    header("Location: controle.php");
    exit;
}

$erreur = "";
$redirect = isset($_GET['redirect']) && !empty($_GET['redirect']) ? $_GET['redirect'] : "controle.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $login = isset($_POST['login']) ? trim($_POST['login']) : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";
    $redirect = isset($_POST['redirect']) && !empty($_POST['redirect']) ? $_POST['redirect'] : "controle.php";

    if($login === "admin" && $password === "admin123"){
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login'] = "admin";

        if(strpos($redirect, "http://") === 0 || strpos($redirect, "https://") === 0){
            $redirect = "controle.php";
        }

        header("Location: ".$redirect);
        exit;
    }else{
        $erreur = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion admin - LinaFAI</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-box{
            max-width:500px;
            margin:60px auto;
        }

        .error-box{
            background:#3a1010;
            color:#ffb4b4;
            border:1px solid #7f1d1d;
            padding:12px 14px;
            border-radius:8px;
            margin-bottom:15px;
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
    <div class="box login-box">
        <h1>Connexion administrateur</h1>
        <p class="small">Accès réservé aux fonctions sensibles de la box.</p>

        <?php if(!empty($erreur)): ?>
            <div class="error-box"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

            <label>Identifiant</label>
            <input type="text" name="login" required>

            <label>Mot de passe</label>
            <input type="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <p class="small" style="margin-top:15px;">
            Identifiant : <strong>admin</strong><br>
            Mot de passe : <strong>admin123</strong>
        </p>
    </div>
</div>

<div class="footer">
    LinaFAI – Connexion administrateur
</div>

</body>
</html>

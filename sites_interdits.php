<?php
require_once("db_natbox.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!empty($_POST['domaine'])){
        $domaine = trim($_POST['domaine']);
        $categorie = !empty($_POST['categorie']) ? trim($_POST['categorie']) : null;

        $stmt = $pdo->prepare("INSERT IGNORE INTO sites_interdits(domaine, categorie, actif) VALUES(?, ?, 1)");
        $stmt->execute([$domaine, $categorie]);
    }
    header("Location: sites_interdits.php");
    exit;
}

if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM sites_interdits WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: sites_interdits.php");
    exit;
}

$sites = $pdo->query("SELECT * FROM sites_interdits ORDER BY domaine ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sites interdits NATBOX</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f6f9;padding:30px;}
        .box{background:#fff;padding:20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
        input{width:100%;padding:10px;margin-top:8px;margin-bottom:12px;border:1px solid #ccc;border-radius:8px;box-sizing:border-box;}
        button{background:#2563eb;color:white;border:none;padding:12px 18px;border-radius:8px;cursor:pointer;}
        table{width:100%;border-collapse:collapse;margin-top:15px;}
        th,td{border:1px solid #ddd;padding:10px;text-align:left;}
        th{background:#2563eb;color:white;}
        a{color:#b91c1c;text-decoration:none;}
    </style>
</head>
<body>
    <div class="box">
        <h1>Sites interdits</h1>
        <form method="post">
            <label>Domaine</label>
            <input type="text" name="domaine" placeholder="facebook.com" required>

            <label>Catégorie</label>
            <input type="text" name="categorie" placeholder="reseaux sociaux">

            <button type="submit">Ajouter</button>
        </form>
    </div>

    <div class="box">
        <h2>Liste actuelle</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Domaine</th>
                <th>Catégorie</th>
                <th>Actif</th>
                <th>Action</th>
            </tr>
            <?php foreach($sites as $site): ?>
                <tr>
                    <td><?= htmlspecialchars($site['id']) ?></td>
                    <td><?= htmlspecialchars($site['domaine']) ?></td>
                    <td><?= htmlspecialchars($site['categorie']) ?></td>
                    <td><?= $site['actif'] ? 'Oui' : 'Non' ?></td>
                    <td><a href="?delete=<?= $site['id'] ?>" onclick="return confirm('Supprimer ce site ?')">Supprimer</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>

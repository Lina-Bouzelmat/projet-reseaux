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
<title>Sites interdits – LinaFAI</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">

    <h1>Sites interdits</h1>

    <div class="card">
        <h2>Ajouter un site</h2>

        <form method="post">
            <label>Domaine</label>
            <input type="text" name="domaine" placeholder="facebook.com" required>

            <label>Catégorie</label>
            <input type="text" name="categorie" placeholder="reseaux sociaux">

            <button type="submit">Ajouter</button>
        </form>
    </div>

    <div class="card">
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
                    <td>
                        <a href="?delete=<?= $site['id'] ?>" onclick="return confirm('Supprimer ce site ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

<div class="footer">
    LinaFAI – Gestion des sites interdits
</div>

</body>
</html>

<?php
require_once("db_natbox.php");

$jours = [
    "lundi" => "Lundi",
    "mardi" => "Mardi",
    "mercredi" => "Mercredi",
    "jeudi" => "Jeudi",
    "vendredi" => "Vendredi",
    "samedi" => "Samedi",
    "dimanche" => "Dimanche"
];

$appareilGlobal = $pdo->query("SELECT id FROM appareils WHERE nom = 'TOUS_LES_APPAREILS' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$appareil_global_id = $appareilGlobal ? $appareilGlobal['id'] : 0;

$regles = [];
if($appareil_global_id){
    $stmt = $pdo->prepare("SELECT * FROM regles_parentales WHERE appareil_id = ?");
    $stmt->execute([$appareil_global_id]);
    $regles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$reglesParJour = [];
foreach($regles as $regle){
    $reglesParJour[$regle['jour']] = $regle;
}

$appareils = $pdo->query("SELECT * FROM appareils WHERE nom <> 'TOUS_LES_APPAREILS' ORDER BY ip ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des restrictions NATBOX</title>
    <style>
        body{
            font-family:Arial,sans-serif;
            background:#f4f6f9;
            margin:0;
            padding:30px;
        }
        .container{
            max-width:1200px;
            margin:auto;
        }
        .box{
            background:#fff;
            padding:20px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
            margin-bottom:25px;
        }
        h1,h2{
            margin-top:0;
            color:#1f2d3d;
        }
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }
        th,td{
            border:1px solid #ddd;
            padding:10px;
            text-align:center;
        }
        th{
            background:#2563eb;
            color:#fff;
        }
        input[type="time"]{
            padding:8px;
            border:1px solid #ccc;
            border-radius:8px;
        }
        button{
            margin-top:20px;
            background:#2563eb;
            color:#fff;
            border:none;
            padding:12px 18px;
            border-radius:8px;
            cursor:pointer;
        }
        button:hover{
            background:#1d4ed8;
        }
        .left{
            text-align:left;
        }
        .btn{
            display:inline-block;
            background:#111827;
            color:#fff;
            padding:10px 14px;
            border-radius:8px;
            text-decoration:none;
            margin-right:10px;
        }
        .btn:hover{
            background:#000;
        }
        .vide{
            color:#666;
            font-style:italic;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="box">
        <a class="btn" href="index.php">Accueil</a>
        <a class="btn" href="menu.php">Menu</a>
    </div>

    <div class="box">
        <h1>Gestion des restrictions de connexion</h1>
        <p>
            Cette première version applique les restrictions à tout le réseau interne.
            Les appareils détectés sont affichés plus bas à partir de la box.
        </p>

        <form action="save_restrictions.php" method="post">
            <table>
                <tr>
                    <th>Jour</th>
                    <th>Actif</th>
                    <th>Début blocage</th>
                    <th>Fin blocage</th>
                </tr>
                <?php foreach($jours as $cle => $libelle): 
                    $regle = isset($reglesParJour[$cle]) ? $reglesParJour[$cle] : null;
                ?>
                <tr>
                    <td class="left"><?= $libelle ?></td>
                    <td>
                        <input type="checkbox" name="actif[<?= $cle ?>]" value="1"
                        <?= ($regle && $regle['actif']) ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <input type="time" name="heure_debut[<?= $cle ?>]"
                        value="<?= $regle ? substr($regle['heure_debut'],0,5) : '00:00' ?>">
                    </td>
                    <td>
                        <input type="time" name="heure_fin[<?= $cle ?>]"
                        value="<?= $regle ? substr($regle['heure_fin'],0,5) : '08:00' ?>">
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <button type="submit">Enregistrer les restrictions</button>
        </form>
    </div>

    <div class="box">
        <h2>Appareils connectés à la box</h2>
        <p>
            Cette liste provient de la table <strong>appareils</strong> alimentée par le script de scan ARP.
        </p>

        <?php if(count($appareils) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>IP</th>
                    <th>MAC</th>
                </tr>
                <?php foreach($appareils as $appareil): ?>
                <tr>
                    <td><?= htmlspecialchars($appareil['id']) ?></td>
                    <td><?= htmlspecialchars($appareil['nom'] ? $appareil['nom'] : 'Appareil') ?></td>
                    <td><?= htmlspecialchars($appareil['ip']) ?></td>
                    <td><?= htmlspecialchars($appareil['mac']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="vide">Aucun appareil détecté pour le moment.</p>
        <?php endif; ?>
    </div>

</div>
</body>
</html>

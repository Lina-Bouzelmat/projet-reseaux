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

$appareilGlobal = $pdo->query("SELECT id FROM appareils WHERE nom='TOUS_LES_APPAREILS' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$appareil_global_id = $appareilGlobal ? (int)$appareilGlobal['id'] : 0;

$grille = [];
if($appareil_global_id){
    $stmt = $pdo->prepare("SELECT jour, heure, bloque FROM grille_horaire WHERE appareil_id = ?");
    $stmt->execute([$appareil_global_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($rows as $row){
        $grille[$row['jour']][(int)$row['heure']] = (int)$row['bloque'];
    }
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
            padding:20px;
        }
        .container{
            max-width:1400px;
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
        .grille{
            overflow-x:auto;
        }
        table{
            border-collapse:collapse;
            width:100%;
        }
        th,td{
            border:1px solid #ddd;
            text-align:center;
            padding:6px;
        }
        th{
            background:#2563eb;
            color:#fff;
            font-size:13px;
        }
        .jour{
            background:#f3f4f6;
            font-weight:bold;
            min-width:110px;
            text-align:left;
            padding-left:10px;
        }
        .cell{
            width:28px;
            height:28px;
            cursor:pointer;
            border-radius:4px;
            display:inline-block;
        }
        .bloque{
            background:#ef4444;
        }
        .autorise{
            background:#22c55e;
        }
        .legende{
            margin:15px 0;
        }
        .legende span{
            display:inline-block;
            margin-right:20px;
        }
        .carre{
            width:16px;
            height:16px;
            display:inline-block;
            vertical-align:middle;
            margin-right:6px;
            border-radius:3px;
        }
        .red{background:#ef4444;}
        .green{background:#22c55e;}
        .actions{
            margin-top:20px;
        }
        button{
            background:#2563eb;
            color:#fff;
            border:none;
            padding:12px 18px;
            border-radius:8px;
            cursor:pointer;
            margin-right:10px;
        }
        button:hover{
            background:#1d4ed8;
        }
        .small{
            font-size:13px;
            color:#555;
        }
        .liste{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }
        .liste th,.liste td{
            border:1px solid #ddd;
            padding:10px;
            text-align:left;
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
        <h1>Pages horaires</h1>
        <p class="small">
            Clique sur une case pour changer l’état :
            vert = autorisé, rouge = bloqué.
            Cette première version s’applique à tout le réseau.
        </p>

        <div class="legende">
            <span><span class="carre green"></span>Autorisé</span>
            <span><span class="carre red"></span>Bloqué</span>
        </div>

        <form action="save_restrictions.php" method="post">
            <div class="grille">
                <table>
                    <tr>
                        <th>Jour</th>
                        <?php for($h=0;$h<24;$h++): ?>
                            <th><?= $h ?></th>
                        <?php endfor; ?>
                    </tr>

                    <?php foreach($jours as $cle => $libelle): ?>
                        <tr>
                            <td class="jour"><?= $libelle ?></td>
                            <?php for($h=0;$h<24;$h++): 
                                $bloque = isset($grille[$cle][$h]) ? $grille[$cle][$h] : 0;
                            ?>
                                <td>
                                    <input type="hidden" name="grille[<?= $cle ?>][<?= $h ?>]" value="<?= $bloque ?>" class="input-hidden">
                                    <div class="cell <?= $bloque ? 'bloque' : 'autorise' ?>" onclick="toggleCell(this)"></div>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="actions">
                <button type="submit">Enregistrer la grille</button>
                <button type="button" onclick="toutAutoriser()">Tout autoriser</button>
                <button type="button" onclick="toutBloquer()">Tout bloquer</button>
            </div>
        </form>
    </div>

    <div class="box">
        <h2>Appareils connectés à la box</h2>
        <?php if(count($appareils) > 0): ?>
            <table class="liste">
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
            <p>Aucun appareil détecté.</p>
        <?php endif; ?>
    </div>

</div>

<script>
function toggleCell(cell){
    const input = cell.parentElement.querySelector('.input-hidden');
    if(input.value === "1"){
        input.value = "0";
        cell.classList.remove('bloque');
        cell.classList.add('autorise');
    }else{
        input.value = "1";
        cell.classList.remove('autorise');
        cell.classList.add('bloque');
    }
}

function toutBloquer(){
    document.querySelectorAll('.input-hidden').forEach(function(input){
        input.value = "1";
    });
    document.querySelectorAll('.cell').forEach(function(cell){
        cell.classList.remove('autorise');
        cell.classList.add('bloque');
    });
}

function toutAutoriser(){
    document.querySelectorAll('.input-hidden').forEach(function(input){
        input.value = "0";
    });
    document.querySelectorAll('.cell').forEach(function(cell){
        cell.classList.remove('bloque');
        cell.classList.add('autorise');
    });
}
</script>
</body>
</html>

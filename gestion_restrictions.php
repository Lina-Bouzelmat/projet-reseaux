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

$appareils = $pdo->query("SELECT * FROM appareils ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

$appareil_id = isset($_GET['appareil_id']) ? (int)$_GET['appareil_id'] : 0;

if($appareil_id <= 0 && count($appareils) > 0){
    $appareil_id = (int)$appareils[0]['id'];
}

$grille = [];
if($appareil_id > 0){
    $stmt = $pdo->prepare("SELECT jour, heure, bloque FROM grille_horaire WHERE appareil_id = ?");
    $stmt->execute([$appareil_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($rows as $row){
        $grille[$row['jour']][(int)$row['heure']] = (int)$row['bloque'];
    }
}

$appareilSelectionne = null;
foreach($appareils as $appareil){
    if((int)$appareil['id'] === $appareil_id){
        $appareilSelectionne = $appareil;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des restrictions NATBOX</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background:#0e0e0e;
            color:#eee;
        }

        .container{
            max-width:1300px;
            margin:40px auto;
            padding:20px;
        }

        .box{
            background:linear-gradient(180deg,#1a1a1a,#0c0c0c);
            border:1px solid #222;
            border-radius:12px;
            padding:30px;
            margin-bottom:30px;
            box-shadow:0 10px 30px rgba(0,0,0,0.5);
        }

        h1,h2{
            color:#3ddc84;
            margin-bottom:18px;
        }

        .small{
            font-size:14px;
            color:#bbb;
            line-height:1.6;
        }

        label{
            color:#fff;
            font-weight:bold;
        }

        select{
            padding:10px 14px;
            border:1px solid #333;
            border-radius:8px;
            min-width:420px;
            max-width:100%;
            background:#111;
            color:#eee;
            margin-top:10px;
        }

        .config-line{
            margin-top:18px;
            padding:14px 16px;
            background:#111;
            border:1px solid #2a2a2a;
            border-radius:10px;
            color:#ddd;
        }

        .config-line strong{
            color:#3ddc84;
        }

        .legende{
            margin:20px 0;
            display:flex;
            gap:20px;
            flex-wrap:wrap;
            color:#ddd;
        }

        .legende span{
            display:flex;
            align-items:center;
            gap:8px;
            margin-right:0;
        }

        .carre{
            width:16px;
            height:16px;
            display:inline-block;
            border-radius:4px;
            margin-right:0;
        }

        .red{
            background:#ef4444;
        }

        .green{
            background:#22c55e;
        }

        .table-wrap{
            overflow-x:auto;
            margin-top:20px;
            border:1px solid #2a2a2a;
            border-radius:12px;
            background:#111;
        }

        table{
            border-collapse:collapse;
            width:100%;
            min-width:1100px;
            margin-top:0;
        }

        th,td{
            border:1px solid #2a2a2a;
            text-align:center;
            padding:8px;
        }

        th{
            background:#2563eb;
            color:#fff;
            font-size:13px;
        }

        .jour{
            background:#151515;
            font-weight:bold;
            min-width:110px;
            text-align:left;
            padding-left:10px;
            color:#fff;
        }

        .cell{
            width:22px;
            height:22px;
            cursor:pointer;
            border-radius:5px;
            display:inline-block;
        }

        .bloque{
            background:#ef4444;
        }

        .autorise{
            background:#22c55e;
        }

        .actions{
            margin-top:20px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }

        button{
            background:#3ddc84;
            color:#000;
            border:none;
            padding:12px 18px;
            border-radius:8px;
            cursor:pointer;
            margin-right:0;
            font-weight:bold;
            width:auto;
        }

        button:hover{
            background:#2fbf73;
        }

        .liste{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
            min-width:0;
        }

        .liste th,.liste td{
            border:1px solid #2a2a2a;
            padding:12px;
            text-align:left;
        }

        .liste th{
            background:#2563eb;
            color:#fff;
        }

        .liste td{
            background:#0f0f0f;
            color:#eee;
        }

        .top-form{
            margin:15px 0 20px 0;
        }

        .footer{
            text-align:center;
            padding:20px;
            color:#666;
            font-size:13px;
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">

    <div class="box">
        <h1>Contrôle parental par appareil</h1>
        <p class="small">Choisis un appareil, puis clique sur les cases. Vert = autorisé, rouge = bloqué.</p>

        <form method="get" class="top-form">
            <label><strong>Appareil à configurer :</strong></label><br><br>
            <select name="appareil_id" onchange="this.form.submit()">
                <?php foreach($appareils as $appareil): ?>
                    <option value="<?= (int)$appareil['id'] ?>" <?= ((int)$appareil['id'] === $appareil_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(($appareil['nom'] ?: 'Appareil').' - '.$appareil['ip'].' - '.$appareil['mac']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if($appareilSelectionne): ?>
            <div class="config-line">
                <strong>Configuration actuelle :</strong>
                <?= htmlspecialchars(($appareilSelectionne['nom'] ?: 'Appareil').' - '.$appareilSelectionne['ip'].' - '.$appareilSelectionne['mac']) ?>
            </div>
        <?php endif; ?>

        <div class="legende">
            <span><span class="carre green"></span>Autorisé</span>
            <span><span class="carre red"></span>Bloqué</span>
        </div>

        <form action="save_restrictions.php" method="post">
            <input type="hidden" name="appareil_id" value="<?= (int)$appareil_id ?>">

            <div class="table-wrap">
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
        <h2>Appareils détectés sur la box</h2>
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
    </div>

</div>

<div class="footer">
    LinaFAI – Espace de contrôle parental
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
    document.querySelectorAll('.input-hidden').forEach(function(input){input.value="1";});
    document.querySelectorAll('.cell').forEach(function(cell){
        cell.classList.remove('autorise');
        cell.classList.add('bloque');
    });
}
function toutAutoriser(){
    document.querySelectorAll('.input-hidden').forEach(function(input){input.value="0";});
    document.querySelectorAll('.cell').forEach(function(cell){
        cell.classList.remove('bloque');
        cell.classList.add('autorise');
    });
}
</script>
</body>
</html>

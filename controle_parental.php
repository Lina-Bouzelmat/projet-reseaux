<?php
require_once("db_natbox.php");

$appareils = [];
$regles = [];

try {
    $stmtAppareils = $pdo->query("SELECT * FROM appareils ORDER BY nom ASC, ip ASC");
    $appareils = $stmtAppareils->fetchAll(PDO::FETCH_ASSOC);

    $stmtRegles = $pdo->query("
        SELECT 
            r.id,
            r.jour,
            r.heure_debut,
            r.heure_fin,
            r.actif,
            a.nom,
            a.mac,
            a.ip
        FROM regles_parentales r
        INNER JOIN appareils a ON r.appareil_id = a.id
        ORDER BY a.nom ASC, r.jour ASC, r.heure_debut ASC
    ");
    $regles = $stmtRegles->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur lors du chargement des données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrôle parental NATBOX</title>
    <style>
        body{
            font-family:Arial,sans-serif;
            background:#f4f6f9;
            margin:0;
            padding:30px;
        }
        h1,h2{
            color:#1f2d3d;
        }
        .container{
            max-width:1100px;
            margin:0 auto;
        }
        .box{
            background:white;
            padding:20px;
            border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,0.08);
            margin-bottom:25px;
        }
        label{
            display:block;
            margin-top:12px;
            margin-bottom:5px;
            font-weight:bold;
            color:#333;
        }
        select,input{
            width:100%;
            padding:10px;
            border:1px solid #ccc;
            border-radius:8px;
            box-sizing:border-box;
        }
        button{
            margin-top:18px;
            background:#2563eb;
            color:white;
            border:none;
            padding:12px 18px;
            border-radius:8px;
            cursor:pointer;
            font-size:15px;
        }
        button:hover{
            background:#1d4ed8;
        }
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
            background:white;
        }
        th,td{
            border:1px solid #ddd;
            padding:10px;
            text-align:left;
        }
        th{
            background:#2563eb;
            color:white;
        }
        tr:nth-child(even){
            background:#f9fafb;
        }
        .vide{
            color:#777;
            font-style:italic;
            margin-top:10px;
        }
        .top-links{
            margin-bottom:20px;
        }
        .top-links a{
            display:inline-block;
            margin-right:10px;
            text-decoration:none;
            background:#111827;
            color:white;
            padding:10px 14px;
            border-radius:8px;
        }
        .top-links a:hover{
            background:#000;
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="top-links">
            <a href="index.php">Accueil</a>
            <a href="menu.php">Menu</a>
        </div>

        <div class="box">
            <h1>Contrôle parental NATBOX</h1>
            <p>
                Cette page permet de définir des plages horaires de blocage Internet
                par appareil du réseau, en fonction du jour.
            </p>

            <form action="save_regle.php" method="post">
                <label for="appareil_id">Appareil</label>
                <select name="appareil_id" id="appareil_id" required>
                    <option value="">-- Sélectionner un appareil --</option>
                    <?php foreach($appareils as $appareil): ?>
                        <option value="<?= htmlspecialchars($appareil['id']) ?>">
                            <?= htmlspecialchars(($appareil['nom'] ? $appareil['nom'] : 'Appareil').' - '.$appareil['ip'].' - '.$appareil['mac']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="jour">Jour</label>
                <select name="jour" id="jour" required>
                    <option value="">-- Sélectionner un jour --</option>
                    <option value="lundi">Lundi</option>
                    <option value="mardi">Mardi</option>
                    <option value="mercredi">Mercredi</option>
                    <option value="jeudi">Jeudi</option>
                    <option value="vendredi">Vendredi</option>
                    <option value="samedi">Samedi</option>
                    <option value="dimanche">Dimanche</option>
                </select>

                <label for="heure_debut">Heure de début de blocage</label>
                <input type="time" name="heure_debut" id="heure_debut" required>

                <label for="heure_fin">Heure de fin de blocage</label>
                <input type="time" name="heure_fin" id="heure_fin" required>

                <button type="submit">Enregistrer la règle</button>
            </form>
        </div>

        <div class="box">
            <h2>Appareils enregistrés</h2>

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
                <p class="vide">Aucun appareil enregistré dans la base.</p>
            <?php endif; ?>
        </div>

        <div class="box">
            <h2>Règles enregistrées</h2>

            <?php if(count($regles) > 0): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Appareil</th>
                        <th>IP</th>
                        <th>MAC</th>
                        <th>Jour</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Actif</th>
                    </tr>
                    <?php foreach($regles as $regle): ?>
                        <tr>
                            <td><?= htmlspecialchars($regle['id']) ?></td>
                            <td><?= htmlspecialchars($regle['nom'] ? $regle['nom'] : 'Appareil') ?></td>
                            <td><?= htmlspecialchars($regle['ip']) ?></td>
                            <td><?= htmlspecialchars($regle['mac']) ?></td>
                            <td><?= htmlspecialchars($regle['jour']) ?></td>
                            <td><?= htmlspecialchars($regle['heure_debut']) ?></td>
                            <td><?= htmlspecialchars($regle['heure_fin']) ?></td>
                            <td><?= $regle['actif'] ? 'Oui' : 'Non' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="vide">Aucune règle enregistrée pour le moment.</p>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>

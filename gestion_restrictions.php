<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrôle parental - CeriFAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">

    <div class="card">
        <div class="page-header">
            <div>
                <h1>Contrôle parental par appareil</h1>
                <p class="subtitle">
                    Choisis un appareil, puis clique sur les cases horaires.
                    <strong>Vert</strong> = autorisé, <strong>rouge</strong> = bloqué.
                </p>
            </div>
        </div>

        <div class="top-actions">
            <a href="index.php" class="btn-small">Accueil</a>
            <a href="controle.php" class="btn-small">Retour au menu contrôle</a>
        </div>

        <form method="get" style="margin-top:25px;">
            <label for="appareil_id">Appareil à configurer :</label>
            <select name="appareil_id" id="appareil_id" onchange="this.form.submit()">
                <?php foreach($appareils as $appareil): ?>
                    <option value="<?= (int)$appareil['id'] ?>" <?= ((int)$appareil['id'] === $appareil_id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(($appareil['nom'] ?: 'Appareil').' - '.$appareil['ip'].' - '.$appareil['mac']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if($appareilSelectionne): ?>
        <div class="info-box">
            <div class="info-item">
                <strong>Nom</strong>
                <?= htmlspecialchars($appareilSelectionne['nom'] ?: 'Appareil') ?>
            </div>
            <div class="info-item">
                <strong>Adresse IP</strong>
                <?= htmlspecialchars($appareilSelectionne['ip']) ?>
            </div>
            <div class="info-item">
                <strong>Adresse MAC</strong>
                <?= htmlspecialchars($appareilSelectionne['mac']) ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="legend-box">
            <div class="legend-item">
                <span class="legend-color legend-green"></span>
                <span>Autorisé</span>
            </div>
            <div class="legend-item">
                <span class="legend-color legend-red"></span>
                <span>Bloqué</span>
            </div>
        </div>

        <form action="save_restrictions.php" method="post">
            <input type="hidden" name="appareil_id" value="<?= (int)$appareil_id ?>">

            <div class="grid-wrapper">
                <table class="grid-table">
                    <tr>
                        <th>Jour</th>
                        <?php for($h=0; $h<24; $h++): ?>
                            <th><?= $h ?></th>
                        <?php endfor; ?>
                    </tr>

                    <?php foreach($jours as $jour): ?>
                    <tr>
                        <td><?= htmlspecialchars($jour) ?></td>

                        <?php for($h=0; $h<24; $h++): 
                            $cle = $jour . '_' . $h;
                            $etat = isset($grille[$jour][$h]) ? (int)$grille[$jour][$h] : 1;
                        ?>
                            <td>
                                <input
                                    type="hidden"
                                    name="cases[<?= htmlspecialchars($jour) ?>][<?= $h ?>]"
                                    value="<?= $etat ?>"
                                    class="state-input"
                                >

                                <button
                                    type="button"
                                    class="slot <?= $etat ? 'allowed' : 'blocked' ?>"
                                    onclick="toggleSlot(this)">
                                </button>
                            </td>
                        <?php endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="actions-row">
                <button type="submit">Enregistrer la grille</button>
                <button type="button" onclick="setAllSlots(1)">Tout autoriser</button>
                <button type="button" onclick="setAllSlots(0)">Tout bloquer</button>
            </div>
        </form>
    </div>

    <div class="card table-card">
        <h2 class="section-title">Appareils détectés sur la box</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>IP</th>
                <th>MAC</th>
            </tr>

            <?php foreach($appareils as $appareil): ?>
            <tr>
                <td><?= (int)$appareil['id'] ?></td>
                <td><?= htmlspecialchars($appareil['nom']) ?></td>
                <td><?= htmlspecialchars($appareil['ip']) ?></td>
                <td><?= htmlspecialchars($appareil['mac']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

<script>
function toggleSlot(button){
    var input = button.parentElement.querySelector('.state-input');
    var current = parseInt(input.value);

    if(current === 1){
        input.value = 0;
        button.classList.remove('allowed');
        button.classList.add('blocked');
    } else {
        input.value = 1;
        button.classList.remove('blocked');
        button.classList.add('allowed');
    }
}

function setAllSlots(state){
    var inputs = document.querySelectorAll('.state-input');
    var buttons = document.querySelectorAll('.slot');

    inputs.forEach(function(input){
        input.value = state;
    });

    buttons.forEach(function(button){
        button.classList.remove('allowed', 'blocked');
        button.classList.add(state === 1 ? 'allowed' : 'blocked');
    });
}
</script>

</body>
</html>

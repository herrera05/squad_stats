<?php 
session_start(); 
include 'config/db.php'; 
include 'config/lingue.php'; // Inclusione del sistema lingue

if(!isset($_SESSION['token'])) { $_SESSION['token'] = bin2hex(random_bytes(16)); }
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <title>Squad Stats</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    
    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 10px; font-size: 1.2em;">
        <a href="?lang=it" style="text-decoration:none;" title="Italiano">ITA</a>
        <a href="?lang=en" style="text-decoration:none;" title="English">ENG</a>
        <a href="?lang=es" style="text-decoration:none;" title="Español">ESP</a>
    </div>

    <header class="card header-box">
        <h1><?php echo $txt[$lang]['dashboard_title']; ?></h1>
        <form action="includes/azioni.php" method="POST" class="flex-form">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <input type="number" name="gol_fatti" placeholder="<?php echo $txt[$lang]['gol_fatti']; ?>" required min="0">
            <input type="number" name="gol_subiti" placeholder="<?php echo $txt[$lang]['gol_subiti']; ?>" required min="0">
            <button type="submit" name="registra_partita" class="btn btn-v"><?php echo $txt[$lang]['registra_match']; ?></button>
        </form>
    </header>

    <?php
    $query_podo = $conn->query("SELECT *, (gol * 3 + motm * 2 + assist * 1) as punteggio 
                                FROM giocatori 
                                ORDER BY punteggio DESC, gol DESC, motm DESC, assist DESC LIMIT 1");
    if ($query_podo && $query_podo->num_rows > 0):
        $podo = $query_podo->fetch_assoc();
        if ($podo['punteggio'] > 0): 
    ?>
        <div class="card" style="background: linear-gradient(135deg, rgba(0, 20, 60, 0.9) 0%, rgba(212, 175, 55, 0.2) 100%); border: 3px solid #f9e27d; text-align: center; padding: 30px; margin-bottom: 25px; box-shadow: 0 0 35px rgba(249, 226, 125, 0.4);">
            <h2 style="color: #f9e27d; font-size: 2em; margin-bottom: 5px; text-shadow: 0 0 15px #d4af37;"><?php echo $txt[$lang]['podo_title']; ?></h2>
            <p style="color: #ffffff; text-transform: uppercase; letter-spacing: 2px; font-size: 0.9em; margin-bottom: 20px;"><?php echo $txt[$lang]['podo_sub']; ?></p>
            <h3 style="color: #ffffff; font-size: 2.2em; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                <?php echo htmlspecialchars($podo['nome']); ?>
            </h3>
            <div class="img-stack" style="gap: 15px; justify-content: center; margin-bottom: 15px;">
                <?php 
                if(!empty($podo['immagini'])){ 
                    foreach(explode(',',$podo['immagini']) as $img) {
                        echo "<img src='uploads/".trim($img)."' style='width: 110px; height: auto; border: 2px solid #f9e27d; border-radius: 8px; box-shadow: 0 8px 16px rgba(0,0,0,0.6);'>";
                    }
                }
                ?>
            </div>
            <div style="display: flex; gap: 20px; justify-content: center; font-weight: bold; font-size: 1.1em; background: rgba(0,0,0,0.4); padding: 10px; border-radius: 8px; display: inline-flex; border: 1px solid rgba(212,175,55,0.3);">
                <span><?php echo $txt[$lang]['th_gol']; ?>: <span style="color: #2ecc71;"><?php echo $podo['gol']; ?></span></span>
                <span><?php echo $txt[$lang]['th_assist']; ?>: <span style="color: #3498db;"><?php echo $podo['assist']; ?></span></span>
                <span><?php echo $txt[$lang]['th_motm']; ?>: <span style="color: #f1c40f;"><?php echo $podo['motm']; ?></span></span>
                <span style="color: #f9e27d; margin-left: 10px; border-left: 1px solid rgba(255,255,255,0.3); padding-left: 10px;"><?php echo $txt[$lang]['score_totale']; ?>: <?php echo $podo['punteggio']; ?></span>
            </div>
        </div>
    <?php 
        endif;
    endif; 
    ?>

    <div class="dashboard-grid">
        <div class="card chart-box">
            <canvas id="matchChart"></canvas>
        </div>
        <div class="card stats-summary">
            <h3><?php echo $txt[$lang]['risultati_correnti']; ?></h3>
            <?php 
            $res = $conn->query("SELECT COUNT(*) as t, 
                   SUM(CASE WHEN risultato='V' THEN 1 ELSE 0 END) as v, 
                   SUM(CASE WHEN risultato='N' THEN 1 ELSE 0 END) as n, 
                   SUM(CASE WHEN risultato='P' THEN 1 ELSE 0 END) as p FROM partite")->fetch_assoc();
            $cs_tot = $conn->query("SELECT COUNT(*) FROM partite WHERE gol_subiti = 0")->fetch_row()[0];
            ?>
            <p><?php echo $txt[$lang]['partite']; ?>: <b><?php echo $res['t'] ?? 0; ?></b></p>
            <p class="text-v"><?php echo $txt[$lang]['vittorie']; ?>: <b><?php echo $res['v'] ?? 0; ?></b></p>
            <p class="text-n"><?php echo $txt[$lang]['pareggi']; ?>: <b><?php echo $res['n'] ?? 0; ?></b></p>
            <p class="text-r"><?php echo $txt[$lang]['sconfitte']; ?>: <b><?php echo $res['p'] ?? 0; ?></b></p>
            <p class="text-b"><?php echo $txt[$lang]['clean_sheets']; ?>: <b><?php echo $cs_tot ?? 0; ?></b></p>
            
            <div style="display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap;">
                <form action="includes/azioni.php" method="POST" onsubmit="return confirm('Reset?')">
                    <button type="submit" name="reset_partite" class="btn btn-r btn-small"><?php echo $txt[$lang]['reset_partite']; ?></button>
                </form>
                <form action="includes/azioni.php" method="POST" onsubmit="return confirm('Reset?')">
                    <button type="submit" name="reset_stats_giocatori" class="btn btn-r btn-small" style="background: linear-gradient(145deg, #d32f2f, #7b1fa2);"><?php echo $txt[$lang]['reset_giocatori']; ?></button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <h3><?php echo $txt[$lang]['add_giocatore']; ?></h3>
        <form action="includes/azioni.php" method="POST" enctype="multipart/form-data" class="flex-form" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <input type="text" name="nome" placeholder="<?php echo $txt[$lang]['nome_giocatore']; ?>" required style="flex: 1; min-width: 180px;">
            <input type="file" name="immagini[]" multiple>
            <button type="submit" name="aggiungi_giocatore" class="btn btn-v"><?php echo $txt[$lang]['btn_aggiungi']; ?></button>
            <a href="https://renderz.app/" target="_blank" class="btn btn-n" style="background: linear-gradient(145deg, #2c3e50, #1a252f); color: #f9e27d; border: 1px solid #d4af37; text-decoration: none; display: inline-flex; align-items: center; padding: 10px 15px; font-size: 0.9em; font-weight: bold; border-radius: 6px;">
                <?php echo $txt[$lang]['btn_crea_carta']; ?>
            </a>
        </form>
    </div>

    <form action="includes/azioni.php" method="POST" enctype="multipart/form-data" class="card">
        <table class="table-stats">
            <thead>
                <tr>
                    <th><?php echo $txt[$lang]['th_sel']; ?></th>
                    <th><?php echo $txt[$lang]['th_giocatore']; ?></th>
                    <th><?php echo $txt[$lang]['th_gol']; ?></th>
                    <th><?php echo $txt[$lang]['th_assist']; ?></th>
                    <th><?php echo $txt[$lang]['th_presenze']; ?></th>
                    <th><?php echo $txt[$lang]['th_cs']; ?></th>
                    <th><?php echo $txt[$lang]['th_titolare']; ?></th>
                    <th><?php echo $txt[$lang]['th_motm']; ?></th>
                    <th><?php echo $txt[$lang]['th_carte']; ?></th>
                    <th><?php echo $txt[$lang]['th_stato']; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $giocatori = $conn->query("SELECT * FROM giocatori ORDER BY gol DESC, motm DESC, nome ASC");
                while($g = $giocatori->fetch_assoc()): ?>
                <tr>
                    <td><input type="checkbox" name="selezionati[]" value="<?php echo $g['id']; ?>"></td>
                    <td><b><?php echo htmlspecialchars($g['nome']); ?></b></td>
                    <td><input type="number" name="stats[<?php echo $g['id']; ?>][gol]" value="<?php echo $g['gol']; ?>" min="0"></td>
                    <td><input type="number" name="stats[<?php echo $g['id']; ?>][assist]" value="<?php echo $g['assist']; ?>" min="0"></td>
                    <td><input type="number" name="stats[<?php echo $g['id']; ?>][presenze]" value="<?php echo $g['presenze']; ?>" min="0"></td>
                    <td><input type="number" name="stats[<?php echo $g['id']; ?>][clean_sheets]" value="<?php echo $g['clean_sheets']; ?>" min="0"></td>
                    <td>
                        <input type="radio" name="stats[<?php echo $g['id']; ?>][titolare]" value="1" <?php echo $g['titolare'] ? 'checked' : ''; ?>> S
                        <input type="radio" name="stats[<?php echo $g['id']; ?>][titolare]" value="0" <?php echo !$g['titolare'] ? 'checked' : ''; ?>> N
                    </td>
                    <td><input type="number" name="stats[<?php echo $g['id']; ?>][motm]" value="<?php echo $g['motm']; ?>" min="0"></td>
                    <td>
                        <div class="img-stack">
                            <?php if($g['immagini']){ foreach(explode(',',$g['immagini']) as $img) echo "<img src='uploads/".trim($img)."' class='thumb'>"; } ?>
                        </div>
                    </td>
                    <td>
                        <input type="radio" name="stats[<?php echo $g['id']; ?>][in_rosa]" value="1" <?php echo $g['in_rosa'] ? 'checked' : ''; ?>> In
                        <input type="radio" name="stats[<?php echo $g['id']; ?>][in_rosa]" value="0" <?php echo !$g['in_rosa'] ? 'checked' : ''; ?>> Out
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div class="actions-footer" style="border-top: 1px solid rgba(212,175,55,0.3); padding-top: 20px; margin-top: 20px;">
            <div style="background: rgba(0,0,0,0.3); padding: 10px; border-radius: 8px; border: 1px dashed #d4af37; display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <div>
                    <span style="font-size: 0.9em; color: #f9e27d;"><?php echo $txt[$lang]['foto_selezionati']; ?></span>
                    <input type="file" name="nuova_immagine" style="font-size: 0.85em;">
                    <button type="submit" name="aggiungi_foto_selezionati" class="btn btn-v" style="padding: 6px 12px; font-size: 0.85em;"><?php echo $txt[$lang]['carica_foto']; ?></button>
                </div>
                <div style="border-left: 1px solid rgba(212,175,55,0.4); padding-left: 15px;">
                    <button type="submit" name="assegna_pallone_oro" class="btn btn-v" style="background: linear-gradient(145deg, #ffe066, #f5b041); color: #000; padding: 8px 15px; font-size: 0.9em;"><?php echo $txt[$lang]['assegna_podo']; ?></button>
                </div>
            </div>
        </div>

        <div class="actions-footer">
            <button type="submit" name="salva_modifiche" class="btn btn-n"><?php echo $txt[$lang]['salva_modifiche']; ?></button>
            <button type="submit" name="elimina_selezionati" class="btn btn-r"><?php echo $txt[$lang]['elimina']; ?></button>
            <button type="submit" name="sposta_storico" class="btn btn-v"><?php echo $txt[$lang]['sposta_storico']; ?></button>
            <a href="storico.php" class="btn btn-n"><?php echo $txt[$lang]['storico']; ?></a>
            <a href="esporta.php" class="btn btn-v"><?php echo $txt[$lang]['esporta']; ?></a>
        </div>
    </form>
</div>

<script>
const ctx = document.getElementById('matchChart').getContext('2d');
<?php 
$l=[]; $d=[];
$q = $conn->query("SELECT risultato FROM partite ORDER BY id ASC");
$i=1; while($r=$q->fetch_assoc()){ $l[]="G".$i++; $d[]=($r['risultato']=='V'?3:($r['risultato']=='N'?1:0)); }
?>
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($l); ?>,
        datasets: [{ label: 'Punti', data: <?php echo json_encode($d); ?>, borderColor: '#d4af37', tension: 0.3 }]
    }
});
</script>

<?php include 'includes/player_audio.php'; ?>
</body>
</html>
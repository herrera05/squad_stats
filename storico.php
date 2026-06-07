<?php 
session_start();
include 'config/db.php'; 
include 'config/lingue.php'; // Inclusione del sistema lingue

if(isset($_GET['del'])){ 
    $id_del = intval($_GET['del']);
    $conn->query("DELETE FROM storico WHERE id = $id_del"); 
    header("Location: storico.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <title>Hall Of Fame</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body class="page-storico">
<div class="container">
    
    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 10px; font-size: 1.2em;">
        <a href="?lang=it" style="text-decoration:none;" title="Italiano">ITA</a>
        <a href="?lang=en" style="text-decoration:none;" title="English">ENG</a>
        <a href="?lang=es" style="text-decoration:none;" title="Español">ESP</a>
    </div>

    <header class="header-box">
        <h1><?php echo $txt[$lang]['albo_oro']; ?></h1>
        <div class="actions-footer">
            <a href="index.php" class="btn btn-n"><?php echo $txt[$lang]['torna_dash']; ?></a>
        </div>
    </header>

    <div class="card">
        <table class="table-stats">
            <thead>
                <tr>
                    <th><?php echo $txt[$lang]['nome_giocatore']; ?></th>
                    <th><?php echo $txt[$lang]['th_gol']; ?></th>
                    <th><?php echo $txt[$lang]['th_assist']; ?></th>
                    <th><?php echo $txt[$lang]['th_presenze']; ?></th>
                    <th><?php echo $txt[$lang]['clean_sheets']; ?></th>
                    <th><?php echo $txt[$lang]['th_motm']; ?></th>
                    <th><?php echo $txt[$lang]['podo_vinti']; ?></th>
                    <th><?php echo $txt[$lang]['carte_coll']; ?></th>
                    <th><?php echo $txt[$lang]['data_arch']; ?></th>
                    <th><?php echo $txt[$lang]['azioni']; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $res = $conn->query("SELECT * FROM storico ORDER BY palloni_doro DESC, data_archiviazione DESC");
                if ($res->num_rows > 0):
                    while($s = $res->fetch_assoc()): ?>
                    <tr>
                        <td><b><?php echo htmlspecialchars($s['nome']); ?></b></td>
                        <td><?php echo $s['gol']; ?></td>
                        <td><?php echo $s['assist']; ?></td>
                        <td><?php echo $s['presenze']; ?></td>
                        <td><?php echo $s['clean_sheets']; ?></td>
                        <td class="text-n"><?php echo $s['motm']; ?></td>
                        <td style="color: #f9e27d; font-weight: bold; font-size: 1.2em;">
                            <?php echo $s['palloni_doro'] > 0 ? "🏅 " . $s['palloni_doro'] : "0"; ?>
                        </td>
                        <td>
                            <div class="img-stack">
                                <?php if($s['immagini']){ foreach(explode(',',$s['immagini']) as $img) echo "<img src='uploads/".trim($img)."' class='thumb'>"; } ?>
                            </div>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($s['data_archiviazione'])); ?></td>
                        <td>
                            <a href="storico.php?del=<?php echo $s['id']; ?>" class="btn btn-r btn-small" onclick="return confirm('Delete?')"><?php echo $txt[$lang]['elimina']; ?></a>
                        </td>
                    </tr>
                <?php endwhile; 
                else: ?>
                    <tr><td colspan="10">Nessun dato presente.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/player_audio.php'; ?>
</body>
</html>
<?php
session_start();
include 'config/db.php';
include 'config/lingue.php'; // Inclusione del sistema lingue

// Impostiamo gli header per forzare il download del file TXT con codifica UTF-8
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="report_squadra_' . $lang . '.txt"');

// 1. TITOLO E STATISTICHE GENERALI (Tradotte)
echo "=========================================\n";
echo "   " . strtoupper($txt[$lang]['dashboard_title']) . " ($lang)\n";
echo "=========================================\n\n";

echo "--- " . $txt[$lang]['risultati_correnti'] . " ---\n";

$res = $conn->query("SELECT COUNT(*) as t, 
       SUM(CASE WHEN risultato='V' THEN 1 ELSE 0 END) as v, 
       SUM(CASE WHEN risultato='N' THEN 1 ELSE 0 END) as n, 
       SUM(CASE WHEN risultato='P' THEN 1 ELSE 0 END) as p FROM partite")->fetch_assoc();
$cs_tot = $conn->query("SELECT COUNT(*) FROM partite WHERE gol_subiti = 0")->fetch_row()[0];

echo $txt[$lang]['partite'] . ": " . ($res['t'] ?? 0) . "\n";
echo $txt[$lang]['vittorie'] . ": " . ($res['v'] ?? 0) . "\n";
echo $txt[$lang]['pareggi'] . ": " . ($res['n'] ?? 0) . "\n";
echo $txt[$lang]['sconfitte'] . ": " . ($res['p'] ?? 0) . "\n";
echo $txt[$lang]['clean_sheets'] . ": " . ($cs_tot ?? 0) . "\n\n";

// 2. GIOCATORE PALLONE D'ORO ATTUALE (Tradotto)
$query_podo = $conn->query("SELECT *, (gol * 3 + motm * 2 + assist * 1) as punteggio 
                            FROM giocatori 
                            ORDER BY punteggio DESC, gol DESC, motm DESC, assist DESC LIMIT 1");
if ($query_podo && $query_podo->num_rows > 0) {
    $podo = $query_podo->fetch_assoc();
    if ($podo['punteggio'] > 0) {
        echo "--- " . $txt[$lang]['podo_title'] . " ---\n";
        echo $podo['nome'] . " (" . $txt[$lang]['score_totale'] . ": " . $podo['punteggio'] . ")\n";
        echo $txt[$lang]['th_gol'] . ": " . $podo['gol'] . " | " . $txt[$lang]['th_assist'] . ": " . $podo['assist'] . " | " . $txt[$lang]['th_motm'] . ": " . $podo['motm'] . "\n\n";
    }
}

// 3. CLASSIFICA E STATISTICHE GIOCATORI CORRENTI (Tradotte)
echo "--- " . strtoupper($txt[$lang]['th_giocatore']) . " ---\n";
// Creazione intestazione colonne incolonnate dinamicamente
printf(
    "%-20s | %-6s | %-6s | %-6s | %-4s | %-4s\n", 
    $txt[$lang]['th_giocatore'], 
    $txt[$lang]['th_gol'], 
    $txt[$lang]['th_assist'], 
    $txt[$lang]['th_presenze'], 
    $txt[$lang]['th_cs'], 
    $txt[$lang]['th_motm']
);
echo str_repeat("-", 60) . "\n";

$giocatori = $conn->query("SELECT * FROM giocatori ORDER BY gol DESC, motm DESC, nome ASC");
while($g = $giocatori->fetch_assoc()) {
    printf(
        "%-20s | %-6d | %-6d | %-6d | %-4d | %-4d\n",
        $g['nome'],
        $g['gol'],
        $g['assist'],
        $g['presenze'],
        $g['clean_sheets'],
        $g['motm']
    );
}
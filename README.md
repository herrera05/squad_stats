# ⚽ Squad Stats Dashboard

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%207.4-8892BF?style=flat-square&logo=php)](https://www.php.net/)
[![MySQL Version](https://img.shields.io/badge/MySQL-%3E%3D%205.7-4479A1?style=flat-square&logo=mysql)](https://www.mysql.com/)
[![JS Version](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript)](https://developer.mozilla.org/it/)
[![Languages](https://img.shields.io/badge/Languages-IT%20%7C%20EN%20%7C%20ES-orange?style=flat-square)](#)

Benvenuto nella **Squad Stats Dashboard**, un'applicazione web dinamica progettata per digitalizzare, tracciare e valorizzare le statistiche sportive (Gol, Assist, Presenze, Clean Sheet, MOTM) di una squadra di calcio.

L'interfaccia utente adotta una *Dark Mode* accattivante con dettagli dorati, fortemente ispirata alla modalità **Ultimate Team** dei più famosi videogiochi calcistici, portando il concetto di *gamification* all'interno del calcio dilettantistico e amatoriale.

---

## 🚀 Funzionalità Principali

- **📊 Dashboard in Tempo Reale:** Sintesi immediata dei risultati globali della squadra (Vittorie, Pareggi, Sconfitte, Clean Sheets totali) e grafico lineare dinamico dell'andamento punti sviluppato con *Chart.js*.
- **🏆 Algoritmo "Pallone d'Oro Momentaneo":** Sistema meritocratico che calcola in tempo reale il miglior giocatore della rosa in base a un punteggio pesato:
  $$\text{Score} = (\text{Gol} \times 3) + (\text{MOTM} \times 2) + (\text{Assist} \times 1)$$
- **🎵 Riproduttore Audio Continuo (FIFA Style):** Playlist musicale di sottofondo integrata tramite JavaScript (`localStorage`). La musica non si interrompe durante il passaggio tra le pagine, riprende dallo stesso millesimo di secondo e mostra un banner temporaneo con il titolo del brano.
- **🌐 Supporto Multilingua Globale:** Interfaccia interamente localizzata e commutabile istantaneamente in tre lingue: **Italiano (🇮🇹)**, **Inglese (🇬🇧)** e **Spagnolo (🇪🇸)**.
- **📥 Esportazione Report Intelligente (.TXT):** Generazione di un file di testo pulito, formattato in colonne perfette e **tradotto dinamicamente nella lingua attiva sul sito al momento del download**. L'export si concentra sulla stagione corrente, escludendo l'archivio.
- **🏛️ Albo d'Oro e Storico Leggende:** Sezione dedicata all'archiviazione delle stagioni passate, con contatore dei Palloni d'Oro ufficiali vinti e supporto al caricamento di card multiple per rappresentare l'evoluzione del giocatore nel tempo.
- **🖼️ Collegamento RenderZ:** Pulsante integrato per accedere in una nuova scheda alla piattaforma *RenderZ*, ideale per ideare o consultare l'estetica delle carte prima del caricamento.

---

## 🛠️ Stack Tecnologico

- **Backend:** PHP (Gestione sessioni, elaborazione form, logica multilingua)
- **Database:** MySQL / MariaDB (Tabelle: `giocatori`, `partite`, `storico`)
- **Frontend:** HTML5, CSS3 (Layout responsive strutturato per moduli), JavaScript (ES6+)
- **Librerie:** [Chart.js](https://www.chartjs.org/) (Grafici lineari renderizzati lato client)

<?php
// Připojení k SQLite databázi [cite: 38]
$db = new PDO("sqlite:profile.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// SQL příkaz pro vytvoření tabulky se sloupci id a name [cite: 26, 27, 28, 29]
$sql = "CREATE TABLE IF NOT EXISTS interests (
    id INTEGER PRIMARY KEY AUTOINCREMENT, -- unikátní identifikátor [cite: 32, 33]
    name TEXT NOT NULL UNIQUE              -- název zájmu, nesmí být duplicitní [cite: 34, 35, 50]
)";

$db->exec($sql);
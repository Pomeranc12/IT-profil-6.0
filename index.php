<?php
session_start(); // Nutné pro přenos hlášek [cite: 73, 80]
require 'init.php'; // Připojení k databázi [cite: 38]

// --- 1. ZPRACOVÁNÍ FORMULÁŘE (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');

    // Přidání zájmu [cite: 44]
    if ($action === 'add') {
        if (empty($name)) {
            $_SESSION['msg'] = "Pole nesmí být prázdné."; // [cite: 72]
        } else {
            try {
                $stmt = $db->prepare("INSERT INTO interests (name) VALUES (?)"); // [cite: 47, 77]
                $stmt->execute([$name]); // [cite: 78]
                $_SESSION['msg'] = "Zájem byl přídán."; // [cite: 68]
            } catch (PDOException $e) {
                $_SESSION['msg'] = "Tento zájem už existuje."; // [cite: 71]
            }
        }
    }

    // Smazání zájmu [cite: 51]
    if ($action === 'delete') {
        $stmt = $db->prepare("DELETE FROM interests WHERE id = ?"); // [cite: 54, 86]
        $stmt->execute([$id]);
        $_SESSION['msg'] = "Zájem byl odstraněn."; // [cite: 70]
    }

    // Editace zájmu [cite: 55]
    if ($action === 'edit') {
        if (empty($name)) {
            $_SESSION['msg'] = "Pole nesmí být prázdné.";
        } else {
            $stmt = $db->prepare("UPDATE interests SET name = ? WHERE id = ?"); // [cite: 58, 85]
            $stmt->execute([$name, $id]);
            $_SESSION['msg'] = "Zájem byl upraven."; // [cite: 69]
        }
    }

    // PRG Pattern - Přesměrování [cite: 61, 62, 64]
    header("Location: index.php");
    exit;
}

// --- 2. NAČTENÍ DAT (GET) ---
// Načtení všech zájmů z databáze [cite: 41, 42, 83]
$interests = $db->query("SELECT * FROM interests")->fetchAll(PDO::FETCH_ASSOC); // [cite: 79]
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil 6.0</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Moje zájmy</h1>

        <?php if (isset($_SESSION['msg'])): ?>
            <p class="alert"><?= $_SESSION['msg'] ?></p>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <form method="post" class="add-form">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" placeholder="Nový zájem">
            <button type="submit">Přidat</button>
        </form>

        <ul class="interest-list">
            <?php foreach ($interests as $item): ?>
                <li>
                    <form method="post" class="inline-form">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>">
                        <button type="submit" name="action" value="edit" class="btn-edit">Upravit</button>
                        <button type="submit" name="action" value="delete" class="btn-delete">Smazat</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
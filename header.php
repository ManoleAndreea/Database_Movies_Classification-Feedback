<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Cinema</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Get current page name to set active class
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="nav-bar">
    <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
        🏠 Acasă
    </a>
    <a href="filme.php" class="<?php echo ($current_page == 'filme.php' || $current_page == 'detalii.php') ? 'active' : ''; ?>">
        🎬 Filme
    </a>
    <a href="actori.php" class="<?php echo ($current_page == 'actori.php') ? 'active' : ''; ?>">
        🎭 Actori
    </a>
    <a href="regizori.php" class="<?php echo ($current_page == 'regizori.php') ? 'active' : ''; ?>">
    🎬 Regizori
    </a>
    <a href="adaugare.php" class="<?php echo ($current_page == 'adaugare.php') ? 'active' : ''; ?>">
        ➕ Adaugă Film
    </a>
    <a href="statistici.php" class="<?php echo ($pagina_curenta == 'statistici.php') ? 'active' : ''; ?>">
        📊 Statistici
    </a>
    

    <a href="clasificare.php" class="<?php echo ($current_page == 'clasificare.php') ? 'active' : ''; ?>">
        📂 Clasificare
    </a>
    <a href="logout.php" class="logout">🚪 Deconectare</a>
</div>
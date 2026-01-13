<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

$mesaj = "";
$tip_mesaj = "";

if (isset($_POST['salveaza'])) {
    $nume = $mysqli->real_escape_string($_POST['nume']);
    $varsta = intval($_POST['varsta']);
    $bio = $mysqli->real_escape_string($_POST['bibliografie']);

    // 1. Insert details first
    $sql_detalii = "INSERT INTO detalii_persoana (nume_persoana, varsta, bibliografie) VALUES ('$nume', $varsta, '$bio')";
    
    if ($mysqli->query($sql_detalii)) {
        $id_date_nou = $mysqli->insert_id;

        // 2. Insert into regizor
        $sql_regizor = "INSERT INTO regizor (nume_regizor, id_date) VALUES ('$nume', $id_date_nou)";
        
        if ($mysqli->query($sql_regizor)) {
            $mesaj = "✅ Regizorul a fost creat cu succes!";
            $tip_mesaj = "success";
        } else {
            $mesaj = "❌ Eroare la crearea regizorului: " . $mysqli->error;
            $tip_mesaj = "error";
        }
    } else {
        $mesaj = "❌ Eroare la detalii: " . $mysqli->error;
        $tip_mesaj = "error";
    }
}

// INCLUDE HEADER (Nav + CSS)
include 'header.php';
?>

<div class="container">
    <div class="form-card">
        <h1>Creează Regizor Nou</h1>
        
        <?php if($mesaj): ?>
            <div class="msg <?php echo $tip_mesaj; ?>"><?php echo $mesaj; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nume Regizor:</label>
            <input type="text" name="nume" required placeholder="ex: Christopher Nolan">

            <label>Vârstă:</label>
            <input type="number" name="varsta" required placeholder="ex: 53" min="1" max="120">

            <label>Biografie:</label>
            <textarea name="bibliografie" required placeholder="Scrie o scurtă descriere despre stilul și filmele regizorului..."></textarea>

            <button type="submit" name="salveaza" class="btn-submit">💾 Salvează Regizor</button>
        </form>

        <a href="dashboard.php" class="btn-back">⬅ Înapoi la Dashboard</a>
    </div>
</div>

</body>
</html>
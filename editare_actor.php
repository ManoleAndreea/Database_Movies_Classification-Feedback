<?php
session_start();
if (!isset($_SESSION['loggedin'])) { header("Location: login.php"); exit(); }
require_once 'config.php';

$mesaj = "";
$id_actor = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_actor == 0) { die("ID invalid!"); }

// --- PROCESARE UPDATE ---
if (isset($_POST['salveaza'])) {
    $nume = $mysqli->real_escape_string($_POST['nume']);
    $varsta = intval($_POST['varsta']);
    $bio = $mysqli->real_escape_string($_POST['bibliografie']);
    
    // 1. Luam ID-ul detaliilor asociate acestui actor
    $res_check = $mysqli->query("SELECT id_date FROM actor WHERE id_actor = $id_actor");
    $row_check = $res_check->fetch_assoc();
    $id_date = $row_check['id_date'];

    if ($id_date) {
        // 2. Update tabela DETALII_PERSOANA
        $sql_detalii = "UPDATE detalii_persoana SET 
                        nume_persoana = '$nume', 
                        varsta = $varsta, 
                        bibliografie = '$bio' 
                        WHERE id_date = $id_date";
        $mysqli->query($sql_detalii);

        // 3. Update tabela ACTOR (doar numele)
        $sql_actor = "UPDATE actor SET nume_actor = '$nume' WHERE id_actor = $id_actor";
        $mysqli->query($sql_actor);

        $mesaj = "<div class='msg success'>✅ Datele actorului au fost actualizate!</div>";
    } else {
        $mesaj = "<div class='msg error'>❌ Eroare: Nu am găsit detaliile actorului.</div>";
    }
}

// --- PRELUARE DATE PENTRU FORMULAR ---
$sql_get = "SELECT a.nume_actor, d.varsta, d.bibliografie 
            FROM actor a 
            LEFT JOIN detalii_persoana d ON a.id_date = d.id_date 
            WHERE a.id_actor = $id_actor";
$res = $mysqli->query($sql_get);
$actor = $res->fetch_assoc();
?>

<?php include 'header.php'; ?>


<div class="container">
    <div class="form-card">
        <h2>✏️ Editează Actor</h2>
        <?php echo $mesaj; ?>

        <form method="POST">
            <label>Nume Actor:</label>
            <input type="text" name="nume" value="<?php echo htmlspecialchars($actor['nume_actor']); ?>" required>

            <label>Vârstă:</label>
            <input type="number" name="varsta" value="<?php echo $actor['varsta']; ?>" required>

            <label>Biografie:</label>
            <textarea name="bibliografie" required><?php echo htmlspecialchars($actor['bibliografie']); ?></textarea>

            <button type="submit" name="salveaza" class="btn-submit">💾 Salvează Modificările</button>
        </form>

        <a href="detalii_actor.php?id=<?php echo $id_actor; ?>" class="btn-back">⬅ Înapoi la Detalii</a>
    </div>
</div>
</body>
</html>
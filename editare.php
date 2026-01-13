<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

$mesaj = "";
$id_film = 0;

// VERIFICAM DACA AVEM UN ID VALID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_film = intval($_GET['id']);
} else {
    die("ID invalid!");
}

// --- PROCESARE UPDATE (CAND SE APASA BUTONUL) ---
if (isset($_POST['update_film'])) {
    $nume = $mysqli->real_escape_string($_POST['nume_film']);
    $an = intval($_POST['an_aparitie']);
    $durata = intval($_POST['durata']);
    $id_regizor = intval($_POST['id_regizor']);
    // Adaugam si descrierea la update
    $descriere = $mysqli->real_escape_string($_POST['descriere']);
    
    // 1. UPDATE TABEL FILM
    $sql_update = "UPDATE film SET 
                   nume_film = '$nume', 
                   an_aparitie = $an, 
                   durata = $durata, 
                   id_regizor = $id_regizor,
                   descriere = '$descriere'
                   WHERE id_film = $id_film";
                    
    if ($mysqli->query($sql_update)) {
        // 2. UPDATE GENURI (Relatie N:N)
        $mysqli->query("DELETE FROM film_gen WHERE id_film = $id_film");
        
        if (isset($_POST['genuri']) && is_array($_POST['genuri'])) {
            foreach ($_POST['genuri'] as $id_gen) {
                $id_gen = intval($id_gen);
                $mysqli->query("INSERT INTO film_gen (id_film, id_gen) VALUES ($id_film, $id_gen)");
            }
        }
        
        $mesaj = "<div class='alert success'>✅ Filmul a fost actualizat cu succes! <a href='filme.php'>Înapoi la listă →</a></div>";
    } else {
        $mesaj = "<div class='alert error'>❌ Eroare SQL: " . $mysqli->error . "</div>";
    }
}

// --- PRELUARE DATE PENTRU FORMULAR ---
$result = $mysqli->query("SELECT * FROM film WHERE id_film = $id_film");
if ($result->num_rows == 0) { die("Filmul nu exista."); }
$film = $result->fetch_assoc();

// Luam genurile actuale ale filmului
$genuri_curente = [];
$res_gen = $mysqli->query("SELECT id_gen FROM film_gen WHERE id_film = $id_film");
while($row = $res_gen->fetch_assoc()){
    $genuri_curente[] = $row['id_gen'];
}
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editează Film - Cinema BD</title>
    <style>

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        .form-card {
            background: rgba(44, 62, 80, 0.95); backdrop-filter: blur(15px);
            border-radius: 12px; padding: 40px; box-shadow: 0 15px 40px rgba(0,0,0,0.6);
            border: 1px solid rgba(255, 255, 255, 0.1); animation: fadeInUp 0.8s ease-out;
        }
        
        .form-card h2 {
            color: #fff; font-size: 28px; margin-bottom: 10px; padding-bottom: 15px;
            border-bottom: 3px solid #f39c12; text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }
        .form-card h2::before { content: '✏️ '; font-size: 32px; margin-right: 10px; }
        
        label {
            display: block; margin-bottom: 8px; margin-top: 15px; color: #ecf0f1;
            font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        input[type="text"], input[type="number"], select, textarea {
            width: 100%; padding: 14px; background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2); color: white; border-radius: 8px;
            margin-bottom: 5px; box-sizing: border-box; font-size: 15px; transition: all 0.3s ease;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none; background: rgba(255, 255, 255, 0.15);
            border: 1px solid #f39c12; box-shadow: 0 0 15px rgba(243, 156, 18, 0.3);
        }
        
        textarea { resize: vertical; min-height: 100px; }
        select option { background: #2c3e50; color: white; }
        
        .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .checkbox-group {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px; margin-bottom: 10px; padding: 15px;
            background: rgba(0, 0, 0, 0.2); border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .checkbox-group label {
            display: flex; align-items: center; margin: 0; padding: 10px;
            background: rgba(255, 255, 255, 0.05); border-radius: 6px; cursor: pointer;
            transition: all 0.3s ease; text-transform: none; font-weight: 400;
        }
        .checkbox-group label:hover { background: rgba(243, 156, 18, 0.2); transform: translateX(3px); }
        .checkbox-group input[type="checkbox"] { width: auto; margin-right: 10px; cursor: pointer; transform: scale(1.2); }
        
        .btn-submit {
            width: 100%; padding: 16px; background: linear-gradient(45deg, #f39c12, #f1c40f);
            color: white; border: none; border-radius: 8px; font-weight: 700;
            font-size: 15px; text-transform: uppercase; letter-spacing: 1px;
            cursor: pointer; margin-top: 25px; transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(243, 156, 18, 0.4);
        }
        .btn-submit:hover {
            transform: translateY(-2px); box-shadow: 0 8px 30px rgba(243, 156, 18, 0.6);
            background: linear-gradient(45deg, #f1c40f, #f39c12);
        }
        
        .alert { padding: 16px; margin-bottom: 25px; border-radius: 8px; text-align: center; font-weight: 600; }
        .success { background: rgba(46, 204, 113, 0.2); color: #2ecc71; border: 2px solid #2ecc71; }
        .error { background: rgba(231, 76, 60, 0.2); color: #e74c3c; border: 2px solid #e74c3c; }
        .alert a { color: inherit; text-decoration: none; border-bottom: 2px solid currentColor; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>



<div class="container">
    <div class="form-card">
        <h2>Editează Film</h2>
        
        <?php echo $mesaj; ?>

        <form method="POST" action="">
            <label>Titlu Film:</label>
            <input type="text" name="nume_film" value="<?php echo htmlspecialchars($film['nume_film']); ?>" required placeholder="Introdu titlul filmului">

            <div class="input-row">
                <div>
                    <label>An Apariție:</label>
                    <input type="number" name="an_aparitie" value="<?php echo $film['an_aparitie']; ?>" placeholder="2024">
                </div>
                <div>
                    <label>Durata (minute):</label>
                    <input type="number" name="durata" value="<?php echo $film['durata']; ?>" placeholder="120">
                </div>
            </div>

            <label>Regizor:</label>
            <div style="display: flex; gap: 10px; align-items: flex-start;">
                <select name="id_regizor" style="flex-grow: 1;">
                    <option value="0">-- Selectează Regizor --</option>
                    <?php
                    $res_regizori = $mysqli->query("SELECT * FROM regizor ORDER BY nume_regizor ASC");
                    while($r = $res_regizori->fetch_assoc()){
                        $selected = ($r['id_regizor'] == $film['id_regizor']) ? "selected" : "";
                        echo "<option value='".$r['id_regizor']."' $selected>".$r['nume_regizor']."</option>";
                    }
                    ?>
                </select>
                <a href="adauga_regizor.php" title="Adaugă Regizor Nou" 
                   style="background: #27ae60; padding: 0 15px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: white; text-decoration: none; border: 1px solid #2ecc71; margin-bottom: 5px;">
                    ➕
                </a>
            </div>

            <label>Descriere / Sinopsis:</label>
            <textarea name="descriere" placeholder="Despre ce este filmul..."><?php echo htmlspecialchars($film['descriere']); ?></textarea>

            <label>Genuri (selectează unul sau mai multe):</label>
            <div class="checkbox-group">
                <?php
                $res_genuri = $mysqli->query("SELECT * FROM gen ORDER BY nume_gen ASC");
                while($g = $res_genuri->fetch_assoc()){
                    $checked = in_array($g['id_gen'], $genuri_curente) ? "checked" : "";
                    echo "<label>
                            <input type='checkbox' name='genuri[]' value='".$g['id_gen']."' $checked> 
                            " . htmlspecialchars($g['nume_gen']) . "
                          </label>";
                }
                ?>
            </div>

            <button type="submit" name="update_film" class="btn-submit">💾 Salvează Modificările</button>
        </form>
    </div>
</div>

</body>
</html>
<?php
session_start();
// Verificare autentificare
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

$mesaj = "";
$tip_mesaj = "";

// PROCESARE FORMULAR
if (isset($_POST['salveaza_film'])) {
    $nume = $mysqli->real_escape_string($_POST['nume_film']);
    $an = intval($_POST['an_aparitie']);
    $durata = intval($_POST['durata']);
    $id_regizor = intval($_POST['id_regizor']);
    $descriere = $mysqli->real_escape_string($_POST['descriere']);
    
    // Validare simpla
    if (!empty($nume) && $id_regizor > 0) {
        
        // 1. INSERARE IN TABELUL FILM
        $sql_film = "INSERT INTO film (nume_film, an_aparitie, durata, id_regizor, descriere) 
                     VALUES ('$nume', $an, $durata, $id_regizor, '$descriere')";
        
        if ($mysqli->query($sql_film)) {
            // Luam ID-ul filmului tocmai creat
            $id_film_nou = $mysqli->insert_id;
            
            // 2. INSERARE GENURI
            if (isset($_POST['genuri']) && is_array($_POST['genuri'])) {
                foreach ($_POST['genuri'] as $id_gen) {
                    $id_gen = intval($id_gen);
                    $mysqli->query("INSERT INTO film_gen (id_film, id_gen) VALUES ($id_film_nou, $id_gen)");
                }
            }

            // 3. INSERARE DISTRIBUTIE (ACTORI) - NOUTATEA
            if (isset($_POST['actori']) && is_array($_POST['actori'])) {
                foreach ($_POST['actori'] as $id_actor) {
                    $id_actor = intval($id_actor);
                    // Inseram legatura in tabelul distributie
                    $mysqli->query("INSERT INTO distributie (id_film, id_actor) VALUES ($id_film_nou, $id_actor)");
                }
            }
            
            $mesaj = "✅ Filmul și distribuția au fost salvate cu succes!";
            $tip_mesaj = "success";
        } else {
            $mesaj = "❌ Eroare SQL: " . $mysqli->error;
            $tip_mesaj = "error";
        }
    } else {
        $mesaj = "⚠️ Te rog completează titlul și alege un regizor.";
        $tip_mesaj = "error";
    }
}
?>


<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă Film Nou</title>
    <style>
      
        .container { padding: 40px 20px; max-width: 800px; margin: 0 auto; }
        
        .form-card {
            background: rgba(44, 62, 80, 0.95); backdrop-filter: blur(15px);
            border-radius: 12px; padding: 40px; box-shadow: 0 15px 50px rgba(0,0,0,0.7);
            border: 1px solid rgba(255, 255, 255, 0.1); animation: fadeInUp 0.8s ease-out;
        }
        
        .form-card h1 {
            text-align: center; color: #2ecc71; margin-bottom: 30px;
            font-size: 32px; text-transform: uppercase; letter-spacing: 2px;
            border-bottom: 3px solid #27ae60; padding-bottom: 15px;
        }
        
        label { display: block; margin-bottom: 8px; margin-top: 20px; color: #bdc3c7; font-weight: bold; font-size: 14px; text-transform: uppercase; }
        
        input[type="text"], input[type="number"], select, textarea {
            width: 100%; padding: 14px; background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2); color: white; border-radius: 8px;
            margin-bottom: 5px; font-size: 15px; transition: all 0.3s ease;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none; background: rgba(255, 255, 255, 0.15);
            border: 1px solid #2ecc71; box-shadow: 0 0 15px rgba(46, 204, 113, 0.3);
        }
        
        textarea { resize: vertical; min-height: 100px; }
        select option { background: #2c3e50; color: white; }
        
        .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        /* Checkbox Grid pentru Genuri si Actori */
        .checkbox-group {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px; padding: 15px; background: rgba(0, 0, 0, 0.2);
            border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1);
            max-height: 200px; overflow-y: auto; /* Scroll daca sunt multi actori */
        }
        
        .checkbox-group label {
            margin: 0; padding: 10px; background: rgba(255, 255, 255, 0.05);
            border-radius: 6px; cursor: pointer; text-transform: none; font-weight: normal;
            display: flex; align-items: center; transition: background 0.3s;
        }
        
        .checkbox-group label:hover { background: rgba(46, 204, 113, 0.2); }
        .checkbox-group input { width: auto; margin-right: 10px; transform: scale(1.2); }
        
        /* Butoane de adaugare rapida (+) */
        .btn-plus {
            background: #27ae60; padding: 0 15px; height: 50px; 
            display: flex; align-items: center; justify-content: center; 
            border-radius: 8px; color: white; text-decoration: none; 
            border: 1px solid #2ecc71; transition: all 0.3s;
        }
        .btn-plus:hover { background: #2ecc71; transform: scale(1.05); }

        .btn-plus-small {
            display: inline-block; background: #27ae60; color: white; 
            text-decoration: none; padding: 5px 10px; border-radius: 5px; 
            font-size: 12px; margin-left: 10px; border: 1px solid #2ecc71;
        }
        .btn-plus-small:hover { background: #2ecc71; }

        .btn-submit {
            width: 100%; padding: 16px; background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white; border: none; border-radius: 8px; font-weight: 700;
            font-size: 16px; text-transform: uppercase; cursor: pointer; margin-top: 30px;
            transition: all 0.3s ease; box-shadow: 0 5px 20px rgba(46, 204, 113, 0.4);
        }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(46, 204, 113, 0.6); }
        
        .msg { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .success { background: rgba(46, 204, 113, 0.2); color: #2ecc71; border: 2px solid #2ecc71; }
        .error { background: rgba(231, 76, 60, 0.2); color: #e74c3c; border: 2px solid #e74c3c; }
        
        .btn-back { display: block; text-align: center; margin-top: 20px; color: #95a5a6; text-decoration: none; }
        .btn-back:hover { color: white; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>



<div class="container">
    <div class="form-card">
        <h1>Adaugă Film Nou</h1>
        
        <?php if($mesaj): ?>
            <div class="msg <?php echo $tip_mesaj; ?>"><?php echo $mesaj; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Titlu Film:</label>
            <input type="text" name="nume_film" required placeholder="ex: Interstellar">

            <div class="input-row">
                <div>
                    <label>An Apariție:</label>
                    <input type="number" name="an_aparitie" placeholder="2024" min="1900" max="2100">
                </div>
                <div>
                    <label>Durata (minute):</label>
                    <input type="number" name="durata" placeholder="120">
                </div>
            </div>

            <label>Regizor:</label>
            <div style="display: flex; gap: 10px; align-items: flex-start;">
                <select name="id_regizor" required style="flex-grow: 1;">
                    <option value="">-- Selectează Regizor --</option>
                    <?php
                    $res_regizori = $mysqli->query("SELECT id_regizor, nume_regizor FROM regizor ORDER BY nume_regizor ASC");
                    while($r = $res_regizori->fetch_assoc()){
                        echo "<option value='".$r['id_regizor']."'>".$r['nume_regizor']."</option>";
                    }
                    ?>
                </select>
                <a href="adauga_regizor.php" class="btn-plus" title="Adaugă Regizor Nou">➕</a>
            </div>

            <label>Descriere / Sinopsis:</label>
            <textarea name="descriere" placeholder="Despre ce este vorba în film..."></textarea>

            <label>Genuri (poți selecta mai multe):</label>
            <div class="checkbox-group">
                <?php
                $res_genuri = $mysqli->query("SELECT * FROM gen ORDER BY nume_gen ASC");
                while($g = $res_genuri->fetch_assoc()){
                    echo "<label>
                            <input type='checkbox' name='genuri[]' value='".$g['id_gen']."'> 
                            " . htmlspecialchars($g['nume_gen']) . "
                          </label>";
                }
                ?>
            </div>

            <label>
                Distribuție (selectează actorii): 
                <a href="adauga_actor.php" class="btn-plus-small" title="Crează un actor nou">➕ Actor Nou</a>
            </label>
            <div class="checkbox-group">
                <?php
                $res_actori = $mysqli->query("SELECT id_actor, nume_actor FROM actor ORDER BY nume_actor ASC");
                if ($res_actori->num_rows > 0) {
                    while($actor = $res_actori->fetch_assoc()){
                        echo "<label>
                                <input type='checkbox' name='actori[]' value='".$actor['id_actor']."'> 
                                " . htmlspecialchars($actor['nume_actor']) . "
                              </label>";
                    }
                } else {
                    echo "<p style='padding:10px; color:#bdc3c7;'>Nu există actori. Adaugă unul!</p>";
                }
                ?>
            </div>

            <button type="submit" name="salveaza_film" class="btn-submit">💾 Salvează Filmul & Distribuția</button>
        </form>

        <a href="filme.php" class="btn-back">⬅ Înapoi la Lista de Filme</a>
    </div>
</div>

</body>
</html>
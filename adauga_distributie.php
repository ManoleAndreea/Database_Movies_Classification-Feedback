<?php
session_start();
// Verificare autentificare
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

// Mesaje pentru utilizator
$mesaj_succes = "";
$mesaj_eroare = "";

// 1. Daca s-a trimis formularul
if (isset($_POST['salveaza'])) {
    $id_film = intval($_POST['id_film']);
    $id_actor = intval($_POST['id_actor']);

    // Validare simpla
    if ($id_film > 0 && $id_actor > 0) {
        
        // A. Verificam daca legatura exista deja
        $check_sql = "SELECT * FROM distributie WHERE id_film = $id_film AND id_actor = $id_actor";
        $check_res = $mysqli->query($check_sql);

        if ($check_res->num_rows > 0) {
            $mesaj_eroare = "⚠️ Acest actor este deja în distribuția filmului selectat!";
        } else {
            // B. Inseram legatura
            $sql_insert = "INSERT INTO distributie (id_film, id_actor) VALUES ('$id_film', '$id_actor')";
            
            if ($mysqli->query($sql_insert)) {
                $mesaj_succes = "✅ Actor adăugat cu succes în film!";
            } else {
                $mesaj_eroare = "❌ Eroare la baza de date: " . $mysqli->error;
            }
        }
    } else {
        $mesaj_eroare = "⚠️ Te rog selectează atât filmul cât și actorul.";
    }
}

// 2. Verificam daca am venit cu un ID de film din URL
$preselected_film = isset($_GET['id_film']) ? intval($_GET['id_film']) : 0;
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă în Distribuție</title>
    <style>
       
        
        .container {
            padding: 40px 20px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .form-card {
            background: rgba(44, 62, 80, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 45px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeInUp 0.8s ease-out;
        }
        
        .form-card h1 {
            color: #fff;
            font-size: 32px;
            margin-bottom: 15px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3498db;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .form-card h1::before {
            content: '🎬 ';
            font-size: 36px;
            margin-right: 10px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            margin-top: 20px;
            color: #ecf0f1;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        select {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 10px;
            margin-bottom: 5px;
            box-sizing: border-box;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            cursor: pointer;
        }
        
        select:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid #3498db;
            box-shadow: 0 0 20px rgba(52, 152, 219, 0.4);
        }
        
        select option {
            background: #2c3e50;
            color: white;
            padding: 10px;
        }
        
        .actor-row {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            align-items: stretch;
        }
        
        .actor-row select {
            flex-grow: 1;
            margin-bottom: 0;
        }
        
        .btn-add-actor {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            border: 1px solid rgba(46, 204, 113, 0.3);
            font-size: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
            min-width: 55px;
        }
        
        .btn-add-actor:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.5);
            background: linear-gradient(45deg, #2ecc71, #27ae60);
        }
        
        .btn-submit {
            width: 100%;
            padding: 18px;
            background: linear-gradient(45deg, #2980b9, #3498db);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            margin-top: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(52, 152, 219, 0.4);
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 35px rgba(52, 152, 219, 0.6);
            background: linear-gradient(45deg, #3498db, #2980b9);
        }
        
        .btn-submit:active {
            transform: translateY(-1px);
        }
        
        .msg {
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            font-size: 15px;
            animation: slideDown 0.5s ease-out;
        }
        
        .success {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 2px solid #2ecc71;
        }
        
        .error {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 2px solid #e74c3c;
        }
        
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #95a5a6;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            padding: 10px;
            border-radius: 8px;
        }
        
        .btn-back:hover {
            color: #ecf0f1;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .form-hint {
            color: #95a5a6;
            font-size: 13px;
            margin-top: 5px;
            margin-bottom: 15px;
            font-style: italic;
        }
        
        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
        
        @keyframes slideDown {
            from { 
                opacity: 0; 
                transform: translateY(-20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
    </style>
</head>
<body>



<div class="container">
    <div class="form-card">
        <h1>Adaugă în Distribuție</h1>

        <?php if($mesaj_succes): ?>
            <div class="msg success"><?php echo $mesaj_succes; ?></div>
        <?php endif; ?>
        <?php if($mesaj_eroare): ?>
            <div class="msg error"><?php echo $mesaj_eroare; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <label>Alege Filmul:</label>
            <select name="id_film" required>
                <option value="">-- Selectează Filmul --</option>
                <?php
                $res_filme = $mysqli->query("SELECT id_film, nume_film FROM film ORDER BY nume_film ASC");
                while($row = $res_filme->fetch_assoc()) {
                    $selected = "";
                    if ($row['id_film'] == $preselected_film) {
                        $selected = "selected";
                    } elseif (isset($_POST['id_film']) && $_POST['id_film'] == $row['id_film']) {
                        $selected = "selected";
                    }
                    
                    echo "<option value='".$row['id_film']."' $selected>".$row['nume_film']."</option>";
                }
                ?>
            </select>
            <p class="form-hint">Selectează filmul în care dorești să adaugi un actor</p>

            <label>Alege Actorul:</label>
            <div class="actor-row">
                <select name="id_actor" required>
                    <option value="">-- Selectează Actorul --</option>
                    <?php
                    $res_actori = $mysqli->query("SELECT id_actor, nume_actor FROM actor ORDER BY nume_actor ASC");
                    while($row = $res_actori->fetch_assoc()) {
                        echo "<option value='".$row['id_actor']."'>".$row['nume_actor']."</option>";
                    }
                    ?>
                </select>
                
                <a href="adauga_actor.php" class="btn-add-actor" title="Actorul nu e în listă? Adaugă unul nou!">
                    ➕
                </a>
            </div>
            <p class="form-hint" style="margin-top: -15px;">Selectează actorul sau adaugă unul nou cu butonul ➕</p>
            
            <button type="submit" name="salveaza" class="btn-submit">💾 Salvează Legătura</button>
        </form>

        <?php 
        $back_link = ($preselected_film > 0) ? "detalii.php?id=$preselected_film" : "dashboard.php";
        ?>
        <a href="<?php echo $back_link; ?>" class="btn-back">⬅ Înapoi</a>
    </div>
</div>

</body>
</html>
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

    $sql_detalii = "INSERT INTO detalii_persoana (nume_persoana, varsta, bibliografie) VALUES ('$nume', $varsta, '$bio')";
    
    if ($mysqli->query($sql_detalii)) {
        $id_date_nou = $mysqli->insert_id;

        $sql_actor = "INSERT INTO actor (nume_actor, id_date) VALUES ('$nume', $id_date_nou)";
        
        if ($mysqli->query($sql_actor)) {
            $mesaj = "✅ Actorul a fost creat cu succes!";
            $tip_mesaj = "success";
        } else {
            $mesaj = "❌ Eroare la crearea actorului: " . $mysqli->error;
            $tip_mesaj = "error";
        }
    } else {
        $mesaj = "❌ Eroare la detalii: " . $mysqli->error;
        $tip_mesaj = "error";
    }
}
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă Actor Nou</title>
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
            border-bottom: 3px solid #2ecc71;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .form-card h1::before {
            content: '🎭 ';
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
        
        input[type="text"],
        input[type="number"],
        textarea {
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
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid #2ecc71;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.4);
        }
        
        textarea {
            resize: vertical;
            min-height: 140px;
            line-height: 1.6;
        }
        
        input::placeholder,
        textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .btn-submit {
            width: 100%;
            padding: 18px;
            background: linear-gradient(45deg, #27ae60, #2ecc71);
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
            box-shadow: 0 6px 25px rgba(46, 204, 113, 0.4);
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 35px rgba(46, 204, 113, 0.6);
            background: linear-gradient(45deg, #2ecc71, #27ae60);
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
        <h1>Creează Actor Nou</h1>
        
        <?php if($mesaj): ?>
            <div class="msg <?php echo $tip_mesaj; ?>"><?php echo $mesaj; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nume Actor:</label>
            <input type="text" name="nume" required placeholder="ex: Leonardo DiCaprio">
            <p class="form-hint">Introdu numele complet al actorului</p>

            <label>Vârstă:</label>
            <input type="number" name="varsta" required placeholder="ex: 48" min="1" max="120">
            <p class="form-hint">Vârsta actuală a actorului</p>

            <label>Biografie:</label>
            <textarea name="bibliografie" required placeholder="Scrie o scurtă descriere despre cariera și realizările actorului..."></textarea>
            <p class="form-hint">O descriere succintă a carierei actorului</p>

            <button type="submit" name="salveaza" class="btn-submit">💾 Salvează Actor</button>
        </form>

        <a href="actori.php" class="btn-back">⬅ Înapoi la Lista de Actori</a>
    </div>
</div>

</body>
</html>
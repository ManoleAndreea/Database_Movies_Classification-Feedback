<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

// Verificam ID-ul filmului
if (!isset($_GET['id_film']) || !is_numeric($_GET['id_film'])) {
    header("Location: filme.php");
    exit();
}

$id_film = intval($_GET['id_film']);
$mesaj = "";

// --- ADAUGARE RECENZIE (INSERT) ---
if (isset($_POST['adauga_feedback'])) {
    $nume = $mysqli->real_escape_string($_POST['nume_autor']);
    $nota = intval($_POST['nr_stele']);
    $comentariu = $mysqli->real_escape_string($_POST['continut']);
    
    // Validare simpla
    if(!empty($nume) && $nota >= 1 && $nota <= 10) {
        $sql_insert = "INSERT INTO feedback (id_film, nume_autor, nr_stele, continut) 
                       VALUES ($id_film, '$nume', $nota, '$comentariu')";
        if($mysqli->query($sql_insert)) {
            $mesaj = "<div class='alert success'>✅ Recenzia a fost adăugată cu succes!</div>";
        } else {
            $mesaj = "<div class='alert error'>❌ Eroare: " . $mysqli->error . "</div>";
        }
    } else {
        $mesaj = "<div class='alert error'>⚠️ Completează numele și alege o notă validă!</div>";
    }
}

// --- PRELUARE DATE FILM ---
$res_film = $mysqli->query("SELECT * FROM film WHERE id_film = $id_film");
$film = $res_film->fetch_assoc();

// --- PRELUARE RECENZII ---
$sql_reviews = "SELECT * FROM feedback WHERE id_film = $id_film ORDER BY id_feedback DESC";
$res_reviews = $mysqli->query($sql_reviews);

// Calculam media notelor
$sql_avg = "SELECT AVG(nr_stele) as media FROM feedback WHERE id_film = $id_film";
$row_avg = $mysqli->query($sql_avg)->fetch_assoc();
$media = round($row_avg['media'], 1);
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Recenzii - <?php echo htmlspecialchars($film['nume_film']); ?></title>
    <style>
   
        .container {
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .movie-header {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.95), rgba(52, 73, 94, 0.95));
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 12px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
            animation: fadeInDown 0.8s ease-out;
        }
        
        .movie-header h1 {
            color: #fff;
            font-size: 36px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }
        
        .movie-header h1::before {
            content: '🎬 ';
            margin-right: 10px;
        }
        
        .movie-year {
            font-size: 20px;
            color: #bdc3c7;
            font-weight: 400;
        }
        
        .rating-badge {
            background: linear-gradient(45deg, #f1c40f, #f39c12);
            color: #000;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 20px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(241, 196, 15, 0.4);
        }
        
        .review-count {
            margin-left: 20px;
            color: #ecf0f1;
            font-size: 16px;
        }
        
        .reviews-container {
            display: grid;
            grid-template-columns: 1fr 500px;
            gap: 40px;
        }
        
        .reviews-list h3 {
            color: #fff;
            font-size: 24px;
            border-bottom: 3px solid #3498db;
            padding-bottom: 12px;
            margin-top: 0;
            margin-bottom: 25px;
        }
        
        .review-card {
            background: rgba(44, 62, 80, 0.9);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-left: 5px solid #3498db;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
        }
        
        .review-card:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 30px rgba(52, 152, 219, 0.3);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .review-author {
            color: #3498db;
            font-weight: 600;
            font-size: 16px;
        }
        
        .stars {
            color: #f1c40f;
            font-size: 14px;
        }
        
        .review-content {
            color: #ecf0f1;
            line-height: 1.6;
            font-size: 15px;
        }
        
        .add-review-box {
            background: rgba(44, 62, 80, 0.95);
            backdrop-filter: blur(15px);
            padding: 35px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
            position: sticky;
            top: 20px;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .add-review-box h3 {
            margin-top: 0;
            color: #2ecc71;
            font-size: 22px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(46, 204, 113, 0.3);
        }
        
        .add-review-box h3::before {
            content: '💬 ';
            margin-right: 8px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            margin-top: 15px;
            color: #ecf0f1;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 14px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            margin-bottom: 5px;
            box-sizing: border-box;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid #2ecc71;
            box-shadow: 0 0 15px rgba(46, 204, 113, 0.3);
        }
        
        select option {
            background: #2c3e50;
            color: white;
        }
        
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(46, 204, 113, 0.4);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(46, 204, 113, 0.6);
            background: linear-gradient(45deg, #2ecc71, #27ae60);
        }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
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
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #95a5a6;
            background: rgba(44, 62, 80, 0.5);
            border-radius: 12px;
            margin-top: 20px;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        @keyframes fadeInDown {
            from { 
                opacity: 0; 
                transform: translateY(-30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
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
        
        @media (max-width: 1024px) {
            .reviews-container {
                grid-template-columns: 1fr;
            }
            
            .add-review-box {
                position: relative;
                top: 0;
            }
        }
    </style>
</head>
<body>


<div class="container">
    
    <div class="movie-header">
        <h1><?php echo htmlspecialchars($film['nume_film']); ?> <span class="movie-year">(<?php echo $film['an_aparitie']; ?>)</span></h1>
        <div style="margin-top: 20px;">
            <span class="rating-badge">⭐ <?php echo $media > 0 ? $media : '-'; ?> / 10</span>
            <span class="review-count">💬 <?php echo $res_reviews->num_rows; ?> recenzii</span>
        </div>
    </div>

    <div class="reviews-container">
        
        <div class="reviews-list">
            <h3>Ce spun utilizatorii:</h3>
            
            <?php if($res_reviews->num_rows > 0): ?>
                <?php while($rev = $res_reviews->fetch_assoc()): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <span class="review-author">👤 <?php echo htmlspecialchars($rev['nume_autor']); ?></span>
                            <span class="stars">
                                <?php 
                                for($i=1; $i<=10; $i++) {
                                    echo ($i <= $rev['nr_stele']) ? '⭐' : '☆';
                                }
                                echo ' ' . $rev['nr_stele'] . '/10';
                                ?>
                            </span>
                        </div>
                        <p class="review-content">"<?php echo nl2br(htmlspecialchars($rev['continut'])); ?>"</p>
                       <a href="stergere.php?tip=recenzie&id=<?php echo $row['id_feedback']; ?>" 
   onclick="return confirm('Ștergi acest comentariu?');"
   style="display: inline-block; padding: 6px 12px; background: linear-gradient(45deg, #c0392b, #e74c3c); 
          color: white; text-decoration: none; border-radius: 6px; font-size: 12px; 
          font-weight: 600; margin-left: 10px; transition: all 0.3s ease; 
          box-shadow: 0 2px 8px rgba(192, 57, 43, 0.3);"
   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(231, 76, 60, 0.5)';"
   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(192, 57, 43, 0.3)';">
   🗑️ Șterge
</a>
</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div style="font-size: 64px; margin-bottom: 20px;">💬</div>
                    <div style="font-size: 18px; color: #ecf0f1; margin-bottom: 10px;">Nu există recenzii pentru acest film</div>
                    <div style="color: #95a5a6;">Fii primul care scrie o recenzie!</div>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="add-review-box">
                <h3>Adaugă Feedback</h3>
                <?php echo $mesaj; ?>
                
                <form method="POST" action="">
                    <label>Numele tău:</label>
                    <input type="text" name="nume_autor" placeholder="Ex: Popescu Ion" required>
                    
                    <label>Nota (1-10):</label>
                    <select name="nr_stele" required>
                        <option value="">-- Alege Nota --</option>
                        <?php for($i=10; $i>=1; $i--) echo "<option value='$i'>$i ⭐</option>"; ?>
                    </select>
                    
                    <label>Opinia ta:</label>
                    <textarea name="continut" placeholder="Scrie recenzia ta aici..." required></textarea>
                    
                    <button type="submit" name="adauga_feedback" class="btn-submit">📝 Publică Recenzia</button>
                </form>
            </div>
        </div>

    </div>
</div>

</body>
</html>
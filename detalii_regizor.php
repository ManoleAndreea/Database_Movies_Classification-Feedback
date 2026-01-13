<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

// 1. Verificam ID-ul regizorului
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id_regizor = intval($_GET['id']);

// 2. Interogare: Date Regizor + Detalii Personale (Varsta, Bibliografie)
// Atentie: Folosim LEFT JOIN ca sa nu crape daca nu are detalii in tabela secundara
$sql_regizor = "SELECT r.nume_regizor, d.varsta, d.bibliografie 
              FROM regizor r
              LEFT JOIN detalii_persoana d ON r.id_date = d.id_date
              WHERE r.id_regizor = $id_regizor";

$result_regizor = $mysqli->query($sql_regizor);
$regizor = $result_regizor->fetch_assoc();

if (!$regizor) {
    die("Regizorul nu a fost gasit!");
}
?>


<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Regizor: <?php echo htmlspecialchars($regizor['nume_regizor']); ?></title>
    <style>
   
        .container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.95), rgba(52, 73, 94, 0.95));
            backdrop-filter: blur(15px); padding: 50px; border-radius: 15px;
            margin-bottom: 40px; border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 50px rgba(0,0,0,0.7); animation: fadeInDown 0.8s ease-out;
            display: flex; align-items: center; gap: 30px;
        }

        .avatar-placeholder {
            width: 100px; height: 100px; background: #f1c40f; border-radius: 50%; /* Galben pt regizor */
            display: flex; align-items: center; justify-content: center;
            font-size: 40px; color: #2c3e50; box-shadow: 0 5px 15px rgba(241, 196, 15, 0.5);
        }
        
        .hero-section h1 {
            color: #fff; font-size: 42px; margin-bottom: 10px; text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
        }
        
        .age-badge {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            color: white; padding: 8px 16px; border-radius: 20px; font-weight: bold;
            font-size: 16px; display: inline-block;
        }
        
        .bio-box {
            background: rgba(44, 62, 80, 0.9); backdrop-filter: blur(10px);
            padding: 30px; border-radius: 12px; border-left: 5px solid #f39c12;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); margin-bottom: 40px;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .bio-box h3 { color: #f39c12; font-size: 20px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        .bio-text { color: #ecf0f1; font-size: 16px; line-height: 1.8; }
        
        .section-title {
            color: #fff; font-size: 28px; margin-bottom: 25px; padding-left: 20px;
            border-left: 5px solid #3498db; text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }
        
        /* Grila Filme */
        .movies-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px; margin-bottom: 40px;
        }
        
        .movie-card {
            background: rgba(44, 62, 80, 0.9); padding: 25px; border-radius: 12px;
            text-align: center; transition: all 0.3s ease; border: 2px solid transparent;
            box-shadow: 0 5px 20px rgba(0,0,0,0.4); animation: fadeInUp 0.8s ease-out;
        }
        
        .movie-card:hover {
            transform: translateY(-8px); border-color: #3498db;
            background: rgba(52, 152, 219, 0.15);
        }
        
        .movie-card a {
            text-decoration: none; color: #fff; font-weight: 700; font-size: 18px;
            display: block; margin-bottom: 5px;
        }
        
        .movie-year { color: #bdc3c7; font-size: 14px; }
        
        .btn-back {
            display: inline-block; padding: 15px 30px; border-radius: 10px;
            text-decoration: none; font-weight: 700; text-transform: uppercase;
            background: linear-gradient(45deg, #7f8c8d, #95a5a6); color: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3); transition: all 0.3s;
        }
        .btn-back:hover { transform: translateY(-2px); }

        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>



<div class="container">
    <div class="hero-section">
        <div class="avatar-placeholder">🎬</div>
        <div>
            <h1><?php echo htmlspecialchars($regizor['nume_regizor']); ?>
                <a href="editare_actor.php?id=<?php echo $id_actor; ?>" style="font-size:14px; color:#f39c12; text-decoration:none; margin-left:10px;">✏️ Editează</a>
                
        </h1>
            <?php if(!empty($regizor['varsta'])): ?>
                <span class="age-badge">Vârstă: <?php echo $regizor['varsta']; ?> ani</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="bio-box">
        <h3>Biografie / Detalii</h3>
        <p class="bio-text">
            <?php 
            if (!empty($regizor['bibliografie'])) {
                echo nl2br(htmlspecialchars($regizor['bibliografie'])); 
            } else {
                echo "Nu există detalii biografice disponibile momentan.";
            }
            ?>
        </p>
    </div>

    <h2 class="section-title">🎥 Filme Regizate</h2>
    <div class="movies-grid">
        <?php
        // Selectam filmele regizate de acesta (WHERE id_regizor = ...)
        $sql_filme = "SELECT id_film, nume_film, an_aparitie
                      FROM film
                      WHERE id_regizor = $id_regizor
                      ORDER BY an_aparitie DESC";
        
        $res_filme = $mysqli->query($sql_filme);

        if ($res_filme->num_rows > 0) {
            while($film = $res_filme->fetch_assoc()) {
                echo '<div class="movie-card">';
                // Link inapoi catre detalii_film.php
                echo '<a href="detalii.php?id=' . $film['id_film'] . '">' . htmlspecialchars($film['nume_film']) . '</a>';
                echo '<span class="movie-year">(' . $film['an_aparitie'] . ')</span>';
                echo '</div>';
            }
        } else {
            echo '<p style="color:#bdc3c7; padding: 20px;">Nu există filme asociate acestui regizor.</p>';
        }
        ?>
    </div>

    <a href="javascript:history.back()" class="btn-back">⬅ Înapoi</a>
</div>

</body>
</html>
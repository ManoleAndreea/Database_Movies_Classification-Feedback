<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

// 1. Verificam ID-ul actorului
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id_actor = intval($_GET['id']);

// 2. Interogare: Date Actor + Detalii Personale (Varsta, Bibliografie)
// Atentie: Folosim LEFT JOIN ca sa nu crape daca nu are detalii in tabela secundara
$sql_actor = "SELECT a.nume_actor, d.varsta, d.bibliografie 
              FROM actor a
              LEFT JOIN detalii_persoana d ON a.id_date = d.id_date
              WHERE a.id_actor = $id_actor";

$result_actor = $mysqli->query($sql_actor);
$actor = $result_actor->fetch_assoc();

if (!$actor) {
    die("Actorul nu a fost gasit!");
}
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Actor: <?php echo htmlspecialchars($actor['nume_actor']); ?></title>
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
            width: 100px; height: 100px; background: #3498db; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 40px; color: white; box-shadow: 0 5px 15px rgba(52, 152, 219, 0.5);
        }
        
        .hero-section h1 {
            color: #fff; font-size: 42px; margin-bottom: 10px; text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
        }
        
        .age-badge {
            background: linear-gradient(45deg, #8e44ad, #9b59b6);
            color: white; padding: 8px 16px; border-radius: 20px; font-weight: bold;
            font-size: 16px; display: inline-block;
        }
        
        .bio-box {
            background: rgba(44, 62, 80, 0.9); backdrop-filter: blur(10px);
            padding: 30px; border-radius: 12px; border-left: 5px solid #9b59b6;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); margin-bottom: 40px;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .bio-box h3 { color: #9b59b6; font-size: 20px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        .bio-text { color: #ecf0f1; font-size: 16px; line-height: 1.8; }
        
        .section-title {
            color: #fff; font-size: 28px; margin-bottom: 25px; padding-left: 20px;
            border-left: 5px solid #e67e22; text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }
        
        /* Grila Filme - Similar cu Actor Grid */
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
            transform: translateY(-8px); border-color: #e67e22;
            background: rgba(230, 126, 34, 0.15);
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
        <div class="avatar-placeholder">🎭</div>
        <div>
            <h1><?php echo htmlspecialchars($actor['nume_actor']); ?>
            <a href="editare_actor.php?id=<?php echo $id_actor; ?>" style="font-size:14px; color:#f39c12; text-decoration:none; margin-left:10px;">✏️ Editează</a>
        
        </h1>
            <?php if(!empty($actor['varsta'])): ?>
                <span class="age-badge">Vârstă: <?php echo $actor['varsta']; ?> ani</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="bio-box">
        <h3>Biografie / Detalii</h3>
        <p class="bio-text">
            <?php 
            if (!empty($actor['bibliografie'])) {
                echo nl2br(htmlspecialchars($actor['bibliografie'])); 
            } else {
                echo "Nu există detalii biografice disponibile momentan.";
            }
            ?>
        </p>
    </div>

    <h2 class="section-title">🎬 Filmografie</h2>
    <div class="movies-grid">
        <?php
        // Selectam filmele legate de acest actor prin tabela DISTRIBUTIE
        $sql_filme = "SELECT f.id_film, f.nume_film, f.an_aparitie
                      FROM film f
                      JOIN distributie d ON f.id_film = d.id_film
                      WHERE d.id_actor = $id_actor
                      ORDER BY f.an_aparitie DESC"; // Le ordonam descrescator dupa an
        
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
            echo '<p style="color:#bdc3c7; padding: 20px;">Nu există filme asociate acestui actor.</p>';
        }
        ?>
    </div>

    <a href="javascript:history.back()" class="btn-back">⬅ Înapoi</a>
</div>

</body>
</html>
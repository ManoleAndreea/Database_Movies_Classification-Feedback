<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

// 1. Verificam ID-ul
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: filme.php");
    exit();
}

$id_film = intval($_GET['id']);

// 2. Interogare Principala: Film + Regizor + Detalii Regizor
// Selectam si ID-ul regizorului pentru link
$sql_film = "SELECT f.*, r.nume_regizor, r.id_regizor, d.bibliografie as bio_regizor 
             FROM film f
             LEFT JOIN regizor r ON f.id_regizor = r.id_regizor
             LEFT JOIN detalii_persoana d ON r.id_date = d.id_date
             WHERE f.id_film = $id_film";

$result_film = $mysqli->query($sql_film);
$film = $result_film->fetch_assoc();

if (!$film) {
    die("Filmul nu a fost gasit!");
}
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Detalii: <?php echo htmlspecialchars($film['nume_film']); ?></title>
    <style>
   
        
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        .film-hero {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.95), rgba(52, 73, 94, 0.95));
            backdrop-filter: blur(15px); padding: 50px; border-radius: 15px;
            margin-bottom: 40px; border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 50px rgba(0,0,0,0.7); animation: fadeInDown 0.8s ease-out;
        }
        .film-hero h1 { color: #fff; font-size: 48px; margin-bottom: 15px; text-shadow: 3px 3px 10px rgba(0,0,0,0.7); line-height: 1.2; }
        .year-badge {
            background: linear-gradient(45deg, #f1c40f, #f39c12); color: #000;
            padding: 10px 20px; border-radius: 25px; font-weight: bold; font-size: 20px;
            display: inline-block; box-shadow: 0 5px 20px rgba(241, 196, 15, 0.5);
        }

        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-top: 30px; }
        .info-card {
            background: rgba(44, 62, 80, 0.9); backdrop-filter: blur(10px);
            padding: 25px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4); transition: all 0.3s ease;
        }
        .info-card:hover { transform: translateY(-5px); box-shadow: 0 12px 35px rgba(52, 152, 219, 0.3); }
        .info-label { color: #3498db; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1.5px; margin-bottom: 10px; }
        .info-value { color: #fff; font-size: 20px; font-weight: 600; }

        .genre-badge {
            display: inline-block; background: linear-gradient(45deg, #2980b9, #3498db);
            color: white; padding: 8px 18px; margin-right: 10px; margin-bottom: 10px;
            border-radius: 20px; font-size: 14px; font-weight: 600; box-shadow: 0 3px 10px rgba(52, 152, 219, 0.4);
        }

        .description-box {
            background: rgba(44, 62, 80, 0.9); backdrop-filter: blur(10px);
            padding: 30px; border-radius: 12px; border-left: 5px solid #3498db;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); margin-bottom: 40px;
            margin-top: 40px; animation: fadeInUp 0.8s ease-out;
        }
        .description-box h3 { color: #3498db; font-size: 20px; margin-bottom: 15px; }
        .description-text { color: #ecf0f1; font-size: 16px; line-height: 1.8; font-style: italic; }

        .section-title {
            color: #fff; font-size: 28px; margin-top: 50px; margin-bottom: 25px;
            padding-left: 20px; border-left: 5px solid #e67e22; text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        }

        /* --- STILURI NOI PENTRU ACTORI CLICKABILI --- */
        .actor-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px; margin-bottom: 40px;
        }

        /* Schimbam din div in a, deci trebuie sa arate a bloc */
        .actor-card {
            display: block; /* Important pt a */
            background: rgba(44, 62, 80, 0.9); backdrop-filter: blur(10px);
            padding: 25px; border-radius: 12px; text-align: center;
            transition: all 0.3s ease; border: 2px solid transparent;
            box-shadow: 0 5px 20px rgba(0,0,0,0.4); text-decoration: none; /* Fara subliniere */
            color: white; /* Text alb */
        }

        .actor-card:hover {
            transform: translateY(-8px) scale(1.03); border-color: #3498db;
            box-shadow: 0 10px 35px rgba(52, 152, 219, 0.5);
            background: rgba(52, 152, 219, 0.15);
        }
        
        .actor-icon { font-size: 48px; margin-bottom: 15px; }
        .actor-name { font-weight: 700; font-size: 18px; display: block; }

        /* Cardul de ADAUGARE ACTOR */
        .add-actor-card {
            border: 2px dashed #2ecc71; background: rgba(46, 204, 113, 0.1);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .add-actor-card:hover {
            background: rgba(46, 204, 113, 0.2); border-color: #27ae60;
            transform: translateY(-5px);
        }
        .add-icon { font-size: 50px; color: #2ecc71; margin-bottom: 10px; }
        .add-text { color: #2ecc71; font-weight: bold; text-transform: uppercase; }

        /* Linkuri mici de administrare (Regizor) */
        .admin-link {
            font-size: 14px; color: #f1c40f; text-decoration: none;
            margin-left: 10px; border: 1px solid #f1c40f; padding: 2px 8px; border-radius: 5px;
            transition: all 0.3s;
        }
        .admin-link:hover { background: #f1c40f; color: #000; }

        .regizor-link { color: white; text-decoration: none; border-bottom: 1px dotted #ccc; transition: color 0.3s; }
        .regizor-link:hover { color: #3498db; border-color: #3498db; }

        /* Butoane Actiuni */
        .action-buttons { display: flex; gap: 15px; margin-top: 40px; flex-wrap: wrap; }
        .btn-action {
            display: inline-block; padding: 15px 30px; border-radius: 10px;
            text-decoration: none; font-weight: 700; font-size: 14px;
            text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }
        .btn-back { background: linear-gradient(45deg, #7f8c8d, #95a5a6); color: white; }
        .btn-reviews { background: linear-gradient(45deg, #2980b9, #3498db); color: white; }
        .btn-edit { background: linear-gradient(45deg, #f39c12, #f1c40f); color: white; }
        .btn-action:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.5); }
        
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>



<div class="container">
    <div class="film-hero">
        <h1><?php echo htmlspecialchars($film['nume_film']); ?></h1>
        <span class="year-badge">📅 <?php echo $film['an_aparitie']; ?></span>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">⏱️ Durata</div>
            <div class="info-value"><?php echo $film['durata']; ?> minute</div>
        </div>
        
        <div class="info-card">
            <div class="info-label">🎬 Regizor</div>
            <div class="info-value">
                <?php if($film['nume_regizor']): ?>
                    <a href="detalii_regizor.php?id=<?php echo $film['id_regizor']; ?>" class="regizor-link">
                        <?php echo htmlspecialchars($film['nume_regizor']); ?>
                    </a>
                    <a href="editare.php?id=<?php echo $id_film; ?>" class="admin-link" title="Schimbă Regizorul">✏️</a>
                <?php else: ?>
                    <span style="color: #95a5a6;">Necunoscut</span>
                    <a href="editare.php?id=<?php echo $id_film; ?>" class="admin-link">➕ Adaugă</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-label">🎭 Genuri</div>
            <div style="margin-top: 10px;">
                <?php
                $sql_gen = "SELECT g.nume_gen FROM gen g 
                            JOIN film_gen fg ON g.id_gen = fg.id_gen 
                            WHERE fg.id_film = $id_film";
                $res_gen = $mysqli->query($sql_gen);
                if ($res_gen->num_rows > 0) {
                    while($g = $res_gen->fetch_assoc()) {
                        echo '<span class="genre-badge">' . htmlspecialchars($g['nume_gen']) . '</span>';
                    }
                } else {
                    echo '<span style="color:#95a5a6;">Nespecificat</span>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="description-box">
        <h3>Sinopsis</h3>
        <p class="description-text">
            <?php 
            if (!empty($film['descriere'])) {
                echo nl2br(htmlspecialchars($film['descriere'])); 
            } else {
                echo "Nu există o descriere disponibilă pentru acest film.";
            }
            ?>
        </p>
    </div>

    <h2 class="section-title">🎭 Distribuție</h2>
    <div class="actor-grid">
        <?php
        $sql_actori = "SELECT a.id_actor, a.nume_actor
                       FROM actor a
                       JOIN distributie d ON a.id_actor = d.id_actor
                       WHERE d.id_film = $id_film";
        
        $res_actori = $mysqli->query($sql_actori);

        if ($res_actori->num_rows > 0) {
            while($actor = $res_actori->fetch_assoc()) {
                // Aici e modificarea: Tot cardul este un link <a>
                echo '<a href="detalii_actor.php?id=' . $actor['id_actor'] . '" class="actor-card">';
                echo '<div class="actor-icon">🎭</div>';
                echo '<span class="actor-name">' . htmlspecialchars($actor['nume_actor']) . '</span>';
                echo '</a>';
            }
        }
        ?>
        
        <a href="adauga_distributie.php?id_film=<?php echo $id_film; ?>" class="actor-card add-actor-card">
            <div class="add-icon">➕</div>
            <span class="add-text">Adaugă Actor</span>
        </a>
    </div>

    <div class="action-buttons">
        <a href="filme.php" class="btn-action btn-back">⬅ Înapoi la Liste</a>
        <a href="recenzii.php?id_film=<?php echo $id_film; ?>" class="btn-action btn-reviews">💬 Vezi Recenzii</a>
        <a href="editare.php?id=<?php echo $id_film; ?>" class="btn-action btn-edit">✏️ Editează Film</a>
    </div>
</div>

</body>
</html>
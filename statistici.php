<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
include 'header.php'; 
?>

<style>
    .stats-hero {
        background: linear-gradient(135deg, rgba(44, 62, 80, 0.95), rgba(52, 73, 94, 0.95));
        backdrop-filter: blur(15px);
        padding: 40px;
        border-radius: 15px;
        margin-bottom: 40px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 15px 50px rgba(0,0,0,0.7);
        text-align: center;
        animation: fadeInDown 0.8s ease-out;
    }
    
    .stats-hero h1 {
        color: #fff;
        font-size: 42px;
        margin-bottom: 10px;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
    }
    
    .stats-hero p {
        color: #bdc3c7;
        font-size: 16px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }
    
    .stat-card {
        background: rgba(44, 62, 80, 0.9);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        transition: all 0.3s ease;
        animation: fadeInUp 0.8s ease-out;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(52, 152, 219, 0.3);
    }
    
    .stat-card h3 {
        font-size: 20px;
        padding-bottom: 12px;
        margin-bottom: 15px;
        border-bottom: 3px solid;
    }
    
    .stat-card.blue h3 { color: #3498db; border-color: #3498db; }
    .stat-card.yellow h3 { color: #f1c40f; border-color: #f1c40f; }
    .stat-card.green h3 { color: #2ecc71; border-color: #2ecc71; }
    .stat-card.purple h3 { color: #9b59b6; border-color: #9b59b6; }
    
    .stat-description {
        font-size: 13px;
        color: #95a5a6;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    
    .search-form {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .search-form input {
        flex: 1;
        padding: 12px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .search-form input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid #3498db;
        box-shadow: 0 0 15px rgba(52, 152, 219, 0.3);
    }
    
    .search-form input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .search-btn {
        padding: 12px 20px;
        background: linear-gradient(45deg, #2980b9, #3498db);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
    }
    
    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.5);
    }
    
    .stat-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .stat-card li {
        padding: 12px;
        margin-bottom: 8px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        border-left: 3px solid #3498db;
        color: #ecf0f1;
        transition: all 0.3s ease;
    }
    
    .stat-card li:hover {
        background: rgba(52, 152, 219, 0.1);
        transform: translateX(5px);
    }
    
    .stat-card li strong {
        color: #fff;
        font-weight: 600;
    }
    
    .result-header {
        font-size: 13px;
        color: #2ecc71;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .big-stat {
        text-align: center;
        font-size: 28px;
        font-weight: bold;
        margin-top: 20px;
        color: #fff;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
    }
    
    .big-stat-sub {
        font-size: 14px;
        color: #bdc3c7;
        margin-top: 8px;
        font-weight: normal;
    }
    
    .back-section {
        text-align: center;
        margin-top: 50px;
        margin-bottom: 30px;
    }
    
    .btn-back {
        display: inline-block;
        padding: 15px 30px;
        background: linear-gradient(45deg, #7f8c8d, #95a5a6);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }
    
    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(127, 140, 141, 0.5);
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
</style>

<div class="container">
    <div class="stats-hero">
        <h1>📊 Statistici Cinema BD</h1>
        <p>Analiză complexă a datelor folosind subcereri SQL</p>
    </div>

    <div class="stats-grid">

        <!-- Card 1: Comparație Durată -->
        <div class="stat-card blue">
            <h3>⏳ Comparație Durată</h3>
            <p class="stat-description">
                Găsește filme care durează mai mult decât un film ales de tine.
            </p>
            
            <form method="GET" class="search-form">
                <input type="text" name="film_referinta" placeholder="Ex: Alien" required>
                <button type="submit" class="search-btn">🔍</button>
            </form>

            <ul>
                <?php
                if (isset($_GET['film_referinta']) && !empty($_GET['film_referinta'])) {
                    $film_ref = $_GET['film_referinta'];
                    
                    $sql1 = "SELECT nume_film, durata 
                             FROM film 
                             WHERE durata > (SELECT durata FROM film WHERE nume_film = ? LIMIT 1)
                             ORDER BY durata DESC";

                    if ($stmt = $mysqli->prepare($sql1)) {
                        $stmt->bind_param("s", $film_ref);
                        $stmt->execute();
                        $res1 = $stmt->get_result();

                        if ($res1->num_rows > 0) {
                            echo "<p class='result-header'>✅ Mai lungi decât '$film_ref':</p>";
                            while($row = $res1->fetch_assoc()) {
                                echo "<li><strong>" . htmlspecialchars($row['nume_film']) . "</strong> - " . $row['durata'] . " min</li>";
                            }
                        } else {
                            echo "<li>❌ Niciun film mai lung sau filmul de referință nu există.</li>";
                        }
                        $stmt->close();
                    }
                } else {
                    echo "<li>👉 Introduceți un nume de film pentru comparație.</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Card 2: Filme de Top -->
        <div class="stat-card yellow">
            <h3>⭐ Filme de Top</h3>
            <p class="stat-description">Filme cu nota medie peste media tuturor recenziilor.</p>
            <ul>
                <?php
                $sql2 = "SELECT f.nume_film, AVG(fb.nr_stele) as nota_medie
                         FROM film f
                         JOIN feedback fb ON f.id_film = fb.id_film
                         GROUP BY f.id_film
                         HAVING nota_medie > (SELECT AVG(nr_stele) FROM feedback)
                         ORDER BY nota_medie DESC";

                $res2 = $mysqli->query($sql2);
                if ($res2 && $res2->num_rows > 0) {
                    while($row = $res2->fetch_assoc()) {
                        echo "<li><strong>" . htmlspecialchars($row['nume_film']) . "</strong> - Nota: " . round($row['nota_medie'], 1) . " ⭐</li>";
                    }
                } else {
                    echo "<li>Nu există suficiente recenzii.</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Card 3: Cel mai harnic Regizor -->
        <div class="stat-card green">
            <h3>🎬 Cel mai harnic Regizor</h3>
            <p class="stat-description">Regizorul cu cele mai multe filme în baza noastră.</p>
            
            <div class="big-stat">
                <?php
                $sql3 = "SELECT nume_regizor 
                         FROM regizor 
                         WHERE id_regizor = (
                             SELECT id_regizor 
                             FROM film 
                             GROUP BY id_regizor 
                             ORDER BY COUNT(*) DESC 
                             LIMIT 1
                         )";
                
                $res3 = $mysqli->query($sql3);
                if ($res3 && $res3->num_rows > 0) {
                    $regizor_top = $res3->fetch_assoc();
                    echo "🏆 " . htmlspecialchars($regizor_top['nume_regizor']);
                } else {
                    echo "Indisponibil";
                }
                ?>
            </div>
        </div>

        <!-- Card 4: Actorul Omniprezent -->
        <div class="stat-card purple">
            <h3>🎭 Actorul Omniprezent</h3>
            <p class="stat-description">Actorul care a jucat în cele mai multe filme.</p>
            
            <div class="big-stat">
                <?php
                $sql4 = "SELECT a.nume_actor, 
                                (SELECT COUNT(*) FROM distributie d WHERE d.id_actor = a.id_actor) as nr_roluri
                         FROM actor a
                         ORDER BY nr_roluri DESC
                         LIMIT 1";
                
                $res4 = $mysqli->query($sql4);
                if ($res4 && $res4->num_rows > 0) {
                    $actor_top = $res4->fetch_assoc();
                    echo "🌟 " . htmlspecialchars($actor_top['nume_actor']);
                    echo "<div class='big-stat-sub'>(" . $actor_top['nr_roluri'] . " filme)</div>";
                } else {
                    echo "Indisponibil";
                }
                ?>
            </div>
        </div>

    </div>
    
    <div class="back-section">
        <a href="dashboard.php" class="btn-back">⬅ Înapoi la Dashboard</a>
    </div>
</div>

</body>
</html>
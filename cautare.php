<?php
session_start();
if (!isset($_SESSION['loggedin'])) { header("Location: login.php"); exit(); }
require_once 'config.php';
include 'header.php';

// Preluam parametrul de cautare (acesta este "?")
$term = isset($_GET['q']) ? $mysqli->real_escape_string($_GET['q']) : '';
?>

<div class="container">
    <div class="form-card">
        <h1>Rezultate pentru: "<?php echo htmlspecialchars($term); ?>"</h1>
        
        <?php if($term): ?>
            
            <h2 style="text-align: left; border-bottom: 1px solid #555;">🎬 Filme găsite</h2>
            <div class="dashboard-grid" style="margin-bottom: 40px;">
                <?php
                // Interogare cu parametru (LIKE)
                $sql_filme = "SELECT * FROM film WHERE nume_film LIKE '%$term%'";
                $res_filme = $mysqli->query($sql_filme);

                if($res_filme->num_rows > 0) {
                    while($row = $res_filme->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '<h3><a href="detalii.php?id='.$row['id_film'].'" style="color:#3498db; text-decoration:none;">'.$row['nume_film'].'</a></h3>';
                        echo '<p>An: '.$row['an_aparitie'].'</p>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Niciun film găsit.</p>";
                }
                ?>
            </div>

            <h2 style="text-align: left; border-bottom: 1px solid #555;">🎭 Actori găsiți</h2>
            <div class="dashboard-grid">
                <?php
                $sql_actori = "SELECT * FROM actor WHERE nume_actor LIKE '%$term%'";
                $res_actori = $mysqli->query($sql_actori);

                if($res_actori->num_rows > 0) {
                    while($row = $res_actori->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '<h3><a href="detalii_actor.php?id='.$row['id_actor'].'" style="color:#e67e22; text-decoration:none;">'.$row['nume_actor'].'</a></h3>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Niciun actor găsit.</p>";
                }
                ?>
            </div>

        <?php else: ?>
            <p>Te rog introdu un termen de căutare.</p>
        <?php endif; ?>
        
        <a href="dashboard.php" class="btn-back">⬅ Înapoi la Dashboard</a>
    </div>
</div>
</body>
</html>
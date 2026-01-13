<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
include 'header.php';

// Preluam ID-ul genului selectat din URL (daca exista)
$id_gen_selectat = isset($_GET['id_gen']) ? intval($_GET['id_gen']) : 0;
$nume_gen_curent = "Toate Filmele";
?>

<div class="container">
    <div class="form-card">
        <h1>📂 Clasificare după Gen</h1>
        <p style="text-align: center; color: #bdc3c7; margin-bottom: 20px;">
            Selectează o categorie pentru a filtra filmele din bază.
        </p>

        <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-bottom: 30px;">
            <a href="clasificare.php" 
               class="btn-submit" 
               style="width: auto; margin: 0; background: <?php echo ($id_gen_selectat == 0) ? '#e67e22' : '#34495e'; ?>;">
               Toate
            </a>

            <?php
            // Luam toate genurile din baza de date pentru butoane
            $res_genuri = $mysqli->query("SELECT * FROM gen ORDER BY nume_gen ASC");
            while($g = $res_genuri->fetch_assoc()) {
                // Verificam daca acest gen este cel selectat ca sa il coloram diferit
                $activ = ($id_gen_selectat == $g['id_gen']);
                $culoare = $activ ? '#e67e22' : '#34495e'; // Portocaliu daca e activ, gri daca nu
                
                if($activ) { $nume_gen_curent = $g['nume_gen']; }

                echo '<a href="clasificare.php?id_gen='.$g['id_gen'].'" 
                         class="btn-submit" 
                         style="width: auto; margin: 0; background: '.$culoare.';">
                         '.htmlspecialchars($g['nume_gen']).'
                      </a>';
            }
            ?>
        </div>

        <h2 style="border-bottom: 1px solid #555; padding-bottom: 10px;">
            Rezultate: <span style="color: #f1c40f;"><?php echo htmlspecialchars($nume_gen_curent); ?></span>
        </h2>

        <div class="dashboard-grid">
            <?php
            // CONSTRUIREA INTEROGARII
            if ($id_gen_selectat > 0) {
                // Daca avem un gen selectat, facem JOIN cu tabela de legatura film_gen
                $sql = "SELECT f.id_film, f.nume_film, f.an_aparitie, r.nume_regizor 
                        FROM film f
                        LEFT JOIN regizor r ON f.id_regizor = r.id_regizor
                        JOIN film_gen fg ON f.id_film = fg.id_film
                        WHERE fg.id_gen = $id_gen_selectat
                        ORDER BY f.nume_film ASC";
            } else {
                // Daca nu e selectat nimic, aratam tot
                $sql = "SELECT f.id_film, f.nume_film, f.an_aparitie, r.nume_regizor 
                        FROM film f
                        LEFT JOIN regizor r ON f.id_regizor = r.id_regizor
                        ORDER BY f.nume_film ASC";
            }

            $result = $mysqli->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="card" style="text-align: center; padding: 20px;">
                        <div style="font-size: 30px; margin-bottom: 10px;">🎬</div>
                        <h3>
                            <a href="detalii.php?id=<?php echo $row['id_film']; ?>" style="text-decoration: none; color: #3498db;">
                                <?php echo htmlspecialchars($row['nume_film']); ?>
                            </a>
                        </h3>
                        <p style="color: #bdc3c7; font-size: 14px;">
                            <?php echo $row['an_aparitie']; ?> • <?php echo htmlspecialchars($row['nume_regizor']); ?>
                        </p>
                        <a href="detalii.php?id=<?php echo $row['id_film']; ?>" class="action-btn" style="margin-top: 15px; padding: 8px;">Vezi Detalii</a>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='grid-column: 1/-1; text-align: center; padding: 20px; color: #e74c3c;'>
                        Nu există filme în categoria '$nume_gen_curent'.
                      </p>";
            }
            ?>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="dashboard.php" class="btn-back">⬅ Înapoi la Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
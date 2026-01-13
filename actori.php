<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
include 'header.php';

// Variabila pentru cautare
$search_term = "";
?>

<div class="container">
    <div class="form-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <h1>🎭 Lista Actori</h1>
            
            <a href="adauga_actor.php" class="btn-submit" style="width: auto; padding: 10px 20px; text-decoration: none; margin-top:0;">
                ➕ Adaugă Actor
            </a>
        </div>

        <div style="margin: 20px 0; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 8px;">
            <form method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="cauta" placeholder="Caută un actor după nume..." 
                       value="<?php echo isset($_GET['cauta']) ? htmlspecialchars($_GET['cauta']) : ''; ?>"
                       style="margin: 0; flex-grow: 1;">
                <button type="submit" class="btn-submit" style="margin: 0; width: auto;">🔍 Caută</button>
                <?php if(isset($_GET['cauta'])): ?>
                    <a href="actori.php" class="btn-back" style="margin: 0; padding: 12px; display:inline-block;">❌ Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="dashboard-grid">
            <?php
            // --- LOGICA PENTRU INTEROGAREA CU '?' ---
            if (isset($_GET['cauta']) && !empty($_GET['cauta'])) {
                $search_term = "%" . $_GET['cauta'] . "%";
                
                // CERINTA: Interogare simpla (SELECT + JOIN) cu parametru ?
                $sql = "SELECT a.id_actor, a.nume_actor, d.varsta 
                        FROM actor a
                        LEFT JOIN detalii_persoana d ON a.id_date = d.id_date
                        WHERE a.nume_actor LIKE ?
                        ORDER BY a.nume_actor ASC";
                
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("s", $search_term); // "s" inseamna string
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                // Interogarea standard (fara filtru)
                $sql = "SELECT a.id_actor, a.nume_actor, d.varsta 
                        FROM actor a
                        LEFT JOIN detalii_persoana d ON a.id_date = d.id_date
                        ORDER BY a.nume_actor ASC";
                $result = $mysqli->query($sql);
            }

            // --- AFISAREA REZULTATELOR ---
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="card" style="text-align: center;">
                        <div style="font-size: 40px; margin-bottom: 10px;">🎭</div>
                        
                        <h2>
                            <a href="detalii_actor.php?id=<?php echo $row['id_actor']; ?>" style="text-decoration: none; color: #e67e22;">
                                <?php echo htmlspecialchars($row['nume_actor']); ?>
                            </a>
                        </h2>
                        
                        <div class="stat-item" style="justify-content: center; gap: 10px;">
                            <span class="stat-label">Vârstă:</span>
                            <span class="stat-value" style="font-size: 18px;">
                                <?php echo $row['varsta'] ? $row['varsta'] . " ani" : "Nespecificat"; ?>
                            </span>
                        </div>

                       <div class="quick-actions" style="margin-top: 20px;">
    <a href="detalii_actor.php?id=<?php echo $row['id_actor']; ?>" class="action-btn">👁️ Profil</a>
    <a href="editare_actor.php?id=<?php echo $row['id_actor']; ?>" class="action-btn" style="background: linear-gradient(45deg, #f39c12, #f1c40f);">✏️ Edit</a>
    
    <a href="stergere.php?tip=actor&id=<?php echo $row['id_actor']; ?>" 
       class="action-btn" 
       style="background: linear-gradient(45deg, #e74c3c, #c0392b);"
       onclick="return confirm('⚠️ Ești sigur că vrei să ștergi acest actor?');">
       🗑️ Șterge
    </a>
</div>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='grid-column: 1/-1; text-align: center; padding: 20px;'>Nu am găsit niciun actor.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
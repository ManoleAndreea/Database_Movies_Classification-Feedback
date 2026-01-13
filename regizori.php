<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
include 'header.php';

// Variable for search
$search_term = "";
?>

<div class="container">
    <div class="form-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <h1>🎬 Lista Regizori</h1>
            
            <a href="adauga_regizor.php" class="btn-submit" style="width: auto; padding: 10px 20px; text-decoration: none; margin-top:0;">
                ➕ Adaugă Regizor
            </a>
        </div>

        <div style="margin: 20px 0; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 8px;">
            <form method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="cauta" placeholder="Caută un regizor după nume..." 
                       value="<?php echo isset($_GET['cauta']) ? htmlspecialchars($_GET['cauta']) : ''; ?>"
                       style="margin: 0; flex-grow: 1;">
                <button type="submit" class="btn-submit" style="margin: 0; width: auto;">🔍 Caută</button>
                <?php if(isset($_GET['cauta'])): ?>
                    <a href="regizori.php" class="btn-back" style="margin: 0; padding: 12px; display:inline-block;">❌ Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="dashboard-grid">
            <?php
            // --- LOGIC FOR QUERY WITH '?' ---
            if (isset($_GET['cauta']) && !empty($_GET['cauta'])) {
                $search_term = "%" . $_GET['cauta'] . "%";
                
                $sql = "SELECT r.id_regizor, r.nume_regizor, d.varsta 
                        FROM regizor r
                        LEFT JOIN detalii_persoana d ON r.id_date = d.id_date
                        WHERE r.nume_regizor LIKE ?
                        ORDER BY r.nume_regizor ASC";
                
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("s", $search_term);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $sql = "SELECT r.id_regizor, r.nume_regizor, d.varsta 
                        FROM regizor r
                        LEFT JOIN detalii_persoana d ON r.id_date = d.id_date
                        ORDER BY r.nume_regizor ASC";
                $result = $mysqli->query($sql);
            }

            // --- DISPLAY RESULTS ---
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="card" style="text-align: center;">
                        <div style="font-size: 40px; margin-bottom: 10px;">🎬</div>
                        
                        <h2>
                            <a href="detalii_regizor.php?id=<?php echo $row['id_regizor']; ?>" style="text-decoration: none; color: #3498db;">
                                <?php echo htmlspecialchars($row['nume_regizor']); ?>
                            </a>
                        </h2>
                        
                        <div class="stat-item" style="justify-content: center; gap: 10px;">
                            <span class="stat-label">Vârstă:</span>
                            <span class="stat-value" style="font-size: 18px;">
                                <?php echo $row['varsta'] ? $row['varsta'] . " ani" : "Nespecificat"; ?>
                            </span>
                        </div>

                        <div class="quick-actions" style="margin-top: 20px;">
                            <a href="detalii_regizor.php?id=<?php echo $row['id_regizor']; ?>" class="action-btn">👁️ Profil</a>
                            <a href="editare_regizor.php?id=<?php echo $row['id_regizor']; ?>" class="action-btn" style="background: linear-gradient(45deg, #f39c12, #f1c40f);">✏️ Edit</a>
                            
                            <a href="stergere.php?tip=regizor&id=<?php echo $row['id_regizor']; ?>" 
                               class="action-btn" 
                               style="background: linear-gradient(45deg, #c0392b, #e74c3c);"
                               onclick="return confirm('⚠️ Ești sigur că vrei să ștergi acest regizor?');">
                               🗑️ Șterge
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='grid-column: 1/-1; text-align: center; padding: 20px;'>Nu am găsit niciun regizor.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
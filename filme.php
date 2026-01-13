<?php
session_start();

// 1. Verificare securitate
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// 2. Conectare la baza de date
require_once 'config.php';

// Includem Header-ul (care are si CSS-ul)
include 'header.php';

// Variabila pentru cautare
$search_term = "";
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Lista Filme - Cinema BD</title>
    <style>
        .container {
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease-out;
        }
        
        .page-header h1 {
            color: #fff;
            font-size: 36px;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        .add-btn {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4);
        }
        
        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.6);
        }
        
        .search-container {
            background: rgba(44, 62, 80, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            animation: fadeInUp 0.8s ease-out;
        }
        
        .search-container h3 {
            color: #fff;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .search-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-form input[type="text"] {
            flex: 1;
            padding: 14px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            margin: 0;
        }
        
        .search-form input[type="text"]:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid #3498db;
            box-shadow: 0 0 20px rgba(52, 152, 219, 0.4);
        }
        
        .search-form input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .btn-search {
            padding: 14px 30px;
            background: linear-gradient(45deg, #2980b9, #3498db);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.4);
            white-space: nowrap;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(52, 152, 219, 0.6);
        }
        
        .btn-reset {
            padding: 14px 20px;
            background: linear-gradient(45deg, #7f8c8d, #95a5a6);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(127, 140, 141, 0.4);
            white-space: nowrap;
            display: inline-block;
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(127, 140, 141, 0.6);
        }
        
        .table-container {
            background: rgba(44, 62, 80, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow-x: auto;
            animation: fadeInUp 0.8s ease-out;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            color: #ecf0f1;
        }
        
        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        th {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.3), rgba(41, 128, 185, 0.3));
            color: #3498db;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            position: sticky;
            top: 0;
        }
        
        tbody tr {
            transition: all 0.3s ease;
        }
        
        tbody tr:hover {
            background: rgba(52, 152, 219, 0.15);
            transform: scale(1.01);
        }
        
        td strong {
            color: #fff;
            font-size: 15px;
        }
        
        .badge {
            background: linear-gradient(45deg, #2980b9, #3498db);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 11px;
            margin-right: 5px;
            display: inline-block;
            margin-bottom: 3px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
        }
        
        .btn-action {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            margin-right: 5px;
            transition: all 0.3s ease;
            display: inline-block;
            font-weight: 600;
        }
        
        .btn-details {
            background: linear-gradient(45deg, #9b59b6, #8e44ad);
            box-shadow: 0 3px 10px rgba(155, 89, 182, 0.3);
        }
        
        .btn-reviews {
            background: linear-gradient(45deg, #3498db, #2980b9);
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
        }
        
        .btn-edit { 
            background: linear-gradient(45deg, #f39c12, #f1c40f);
            box-shadow: 0 3px 10px rgba(243, 156, 18, 0.3);
        }
        
        .btn-delete { 
            background: linear-gradient(45deg, #c0392b, #e74c3c);
            box-shadow: 0 3px 10px rgba(192, 57, 43, 0.3);
        }
        
        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(155, 89, 182, 0.5);
        }
        
        .btn-reviews:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.5);
        }
        
        .btn-edit:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.5);
        }
        
        .btn-delete:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.5);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #bdc3c7;
        }
        
        .film-id {
            color: #3498db;
            font-weight: 600;
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
</head>
<body>

<div class="container">
   

    <!-- BARA DE CĂUTARE -->
    <div class="search-container">
        <h3>🔍 Caută în lista de filme</h3>
        <form method="GET" class="search-form">
            <input type="text" name="cauta" placeholder="Caută după titlu, regizor sau an..." 
                   value="<?php echo isset($_GET['cauta']) ? htmlspecialchars($_GET['cauta']) : ''; ?>">
            <button type="submit" class="btn-search">🔎 Caută</button>
            <?php if(isset($_GET['cauta']) && !empty($_GET['cauta'])): ?>
                <a href="filme.php" class="btn-reset">✖ Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="25%">Titlu Film</th>
                    <th width="10%">An</th>
                    <th width="10%">Durata</th>
                    <th width="20%">Regizor</th>
                    <th width="20%">Genuri</th>
                    <th width="10%">Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // --- LOGICA PENTRU INTEROGAREA CU FILTRARE ---
                if (isset($_GET['cauta']) && !empty($_GET['cauta'])) {
                    $search_term = "%" . $_GET['cauta'] . "%";
                    
                    $sql = "SELECT f.id_film, f.nume_film, f.an_aparitie, f.durata, r.nume_regizor 
                            FROM film f 
                            LEFT JOIN regizor r ON f.id_regizor = r.id_regizor 
                            WHERE f.nume_film LIKE ? OR r.nume_regizor LIKE ? OR f.an_aparitie LIKE ?
                            ORDER BY f.id_film DESC";
                            
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $sql = "SELECT f.id_film, f.nume_film, f.an_aparitie, f.durata, r.nume_regizor 
                            FROM film f 
                            LEFT JOIN regizor r ON f.id_regizor = r.id_regizor 
                            ORDER BY f.id_film DESC";
                    $result = $mysqli->query($sql);
                }

                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><span class='film-id'>#" . $row['id_film'] . "</span></td>";
                        echo "<td><strong>" . htmlspecialchars($row['nume_film']) . "</strong></td>";
                        echo "<td>" . $row['an_aparitie'] . "</td>";
                        echo "<td>" . $row['durata'] . " min</td>";
                        
                        $regizor = $row['nume_regizor'] ? htmlspecialchars($row['nume_regizor']) : "<em style='color:#7f8c8d'>Necunoscut</em>";
                        echo "<td>" . $regizor . "</td>";
                        
                        echo "<td>";
                        $id_film = $row['id_film'];
                        $sql_gen = "SELECT g.nume_gen FROM gen g 
                                    JOIN film_gen fg ON g.id_gen = fg.id_gen 
                                    WHERE fg.id_film = $id_film";
                        $res_gen = $mysqli->query($sql_gen);
                        if($res_gen && $res_gen->num_rows > 0){
                            while($g = $res_gen->fetch_assoc()){
                                echo "<span class='badge'>" . htmlspecialchars($g['nume_gen']) . "</span>";
                            }
                        } else {
                            echo "<span style='color:#95a5a6;'>-</span>";
                        }
                        echo "</td>";
                        
                        echo "<td>
                                <a href='detalii.php?id=".$row['id_film']."' class='btn-action btn-details' title='Vezi Detalii'>📋</a>
                                <a href='recenzii.php?id_film=".$row['id_film']."' class='btn-action btn-reviews' title='Recenzii'>💬</a>
                                <a href='editare.php?id=".$row['id_film']."' class='btn-action btn-edit' title='Editează'>✏️</a>
                                <a href='stergere.php?tip=film&id=".$row['id_film']."' class='btn-action btn-delete' title='Șterge' onclick='return confirm(\"Sigur ștergi acest film?\");'>🗑️</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='empty-state'>
                            <div style='font-size: 48px; margin-bottom: 15px;'>🎬</div>
                            <div style='font-size: 18px; color: #ecf0f1;'>Nu am găsit niciun film care să corespundă căutării</div>
                            <div style='margin-top: 10px; color: #95a5a6;'>Încearcă un alt termen de căutare!</div>
                          </td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
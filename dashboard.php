<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php'; 
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Cinema</title>
    <style>
}
       
        .container {
            padding: 50px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 50px;
            animation: fadeInDown 0.8s ease-out;
        }
        
        .welcome-section h1 {
            color: #fff;
            font-size: 42px;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .welcome-section p {
            color: #ecf0f1;
            font-size: 18px;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .card {
            background: rgba(44, 62, 80, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(52, 152, 219, 0.3);
        }
        
        .card h2 {
            color: #3498db;
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(52, 152, 219, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-icon {
            font-size: 28px;
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .stat-item:last-child {
            border-bottom: none;
        }
        
        .stat-label {
            color: #bdc3c7;
            font-size: 15px;
        }
        
        .stat-value {
            color: #fff;
            font-size: 24px;
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .status-success {
            background-color: rgba(39, 174, 96, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }
        
        .status-error {
            background-color: rgba(192, 57, 43, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .action-btn {
            display: block;
            padding: 15px;
            background: linear-gradient(45deg, #2980b9, #3498db);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.4);
        }
        
        .info-text {
            color: #95a5a6;
            font-size: 14px;
            line-height: 1.6;
            margin-top: 15px;
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
        
        .user-info {
            background: rgba(52, 152, 219, 0.1);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
            color: #ecf0f1;
        }
        
        .user-info strong {
            color: #3498db;
        }
    </style>
</head>
<body>


<div class="container">
    <div class="welcome-section">
        <h1>Bine ai venit, <?php echo htmlspecialchars($_SESSION['username']); ?>! 🎉
    <div class="card" style="margin-bottom: 30px; text-align: center;">
    <h2 style="color: #fff; margin-bottom: 20px;">🔍 Caută în baza de date</h2>
    <form action="cautare.php" method="GET" style="display: flex; gap: 15px; max-width: 600px; margin: 0 auto;">
        <input type="text" name="q" placeholder="Caută film sau actor..." required 
               style="flex: 1; padding: 15px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); 
                      color: white; border-radius: 10px; font-size: 15px; transition: all 0.3s ease; margin: 0;">
        <button type="submit" 
                style="padding: 15px 30px; background: linear-gradient(45deg, #2980b9, #3498db); color: white; 
                       border: none; border-radius: 10px; font-weight: 700; font-size: 15px; text-transform: uppercase; 
                       letter-spacing: 1px; cursor: pointer; transition: all 0.3s ease; 
                       box-shadow: 0 5px 20px rgba(52, 152, 219, 0.4); margin: 0; min-width: 150px;">
            🔎 Caută
        </button>
    </form>
</div>

<style>
input[type="text"]:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid #3498db !important;
    box-shadow: 0 0 20px rgba(52, 152, 219, 0.4) !important;
}

button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(52, 152, 219, 0.6) !important;
    background: linear-gradient(45deg, #3498db, #2980b9) !important;
}
</style>
    </h1>
        <p>Panou de control - Feedback & Clasificare Filme</p>
    </div>

    <div class="dashboard-grid">
        <div class="card">
            <h2><span class="card-icon">⚙️</span> Status Sistem</h2>
            <?php
            if ($mysqli->ping()) {
                echo '<div class="stat-item">';
                echo '<span class="stat-label">Conexiune baza de date</span>';
                echo '<span class="status-badge status-success">✅ ACTIV</span>';
                echo '</div>';
                
                echo '<div class="user-info" style="margin-top: 20px;">';
                echo '<strong>Utilizator conectat:</strong> ' . htmlspecialchars($_SESSION['username']);
                echo '</div>';
            } else {
                echo '<div class="stat-item">';
                echo '<span class="stat-label">Conexiune baza de date</span>';
                echo '<span class="status-badge status-error">❌ EROARE</span>';
                echo '</div>';
                echo '<p class="info-text" style="color:#e74c3c;">Verifica serverul MySQL!</p>';
            }
            ?>
        </div>

        <div class="card">
            <h2><span class="card-icon">🎬</span> Statistici Filme</h2>
            <?php
            if ($mysqli->ping()) {
              
                $sql="SELECT COUNT(*) as total FROM film";
                $result=$mysqli->query($sql);
                
                if($result) {
                    $row=$result->fetch_assoc();
                    echo '<div class="stat-item">';
                    echo '<span class="stat-label">Total filme</span>';
                    echo '<span class="stat-value">' . $row['total'] . '</span>';
                    echo '</div>';
                }
                
                $sql="SELECT COUNT(*) as recent FROM film LIMIT 5";
                $result=$mysqli->query($sql);
                
                if($result) {
                    $row=$result->fetch_assoc();
                    echo '<div class="stat-item">';
                    echo '<span class="stat-label">Inregistrari afisate</span>';
                    echo '<span class="stat-value" style="font-size: 20px;">' . $row['recent'] . '</span>';
                    echo '</div>';
                }
                
                echo '<p class="info-text">Baza de date este functionala si gata de utilizare.</p>';
            } else {
                echo '<p class="info-text" style="color:#e74c3c;">Nu se pot incarca statisticile.</p>';
            }
            ?>
        </div>

        <div class="card">
            <h2><span class="card-icon">⚡</span> Actiuni Rapide</h2>
            <div class="quick-actions">
                <a href="filme.php" class="action-btn">Vezi Filme</a>
                <a href="adaugare.php" class="action-btn">Adauga Film</a>
              
            
                <a href="statistici.php" class="action-btn" style="background: linear-gradient(45deg, #8e44ad, #9b59b6);">
                    📊 Vezi Statistici
                </a>
           
      
            </div>
            <p class="info-text">Acceseaza rapid functiile principale ale aplicatiei din meniul de mai sus sau folosind butoanele de actiune rapida.</p>
        </div>
    </div>
</div>

</body>
</html>
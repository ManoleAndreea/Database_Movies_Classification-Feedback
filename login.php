<?php
session_start();
$mesaj=""; 

if (isset($_POST['login_btn'])) 
{
    $host="localhost";
    $db="feedback_clasificare_filme"; 
    $user=$_POST['username'];
    $pass=$_POST['password'];

    try 
    {
        $mysqli=new mysqli($host, $user, $pass, $db);

        if ($mysqli->connect_error) 
        {
            throw new Exception("Eroare: " . $mysqli->connect_error);
        }

        $_SESSION['loggedin']=true;
        $_SESSION['username']=$user;
        
        header("Location: dashboard.php");
        exit();

    } catch (Exception $e) 
    {
        $error=$e->getMessage();
        
        $explicatie="";
        if (strpos($error, 'Unknown database')!==false) {
            $explicatie="Baza de date nu exista.";
        } elseif (strpos($error, 'Access denied')!==false) {
            $explicatie="Utilizator sau parola gresite.";
        } elseif (strpos($error, 'Can\'t connect')!==false) {
            $explicatie="Verifica daca MySQL ruleaza.";
        }
        $mesaj="<div class='mesaj eroare'>Nu a mers frate ;-;<br><small>$error</small><br><strong>$explicatie</strong></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Login Cinema DB</title>
    <style>
        body 
        {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=2070&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #ecf0f1;
        }
        body::before 
        {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }
        .login-container 
        {
            background-color: rgba(44, 62, 80, 0.85); 
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.7);
            width: 340px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeInDown 0.8s ease-out;
        }
        h2 
        {
            color: #fff;
            margin-bottom: 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #3498db;
            display: inline-block;
            padding-bottom: 10px;
        }
        input[type="text"], input[type="password"] 
        {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 6px;
            box-sizing: border-box;
            outline: none;
            transition: background 0.3s, box-shadow 0.3s;
            font-size: 15px;
        }
        input[type="text"]::placeholder, input[type="password"]::placeholder 
        {
            color: #bdc3c7;
        }
        input[type="text"]:focus, input[type="password"]:focus 
        {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.6);
        }
        input[type="submit"] 
        {
            width: 100%;
            background: linear-gradient(45deg, #2980b9, #3498db);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        input[type="submit"]:hover 
        {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        .mesaj 
        {
            margin-top: 25px;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.4;
        }
        .succes 
        {
            background-color: rgba(39, 174, 96, 0.9);
            color: white;
            border: 1px solid #2ecc71;
        }
        .eroare 
        {
            background-color: rgba(192, 57, 43, 0.9);
            color: white;
            border: 1px solid #e74c3c;
        }
        .warning 
        {
            color: #f39c12;
            margin-bottom: 15px;
            font-size: 13px;
            font-weight: bold;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login BD_proiect</h2>
    
    <div class="warning">TE ROG EU MERGI!!!</div>
    
    <form method="POST" action="">
        <input type="text" name="username" value="root" placeholder="Utilizator" required>
        <input type="password" name="password" placeholder="Parola">
        <input type="submit" name="login_btn" value="Intra in cont frt">
    </form>

    <?php echo $mesaj; ?>

</div>

</body>
</html>
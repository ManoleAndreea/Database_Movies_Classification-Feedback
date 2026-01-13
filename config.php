<?php
$host="localhost";
$user="root";
$pass="12Dixy*21"; 
$db="feedback_clasificare_filme";

try {
    $mysqli=new mysqli($host, $user, $pass, $db);
    

    if($mysqli->connect_error) 
    {
        throw new Exception("Eroare conexiune: " . $mysqli->connect_error);
    }
} catch (Exception $e) {
    die("<h1>Eroare fatala:</h1> " . $e->getMessage());

$mysqli->set_charset("utf8mb4");
}
?>
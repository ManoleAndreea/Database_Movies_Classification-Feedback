<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

// Verificam daca avem parametrii necesari in URL
if (isset($_GET['id']) && isset($_GET['tip'])) {
    $id = intval($_GET['id']);
    $tip = $_GET['tip'];

    switch ($tip) {
        // --- STERGE FILM ---
        case 'film':
            // 1. Stergem dependintele (Recenzii, Genuri, Distributie)
            $mysqli->query("DELETE FROM feedback WHERE id_film = $id");
            $mysqli->query("DELETE FROM film_gen WHERE id_film = $id");
            $mysqli->query("DELETE FROM distributie WHERE id_film = $id");
            
            // 2. Stergem filmul
            if ($mysqli->query("DELETE FROM film WHERE id_film = $id")) {
                header("Location: filme.php?msg=sters");
            } else {
                die("Eroare la stergerea filmului: " . $mysqli->error);
            }
            break;

        // --- STERGE ACTOR ---
        case 'actor':
            // 1. Aflam ID-ul detaliilor (ca sa stergem si din tabela detalii_persoana)
            $res = $mysqli->query("SELECT id_date FROM actor WHERE id_actor = $id");
            $row = $res->fetch_assoc();
            $id_date = $row['id_date'];

            // 2. Stergem din distributie
            $mysqli->query("DELETE FROM distributie WHERE id_actor = $id");

            // 3. Stergem actorul
            $mysqli->query("DELETE FROM actor WHERE id_actor = $id");

            // 4. Stergem detaliile personale
            if ($id_date) {
                $mysqli->query("DELETE FROM detalii_persoana WHERE id_date = $id_date");
            }
            
            header("Location: actori.php?msg=sters");
            break;

        // --- STERGE RECENZIE (FEEDBACK) ---
        case 'recenzie':
            // Avem nevoie de ID-ul filmului pentru redirect
            $res = $mysqli->query("SELECT id_film FROM feedback WHERE id_feedback = $id");
            $row = $res->fetch_assoc();
            $id_film = $row['id_film'];

            if ($mysqli->query("DELETE FROM feedback WHERE id_feedback = $id")) {
                header("Location: detalii.php?id=$id_film&msg=recenzie_stersa");
            }
            break;
            
        // --- STERGE REGIZOR ---
        case 'regizor':
             // Verificam daca are filme (nu stergem regizori care au filme active!)
             $check = $mysqli->query("SELECT COUNT(*) as nr FROM film WHERE id_regizor = $id");
             $row = $check->fetch_assoc();
             
             if ($row['nr'] > 0) {
                 die("Nu poți șterge acest regizor deoarece are filme asociate! Șterge filmele întâi.");
             }

             // Luam ID detalii
             $res = $mysqli->query("SELECT id_date FROM regizor WHERE id_regizor = $id");
             $r = $res->fetch_assoc();
             $id_date = $r['id_date'];

             $mysqli->query("DELETE FROM regizor WHERE id_regizor = $id");
             if($id_date) {
                 $mysqli->query("DELETE FROM detalii_persoana WHERE id_date = $id_date");
             }
             
             header("Location: dashboard.php?msg=regizor_sters");
             break;

        default:
            die("Tip de ștergere invalid.");
    }
} else {
    header("Location: dashboard.php");
}
?>
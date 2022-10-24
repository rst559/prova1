<?php
session_start();

require_once("process.php");
# Afegim l'estat al fitxer de conexions
$dades = array("IP" =>$_SERVER['REMOTE_ADDR'], "user"=>$_SESSION["email"],"time"=> date('m/d/Y h:i:s a', time()), "status"=>"login");
estat($dades,"conexions.json");
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <title>Benvingut</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="container noheight" id="container">
    <div class="welcome-container">
        <h1>Benvingut!</h1>
        <div>Hola <?php
            if($_SESSION){
                print_r($_SESSION["nom"]);
            }
        ?>, les teves darreres connexions són:</div>
        <?php print_r(sessions($_SESSION["email"])) ; ?>
        <form action="logout.php">
            <button>Tanca la sessió</button>
        </form>
    </div>
</div>
</body>
</html>
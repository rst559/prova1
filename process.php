<?php
session_start();


/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */


function llegeix(string $file) : array
{
    $var = [];
    if ( file_exists($file) ) {
        $var = json_decode(file_get_contents($file), true);
    }
    return $var;
}

/**
 * Guarda les dades a un fitxer
 *
 * @param array $dades
 * @param string $file
 */
function escriu(array $dades, string $file): void
{
    file_put_contents($file,json_encode($dades, JSON_PRETTY_PRINT));
}

/*
Comprobem si l'usuari ha seleccionat iniciar sessio o sha de registrar;
*/
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $users = llegeix("users.json");
    /* Si vol iniciar sessio */
    if($_POST['method'] == "signin"){
        if(empty($_POST["email"]) || empty($_POST["password"])){
            header("Location:index.php");
        }
        if($users[$_POST["email"]]["password"] == $_POST["password"]){

        # Si l'usuari es correcte
            $_SESSION["nom"] = $users[$_POST["email"]]["nom"];
            $_SESSION["email"] = $users[$_POST["email"]]["email"];
            $_SESSION["password"] = $users[$_POST["email"]]["password"];
            $dades = array("IP" =>$_SERVER['REMOTE_ADDR'], "user"=>$_SESSION["email"],"time"=> date('m/d/Y h:i:s a', time()), "status"=>"signin_success");
            estat($dades,"conexions.json");
            header("Location: hola.php");
        }else{
            
            # Si la contrasenya es invalida
            print("la contrasenya no es valida");
            $dades = array("IP" =>$_SERVER['REMOTE_ADDR'], "user"=>$_SESSION["email"],"time"=> date('m/d/Y h:i:s a', time()), "status"=>"signin_password_error");
            estat($dades,"conexions.json");
            header("Location: index.php");
        }
    }
    /*  Si es vol registrar */
    elseif($_POST['method'] == "signup"){
        $users = llegeix("users.json");
        $users[$_POST["email"]] = array("email" => $_POST["email"], "password" => $_POST["password"], "nom" => $_POST["nom"]);
        escriu($users,"users.json");
        # Creem la sessio amb les dades rebudes
        $_SESSION["nom"] =  $_POST["nom"];
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["password"] = $_POST["password"];
        # Creem el fitxer de conexions
        $dades = array("IP" =>$_SERVER['REMOTE_ADDR'], "user"=>$_SESSION["email"],"time"=> date('m/d/Y h:i:s a', time()), "status"=>"signup");
        estat($dades,"conexions.json");

        # Redireccionem l'usuari al seu perfil
        header("Location: hola.php");
    }
}

function estat(array $dades, string $file){
    $status = llegeix($file);
    $status[] = array("ip" =>$_SERVER['REMOTE_ADDR'],"user"=>$dades["user"],"time"=> date('m/d/Y h:i:s a', time()), "status"=>$dades["status"]);
    echo $status;
    escriu($status,$file);
}

function sessions($user){
    $sessions = llegeix("conexions.json");
    $arr = [];
    for($i=0;$i<count($sessions);$i++){
        if($sessions[$i]["user"] == $user){
            $arr[] = $sessions[$i];
            return $arr;
        }
        else{
            return $arr;
        }
    }
}

?>
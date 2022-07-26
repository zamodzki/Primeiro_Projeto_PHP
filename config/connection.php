<?php
session_start();

$mode = 'online';

if($mode =='local'){
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "login";

}

if($mode=='online'){
    $host = "";
    $user = "";
    $password = "";
    $db = "";

}

try{
    $pdo = new PDO("mysql:host=$host;dbname=$db",$user,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conectado com sucesso";
}catch(PDOException $error){
    echo "Falha ao se conectar ao banco de dados";

}

function cleanPost($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;

}

function auth($token){
    //VERIFICAR AUTORIZAÇÃO
    global $pdo;
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
    $sql->execute(array($token));
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        return false;
    }else{
        return $user;
    }
}


?>
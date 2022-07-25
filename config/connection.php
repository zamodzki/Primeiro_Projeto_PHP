<?php
$mode = 'local';

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
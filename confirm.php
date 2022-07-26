<?php
require('config/connection.php');

if(isset($_GET['cod-confirm']) && !empty($_GET['cod-confirm'])){
    //limpar get
    $cod = cleanPost($_GET['cod-confirm']);
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE cod_confirm=? LIMIT 1");
    $sql->execute(array($cod));
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    if($user){
        //ATT O STATUS NO BANCO
        $status= "confirmado";
        $sql=$pdo->prepare("UPDATE usuarios SET status=? WHERE cod_confirm=?");
        if($sql->execute(array($status,$cod))){

        header('location: index.php?result=ok');
        }


    }else{
        echo  "<h1>Código de confirmação inválido</h1>";
    }
}

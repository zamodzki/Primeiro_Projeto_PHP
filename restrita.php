<?php
require('config/connection.php');

// VERIFICANDO AUTENTICAÇÃO
$user = auth($_SESSION['TOKEN']);
if($user){
    echo "<h1> SEJA BEM-VINDO <b style='color:red'>".$user['name']."!</b></h1>";
    echo  "<br><br><a style='background:blue; color:white; text-decoration:none; padding:20px; border-radius:5px;' href='logout.php'>Logout</a>";
}else{
    header('location: index.php');

}


/*
//VERIFICAR AUTORIZAÇÃO
$sql= $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
$sql->execute(array($_SESSION['TOKEN']));
$user = $sql->fetch(PDO::FETCH_ASSOC);

if(!$user){
    header('location: index.php');
}else{
    echo "<h1> SEJA BEM-VINDO <b style='color:red'>".$user['name']."!</b></h1>";
    echo  "<br><br><a style='background:blue; color:white; text-decoration:none; padding:20px; border-radius:5px;' href='logout.php'>Sair do sistema</a>";
}
*/

?>

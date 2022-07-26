<?php
// REQUIRE PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';

if(isset($_GET['cod']) && !empty($_GET['cod'])){
    $cod = cleanPost($_GET['cod']);
    if(isset($_POST['password']) && isset($_POST['r_password'])){
        // VERIFICANDO O PREENCHIMENTO DOS CAMPOS
        if(empty($_POST['password']) or empty($_POST['r_password']) or empty($_POST['terms'])){
            $general_error = "Todos os campos devem ser preenchidos!";
        }else{
            // RECEBENDO E LIMPANDO O POST
            $password = cleanPost($_POST['password']);
            $pass_cript= sha1($password);
            $r_password = cleanPost($_POST['r_password']);
    
            // VERIFICANDO DIGITOS DA SENHA
            if(strlen($password)<6){
                $passw_error = "A senha deve conter 6 dígitos!" ;
            }
            // VERIFICANDO IGUALDADE DAS SENHAS
            if($password !== $r_password){
                $r_passw_error = "As senhas não coincidem!";
            }
    
            if(!isset($general_error) && !isset($passw_error) && !isset($r_passw_error)){
                // VERIFICAR SE O USUARIO JA ESTA CADASTRADO
              $sql = $pdo->prepare("SELECT * FROM usuarios WHERE recover_password=? LIMIT 1");  
              $sql->execute(array($cod));
              $user = $sql->fetch();
              //    SE NÃO EXISTIR ADD NO BANCO
              if(!$user){
                echo "Recuperação de senha inválida!";
              }else{
                //ATT O TOKEN NO BANCO
                $sql=$pdo->prepare("UPDATE usuarios SET password=? WHERE recover_password=?");
                if($sql->execute(array($pass_cript,$cod))){
                header('location: index.php');
                }
              }
    
            }
        
        }
    }
}else{
    header('location: index.php');
}


//  VERIFICANDO A EXISTENCIA DA POSTAGEM
require('config/connection.php');


?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <title>Login</title>
    
</head>
<body>
    <form method="post">
        <h1>Alterar a Senha</h1>

        <?php if(isset($general_error)){ ?>
            <div class="general-error animate__animated animate__rubberBand">
            <?php  echo $general_error; ?>

            </div>;
        <?php } ?>
        

        <div class="input-group">
            <img class= "input-icon"src="img/password.png">
            <input <?php if(isset($general_error) or isset($passw_error)){echo 'class="input-error"';}?> type="password" name="password"placeholder="Nova Senha de pelo menos 6 dígitos" <?php if(isset($_POST['password'])){ echo "value='".$_POST['password']."'";} ?> required>
            <?php if(isset($passw_error)){ ?>
            <div class="error"><?php echo $passw_error; ?> </div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class= "input-icon"src="img/password.png">        
            <input <?php if(isset($general_error)or isset($r_passw_error)){echo 'class="input-error"';}?> type="password" name="r_password" placeholder="Repita a nova senha" <?php if(isset($_POST['r_password'])){ echo "value='".$_POST['r_password']."'";} ?> required>
            <?php if(isset($r_passw_error)){ ?>
            <div class="error"><?php echo $r_passw_error; ?> </div>
            <?php } ?>
        </div>
        
        <button  class="btn-blue"type="submit">Confirmar</button>
    
    </form>
</body>
</html>
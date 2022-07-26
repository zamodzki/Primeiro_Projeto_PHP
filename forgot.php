<?php
// REQUIRE PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';

require('config/connection.php');

if(isset($_POST['email'])&& !empty($_POST['email'])){
    // RECEBER OS DADOS DO POST E LIMPAR
    $email = cleanPost($_POST['email']);
    $status="confirmado";

    // verificando a existencia do usuario
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND status=? LIMIT 1");
    $sql->execute(array($email,$status));
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    if($user){
        $email = new PHPMailer(true);
        $cod = sha1(uniqid());
        // ATT O COD DE RECUPERAÇÃO
        $sql=$pdo->prepare("UPDATE usuarios SET recover_password=? WHERE email=?");
        if($sql->execute(array($cod,$email))){
            try{
                $mail->setFrom('seusistema@sistema.com', 'sistema'); // ORIGEM DO EMAIL
                $mail->addAddress($email, $name);  
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Recuperação de senha';
                $mail->Body    = '<h1> Recuperar a senha:</h1><a style ="background:blue; color:white; text-decoration:none; padding:20px; border-radius:5px;" href="https://buscarep.com.br/login/recover-password.php?cod='.$cod.'">Recuperar a senha<br><br><a/><p>Equipe BuscaRep</p>';
                
                $mail->send();
                    header('location: email_forgot.php');
    
    
                } catch (Exception $e) {
                echo "Ocorreu um erro ao enviar e-mail de confirmação {$mail->ErrorInfo}";
                }
        }   

    }else{
        $user_error = "Usuário não encontrado!";
    }
}



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
    <title>Esqueceu a senha</title>
    
</head>
<body>
    <form method="post">
        <h1>Recuperar senha</h1>
        <?php if(isset($user_error)){ ?>
            <div  style="text-align:center" class="general-error animate__animated animate__rubberBand">
            <?php  echo $user_error; ?>

            </div>;
        <?php } ?>

        <div class="input-group">
            <img class= "input-icon"src="img/email.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>
        
        <button  class="btn-blue"type="submit">Recuperar senha</button>
        <a href="index.php">Voltar para login</a>
    </form>
</body>
</html>
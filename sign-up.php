<?php
// REQUIRE PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/Exception.php';
require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';


//  VERIFICANDO A EXISTENCIA DA POSTAGEM
require('config/connection.php');
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['r_password'])){
    // VERIFICANDO O PREENCHIMENTO DOS CAMPOS
    if(empty($_POST['name']) or empty($_POST['email']) or empty($_POST['password']) or empty($_POST['r_password']) or empty($_POST['terms'])){
        $general_error = "Todos os campos devem ser preenchidos!";
    }else{
        // RECEBENDO E LIMPANDO O POST
        $name = cleanPost($_POST['name']);
        $email = cleanPost($_POST['email']);
        $password = cleanPost($_POST['password']);
        $pass_cript= sha1($password);
        $r_password = cleanPost($_POST['r_password']);
        $checkbox = cleanPost($_POST['terms']);
        // VERIFICANDO SE O NOME CONTEM APENAS LETRAS E ESPAÇOS
        if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
            $name_error = "Permitido apenas letras!";
        }
        //   VALIDAÇÃO DE EMAIL
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = "Formato de e-mail inválido!";
        }
        // VERIFICANDO DIGITOS DA SENHA
        if(strlen($password)<6){
            $passw_error = "A senha deve conter 6 dígitos!" ;
        }
        // VERIFICANDO IGUALDADE DAS SENHAS
        if($password !== $r_password){
            $r_passw_error = "As senhas não coincidem!";
        }
        // VERIFICANDO CHECKBOX
        if($checkbox !=="ok"){
            $error_checkbox = "Você deve concordar com nossa Política de Privacidade e os Termos de uso";
        }
        if(!isset($general_error) && !isset($email_error) && !isset($passw_error) && !isset($r_passw_error) && !isset($error_checkbox)){
            // VERIFICAR SE O USUARIO JA ESTA CADASTRADO
          $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");  
          $sql->execute(array($email));
          $user = $sql->fetch();
          //    SE NÃO EXISTIR ADD NO BANCO
          if(!$user){
            $recover_password = "";
            $token = "";
            $cod_confirm = uniqid();
            $status = "novo";
            $date_register = date('d/m/Y');
            $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null,?,?,?,?,?,?,?,?)");
            if($sql->execute(array($name,$email,$pass_cript,$recover_password,$token,$cod_confirm,$status,$date_register))){
                // MODO LOCAL
                if($mode =="local"){
                    header('location: index.php?result=ok');
                }
                
                // MODO PRODUÇÃO
                if($mode =="online"){
                    $mail = new PHPMailer(true);
                    
                    try{
                    $mail->setFrom('seusistema@sistema.com', 'sistema'); // ORIGEM DO EMAIL
                    $mail->addAddress($email, $name);  
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Confirmação de cadastro';
                    $mail->Body    = '<h1> Por favor confirme seu e-mail abaixo:</h1><a style ="background:blue; color:white; text-decoration:none; padding:20px; border-radius:5px;" href="https://seusistema.com.br/confirm.php?cod_confirm='.$cod_confirm.'">Confirmação E-mail <br><br><a/><p>Equipe BuscaRep</p>';
                    
                    $mail->send();
                        header('location: thanks.php');


                    } catch (Exception $e) {
                    echo "Ocorreu um erro ao enviar e-mail de confirmação {$mail->ErrorInfo}";

                }
            }
            }
          }else{
            $general_error = "Email já cadastrado!";
          }

        }
    
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
    <title>Login</title>
    
</head>
<body>
    <form method="post">
        <h1>Cadastrar</h1>

        <?php if(isset($general_error)){ ?>
            <div class="general-error animate__animated animate__rubberBand">
            <?php  echo $general_error; ?>

            </div>;
        <?php } ?>
        
        
        <div class="input-group">
            <img class= "input-icon"src="img/user.png">
            <input <?php if(isset($general_error) or isset($name_error)){echo 'class="input-error"';}?> name="name" type="text" placeholder="Nome Completo" <?php if(isset($_POST['name'])){ echo "value='".$_POST['name']."'";} ?> required>
            <?php if(isset($name_error)){ ?>
            <div class="error"><?php echo $name_error; ?> </div>
            <?php } ?>
        </div>



        <div class="input-group">
            <img class= "input-icon"src="img/email.png">
            <input <?php if(isset($general_error)or isset($email_error)){echo 'class="input-error"';}?> type="email" name="email" placeholder="Digite seu email" <?php if(isset($_POST['email'])){ echo "value='".$_POST['email']."'";} ?> required>
            <?php if(isset($$email_error)){ ?>
            <div class="error"><?php echo $email_error; ?> </div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class= "input-icon"src="img/password.png">
            <input <?php if(isset($general_error) or isset($passw_error)){echo 'class="input-error"';}?> type="password" name="password"placeholder="Senha de pelo menos 6 dígitos" <?php if(isset($_POST['password'])){ echo "value='".$_POST['password']."'";} ?> required>
            <?php if(isset($passw_error)){ ?>
            <div class="error"><?php echo $passw_error; ?> </div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class= "input-icon"src="img/password.png">        
            <input <?php if(isset($general_error)or isset($r_passw_error)){echo 'class="input-error"';}?> type="password" name="r_password" placeholder="Repita a senha" <?php if(isset($_POST['r_password'])){ echo "value='".$_POST['r_password']."'";} ?> required>
            <?php if(isset($r_passw_error)){ ?>
            <div class="error"><?php echo $r_passw_error; ?> </div>
            <?php } ?>
        </div>

        <div <?php if(isset($general_error) or isset($error_checkbox) ){echo 'class="input_group input-error"';}else{echo 'class="input_group"';}?>>
            <input type="checkbox" id="termos" name="terms" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com nossa <a class="link" href="#">Política de Privacidade</a> e os <a class="link"href="#">Termos de uso</a> </label>
        </div>
        
        <button  class="btn-blue"type="submit">Cadastrar</button>
        <a href="index.php">Já tenho uma conta</a>
    </form>
</body>
</html>
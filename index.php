<?php
require('config/connection.php');

if(isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])){
    // RECEBER OS DADOS DO POST E LIMPAR
    $email = cleanPost($_POST['email']);
    $password = cleanPost($_POST['password']);
    $pass_cript = sha1($password);

    // verificando a existencia do usuario
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND password=? LIMIT 1");
    $sql->execute(array($email,$pass_cript));
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    if($user){
        //VERIFICANDO A CONFIRMaÇÃO DO EMAIL
        if($user['status']=="confirmado"){
        
            //CRIAR UM TOKEN
            $token = sha1(uniqid().date('d-m-Y-H-i-s'));

            //ATT O TOKEN NO BANCO
            $sql=$pdo->prepare("UPDATE usuarios SET token=? WHERE email=? AND password=?");
            if($sql->execute(array($token,$email,$pass_cript))){

            // ARMAZENANDO O TOKE NA SESSAO
            $_SESSION['TOKEN']= $token;
            header('location: restrita.php');
            }
        }else{
            $login_error = "Por favor confirme seu cadastro no e-mail!";

        }

    }else{
        $login_error = "Usuário ou senha incorretos!";
    }
}

if(isset($_GET['result'])){

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
        <h1>Login</h1>

        <?php if(isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
            <div class="success animate__animated animate__rubberBand">
                Cadastro realizado com sucesso!
            </div>
       <?php } ?>

       <?php if(isset($login_error)){ ?>
            <div  style="text-align:center" class="general-error animate__animated animate__rubberBand">
            <?php  echo $login_error; ?>

            </div>;
        <?php } ?>
       
        <div class="input-group">
            <img class= "input-icon"src="img/email.png">
            <input type="email" name="email" placeholder="Digite seu email" required>
        </div>

        <div class="input-group">
            <img class= "input-icon"src="img/password.png">        
            <input type="password" name="password" placeholder="Digite sua senha" required>
        </div>
        
        <button  class="btn-blue"type="submit">Fazer Login</button>
        <a href="sign-up.php">Ainda não tenho cadastro</a>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <?php if(isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
    <script>
        setTimeout(() => {
            $('.success').addClass('hidden');
        }, 3000)
    <?php } ?>
                
    </script>

</body>
</html>
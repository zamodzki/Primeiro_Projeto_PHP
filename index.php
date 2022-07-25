<?php
require('config/connection.php');

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
    <form>
        <h1>Login</h1>

        <?php if(isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
            <div class="success animate__animated animate__rubberBand">
                Cadastro realizado com sucesso!
            </div>
       <?php } ?>
       
        <div class="input-group">
            <img class= "input-icon"src="img/email.png">
            <input type="email" placeholder="Digite seu email">
        </div>

        <div class="input-group">
            <img class= "input-icon"src="img/password.png">        
            <input type="password" placeholder="Digite sua senha">
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
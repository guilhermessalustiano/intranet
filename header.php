<?php
include_once $_SERVER["DOCUMENT_ROOT"].'/keycloak_api.php';
include($_SERVER["DOCUMENT_ROOT"] . "/comum/funcoes.php");

checkKeycloakAuth();

$userData = getUserData();

// informacoes do usuario logado
$_SESSION['username'] = $userData['preferred_username'];
$_SESSION['full_name'] = $userData['given_name'];
$_SESSION['first_name'] = explode(' ', $userData['given_name'])[0];


?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='/comum/css-js/bootstrap-4.2.1-dist/css/bootstrap.min.css' />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <link rel='stylesheet' href='/comum/css-js/iel.css' />
    <title>Intranet Labjor</title>
</head>

<style>
    table, th, tr, td {
        border: 1px solid black;
    }
</style>
<body>
<div class='iel-page'>


<header>
<nav class='navbar bg-iel-vermelho navbar-expand-lg'>
            <div style='width: 100%'>
                <div class='container'>
                    <center>
                        <img src='/comum/imagens/logotipo_labjor_tif-600.png' height='45' border='0'/>
                        &nbsp;&nbsp;
                        <h3 class='bg-iel-vermelho'>LABJOR</h3>
                        &nbsp;&nbsp;
                    </center>
                </div>
            </div>
        </nav>
        <nav class='navbar bg-iel-vermelho navbar-expand-lg'>
            <div style='width: 100%'>
                <div class='container'>
                    <center>
                        <h4 class='bg-iel-vermelho'>Intranet</h4>
                    </center>
                </div>
            </div>
        </nav>
</header>


        <header>
        <nav class='navbar sticky-top navbar-expand-lg navbar-iel-azul bg-iel-vermelho'>
                    <div style='width: 100%'>
                        <div class='container'>
                            <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
                                <spusernamean class='navbar-toggler-icon'></span>
                            </button>

                            <div class='collapse navbar-collapse' id='navbarSupportedContent'>
                                <ul class='navbar-nav mr-auto'>
                                    <!--necessário para deixar o logout no lado direito -->
                                </ul>
                                <?php echo("                      
                                    <span style='color:white'>".$_SESSION['first_name']."</span>
                                        &nbsp; <!-- Espaço entre o nome do usuário e o relógio -->
                                        <span style='color:white'>
                                            <i class='fas fa-clock'></i> <!-- Ícone de relógio -->
                                            <span id='session-expiry-timer'value=". htmlspecialchars($userData['token_expires_in_seconds'])."></span>
                                        </span>
                                        <input type='hidden' id='usuario_codigo_hidden' value='".$_SESSION['logged_user_id']."'>
                                    "); 
                                ?>
                                <div>
                            </div>
                            <a class='nav-link logout-iel-azul bg-iel-vermelho' href='encerra.php'>Sair</a>
                        </div>
                        </div>
                    </div>
        </nav>

        </header> 



<style>

header {
    color: white;
}

</style>
<?php
if (!checkUserExistsPDO($userData['preferred_username'])) { //usuario está no sise e local
    echo "<br><center>O usuário <b>".$_SESSION['username']." </b> não está autorizado a acessar a Intranet do LABJOR!</center>";
    include($_SERVER["DOCUMENT_ROOT"] . "/footer.php");
    exit;
} 

?>



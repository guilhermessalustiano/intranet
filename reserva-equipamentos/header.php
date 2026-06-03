<?php


// Desabilitar cache no navegador
// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Cache-Control: post-check=0, pre-check=0", false);
// header("Pragma: no-cache");
// header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

include_once $_SERVER["DOCUMENT_ROOT"].'/keycloak_api.php';
include($_SERVER["DOCUMENT_ROOT"] . "/comum/funcoes.php");
checkKeycloakAuth();

$userData = getUserData();
// $primeiroNome = explode(' ', $userData['given_name'])[0];  // Pega o pcmrimeiro nome

// informacoes do usuario logado
$_SESSION['logged_user_id'] = checkUserExistsPDO($userData['preferred_username']);
$_SESSION['username'] = $userData['preferred_username'];
$_SESSION['full_name'] = $userData['given_name'];
$_SESSION['first_name']  = explode(' ', $userData['given_name'])[0];

if (!isset( $_SESSION['token_expires_in_seconds'])) {
    $_SESSION['token_expires_in_seconds'] = $userData['token_expires_in_seconds'];   
}

?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src='../comum/css-js/jquery-1.9.1/jquery.min.js'></script>

    <link rel='stylesheet' href='/comum/css-js/bootstrap-4.2.1-dist/css/bootstrap.min.css' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel='stylesheet' href='/comum/css-js/iel.css'>
    <link rel='stylesheet' href='/reserva-equipamentos/css.css'>
    <link rel='stylesheet' href='/comum/css-js/datatables.min.css'>

</head>
<body>


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
                        <h4 class='bg-iel-vermelho'>Reserva de Equipamentos</h4>
                        <title>Reserva de Equipamentos</title>
                    </center>
                </div>
            </div>
        </nav>
</header>


<?php

echo("      <nav class='navbar sticky-top navbar-expand-lg navbar-iel-azul bg-iel-vermelho'>
                <div style='width: 100%;'>
                        <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
                            <span class='navbar-toggler-icon'></span>
                        </button>

                        <div class='collapse navbar-collapse' id='navbarSupportedContent'>
                            <ul class='navbar-nav mr-auto'>
                                <li class='nav-item'>
                                    <a class='nav-link' href='/home.php'>Intranet</a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='reservas.php'>Reservas</a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='reservar.php'>Reservar</a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='gerenciar.php'>Gerenciar</a>
                                </li>
                            </ul>
    ");                           



echo("                      
    <span style='color:white'>".$_SESSION['first_name']."</span>
        &nbsp; <!-- Espaço entre o nome do usuário e o relógio -->
        <span style='color:white'>
            <i class='fas fa-clock'></i> <!-- Ícone de relógio -->
            <span id='session-expiry-timer'value=". htmlspecialchars($userData['token_expires_in_seconds'])."></span>
        </span>
        <input type='hidden' id='usuario_codigo_hidden' value='".$_SESSION['logged_user_id']."'>
        <a class='nav-link logout-iel-azul bg-iel-vermelho' href='../../encerra.php'>Sair</a>
        </div>
    </div>
    </nav>
<br/>");
?>
<style>

header {
    color: white;
}


</style>
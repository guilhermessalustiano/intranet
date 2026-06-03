<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

include __DIR__ . ('/../comum/funcoes.php');

function enviarEmail($destinatario, $nomeDestinatario, $assunto, $mensagem){

    $mail = new PHPMailer();

    try{
        
        $mail->IsSMTP();
        $mail->Host = '';
        $mail->SMTPAuth = true; // Caso o servidor SMTP precise de autenticação
        $mail->Username = ''; // Usuário ou E-mail para autenticação no SMTP
        $mail->Password = ''; // Senha do E-mail
        $mail->Port = '';
        $mail->SMTPSecure = '';

        $mail->From = '';
        $mail->FromName = '';
        $mail->AddAddress($destinatario, $nomeDestinatario); 

        $mail->IsHTML(false); // Enviar como HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $assunto; // Define o Assunto
        $mail->Body = $mensagem; // Corpo da mensagem

        return $mail->send();

    } catch (Exception $e){
        error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
        return false;        
    }
}

?>
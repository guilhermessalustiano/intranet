<?php

require('connect.php');

session_start();

try {

    // Recebe os dados da requisição AJAX
    $id_agenda = $_POST['id_agenda'];
    $is_visible = $_POST['is_visible'];
    $id_usuario = $_SESSION['logged_user_id'];

    // Prepara a consulta SQL para atualizar o campo is_visible
    $sql = "UPDATE mas_agenda_visualizacao_usuario 
    SET is_visible = :is_visible WHERE id_agenda = :id_agenda AND  id_usuario = :id_usuario  ";

    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':is_visible', $is_visible, PDO::PARAM_INT);
    $stmt->bindParam(':id_agenda', $id_agenda, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    $stmt->execute();

} catch(PDOException $e) {
    echo "Erro ao conectar ou executar: " . $e->getMessage();
}

// Fecha a conexão (opcional, PDO fecha automaticamente ao final do script)
$conn = null;
?>





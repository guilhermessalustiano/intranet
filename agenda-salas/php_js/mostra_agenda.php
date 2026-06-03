<?php
header('Content-Type: application/json');
require('connect.php');

try {
    // Tentar conectar ao banco de dados
    $pdo = new PDO($dsn, $user, $pass);
    // Configurar o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar a consulta
    $stmt = $pdo->prepare("SELECT id, nome, backgroundColor, descricao FROM mas_agendas");

    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar eventos em formato JSON
    echo json_encode($events);
} catch (PDOException $e) {
    // Capturar e retornar o erro
    $error = array("error" => $e->getMessage());
    echo json_encode($error);
}

?>

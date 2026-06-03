<?php
header('Content-Type: application/json');
require('../../connect.php');

try {
    // Tentar conectar ao banco de dados
    $pdo = new PDO($dsn, $user, $pass);
    // Configurar o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Preparar a consulta
    $stmt = $pdo->prepare("SELECT codigo, tipopessoa, nome FROM pessoa WHERE tipopessoa = 'Docente' or tipopessoa = 'Funcionario'");

    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar eventos em formato JSON
    echo json_encode($dados);
} catch (PDOException $e) {
    // Capturar e retornar o erro
    $error = array("error" => $e->getMessage());
    echo json_encode($error);
}

?>

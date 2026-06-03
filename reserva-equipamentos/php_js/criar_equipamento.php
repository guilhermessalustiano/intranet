<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');

require ('../../connect.php');

$data = json_decode(file_get_contents('php://input'), true);

$data['emprestado'] = 0; // Definindo emprestado como 0 (não emprestado) por padrão

try {

    $stmt = $pdo->prepare("INSERT INTO mre_equipamento (patrimonio, nome, descricao, ativo, emprestado, obs) VALUES (:patrimonio, :nome, :descricao, :ativo, :emprestado, :obs)");
    $stmt->bindParam(':patrimonio', $data['patrimonio'], PDO::PARAM_STR);
    $stmt->bindParam(':nome', $data['nome'], PDO::PARAM_STR);
    $stmt->bindParam(':descricao', $data['descricao'], PDO::PARAM_STR);
    $stmt->bindParam(':ativo', $data['ativo'], PDO::PARAM_INT);
    $stmt->bindParam(':emprestado', $data['emprestado'], PDO::PARAM_INT);
    $stmt->bindParam(':obs', $data['obs'], PDO::PARAM_STR);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Equipamento criado com sucesso!']);

} 
catch (PDOException $e) {
 if ($e->errorInfo[1] == 1062) {
        echo json_encode([
            'success' => false,
            'error' => 'Este patrimônio já existe no sistema.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Procure o Administrador. Erro: ' . $e->getMessage()
        ]);
    }

}

?>
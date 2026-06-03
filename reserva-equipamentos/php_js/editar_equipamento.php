<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');

require ('../../connect.php');

$data = json_decode(file_get_contents('php://input'), true);




try {
    $stmt = $pdo->prepare("UPDATE mre_equipamento SET nome = :nome, patrimonio = :patrimonio, descricao = :descricao, ativo = :ativo, obs = :obs WHERE id = :id");
    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
    $stmt->bindParam(':nome', $data['nome'], PDO::PARAM_STR);
    $stmt->bindParam(':patrimonio', $data['patrimonio'], PDO::PARAM_STR);
    $stmt->bindParam(':descricao', $data['descricao'], PDO::PARAM_STR);
    $stmt->bindParam(':ativo', $data['ativo'], PDO::PARAM_STR);
    $stmt->bindParam(':obs', $data['obs'], PDO::PARAM_STR);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Equipamento editado com sucesso!']);


} catch (PDOException $e) {
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

    // echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
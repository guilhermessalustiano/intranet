<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');

require ('../../connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {

    $stmt = $pdo->prepare("UPDATE mre_emprestimos SET (nome_aluno = :nome_aluno, telefone = :telefone, email = :email, id_equipamento = :id_equipamento, quantidade = :quantidade, dt_inicio = :dt_inicio, dt_fim = :dt_fim)");
    $stmt->bindParam(':nome_aluno', $data['nomeAluno'], PDO::PARAM_STR);
    $stmt->bindParam(':telefone', $data['telefone'], PDO::PARAM_INT);
    $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
    $stmt->bindParam(':id_equipamento', $data['id_equipamento'], PDO::PARAM_INT);
    $stmt->bindParam(':quantidade', $data['quantidade'], PDO::PARAM_INT);
    $stmt->bindParam(':dt_inicio', $data['dt_inicio'], PDO::PARAM_STR);
    $stmt->bindParam(':dt_fim', $data['dt_fim'], PDO::PARAM_STR);

    
    if ($stmt->execute()){
        echo json_encode(['success' => true, 'message' => 'Empréstimo editado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao editar empréstimo!']);
    }
               
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');

require ('../../connect.php');

$id = json_decode(file_get_contents('php://input'), true);

try {
    $pdo->beginTransaction();

    
    $stmt_equipamento_reserva = $pdo->prepare("DELETE FROM mre_emprestimo_equipamento WHERE id_emprestimo = :id_emprestimo");
    $stmt_equipamento_reserva->bindParam(':id_emprestimo', $id, PDO::PARAM_INT);
    $stmt_equipamento_reserva->execute();
    
    $stmt_emprestimo = $pdo->prepare("DELETE FROM mre_emprestimo WHERE id = :id_emprestimo");
    $stmt_emprestimo->bindParam(':id_emprestimo', $id, PDO::PARAM_INT);
    $stmt_emprestimo->execute();
    
    
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Empréstimo excluído com sucesso!']);
    
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}



?>
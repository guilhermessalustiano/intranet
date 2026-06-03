<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');

require ('../../connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare("DELETE FROM pessoa WHERE codigo = :id");
    $stmt->execute([':id' => $data['id']]);
    echo json_encode(['success' => true, 'message' => 'Pessoa excluída com sucesso!']);
               
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
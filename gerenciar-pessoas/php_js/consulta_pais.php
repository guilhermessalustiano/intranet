<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json; charset=utf-8');

require ('../../connect.php');

$stmt = $pdo->prepare("
    SELECT codigo, pais 
    FROM pais
");

$stmt->execute();

$paises = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($paises, JSON_UNESCAPED_UNICODE);

?>
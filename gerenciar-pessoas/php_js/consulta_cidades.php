<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');

require ('../../connect.php');

$data = json_decode(file_get_contents('php://input'), true);

  $uf = $_GET["uf"];

$stmt = $pdo->prepare("
    SELECT nome 
    FROM cidades 
    WHERE id_estado = :uf
");

  $stmt->bindParam(':uf', $uf, PDO::PARAM_STR);
  $stmt->execute();
  // Busca todas as cidades como array associativo
  $cidades = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode( $cidades ); 




?>




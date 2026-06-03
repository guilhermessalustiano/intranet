<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
require ('../../vendor/autoload.php');
require('../../connect.php');

checkKeycloakAuth();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {

        // Atualiza o cadastro
        $stmt = $pdo->prepare("UPDATE pessoa SET tipopessoa = :tipo_pessoa, nome = :nome_pessoa, email = :email_pessoa, matricula = :matricula_pessoa, endereco = :endereco_pessoa, 
        telefone = :telefone_pessoa, cep = :cep_pessoa, cidade = :municipio, estado = :estado_sigla, pais = :nacionalidade, cpf = :cpf_pessoa, usuario = :user, isAdmin = :administrador
        WHERE codigo = :codigo_pessoa");

        $stmt->bindParam(':codigo_pessoa', $data['codigo_pessoa'], PDO::PARAM_INT);
        $stmt->bindParam(':tipo_pessoa', $data['tipo_pessoa']);
        $stmt->bindParam(':nome_pessoa', $data['nome_pessoa']);
        $stmt->bindParam(':email_pessoa', $data['email_pessoa']);
        $stmt->bindParam(':matricula_pessoa', $data['matricula_pessoa']);
        $stmt->bindParam(':endereco_pessoa', $data['endereco_pessoa']);
        $stmt->bindParam(':telefone_pessoa', $data['telefone_pessoa']);
        $stmt->bindParam(':cep_pessoa', $data['cep_pessoa']);
        $stmt->bindParam(':municipio', $data['municipio']);
        $stmt->bindParam(':estado_sigla', $data['estado_sigla']);
        $stmt->bindParam(':nacionalidade', $data['nacionalidade']);
        $stmt->bindParam(':cpf_pessoa', $data['cpf_pessoa']);
        $stmt->bindParam(':user', $data['user']);
        $stmt->bindParam(':administrador', $data['administrador']);


        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Cadastro atualizado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar cadastro!']);
        }
   
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>

<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
include('verifica_evento.php');
require ('../../vendor/autoload.php');
require('connect.php');

checkKeycloakAuth();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $ol=verifica_sobreposicao_eventos($data, "edit");

    if ($ol==0) { //sem overlap

        // Atualiza o evento
        $stmt = $pdo->prepare("UPDATE mas_eventos SET title = :title, start = :start, end = :end, id_agenda = :id_agenda WHERE id = :id");
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':title', $data['eventName']);
        $stmt->bindParam(':start', $data['startDate']);
        $stmt->bindParam(':end', $data['endDate']);
        $stmt->bindParam(':id_agenda', $data['id_agenda']);

        if ($stmt->execute()) {

            $event_id = $data['id'];

            // Limpa os recursos antigos e associa os novos recursos ao evento
            $stmt_limpar = $pdo->prepare("DELETE FROM mas_evento_recurso WHERE id_evento = :id_evento");
            $stmt_limpar->bindParam(':id_evento', $event_id, PDO::PARAM_INT);
            $stmt_limpar->execute();

            foreach ($data['id_recurso'] as $id_recurso) {
                $stmt_recurso = $pdo->prepare("INSERT INTO mas_evento_recurso (id_evento, id_recurso) VALUES (:id_evento, :id_recurso)");
                $stmt_recurso->bindParam(':id_evento', $event_id, PDO::PARAM_INT);
                $stmt_recurso->bindParam(':id_recurso', $id_recurso, PDO::PARAM_INT);
                $stmt_recurso->execute();
            }

            echo json_encode(['success' => true, 'message' => 'Evento editado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao editar o evento!']);
        }

    }
    else { //erro de overlap
        if ($ol==1) echo json_encode(['success' => false, 'message' => 'Evento em conflito!']);
        else {echo json_encode(['success' => false, 'message' => 'Recurso em conflito!']);}
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>

<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
require ('connect.php');
include('verifica_evento.php');
require ('../../vendor/autoload.php');

checkKeycloakAuth();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

    try {
        $log = new Logger('agenda_log');
        $log->pushHandler(new StreamHandler(__DIR__. '/log_agenda.txt', Logger::INFO));

            // Converte os timestamps de ISO 8601 para o formato MySQL
            $start = (new DateTime($data['start']))->format('Y-m-d H:i:s');
            $end = $data['end'] ? (new DateTime($data['end']))->format('Y-m-d H:i:s') : null;

                $stmt = $pdo->prepare("UPDATE mas_eventos SET start = :start, end = :end WHERE id = :id AND id_agenda = :id_agenda");
                $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
                $stmt->bindParam(':start', $start, PDO::PARAM_STR);
                $stmt->bindParam(':end', $end, PDO::PARAM_STR);
                $stmt->bindParam(':id_agenda', $data['id_agenda'], PDO::PARAM_INT);
                $stmt->execute();

                    $log->info('Evento atualizado', [
                        'id_usuario' => $data['owner_id'],
                        'id_evento' => $data['id'],
                        'inicio' => $data['start'],
                        'fim' => $data['end'],
                        'id_agenda' => $data['id_agenda']
                    ]);

                    echo json_encode(['success' => true, 'message' => 'Evento atualizado com sucesso']);
                
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar evento' .$e->getMessage()]);
    }

?>

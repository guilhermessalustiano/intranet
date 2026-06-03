<?php

include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';

checkKeycloakAuth();

include('verifica_evento.php');
require ('../../vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

header('Content-Type: application/json');

require ('connect.php');

    $data = json_decode(file_get_contents('php://input'), true);

    // var_dump($data);
try {

    $log = new Logger('agenda_log');
    $log->pushHandler(new StreamHandler(__DIR__. '/log_agenda.txt', Logger::INFO));


        $log->info('Evento Criado', [
        'usuario_id' => $data['logged_user_id'],
        'id_evento' => $data['id'],
        'startDate' => $data['startDate'],
        'endDate' => $data['endDate'],
        'id_agenda' => $data['id_agenda'],
        'rrule' => $data['rrule'] ?? 'none',
        'duration' => $data['duration'] ?? 'none'        
    ]);

    $ol=verifica_sobreposicao_eventos($data);
    if ($ol==0) { //sem overlap

        $stmt = $pdo->prepare("INSERT INTO mas_eventos (title, start, end, id_agenda, id_usuario, duration, rrule, rrule_dtstart, rrule_until) VALUES (:title, :start, :end, :id_agenda, :id_usuario, :duration, :rrule, :rrule_dtstart, :rrule_until)");
        $stmt->bindParam(':title', $data['eventName'], PDO::PARAM_STR);
        $stmt->bindParam(':start', $data['startDate'], PDO::PARAM_STR);
        $stmt->bindParam(':end', $data['endDate'], PDO::PARAM_STR);
        $stmt->bindParam(':id_agenda', $data['id_agenda'], PDO::PARAM_STR);
        $stmt->bindParam(':id_usuario', $_SESSION['logged_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':duration', $data['duration'], PDO::PARAM_STR);
        $stmt->bindParam(':rrule', $data['rrule'], PDO::PARAM_STR);
        $stmt->bindParam(':rrule_dtstart', $data['rrule_dtstart'], PDO::PARAM_STR);
        $stmt->bindParam(':rrule_until', $data['rrule_until'], PDO::PARAM_STR);
        

        if ($stmt->execute()) {

            $event_id = $pdo->lastInsertId(); // Obtém o ID do evento recém-criado

            if (!empty($data['id_recurso'])) {
                foreach ($data['id_recurso'] as $id_recurso) {
                    $stmt_recurso = $pdo->prepare("INSERT INTO mas_evento_recurso (id_evento, id_recurso) VALUES (:id_evento, :id_recurso)");
                    $stmt_recurso->bindParam(':id_evento', $event_id, PDO::PARAM_INT);
                    $stmt_recurso->bindParam(':id_recurso', $id_recurso, PDO::PARAM_INT);
                    if (!$stmt_recurso->execute()) {
                        echo json_encode(['success' => false, 'message' => 'Erro ao associar recursos ao evento']);
                        return;
                    }
                }
            }
 
            echo json_encode(['success' => true, 'message' => 'Evento criado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao criar o evento!']);

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

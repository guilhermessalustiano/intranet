<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
include('verifica_evento.php');
require ('../../vendor/autoload.php');
require('connect.php');

checkKeycloakAuth();

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

header('Content-Type: application/json');


$data = json_decode(file_get_contents('php://input'), true);


if ($_SESSION['logged_user_id'] == $data['owner_id']) { //se dono do evento, pode excluir 

    if ($data['tipo_remocao'] === "este_evento") { // este evento -> add em EXDATE

        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $log = new Logger('agenda_log');
            $log->pushHandler(new StreamHandler(__DIR__. '/log_agenda.txt', Logger::INFO));


            // Verifica campo exdate (apend ou primeiro EXDATE)
            $stmt = $pdo->prepare('
                SELECT exdate FROM mas_eventos WHERE id = :id
            ');
            $stmt->execute([':id' => $data['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($result['exdate'])) {
                $exdateAtual = $result['exdate'];
                $data['ex_date'] = $novoExdate = $exdateAtual . ',' . $data['ex_date'];
            }


            $stmt = $pdo->prepare('
            UPDATE mas_eventos SET exdate = :ex_date WHERE id = :id
            ');
            $stmt->execute([
                ':id' => $data['id'],
                ':ex_date' => $data['ex_date'],
            ]);
    
            $log->info('Evento removido', [
                'usuario_id' => $data['owner_id'],
                'id_evento' => $data['id']
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }

    } else {
            try {
                $pdo = new PDO($dsn, $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                $log = new Logger('agenda_log');
                $log->pushHandler(new StreamHandler(__DIR__. '/log_agenda.txt', Logger::INFO));
        
                // Testa se a conexão é válida
                $pdo->query("SELECT 1");
        
                $stmt = $pdo->prepare('
                DELETE FROM mas_eventos WHERE id = :id
                ');
                $stmt->execute([
                    ':id' => $data['id'],
                ]);
        
                $log->info('Evento removido', [
                    'usuario_id' => $data['owner_id'],
                    'id_evento' => $data['id']
                ]);
                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
            }
    }
    }
    else{ //senao erro
        echo json_encode(['success' => false, 'error' => 'Não é possível excluir eventos de outros usuários!']);
    }

?>
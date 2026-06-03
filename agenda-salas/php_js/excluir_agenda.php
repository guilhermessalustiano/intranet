<?php
if(!isset($_SESSION))session_start();

require ('../../vendor/autoload.php');
require('connect.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $log = new Logger('agenda_log');
    $log->pushHandler(new StreamHandler(__DIR__. '/log_agenda.txt', Logger::INFO));


    // Deleta a agenda com base no ID fornecido
    $stmt = $pdo->prepare('DELETE FROM mas_agendas WHERE id = :id');
    $stmt2 = $pdo->prepare('DELETE FROM mas_agenda_visualizacao_usuario WHERE id_agenda = :id');
    $stmt->execute([':id' => $data['id']]);
    $stmt2->execute([':id' => $data['id_agenda']]);
    $log->info('Agenda excluída', [
        'id_usuario' => $_SESSION['codigo'],
        'id_agenda' => $data['id']
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

<?php
require ('../../connect.php');
if(!isset($_SESSION))session_start();

require ('../../../vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
header('Content-Type: application/json');


$data = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $log = new Logger('agenda_log');
    $log->pushHandler(new StreamHandler(__DIR__. '/../log_agenda.txt', Logger::INFO));


    // Deleta o recurso com base no ID fornecido
    $stmt = $pdo->prepare('DELETE FROM mas_recursos WHERE id = :id');
    $stmt->execute([':id' => $data['id']]);
    $log->info('Recurso excluído', [
        'id_usuario' => $_SESSION['codigo'],
        'id_recurso' => $data['id']
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

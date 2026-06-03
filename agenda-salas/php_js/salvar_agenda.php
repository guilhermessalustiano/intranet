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

    $stmt = $pdo->prepare('UPDATE mas_agendas SET nome = :nome, descricao = :descricao, backgroundColor=:backgroundColor  WHERE id = :id');
    $stmt->execute([
        ':nome' => $data['nome_agenda'],
        ':descricao' => $data['desc_agenda'],
        ':backgroundColor' => $data['cor_agenda'],
        ':id' => $data['id']
    ]);

    $log->info('Agenda editada', [
        'id_usuario' => $_SESSION['codigo'],
        'id_agenda' => $data['id'],
        'nome' => $data['nome_agenda']
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
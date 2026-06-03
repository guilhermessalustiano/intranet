<?php
if(!isset($_SESSION))session_start();
require ('../../../vendor/autoload.php');
require ('../../connect.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
header('Content-Type: application/json');


$data = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $log = new Logger('agenda_log');
    $log->pushHandler(new StreamHandler(__DIR__. '/../log_agenda.txt', Logger::INFO));

    $stmt = $pdo->prepare('UPDATE mas_recursos SET nome = :nome, descricao = :descricao WHERE id = :id');
    $stmt->execute([
        ':nome' => $data['nome_recurso'],
        ':descricao' => $data['desc_recurso'],
        ':id' => $data['id']
    ]);

    $log->info('Recurso editado', [
        'id_usuario' => $_SESSION['codigo'],
        'id_recurso' => $data['id'],
        'nome' => $data['nome_recurso']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
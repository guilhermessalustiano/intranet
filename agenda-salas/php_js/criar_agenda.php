<?php
if(!isset($_SESSION))session_start();

require ('../../vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

header('Content-Type: application/json');

require ('../../vendor/autoload.php');

// conexao
require ('connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {

    $log = new Logger('agenda_log');
    $log->pushHandler(new StreamHandler(__DIR__. '/log_agenda.txt', Logger::INFO));

    // Supondo que $pdo seja a instância do PDO e $stmt seja a declaração preparada
    $stmt = $pdo->prepare('INSERT INTO mas_agendas (nome, backgroundColor, descricao) VALUES (:nome, :backgroundColor, :descricao)');
    $stmt->execute([
        ':nome' => $data['nome_agenda'],
        ':backgroundColor' => $data['cor_agenda'],
        ':descricao' => $data['desc_agenda']
    ]);



    // Recuperar o id da agenda recém-inserida
    $id_agenda = $pdo->lastInsertId();


    $stmt2 = $pdo->prepare('SELECT codigo FROM msi_usuario');
    $stmt2->execute();
    $resultados = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultados as $row) {
        //  
        // Inserir na tabela mas_agenda_visualizacao_usuario
        $stmt3 = $pdo->prepare('INSERT INTO mas_agenda_visualizacao_usuario (id_usuario, id_agenda, is_visible) VALUES (:id_usuario, :id_agenda, :is_visible)');
        $stmt3->execute([
            ':id_usuario' => $row['codigo'],
            ':id_agenda' => $id_agenda,
            ':is_visible' => 1
        ]);


    }


    $log->info('Agenda criada', [
        'id_usuario' => $row['codigo'],
        'id_agenda' => $id_agenda,
        'nome' => $data['nome_agenda']
    ]);


    echo json_encode(['success' => true]);
} catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>

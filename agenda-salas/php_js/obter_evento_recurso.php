<?php
header('Content-Type: application/json');
require('connect.php');

$eventoId = $_GET['evento_id']; // Receber o ID do evento via GET

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os recursos associados ao evento
    $stmt = $pdo->prepare("SELECT r.id, r.nome 
        FROM mas_recursos r
        JOIN mas_evento_recurso er ON r.id = er.id_recurso
        WHERE er.id_evento = :evento_id");
    $stmt->bindParam(':evento_id', $eventoId, PDO::PARAM_INT);
    $stmt->execute();
    $recursosSelecionados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($recursosSelecionados);


} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

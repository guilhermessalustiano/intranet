<?php
header('Content-Type: application/json');
require('connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    error.log("código de obter_salas.php executando ");

    $stmt = $pdo->prepare("SELECT id, nome FROM mas_agendas"); 

    $stmt->execute();
    $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error.log("Salas obtifas: ". json_encode($salas));

    echo json_encode($salas);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

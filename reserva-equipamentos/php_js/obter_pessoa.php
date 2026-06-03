<?php
header('Content-Type: application/json');
require('../../connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT codigo, nome FROM pessoa"); 

    $stmt->execute();
    $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error.log("Pessoas obtidas: ". json_encode($pessoas));

    echo json_encode($pessoas);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

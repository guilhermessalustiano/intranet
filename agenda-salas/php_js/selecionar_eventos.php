<?php
require('connect.php');

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['agendas'])) {
        $placeholders = implode(',', array_fill(0, count($data['agendas']), '?'));
        $stmt = $pdo->prepare("SELECT id, title, start, end, id_agenda FROM mas_eventos WHERE id_agenda IN ($placeholders)");
        $stmt->execute($data['agendas']);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $events = [];
    }

    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode([]);
}

<?php

require('php_js/connect.php');

try {
    // Consulta para obter todas as agendas
    $stmt = $pdo->prepare("SELECT id, nome, backgroundColor FROM mas_agendas");
    $stmt->execute();
    $agendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para verificar quais agendas são visíveis para o usuário
    $stmt_visibility = $pdo->prepare("SELECT id_agenda FROM mas_agenda_visualizacao_usuario WHERE id_usuario = :id_usuario AND is_visible = 1");
    $stmt_visibility->bindParam(':id_usuario', $_SESSION['logged_user_id'], PDO::PARAM_INT);
    $stmt_visibility->execute();
    $visibilidade_agendas = $stmt_visibility->fetchAll(PDO::FETCH_COLUMN, 0);

    foreach ($agendas as $agenda) {
        $checked = in_array($agenda['id'], $visibilidade_agendas) ? 'checked' : '';
        $color = htmlspecialchars($agenda['backgroundColor']); // Sanitização da cor

        echo '<div class="agenda-item">';
        echo '<input type="checkbox" class="agenda-checkbox" id="agenda_' . $agenda['id'] . '" name="agenda[]" value="' . $agenda['id'] . '" ' . $checked . ' style="--agenda-color: ' . $color . ';">';
        echo '<label for="agenda_' . $agenda['id'] . '">' . htmlspecialchars($agenda['nome']) . '</label>';
        echo '</div>';
    }
} catch (PDOException $e) {
    echo "Problema de conexão, procure o administrador";
}
?>
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');
require('../../connect.php');

// Recebendo os dados do POST (multipart/form-data)
$id_emprestimo = $_POST['id'] ?? null;
$obs_devol = $_POST['obs_devol'] ?? null;
$arquivo = $_FILES['documento'] ?? null;

$maxFileSize = 5 * 1024 * 1024; 

if ($arquivo['size'] > $maxFileSize) {
    echo json_encode([
        'success' => false,
        'error' => 'Arquivo maior que o limite permitido de 5 MB.'
    ]);
    exit;
}

// Se o PHP não processou o arquivo por limite de tempo
if ($_FILES['documento']['error'] === UPLOAD_ERR_OK) {
    if (!is_uploaded_file($arquivo['tmp_name'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Tempo de upload excedido ou upload interrompido.'
        ]);
        exit;
    }
}

// Validação robusta do arquivo
$allowedExtensions = ['pdf'];
$allowedMimeTypes = ['application/pdf', 'application/x-pdf'];

// Verifica extensão do arquivo
$fileExtension = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
if (!in_array($fileExtension, $allowedExtensions)) {
    echo json_encode([
        'success' => false,
        'error' => 'Apenas arquivos com extensão .pdf são permitidos!'
    ]);
    exit;
}

// Verifica se ID e arquivo foram enviados
if (!$id_emprestimo || !isset($_FILES['documento'])) {
    echo json_encode([
        'success' => false,
        'error' => 'ID ou arquivo não enviados',
        'upload_error' => $_FILES['documento']['error'] ?? 'Nenhum arquivo enviado'
    ]);
    exit;
}




// Verifica o tipo MIME real do arquivo
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $arquivo['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedMimeTypes)) {
    echo json_encode([
        'success' => false,
        'error' => 'O arquivo não é um PDF válido! (Tipo detectado: ' . $mimeType . ')'
    ]);
    exit;
}

// Pasta destino
$pasta = $_SERVER['DOCUMENT_ROOT'] . "/reserva-equipamentos/pdfs/devolucoes/";
if (!is_dir($pasta)) {
    mkdir($pasta, 0775, true);
}

$caminho = $pasta . $id_emprestimo . ".pdf";

// Move o arquivo para o destino
if (!move_uploaded_file($arquivo['tmp_name'], $caminho)) {
    echo json_encode(['success' => false, 'error' => 'Falha ao salvar o arquivo']);
    exit;
}

// Data de devolução
$dt_devolucao = date('Y-m-d H:i:s'); 

try {
    $pdo->beginTransaction();

    // 1. Pegar todos os equipamentos desse empréstimo
    $stmt = $pdo->prepare("
        SELECT id_equipamento 
        FROM mre_emprestimo_equipamento 
        WHERE id_emprestimo = :id_emprestimo
    ");
    $stmt->bindParam(':id_emprestimo', $id_emprestimo, PDO::PARAM_INT);
    $stmt->execute();
    $equipamentos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 2. Atualizar status para emprestado=0 (livre)
    $stmtUpdate = $pdo->prepare("
        UPDATE mre_equipamento 
        SET emprestado = 0 
        WHERE id = :id_equipamento
    ");
    foreach ($equipamentos as $equip_id) {
        $stmtUpdate->bindParam(':id_equipamento', $equip_id, PDO::PARAM_INT);
        $stmtUpdate->execute();
    }

    // 3. Atualizar a data de devolução no empréstimo
    $stmtDevolucao = $pdo->prepare("
        UPDATE mre_emprestimo 
        SET dt_devol = :dt_devol, obs_devol = :obs_devol
        WHERE id = :id_emprestimo
    ");
    $stmtDevolucao->bindParam(':dt_devol', $dt_devolucao, PDO::PARAM_STR);
    $stmtDevolucao->bindParam(':id_emprestimo', $id_emprestimo, PDO::PARAM_INT);
    $stmtDevolucao->bindParam(':obs_devol', $obs_devol, PDO::PARAM_STR);
    $stmtDevolucao->execute();

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Empréstimo devolvido com sucesso!'
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    // Remove o arquivo PDF caso tenha sido salvo antes do erro no banco
    if (file_exists($caminho)) {
        unlink($caminho);
    }
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao devolver empréstimo: ' . $e->getMessage()
    ]);
}
?>
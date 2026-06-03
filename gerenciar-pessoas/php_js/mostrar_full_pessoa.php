<?php
header('Content-Type: application/json');
require('../../connect.php');

try {
    // Tentar conectar ao banco de dados
    $pdo = new PDO($dsn, $user, $pass);
    // Configurar o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_pessoa = $_POST['id_pessoa'] ?? null;

    // Preparar a consulta
    $stmt = $pdo->prepare("SELECT p.codigo, p.tipopessoa, p.nome, p.email, p.matricula, p.endereco, p.telefone, p.cep, p.cidade, p.estado, p.pais, p.cpf, p.datacadastro, 
                            p.usuario, p.isAdmin , p.vinculo, pv.nome AS nome_vinculo
                            FROM pessoa p LEFT JOIN pessoa pv ON p.vinculo = pv.codigo
                            WHERE p.codigo = :id_pessoa");

    $stmt->execute(['id_pessoa' => $id_pessoa]);
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar eventos em formato JSON
    echo json_encode($dados[0]);
} catch (PDOException $e) {
    // Capturar e retornar o erro
    $error = array("error" => $e->getMessage());
    echo json_encode($error);
}

?>

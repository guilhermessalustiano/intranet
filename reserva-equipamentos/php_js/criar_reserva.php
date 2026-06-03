<?php
require_once '../../vendor/autoload.php';
use Dompdf\Dompdf;

include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');
require('../../connect.php');

$data = json_decode(file_get_contents('php://input'), true);

$codigo_pessoa = $data['codigo_pessoa'];
$equipamentos = $data['ids_equipamentos'];
$obs_emp = $data['obs_emp'] ?? null;
$dt_inicio = date('Y-m-d H:i:s'); 
$dt_fim = $data['dtfim'];

try {
    $pdo->beginTransaction();

    // insere em mre_emprestimo
    $stmt = $pdo->prepare("
        INSERT INTO mre_emprestimo (pessoa, dt_inicio, dt_fim, obs_emp)
        VALUES (:pessoa, :dt_inicio, :dt_fim, :obs_emp)
    ");

    $stmt->bindParam(':pessoa', $codigo_pessoa, PDO::PARAM_INT);
    $stmt->bindParam(':dt_inicio', $dt_inicio, PDO::PARAM_STR);
    $stmt->bindParam(':dt_fim', $dt_fim, PDO::PARAM_STR);
    $stmt->bindParam(':obs_emp', $obs_emp, PDO::PARAM_STR);
    $stmt->execute();

    // insere em mre_emprestimo_equipamento
    $emprestimo_id = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare("
        INSERT INTO mre_emprestimo_equipamento(id_emprestimo, id_equipamento) VALUES (:id_emprestimo, :id_equipamento) 
    ");

       // Altera status do equipamento para emprestado=1
    $stmt3 = $pdo->prepare("
        UPDATE mre_equipamento SET emprestado = 1 WHERE id = :id_equipamento
    ");

    //
    foreach ($equipamentos as $equipamento_id) {
        $stmt2->bindParam(':id_emprestimo', $emprestimo_id, PDO::PARAM_STR);
        $stmt2->bindParam(':id_equipamento', $equipamento_id, PDO::PARAM_STR);
        $stmt2->execute();
        $stmt3->bindParam(':id_equipamento', $equipamento_id, PDO::PARAM_STR);
        $stmt3->execute();
    }
        
// MONTAGEM DO PDF


$sql = "SELECT 
    p.nome AS pessoa_nome, 
    p.email, 
    p.telefone, 
    p.endereco, 
    p.cep, 
    p.cidade, 
    p.estado,
    p.matricula,
    p.cpf,
    p.vinculo,
    resp.nome AS responsavel_nome, -- Nome do responsável, se houver vínculo
    e.nome AS equipamento_nome, 
    e.patrimonio AS equipamento_patrimonio 
FROM mre_emprestimo em 
INNER JOIN pessoa p 
    ON em.pessoa = p.codigo 
LEFT JOIN pessoa resp 
    ON resp.codigo = p.vinculo
INNER JOIN mre_emprestimo_equipamento ee 
    ON ee.id_emprestimo = em.id 
INNER JOIN mre_equipamento e 
    ON e.id = ee.id_equipamento 
WHERE em.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $emprestimo_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pessoa = [];
$equipamentos = [];

foreach ($rows as $row) {
    // Pega os dados da pessoa apenas uma vez
    if (empty($pessoa)) {
        $pessoa = [
            'nome' => $row['pessoa_nome'],
            'email' => $row['email'],
            'matricula' => $row['matricula'],
            'cpf' => $row['cpf'],
            'telefone' => $row['telefone'],
            'endereco' => $row['endereco'],
            'cep' => $row['cep'],
            'cidade' => $row['cidade'],
            'estado' => $row['estado'],
            'vinculo' => $row['vinculo'] ?? '',
            'responsavel_nome' => $row['responsavel_nome'] ?? '',
        ];
    }
    
    // Adiciona cada equipamento
    $equipamentos[] = [
        'nome' => $row['equipamento_nome'],
        'patrimonio' => $row['equipamento_patrimonio']
    ];
}


    $dompdf = new Dompdf();
    $dompdf->setPaper('A4', 'portrait'); // 'portrait' ou 'landscape'

    $data_devol_doc = date('d/m/Y', strtotime($dt_fim));
    $data_emp_doc = date('d/m/Y', strtotime($dt_inicio));

    // Monta lista de equipamentos
    $equipamentos_str = '';
    foreach ($equipamentos as $eq) {
        $equipamentos_str .= '<li>'.htmlspecialchars($eq['nome']) .
                            ' (pat. '.htmlspecialchars($eq['patrimonio']) . ')</li>';
    }
    $equipamentos_str = '<ol>' . $equipamentos_str . '</ol>';

    // Não exibir obs do empréstimo se vazia ou só espaços
    $obs_html = '';
    if (trim($obs_emp) !== '') {
        $obs_html = '<ul><b>Obs do empréstimo:</b> ' . htmlspecialchars($obs_emp) . '</ul>';
    }

    $assinatura_html = '';
    if (trim($pessoa['vinculo']) !== '') {
        $assinatura_html = '<div>_________________________________<br>Responsável: ' . htmlspecialchars($pessoa['responsavel_nome']) . ' </div>';
        $assinatura_html = $assinatura_html.'<div>_________________________________<br>Usuário: ' . htmlspecialchars($pessoa['nome']) . '</div>';
    } else {
        $assinatura_html = '<center><div>_________________________________<br>Usuário: ' . htmlspecialchars($pessoa['nome']) . '</div></center>';
    }

    $html = '
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 20px; }
        .dados { margin-bottom: 15px; }
        .dados p { margin: 3px 0; }
        .equipamentos { margin: 15px 0; }
        .assinaturas { margin-top: 40px; }
        .assinaturas div { display: inline-block; width: 45%; text-align: center; }
        .obs { font-size: 11px; margin-top: 20px; text-align: justify; }
    </style>

    <h3>TERMO DE AUTORIZAÇÃO DE USO – SOLICITAÇÃO DE EQUIPAMENTO</h3>

    <div class="dados">
        <p><b>Nome:</b> ' . htmlspecialchars($pessoa['nome']) . '</p>
        <p><b>Endereço:</b> ' . htmlspecialchars($pessoa['endereco']) . ' - ' . htmlspecialchars($pessoa['cidade']) . ', ' . htmlspecialchars($pessoa['estado']) . '- CEP: ' . htmlspecialchars($pessoa['cep']) . '</p>
        <p><b>RG/CPF (se externo ao Labjor):</b> ' . htmlspecialchars($pessoa['cpf']) . ' <b>Matrícula:</b>  ' . htmlspecialchars($pessoa['matricula']) . '</p>
        <p><b>Fone:</b> ' . htmlspecialchars($pessoa['telefone']) . ' &nbsp;&nbsp;&nbsp; 
        <b>E-mail:</b> ' . htmlspecialchars($pessoa['email']) . '</p>
    </div>

    <p>
        Declaro estar retirando o(s) equipamento(s) abaixo relacionado(s), de propriedade da UNICAMP,
        sob administração do Setor de Secretaria de Cursos - Labjor:
    </p>

    <div class="equipamentos">
        ' . $equipamentos_str . '
        ' . $obs_html . '
    </div>

    <p>
        Declaro, ainda, que o(s) equipamento(s) ora retirado(s) encontra(m)-se em perfeito estado de funcionamento,
        devendo ser entregue(s) no dia <b>' . $data_devol_doc . '</b> nas mesmas condições,
        assumindo a responsabilidade pelo equipamento, nos termos previstos pelas normas em vigor.
    </p>

    <p>
        Cidade Universitária Zeferino Vaz, ' . $data_emp_doc . '
    </p>

    <div class="assinaturas">
        '.$assinatura_html.'
    </div>

    <div class="obs">
        <b>Observações:</b><br>
        A saída de bens móveis da Universidade para uso por parte do corpo docente,
        discente e servidores técnicos e administrativos ou para empréstimo em favor de particular
        deve ser previamente anuída pela autoridade competente, através do Termo de Autorização de Uso,
        conforme Deliberação CONSU A-12/2013, de 06/08/2013. Por este Termo a pessoa autorizada a fazer uso
        externo do bem fica responsável pela guarda e conservação do mesmo, inclusive pelo ressarcimento à
        Universidade em caso de dano, sinistro, roubo ou furto, após apuração da ocorrência em processo de
        sindicância administrativa. Até ser registrada a baixa no formulário, o equipamento emprestado
        ficará sob a responsabilidade total e exclusiva do usuário.
    </div>

    <p style="margin-top: 30px;">
        Devolvido em: ____/____/______ &nbsp;&nbsp;&nbsp; Responsável pelo recebimento: __________________________
    </p>';

    $dompdf->loadHtml($html);
    $dompdf->render();
    $output = $dompdf->output();
    file_put_contents($_SERVER["DOCUMENT_ROOT"]."/reserva-equipamentos/pdfs/emprestimos/".$emprestimo_id.".pdf", $output);


    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => "Empréstimo criado com sucesso!"
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao inserir empréstimos: ' . $e->getMessage()
    ]);
}

?>
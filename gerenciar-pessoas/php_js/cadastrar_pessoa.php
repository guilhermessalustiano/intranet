<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/keycloak_api.php';
checkKeycloakAuth();

header('Content-Type: application/json');

require ('../../connect.php');

include('../funcoes.php');

$data = json_decode(file_get_contents('php://input'), true);

    try {

        $pdo->beginTransaction();

        // Insere em pessoa - TODOS
        $stmt_pessoa = $pdo->prepare("INSERT INTO pessoa (tipopessoa, nome, email, matricula, endereco, telefone, cep, cidade, estado, pais, cpf, datacadastro, usuario, 
        isDocente, isAdmin, isAluno, isFuncionario, isExterno, vinculo)
        VALUES (:tipo, :nome, :email, :matricula, :endereco, :telefone, :cep, :cidade, :estado, :pais, :cpf, :data_cadastro, :usuario, 
        :is_docente, :is_admin, :is_aluno, :is_funcionario, :is_externo, :vinculo)");
        $stmt_pessoa->bindParam(':tipo', $data['tipo'], PDO::PARAM_STR); 
        $stmt_pessoa->bindParam(':nome', $data['nome'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':matricula', $data['matricula'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':endereco', $data['endereco'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':telefone', $data['telefone'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':cep', $data['cep'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':cidade', $data['cidade'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam('estado', $data['estado'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':pais', $data['pais'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':cpf', $data['cpf'], PDO::PARAM_STR);
        $stmt_pessoa->bindParam(':data_cadastro', $data['data_cadastro'], PDO::PARAM_STR);         
        if (empty($data['usuario'])) { $stmt_pessoa->bindValue(':usuario', null, PDO::PARAM_NULL);} else {$stmt_pessoa->bindValue(':usuario', $data['usuario'], PDO::PARAM_STR);} // usuario NULL se não for informado
        $stmt_pessoa->bindParam(':is_docente', $data['is_docente'], PDO::PARAM_INT);
        $stmt_pessoa->bindParam(':is_admin', $data['is_admin'], PDO::PARAM_INT);
        $stmt_pessoa->bindParam(':is_aluno', $data['is_aluno'], PDO::PARAM_INT);
        $stmt_pessoa->bindParam(':is_funcionario', $data['is_funcionario'], PDO::PARAM_INT);
        $stmt_pessoa->bindParam(':is_externo', $data['is_externo'], PDO::PARAM_INT);
        $stmt_pessoa->bindParam(':vinculo', $data['vinculo'], PDO::PARAM_INT);
        $stmt_pessoa->execute();
        $pessoa_id = $pdo->lastInsertId();

        // docentes e func na tabela is_funcionario
        if ($data['acesso_sistema']) {  // msi_usuario

            $data['depto'] = 33;
            $data['situacao'] = 'Ativo';

            $stmt_func = $pdo->prepare("
                INSERT INTO funcionario (pessoa, depto, situacao, usuario)
                VALUES (:pessoa, :depto, :situacao, :usuario)");

            $stmt_func->bindParam(':pessoa', $pessoa_id, PDO::PARAM_INT);
            $stmt_func->bindParam(':depto', $data['depto'], PDO::PARAM_INT); 
            $stmt_func->bindParam(':situacao', $data['situacao'], PDO::PARAM_STR); 
            $stmt_func->bindParam(':usuario', $data['usuario'], PDO::PARAM_STR); 
            $stmt_func->execute();
            
            $stmt_usuario = $pdo->prepare("
                INSERT INTO msi_usuario (usuario, pessoa, isAdmin, isDocente)
                VALUES (:usuario, :pessoa, :isAdmin, :isDocente)");
            $stmt_usuario->bindValue(':usuario', $data['usuario'], PDO::PARAM_STR);
            $stmt_usuario->bindParam(':isAdmin', $data['is_admin'], PDO::PARAM_INT);
            $stmt_usuario->bindParam(':isDocente', $data['is_docente'], PDO::PARAM_INT);
            $stmt_usuario->bindParam(':pessoa', $pessoa_id, PDO::PARAM_INT);
            $stmt_usuario->execute();
            $id_msi_usuario = $pdo->lastInsertId();

            if ($data['is_docente'] == 1) {   //msi_usuario_sistema e tabela docente
            

                $data['cursoJC']='S';
                $stmt_docente = $pdo->prepare("
                    INSERT INTO docente (pessoa, usuario, cursoJC )
                    VALUES (:pessoa, :usuario, :cursoJC)");
                $stmt_docente->bindParam(':pessoa', $pessoa_id, PDO::PARAM_INT);
                $stmt_docente->bindValue(':usuario', $data['usuario'], PDO::PARAM_STR);
                $stmt_docente->bindValue(':cursoJC', $data['cursoJC'], PDO::PARAM_STR);
                $stmt_docente->execute();

                $data['sistema']=15;
                $stmt_usuario_sistema = $pdo->prepare("
                    INSERT INTO msi_usuario_sistema (pessoa, sistema)
                    VALUES (:pessoa, :sistema)");
                $stmt_usuario_sistema->bindParam(':pessoa', $pessoa_id, PDO::PARAM_INT);
                $stmt_usuario_sistema->bindParam(':sistema', $data['sistema'], PDO::PARAM_INT);
                $stmt_usuario_sistema->execute();                    
            }
        }

        if ($data['modulos'] != 0){
            $stmt_funcionario_modulo = $pdo->prepare('INSERT INTO modulo_usuario VALUES (:codigo_pessoa, :id_modulo)');
            foreach ($data['modulos'] as $modulo_id){
            $stmt_funcionario_modulo->bindParam(':codigo_pessoa', $pessoa_id, PDO::PARAM_INT);
            $stmt_funcionario_modulo->bindParam(':id_modulo', $modulo_id, PDO::PARAM_INT);
            $stmt_funcionario_modulo->execute();
            }

            if ((int)$modulo_id === 2){

                $stmt_obter_agendas = $pdo->prepare('SELECT id_agenda FROM mas_agendas');
                $stmt_obter_agendas->execute();
                $agendas = $stmt_obter_agendas->fetchAll(PDO::FETCH_COLUMN);

                $stmt_agenda_visualizacao = $pdo->prepare('INSERT INTO mas_agenda_visualizacao_usuario (id_usuario, id_agenda, is_visible) VALUES (:id_usuario, :id_agenda, :is_visible)');
                
                foreach ($agendas as $id_agenda) {
                    $stmt_agenda_visualizacao->execute([
                        ':id_usuario' => $id_msi_usuario,
                        ':id_agenda'  => $id_agenda,
                        ':is_visible' => 1
                    ]);
                }
            }

        }


        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Pessoa cadastrada com sucesso!']);

    } 
    catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == 23000) { // erro de constraint UNIQUE
            $msg = $e->getMessage();
            if (strpos($msg, "for key 'email'") !== false) {
                $erro = "E-mail já cadastrado.";
            } elseif (strpos($msg, "for key 'usuario'") !== false) {
                $erro = "Usuário já cadastrado.";
            } else {
                $erro = "Registro duplicado.";
            }

            echo json_encode([
                "success" => false,
                "error" => $erro
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "error" => "Erro inesperado: " . $e->getMessage()
            ]);
        }
    }

?>
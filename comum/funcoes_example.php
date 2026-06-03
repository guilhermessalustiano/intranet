<?php


# abre conexão com servidor MySQL
function AbreConexao() {
    $conexao = mysqli_connect('localhost','user','password', 'db');

    if (!$conexao) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    // mysqli_set_charset($conexao, 'utf-8');
    mysqli_options($conexao, MYSQLI_INIT_COMMAND, 'SET NAMES utf8mb4');

    mysqli_query($conexao, "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
    return $conexao;
}


function valida_login($usr, $pwd) {
    $conexao = AbreConexao();
    $stmt = mysqli_stmt_init($conexao);
    mysqli_stmt_prepare($stmt, "SELECT * FROM msi_usuario WHERE usuario=? AND senha=md5(?)");
    mysqli_stmt_bind_param($stmt, 'ss', $usr, $pwd);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_fetch($stmt);
}

function valida_sise($usr, $pwd) {
    $conexao = AbreConexao();
    $stmt = mysqli_stmt_init($conexao);
    mysqli_stmt_prepare($stmt, "SELECT * FROM msi_usuario WHERE usuario=? AND senha=md5(?)");
    mysqli_stmt_bind_param($stmt, 'ss', $usr, $pwd);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_fetch($stmt);
}


/** Busca as informações do usuário logado **/
function usuarioLogadoInfo($login) {
    $conexao = AbreConexao();
    $sql = "SELECT msi_usuario.codigo AS usuario_codigo, 
                    pessoa.codigo AS pessoa_codigo,
                    pessoa.nome AS nome,
                    pessoa.email AS email,
                    pessoa.tipopessoa AS tipopessoa,
                    pessoa.isAdmin AS isAdmin,
                    msi_usuario.usuario AS login
        FROM pessoa
        INNER JOIN msi_usuario ON msi_usuario.pessoa = pessoa.codigo
        WHERE msi_usuario.usuario = '$login'";


    


    $rs = mysqli_query($conexao, $sql) or die('Falha ao obter as informações do usuário logado: ' . mysqli_error($conexao));
    $retorno = mysqli_fetch_array($rs);

    // Fecha a conexão com o banco de dados
    mysqli_close($conexao);

    return $retorno;
}



/** Retorna a diferença de dias entre as datas informadas **/
function diferencaDiasEntreDatas($data1, $data2) {
    // converte as datas para o formato timestamp
    $d1 = strtotime(str_replace('/', '-', $data1));
    $d2 = strtotime(str_replace('/', '-', $data2));

    // verifica a diferença em segundos entre as duas datas e divide pelo número de segundos que um dia possui
    $dataFinal = ($d2 - $d1) /86400;

    return $dataFinal;
}



function checkUserExistsPDO($username) {
    // Credenciais de conexão com o banco de dados
    $host = 'localhost';
    $db = 'db';
    $user = 'user';
    $pass = 'password';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";

    // Conectar ao MySQL usando PDO
    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para verificar se o username existe
        $stmt = $pdo->prepare("SELECT codigo FROM msi_usuario WHERE usuario = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Verificar se a consulta retornou resultados
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user['codigo']; // Retorna o código (ID) do usuário
        } else {
            return 0; // Usuário não existe
        }

    } catch (PDOException $e) {
        echo "Erro ao se conectar ao banco de dados: " . $e->getMessage();
        return 0;
    }
}

function checkIsAdmin($username) {

    $username = $_SESSION['username'];
    // Credenciais de conexão com o banco de dados
    $host = 'localhost';
    $db = 'db';
    $user = 'user';
    $pass = 'password';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para verificar se o usuário é admin
        $stmt = $pdo->prepare("SELECT isAdmin FROM msi_usuario WHERE usuario = :username");
        $stmt->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retorna 1 se isAdmin = 1, senão 0
        if ($user && $user['isAdmin'] == 1) {
            return 1;
        } else {
            return 0;
        }

    } catch (PDOException $e) {
        echo "Erro ao se conectar ao banco de dados: " . $e->getMessage();
        return 0;
    }
}

////--------------------------verifica se docente administrador
function checkIsDocente($username) {

    // Credenciais de conexão com o banco de dados
    $host = 'localhost';
    $db = 'db';
    $user = 'user';
    $pass = 'password';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para verificar se o usuário é admin
        $stmt = $pdo->prepare("SELECT isDocente FROM msi_usuario WHERE usuario = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retorna 1 se isAdmin = 1, senão 0
        if ($user && $user['isDocente'] == 1) {
            return 1;
        } else {
            return 0;
        }

    } catch (PDOException $e) {
        echo "Erro ao se conectar ao banco de dados: " . $e->getMessage();
        return 0;
    }
}

function controlarAcessoModulos($username){
    
    // Credenciais de conexão com o banco de dados
    $host = 'localhost';
    $db = 'db';
    $user = 'user';
    $pass = 'password';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";

    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT m.nome, m.url 
                FROM modulos m
                INNER JOIN modulo_usuario mu ON m.id = mu.id_modulo
                INNER JOIN pessoa p ON mu.codigo_pessoa = p.codigo
                WHERE p.usuario = :username";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $modulos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($modulos as $mod) {
            echo("<div>
                    <button class='btn btn-primary' style='margin:10px' 
                            onclick=\"location.href='{$mod['url']}'\" type='button'>
                        <div class='' style='max-width: 18rem;'>
                            <div class='card-body'>{$mod['nome']}</div>
                        </div>
                    </button>
                </div>");
        }

    } catch (PDOException $e) {
        echo "Erro ao se conectar com o banco de dados: " . $e->getMessage();
    }
}

?>
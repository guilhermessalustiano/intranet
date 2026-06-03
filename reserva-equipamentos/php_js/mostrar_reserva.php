<?php
 
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See https://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - https://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'mre_emprestimo';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
$columns = array(
    array( 'db' => 'em.id', 'dt' => 'id', 'field' => 'id'),
    array( 'db' => 'p.nome', 'dt' => 'nome', 'field' => 'nome'),
    array( 'db' => 'p.tipopessoa', 'dt' => 'tipopessoa', 'field' => 'tipopessoa'),
    array( 'db' => 'p.email', 'dt' => 'email', 'field' => 'email'),
    array( 'db' => 'p.telefone', 'dt' => 'telefone', 'field' => 'telefone'),
    array( 'db' => 'em.obs_emp', 'dt' => 'obs_emp', 'field' => 'obs_emp'),
    array( 'db' => 'em.obs_devol', 'dt' => 'obs_devol', 'field' => 'obs_devol'),
    array( 
        'db' => 'DATE_FORMAT(em.dt_inicio, "%d/%m/%Y")', 
        'dt' => 'dt_inicio',
        'field' => 'dt_inicio',
        'as' => 'dt_inicio'
    ),
    array( 
        'db' => 'DATE_FORMAT(em.dt_fim, "%Y-%m-%d")', 
        'dt' => 'dt_fim',
        'field' => 'dt_fim',
        'as' => 'dt_fim'
    ),    
    array( 
        'db' => 'DATE_FORMAT(em.dt_devol, "%d/%m/%Y")', 
        'dt' => 'dt_devol',
        'field' => 'dt_devol',
        'as' => 'dt_devol'
    ),
    array(
        'db' => 'equipamentos',
        'dt' => 'equipamentos',
        'field' => 'equipamentos'
    )


);


// SQL server connection information
$sql_details = array(
    'user' => 'intranet',
    'pass' => 'j7fsds@2u*&878ww@@2Pa22S!!mssD',
    'db'   => 'intranet',
    'host' => 'localhost',
    'charset' => 'utf8' // Depending on your PHP and MySQL config, you may need this
);


// $joinQuery = "FROM mre_emprestimo em JOIN pessoa p ON em.pessoa = p.codigo";
$joinQuery = "
  FROM mre_emprestimo em
  JOIN pessoa p ON p.codigo = em.pessoa
  LEFT JOIN (
      SELECT 
          ee.id_emprestimo,
          GROUP_CONCAT(DISTINCT CONCAT(eq.nome, ' (', IFNULL(eq.patrimonio, ''), ')') SEPARATOR ';') AS equipamentos
      FROM mre_emprestimo_equipamento ee
      JOIN mre_equipamento eq 
            ON eq.id = ee.id_equipamento
      GROUP BY ee.id_emprestimo
  ) AS eqs
    ON eqs.id_emprestimo = em.id
";

// Condição extra para filtrar apenas empréstimos pendentes ou devolvidos
$status = $_GET['status'] ?? '';
if ($status === 'pendente') {
    $extraWhere = "em.dt_devol IS NULL";
} elseif ($status === 'devolvido') {
    $extraWhere = "em.dt_devol IS NOT NULL";
} 
$groupBy    = "";
$having     = "";


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
 require( '../ssp.customized.class.php');

echo json_encode(
    // 'apresentações'
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having  ), JSON_UNESCAPED_UNICODE
);

?>
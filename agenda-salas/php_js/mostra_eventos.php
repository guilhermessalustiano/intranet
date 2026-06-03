<?php
if (!isset($_SESSION)) session_start();
global $_POST, $_GET, $_SESSION;

include($_SERVER["DOCUMENT_ROOT"] . "/comum/funcoes.php");

require('connect.php');

header('Content-Type: application/json');

try {


    // Preparar a consulta
    $stmt = $pdo->prepare("
        SELECT  e.id AS id,
                    a.nome AS nome,
                    e.title AS title,
                    e.start,
                    e.end,
                    a.backgroundColor,
                    a.id AS agenda_id,
                    p.nome AS owner,
                    e.allDay,
                    e.rrule,
                    u.codigo AS owner_id,
                    e.duration,
                    e.exdate,
        CONCAT(
            '[', 
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    '{\"id\":', er.id_recurso, ',\"nome\":\"', REPLACE(r.nome, '\"', '\\\"'), '\"}'
                )
            ), 
            ']'
        ) AS recursos 
            FROM
                mas_eventos AS e
            JOIN
                mas_agendas AS a ON e.id_agenda = a.id
            JOIN
                mas_agenda_visualizacao_usuario AS au ON e.id_agenda = au.id_agenda
            JOIN
                msi_usuario AS u ON e.id_usuario = u.codigo
            JOIN
                pessoa AS p ON u.usuario = p.usuario
            LEFT JOIN  -- LEFT JOIN para pegar os recursos
                mas_evento_recurso AS er ON e.id = er.id_evento
            LEFT JOIN  -- LEFT JOIN para pegar o nome do recurso
                mas_recursos AS r ON er.id_recurso = r.id
            WHERE
                au.is_visible = 1 AND
                au.id_usuario = :usuario_codigo
            GROUP BY
                e.id;
                    "
    );
    
    $stmt->bindParam(':usuario_codigo', $_SESSION['logged_user_id'], PDO::PARAM_INT);


    
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $formattedEvents = array();


    foreach ($events as $event) {

        $rrule = $event['rrule'];
        $exdatesArray=[];
        $recurrenceType = '';

        if (strpos($rrule, 'FREQ=DAILY') !== false){
            $recurrenceType = 'DAILY';
        } elseif (strpos($rrule, 'FREQ=WEEKLY') !== false){
            $recurrenceType = 'WEEKLY';
        } else{
            $recurrenceType = 'N/A';
        }

        if ($event['exdate'] != NULL) { 
            $exdate = isset($event['exdate']) ? $event['exdate'] : '';
            $exdatesArray = explode(',', $exdate);
        }

        $formattedEvents[] = array(
            'id' => $event['id'],
            'title' => $event['title'],
            'start' => $event['start'],
            'end' => $event['end'],
            'backgroundColor' => $event['backgroundColor'],
            'rrule' =>  $event['rrule'],
            'recurrenceType' => $recurrenceType,
            'allDay' => $event['allDay'],
            'duration' => $event['duration'],           
            'exdate' => $exdatesArray,
            'extendedProps' => array(
                'nome' => $event['nome'],
                'agenda_id' => $event['agenda_id'],
                'owner' => $event['owner'],
                'owner_id' => $event['owner_id'],
                'recursos' => $event['recursos']            
            )   
        );
    }
    
    echo json_encode($formattedEvents);
} catch (PDOException $e) {
    // Capturar e retornar o erro
    $error = array("error" => $e->getMessage());
    echo json_encode($error);
}
?>

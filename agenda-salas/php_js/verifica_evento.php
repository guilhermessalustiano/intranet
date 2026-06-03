<?php
    use RRule\RRule;
    require('../../vendor/autoload.php');

    function verifica_sobreposicao_eventos($eventData, $op = 'create') {

        require('connect.php');

        $ignoreEventId = ($op === 'edit') ? $eventData['id'] : null;
        
        //-------------------------INICIO INSERIR EVENTO NÃO RECORRENTE
        if (empty($eventData['rrule'])) { 
            //verificar insercao de evento NÃO RECORRENTE com os eventos NÃO RECORRENTE da agenda
            $sql = "SELECT * FROM mas_eventos WHERE rrule IS NULL AND (:startTime < end AND :endTime > start)";

            if ($ignoreEventId) {
                $sql .= " AND id != :ignoreEventId";
            }
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':startTime', $eventData['startDate']);
            $stmt->bindParam(':endTime', $eventData['endDate']);

            if ($ignoreEventId) {
                $stmt->bindParam(':ignoreEventId', $ignoreEventId, PDO::PARAM_INT);
            }

            $stmt->execute();

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($resultados as $evento) {
                if ($evento['id_agenda'] == $eventData['id_agenda']) {
                    return 1; // Agenda conflict
                }
                else { // Possible resource conflict
                    // Fetch resources of the existing event

                    $sqlRecursosOutroEvento = "SELECT id_recurso FROM mas_evento_recurso WHERE id_evento = :id_evento";
                    $stmtOutroEvento = $pdo->prepare($sqlRecursosOutroEvento);
                    $stmtOutroEvento->bindParam(':id_evento', $evento['id']);
                    $stmtOutroEvento->execute();

                    $recursosOutroEvento = $stmtOutroEvento->fetchAll(PDO::FETCH_COLUMN);

                    $idRecursos = array_map(function($recurso) {return (string) $recurso['id'];}, $eventData['id_recurso']);

                    // Check for common resources
                    $recursosComuns = array_intersect($idRecursos, $recursosOutroEvento);

                    if (!empty($recursosComuns)) {
                        return 2; // Conflict due to common resources
                    }
                }
            }
            //verificar insercao de evento NÃO RECORRENTE com os eventos RECORRENTES da agenda

            $sqlRecorrentes = "SELECT * FROM mas_eventos 
            WHERE rrule IS NOT NULL 
            AND (:endTime > rrule_dtstart) 
            AND (:startTime < rrule_until)";

            if ($ignoreEventId) {
                $sqlRecorrentes .= " AND id != :ignoreEventId";
            }
            
            $stmtRecorrentes = $pdo->prepare($sqlRecorrentes);

            if ($ignoreEventId) {
                $stmtRecorrentes->bindParam(':ignoreEventId', $ignoreEventId, PDO::PARAM_INT);
            }

            $stmtRecorrentes->bindParam(':startTime', $eventData['startDate']);
            $stmtRecorrentes->bindParam(':endTime', $eventData['endDate']);


            $stmtRecorrentes->execute();
            // $stmtRecorrentes->debugDumpParams();
            
            $resultadosRecorrentes = $stmtRecorrentes->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($resultadosRecorrentes as $eventoRecorrente) {

                // evento recorrente
                $datetime = new DateTime($eventoRecorrente['rrule_dtstart']);
                $hora_start = $datetime->format('H:i:s');
                
                $datetime = new DateTime($eventoRecorrente['rrule_until']);
                $hora_end = $datetime->format('H:i:s');

                // evento a ser inserido
                $datetime = new DateTime($eventData['startDate']);
                $event_hora_start = $datetime->format('H:i:s');
                $event_day = $datetime->format('Y-m-d');

                $datetime = new DateTime($eventData['endDate']);
                $event_hora_end = $datetime->format('H:i:s');
                
                $exdates = explode(',', $eventoRecorrente['exdate']);


                if (!(($event_hora_start < $hora_end) && ($event_hora_end > $hora_start))) continue; //sem overlap de horario, verifi. proximo evento recorrente
                else {
                    // Check if the instance is excluded
                    $isExcluded = false;
                    foreach ($exdates as $exdate) {
                        $exdateFormatted = date('Y-m-d', strtotime($exdate));
                        if ($event_day == $exdateFormatted) {
                            $isExcluded = true;
                            break;
                        }
                    }
    
                    if ($isExcluded) {
                        continue; // se tiver o exdate, ignora e continua pro prox. evento recorr. do banco
                    }
                    else {

                        $sqlRecursosOutroEvento = "SELECT id_recurso FROM mas_evento_recurso WHERE id_evento = :id_evento";
                        $stmtOutroEvento = $pdo->prepare($sqlRecursosOutroEvento);
                        $stmtOutroEvento->bindParam(':id_evento', $eventoRecorrente['id']);
                        $stmtOutroEvento->execute();
                        $recursosOutroEvento = $stmtOutroEvento->fetchAll(PDO::FETCH_COLUMN);
                        $recursosComuns = array_intersect($eventData['id_recurso'], $recursosOutroEvento);

                        if ( ($eventoRecorrente['id_agenda'] != $eventData['id_agenda']) && (empty($recursosComuns)) ) continue;

                        else { //agora expandir a recorrencia e comparar 

                            $rrule = new RRule(str_replace("BYWEEKDAY", "BYDAY", $eventoRecorrente['rrule']));
                
                            foreach ($rrule as $instancia) { //basta verificar se o dia da instancia atual é igual ao evento a ser inserido
                                
                                // $event_day
                                $instancia_day = $instancia->format('Y-m-d');
                                if ($instancia_day == $event_day) {
                                    if ( ($eventoRecorrente['id_agenda'] == $eventData['id_agenda']) ) {
                                        return 1;
                                    }
                                    elseif (!empty($recursosComuns)){
                                            return 2;
                                    }
                                }
                                
                            }

                        }
                    }
                }
            }
        }
        //-------------------------FIM INSERIR EVENTO NÃO RECORRENTE
    
        //-------------------------INICIO INSERIR EVENTO RECORRENTE
        else {  
            // Verificar inserção de evento RECORRENTE com os eventos NÃO RECORRENTES da agenda
            $sql = "SELECT * FROM mas_eventos 
                    WHERE rrule IS NULL 
                    AND (:rruleUntil > start AND :rruleDtstart < end) 
                    AND (TIME(:recorrenteStartTime) < TIME(end) AND TIME(:recorrenteEndTime) > TIME(start))";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':rruleDtstart', $eventData['rrule_dtstart']);
            $stmt->bindParam(':rruleUntil', $eventData['rrule_until']);
            
            // Define os horários de início e fim representativos para qualquer instância do evento recorrente
            $recorrenteStartTime = (new DateTime($eventData['rrule_dtstart']))->format('H:i:s');
            $recorrenteEndTime = (new DateTime($eventData['rrule_until']))->format('H:i:s');
        
            $stmt->bindParam(':recorrenteStartTime', $recorrenteStartTime);
            $stmt->bindParam(':recorrenteEndTime', $recorrenteEndTime);
        
            $stmt->execute();


            $eventosNaoRecorrentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($eventosNaoRecorrentes as $eventoNaoRecorrente) {

                $datetime = new DateTime($eventoNaoRecorrente['start']);
                $event_day = $datetime->format('Y-m-d');

                $sqlRecursosOutroEvento = "SELECT id_recurso FROM mas_evento_recurso WHERE id_evento = :id_evento";
                $stmtOutroEvento = $pdo->prepare($sqlRecursosOutroEvento);
                $stmtOutroEvento->bindParam(':id_evento', $eventoNaoRecorrente['id']);
                $stmtOutroEvento->execute();


                $recursosOutroEvento = $stmtOutroEvento->fetchAll(PDO::FETCH_COLUMN);
                $recursosComuns = array_intersect($eventData['id_recurso'], $recursosOutroEvento);

                if ( ($eventoNaoRecorrente['id_agenda'] != $eventData['id_agenda']) && (empty($recursosComuns)) ) {
                    continue; //grande otimizacao!
                }
                else { //agora expandir a recorrencia e comparar 

                    $rrule = new RRule(str_replace("BYWEEKDAY", "BYDAY", $eventData['rrule']));

                    foreach ($rrule as $instancia) { //basta verificar se o dia da instancia atual é igual ao evento a ser inserido
                        
                        $instancia_day = $instancia->format('Y-m-d');

                        if ($instancia_day == $event_day) {
                            if ( ($eventoNaoRecorrente['id_agenda'] == $eventData['id_agenda']) ) {
                                return 1;
                            }
                            elseif (!empty($recursosComuns)){
                                    return 2;
                            }
                        }

                        
                    }

                }
            }
        










            // Verificar inserção de evento RECORRENTE com os eventos RECORRENTES da agenda
            //...

            $sql = "SELECT * FROM mas_eventos 
                    WHERE rrule IS NOT NULL 
                    AND (:rruleUntil > rrule_dtstart AND :rruleDtstart < rrule_until)
                    AND (TIME(:recorrenteStartTime) < TIME(rrule_until) AND TIME(:recorrenteEndTime) > TIME(rrule_dtstart))";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':rruleDtstart', $eventData['rrule_dtstart']);
            $stmt->bindParam(':rruleUntil', $eventData['rrule_until']);
            
            // Define os horários de início e fim representativos para qualquer instância do evento recorrente
            $recorrenteStartTime = (new DateTime($eventData['rrule_dtstart']))->format('H:i:s');
            $recorrenteEndTime = (new DateTime($eventData['rrule_until']))->format('H:i:s');

            $stmt->bindParam(':recorrenteStartTime', $recorrenteStartTime);
            $stmt->bindParam(':recorrenteEndTime', $recorrenteEndTime);

            $stmt->execute();

            $eventosRecorrentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($eventosRecorrentes as $eventoRecorrente) {

                //recursos em comun
                $sqlRecursosOutroEvento = "SELECT id_recurso FROM mas_evento_recurso WHERE id_evento = :id_evento";
                $stmtOutroEvento = $pdo->prepare($sqlRecursosOutroEvento);
                $stmtOutroEvento->bindParam(':id_evento', $eventoRecorrente['id']);
                $stmtOutroEvento->execute();
                $recursosOutroEvento = $stmtOutroEvento->fetchAll(PDO::FETCH_COLUMN);
                $recursosComuns = array_intersect($eventData['id_recurso'], $recursosOutroEvento);

                if ( ($eventoRecorrente['id_agenda'] != $eventData['id_agenda']) && (empty($recursosComuns)) ) continue;


                // Expande a recorrência para verificar instâncias específicas
                $rruleOutroEvento = new RRule(str_replace("BYWEEKDAY", "BYDAY", $eventoRecorrente['rrule']));
                $rruleNovoEvento = new RRule(str_replace("BYWEEKDAY", "BYDAY", $eventData['rrule']));
        
                // Extrai as exdates do evento recorrente existente
                $exdates = !empty($eventoRecorrente['exdate']) ? explode(',', $eventoRecorrente['exdate']) : [];
                
                foreach ($rruleNovoEvento as $instanciaNovo) {


                    $instanciaNovoDay = $instanciaNovo->format('Y-m-d');
                    
                    $isExcluded = false;
                    foreach ($exdates as $exdate) {
                        $exdateFormatted = date('Y-m-d', strtotime($exdate));
                        if ($instanciaNovoDay == $exdateFormatted) {
                            $isExcluded = true;
                            break;
                        }
                    }
                    if ($isExcluded) {
                        continue; // se tiver o exdate, ignora e continua pro prox. evento recorr. do banco
                    }
                    else {

                        foreach ($rruleOutroEvento as $instanciaOutro) {
                            $instanciaOutroDay = $instanciaOutro->format('Y-m-d');
            
    
                            // Verifica se há conflito de data
                            if ($instanciaNovoDay == $instanciaOutroDay) {
    

            
                                if ($eventoRecorrente['id_agenda'] == $eventData['id_agenda']) {
                                    return 1; // Conflito de agenda
                                } elseif (!empty($recursosComuns)) {
                                    return 2; // Conflito de recurso
                                }
                            }
                        }
                    }


                }
            }





            
        }
        //-------------------------FIM INSERIR EVENTO RECORRENTE
        return 0; // No conflicts detected
    }
    

























    //verifica o dono do evento e se o usuario tem permissão para fazer alterações no evento
    function verifica_dono_evento($id) {
        if ($_SESSION['logged_user_id'] == $id) return true; //se usuario dono do evento, remove
        else return false;
    }







 








?>
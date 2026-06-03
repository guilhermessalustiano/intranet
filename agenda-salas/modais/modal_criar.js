
// Submissão do FORM
$('#form_criar_evento').submit(function(event) {

    event.preventDefault(); // Evita o envio padrão do formulário

    var startDate = moment( $('#eventStart').val(), 'DD-MM-YYYY HH:mm:ss');
    var formated_startDate = moment( $('#eventStart').val(), 'DD/MM/YYYY HH:mm[h]').format('YYYY-MM-DD HH:mm:ss');

    var endDate = moment( $('#eventEnd').val(), 'DD/MM/YYYY HH:mm[h]');
    var formated_endDate = moment( $('#eventEnd').val(), 'DD/MM/YYYY HH:mm[h]').format('YYYY-MM-DD HH:mm:ss');



    //definicao do campo da tabela 'rrule_until' (fim da ultima recorrencia)
    var untilDate = moment($('#until').val(), 'YYYY-MM-DD').format('YYYY-MM-DD');
    var endTime = endDate.format('HH:mm:ss');
    var untilDateTimeStr = untilDate + ' ' + endTime;
    var rrule_until = moment.utc(untilDateTimeStr, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
    var until = moment($('#until').val(), 'YYYY-MM-DD').format('YYYYMMDD')+'T'+endDate.format('HHmmss')+'Z';
    var rrule_dtstart= moment( $('#eventStart').val(), 'DD/MM/YYYY HH:mm[h]').format('YYYYMMDDTHHmmss') + 'Z';

    var duration = moment.duration(endDate.diff(startDate));
    duration = moment.utc(duration.asMilliseconds()).format('HH:mm');

    var recorrencia = $('#recorrencia_select').val(); // Captura o valor selecionado
    var rrule_string="";
    var rrule_byweekday = []; // Array para armazenar os dias selecionados
    var formData = {}; // Objeto para armazenar os dias selecionados

    var recursos = [];
    var checkboxesMarcados = $('#recursosContainer input[type="checkbox"]:checked');
    
    if ($('#sim_recurso').is(':checked')) {

        $('#recursosContainer input[type="checkbox"]:checked').each(function() {
            recursos.push($(this).val());
        });

    }    

    $('.checkbox-bolinha').each(function() {
        var ariaChecked = $(this).attr('aria-checked'); // Pegar o valor de aria-checked
        
        // Comparar como string "true" explicitamente
        if (ariaChecked === 'true') {
            // nro_weekdays = nro_weekdays + 1;
            rrule_byweekday.push($(this).attr('data-weekday')); // Adicionar o dia ao array
        }
    });

    if (recorrencia == 'Semanalmente') {
        if (rrule_byweekday.length === 0) {
            toastr.error('Selecione o(s) dia(s) da semana!');
        } else {
            rrule_string = "FREQ=WEEKLY;DTSTART="+rrule_dtstart+";UNTIL="+until+';BYWEEKDAY='+rrule_byweekday;
             formData = {
                // startDate: "NULL",
                // endDate: "NULL",Wed Oct 02 2024 11:00:00 GMT-0300 (Brasilia Standard Time)
                eventName: $('#eventName').val(),
                id_agenda: $('#sala').val(), //sala ou id_agenda
                id_recurso: recursos,
                rrule: rrule_string,
                duration: duration,
                rrule_dtstart: formated_startDate,
                rrule_until: rrule_until

            };
        }
    } else if (recorrencia == 'Diariamente') {var
        rrule_string = "FREQ=DAILY;DTSTART="+rrule_dtstart+";UNTIL="+until;
         formData = {
            eventName: $('#eventName').val(),
            id_agenda: $('#sala').val(), //sala ou id_agenda
            id_recurso: recursos,
            rrule: rrule_string,
            duration: duration,
            rrule_dtstart: formated_startDate,
            rrule_until: rrule_until
        };
    } else { //evento sem recorrencia
         formData = {
            startDate: formated_startDate,
            endDate: formated_endDate,
            eventName: $('#eventName').val(),        // varstartDate: starrtDate,
            id_agenda: $('#sala').val(), //sala ou id_agenda
            id_recurso: recursos
        };

    }


    let recurrency_rrule_dtstart = new Date(formated_startDate);
    let recurrency_rrule_until = new Date(rrule_until);
    let limite_recurrency_ultrapassado = false;
    let limite_until;

    if(recurrency_rrule_dtstart.getMonth() < 6){
        console.log('Primeiro semestre');
        limite_until = new Date(recurrency_rrule_dtstart.getFullYear(), 6, 31, 23, 59, 59);

        // if (recurrency_rrule_until > limite_until){
        //     limite_recurrency_ultrapassado = true;
        //     console.log(limite_recurrency_ultrapassado);
        //     toastr.error('Data limite para a recorrência deste evento: 31-07-' + recurrency_rrule_dtstart.getFullYear());
        // }

    } else{
        console.log('Segundo semestre');
        limite_until = new Date(recurrency_rrule_dtstart.getFullYear(), 11, 31, 23, 59, 59);

        // if(recurrency_rrule_until > limite_until){
        //     limite_recurrency_ultrapassado = true;
        //     console.log(limite_recurrency_ultrapassado);
        //     toastr.error('Data limite para a recorrência deste evento: 31-12-' + recurrency_rrule_dtstart.getFullYear());
        // }
    }

    if(recurrency_rrule_until > limite_until){
        limite_recurrency_ultrapassado = true;
        console.log(limite_recurrency_ultrapassado);
        toastr.error('Data limite para a recorrência deste evento: ' + limite_until.toLocaleDateString('pt-BR') + ' 23:59:59');
    }

    if(!limite_recurrency_ultrapassado){
        $.ajax({
            url: '../../agenda-salas/php_js/criar_evento.php',
            type: 'POST',
            async: false,
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(data) {
                if (data.success) {
                    calendar.refetchEvents();
                    toastr.success(data.message); 
                    $('#modal_criar_evento').modal('hide');
                    $('#recursosContainer').empty();
    
                } else {
                    toastr.error(data.message);
    
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 401) {
                    toastr.error('Usuário desconectado, redirecionando...');
                    setTimeout(function() {window.location.href = '/home.php';}, 3000); 
                } else {
                toastr.error('Procure o Admnistrador!');
                }
            }        
        });
    }




    // ------------------------------------------------------


});


function ModalCreateEvent(event) {
            console.log(event);
    
    if (event.allDay == true) {
        startStr = moment(event.startStr, 'YYYY-MM-DD').format('DD/MM/YYYY 08:30[h]');
        endStr = moment(event.startStr, 'YYYY-MM-DD').format('DD/MM/YYYY 09:30[h]');
    } else {
        startStr = moment(event.startStr).format('DD/MM/YYYY HH:mm[h]');
        endStr = moment(event.endStr).format('DD/MM/YYYY HH:mm[h]');  
    }

    $('#modal_criar_evento #eventStart').val(startStr);
    $('#modal_criar_evento #eventEnd').val(endStr);
     
    var selectSala = document.getElementById('sala');
    fetch('php_js/mostra_agenda.php').then(response => response.json()).then(data => {
    selectSala.innerHTML = '';

    data.forEach(function(sala) {
        var option = document.createElement('option');
        option.value = sala.id;
        option.textContent = sala.nome;
        selectSala.appendChild(option);
    });
    
    }).catch(error => {console.error('Erro ao obter dados:', error);
    });

    // Mostrar o modal
    $('#modal_criar_evento').modal('show'); //mostra modal para preencher evento
    // calendar.unselect(); // Desmarca a seleção
    
}

$('#recorrencia_select').on('change', function() {
    // Verificação do valor selecionado
     $('#box_weekday').empty(); //limpa seleção prévia

     var boxUntil = `
        <div id="box_until" class="form-group row">
            <label for="eventStart" class="col-sm-2 col-form-label">Termina:</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="until" name="until" required>
            </div>
        </div>`;

        $('#box_until').remove();

        if ( $(this).val() === 'Não') {
        } else if ( $(this).val() === 'Diariamente') {
            $(this).closest('.form-group').after(boxUntil);
        } else if ( $(this).val() === 'Semanalmente') {
            //no sistema terá apenas a cada semana. Não terá a cada 2,3...(não é necessário)
            //entao INTERVAL=1 ou omitido



            var conteudo = `
            <div class="">
                <div class="uB7wbb">
                    <span><div class="checkbox-bolinha" data-weekday="MO" role="checkbox" aria-checked="false">S</div></span>
                    <span><div class="checkbox-bolinha" data-weekday="TU" role="checkbox" aria-checked="false">T</div></span>
                    <span><div class="checkbox-bolinha" data-weekday="WE" role="checkbox" aria-checked="false">Q</div></span>
                    <span><div class="checkbox-bolinha" data-weekday="TH" role="checkbox" aria-checked="false">Q</div></span>
                    <span><div class="checkbox-bolinha" data-weekday="FR" role="checkbox" aria-checked="false">S</div></span>
                </div>
            </div>`;

            // Insere o conteúdo abaixo do select
            $('#box_weekday').html(conteudo);

            $(this).closest('.form-group').after(boxUntil);
            
            // Função para alternar o estilo de seleção
            $('.checkbox-bolinha').on('click', function() {
                $(this).toggleClass('checked');

                var isChecked = $(this).hasClass('checked');
                
                // Alterna o valor do atributo aria-checked
                $(this).attr('aria-checked', isChecked ? 'true' : 'false');

  
            });
        }
});



// -----------------------------RESETS
$(document).ready(function(){

    $('#modal_criar_evento').on('hidden.bs.modal', function () { //quando fecha o modal criar evento
        $('#recorrencia_select option').first().prop('selected', true);
        $('#box_until').empty();
        $('#box_weekday').empty();
        $('#recursosContainer').empty();
        $('#eventName').val('');
        $('#recursosContainer input[type="checkbox"]').prop('checked', false);
    });


    $('#modal_criar_evento').on('shown.bs.modal', function () {
        $('#nao_recurso').prop('checked', true);
        $(this).val('').find('input:text').first().focus();
    });

    
    $('input[name="recurso"]').on('change', function() {
        
        if ($('#sim_recurso').is(':checked')) {

            $.ajax({
                url: '../../agenda-salas/php_js/recursos/mostra_recurso.php', // URL do arquivo PHP
                method: 'GET', // Método da requisição (GET, POST, etc.)
                dataType: 'json', // Espera receber dados em formato JSON
                success: function(data) { // Função que será executada se a requisição for bem-sucedida
                    var $recursosContainer = $('#recursosContainer');
                    $recursosContainer.empty();  // Limpa o container
                
                    // Itera sobre os dados recebidos (cada recurso)
                    $.each(data, function(index, recurso) {
                        // Cria o div para o recurso
                        var $recursoDiv = $('<div></div>');

                        // Cria o label que conterá o checkbox e o texto
                        var $label = $('<label></label>').attr('for', `recurso_${recurso.id}`);

                        // Cria o checkbox
                        var $checkbox = $('<input>').attr({
                            type: 'checkbox',
                            name: `recurso_${recurso.id}`,
                            value: recurso.id,
                            id: `recurso_${recurso.id}`
                        });

                        // Garante que o checkbox seja desmarcado inicialmente
                        $checkbox.prop('checked', false);

                        // Adiciona o checkbox ao label
                        $label.append($checkbox);

                        // Adiciona um espaço entre o checkbox e o texto
                        $label.append(' ');

                        // Adiciona o texto do recurso ao label
                        $label.append(recurso.nome);

                        // Adiciona o label ao div
                        $recursoDiv.append($label);

                        // Adiciona o div ao container
                        $('#recursosContainer').append($recursoDiv);
                    });
                },
                error: function(xhr, status, error) { // Função executada se houver erro
                    console.error('Erro na requisição AJAX:', status, error);
                }
            });
                        
        $('#recurso_adicional').show();

        } else {
        //   $('#recurso_adicional').hide();
            $('#recurso_adicional').hide();
            $('#recursosContainer').empty();

        }
      });    


});






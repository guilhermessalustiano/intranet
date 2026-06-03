var calendar;
var mini_agenda;





// Inicialização da mini agenda 
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('mini_agenda');
    mini_agenda = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        defaultView: 'month',
        locale: 'pt-br',
        
        editable: false,
        navLinks: false, // Permite navegação ao clicar em um dia
        dayClick: function(date) {
            // Quando um dia é clicado no calendário pequeno, atualiza a agenda principal
            $('#main-calendar').fullCalendar('gotoDate', date);
            $('#main-calendar').fullCalendar('changeView', 'agendaDay');
        },
        dateClick: function(info) {
            // Quando um dia é clicado no calendário pequeno, atualiza a agenda principal
            calendar.gotoDate(info.date);
            calendar.changeView('timeGridDay');
        },       
    });
    mini_agenda.render();

    var rows = document.querySelectorAll('#mini_agenda .fc-scrollgrid-section-body');
    rows.forEach(function(row) {
        row.style.cursor = 'pointer';
    });

    var dayTopDivs = document.querySelectorAll('#mini_agenda .fc-daygrid-day-top');
    dayTopDivs.forEach(function(div) {
        div.style.color = '#630000';
    });

    var dayDivs = document.querySelectorAll('#mini_agenda .fc-day');
    dayDivs.forEach(function(dayDiv) {
        // Quando o mouse estiver sobre o dia
        dayDiv.addEventListener('mouseover', function() {
            dayDiv.style.backgroundColor = '#999999';
        });

        // Quando o mouse sair do dia
        dayDiv.addEventListener('mouseout', function() {
            dayDiv.style.backgroundColor = ''; // Reseta para o valor original
        });
    });
});

// Inicialização da agenda1
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('agenda');
    calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'timeGridWeek', 
        allDaySlot: false,
        hiddenDays: [ 0, 6 ],
        timeZone: 'America/Sao_Paulo',
        locale: 'pt-br',
        contentHeight:"auto",  //ajusta a altura (tira scroll)
        slotMinTime: "08:00:00", //Horario inicial da agenda (show only)
        slotMaxTime: "18:00:00", //Horario final da agenda (show only)
        nowIndicator: true, // Exibe a linha indicando a hora atual,
        eventTextColor:'#ffffff',
        headerToolbar: {
            left: 'prev,next,today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('php_js/mostra_eventos.php')
                .then(response => response.json())
                .then(data => {
                    var events = data.map(event => {
                        return {
                            id: event.id,
                            title: event.title,
                            start: event.start,
                            end: event.end,
                            duration: event.duration,
                            eventBorderColor: event.backgroundColor, // Cor da borda e 'bolinha' no monthly view
                            backgroundColor: event.backgroundColor, // Cor do evento
                            textColor: '#ffffff', // Cor do texto
                            rrule: event.rrule,
                            exdate: event.exdate,
                            extendedProps: {
                                agenda_id: event.extendedProps.agenda_id,
                                owner: event.extendedProps.owner,
                                nome: event.extendedProps.nome,
                                owner_id: event.extendedProps.owner_id,
                                duration: event.duration,
                                rrule: event.rrule,
                                url_reuniao: event.url_reuniao,
                                recursos: event.extendedProps.recursos ? JSON.parse(event.extendedProps.recursos) : []
                              },                            

                        };
                    });
                // console.log(events);
                successCallback(events);
                })
                .catch(error => {
                    failureCallback(error);
                });
        },  
 
        selectable:true,
        select: function(info) {
            ModalCreateEvent(info);// This determines if NEW event can be created by click and/or resize.
        },
        editable: true,
        eventChange: function(info) {
            updateEvent(info.event); // This determines if the EXISTING events can be dragged and resized.
        },
        eventClick: function(info) {
            ModalShowEvent(info.event);//Details about a clicked event 
        },
        dateClick: function(info) {
            // quando clicamos na data - slot de dia na vizualizacao semanal
        },        

        
    });

    calendar.render();

});

    function updateEvent(event) {

        if ($("#usuario_codigo_hidden").val() != event.extendedProps.owner_id) {
            calendar.refetchEvents();
            toastr.error('Não é possível alterar eventos de outros usuários.');
            return;
        }
        if (event.extendedProps.rrule != null) {
            calendar.refetchEvents();
            toastr.error('Não é possível alterar eventos recorrentes. Remova e crie novamente.');
            return;
        }
        
        let eventData = {
            id: event.id,
            eventName: event.title,
            startDate: moment.utc(event.start).format('YYYY-MM-DD HH:mm:ss'), // Formata a data para o padrão MySQL
            endDate: moment.utc(event.end).format('YYYY-MM-DD HH:mm:ss'), // Formata a data para o padrão MySQL
            rrule: event.extendedProps.rrule,
            id_agenda: event.extendedProps.agenda_id,
            id_recurso: event.extendedProps.recursos
        };



        // Envia a atualização para o servidor usando AJAX
        $.ajax({
            url: 'php_js/editar_eventos.php', // URL do script PHP que processará a atualização
            type: 'POST',
            data: JSON.stringify(eventData), // Envia o objeto eventData
            dataType: 'json', // Espera uma resposta JSON do servidor
            success: function(data) {
                calendar.refetchEvents();
                if (data.success) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao atualizar: ' + error);
            }
        });
        

    }


// Quadro de Agendas e Lista de Visualizacao Agendas
$(document).ready(function() {
    // Atualizacao da visualizacao das agendas
    $('#checkbox-salas input[type="checkbox"]').change(function() {
        var checkboxId = $(this).attr('id'); // Captura o ID do checkbox que foi alterado
        var isChecked = $(this).is(':checked') ? 1 : 0; // Verifica se o checkbox está marcado (1) ou desmarcado (0)

        if (isChecked) {
            $('#checkbox-salas' + checkboxId.split('_')[1]).show(); // Mostra o conteúdo relacionado
        } else {
            $('#checkbox-salas' + checkboxId.split('_')[1]).hide(); // Esconde o conteúdo relacionado
        }

        // Envia a atualização para o servidor usando AJAX
        $.ajax({
            url: 'php_js/atualizar_visualizacao_agendas.php', // URL do script PHP que processará a atualização
            type: 'POST',
            data: {
                id_agenda: checkboxId.split('_')[1], // Captura o número após "agenda_" como o ID da agenda
                is_visible: isChecked
            },
            success: function(response) {
                calendar.refetchEvents();
                                
            },
            error: function(xhr, status, error) {
                console.error('Erro ao atualizar: ' + error);
            }
        });
    });
});




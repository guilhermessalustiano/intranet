var eventoGlobal;

function ModalShowEvent(event) {

    var startFormatted;
    var endFormatted;
    var dateTimeFormatted;

    startFormatted = moment(event.startStr).format('dddd, DD [de] MMMM [–] HH:mm[h]');
    endFormatted = moment(event.endStr).format('HH:mm[h]');
    startFormatted = startFormatted.charAt(0).toUpperCase() + startFormatted.slice(1);
    dateTimeFormatted = startFormatted + ' até ' + endFormatted;



    //event.id
    //SELECT * FROM mas_evento_recurso WHERE id_recurso = :event.id


    // let lista_recursos="";

    // $.ajax({
    //     url: 'php_js/obter_evento_recurso.php', // URL para seu novo script
    //     type: 'GET',
    //     data: { evento_id: event.id }, // Envia o ID do evento
    //     success: function(data) {
    //         $.each(data, function(key, value) {
    //             lista_recursos = lista_recursos+value.nome+'<br>';
    //         });
    //         $('#recursos_atribuidos').html(lista_recursos);
            
    //     },
    //     error: function(xhr, status, error) {
    //         toastr.error('Erro: procure o administrador ' + error);
    //     }
    // });



    if (event.extendedProps.rrule !== null) { // com recorrência

        let rule = rrule.rrulestr(event.extendedProps.rrule); //objeto rrule
        let data_until = rule.options.until.toLocaleDateString('pt-BR',{ day: 'numeric', month: 'short', year: 'numeric' });


        if (rule.options.freq === 3 ) { //frequencia diária
            frequencia = "Todos os dias, até "+data_until;
        }
        else if  (rule.options.freq === 2 ) { //frequencia semanal

            // Semanal: cada segunda-feira, quarta-feira, sexta-feira, até 18 out. 2024

            let daysMap = ['seg', 'ter', 'qua', 'qui', 'sex', 'sáb', 'dom'];
            let diasSemana = rule.options.byweekday.map(weekday => daysMap[weekday]);

            frequencia = `Semanal: cada ${diasSemana.join(', ')}`;
            frequencia += ', até ' + data_until;


            // Função para formatar a data no estilo "17 out. 2024"
            function formatarData(date) {
                const options = { day: 'numeric', month: 'short', year: 'numeric' };
                return date.toLocaleDateString('pt-BR', options);
            }

            
        }

        $('#eventRecorrency').text(frequencia);
        // $('#editar_evento').hide();

    }

    $('.eventColor').css('background-color', event.backgroundColor);
    $('#nome_evento').text(event.title);
    $('#eventDateTime').text(dateTimeFormatted);
    $('#nome_agenda').html('<i class="fa-regular fa-calendar"></i> ' + event.extendedProps.nome);
    $('#owner_evento').html('<i class="fa-regular fa-user"></i> ' + event.extendedProps.owner);



// Verifica e exibe os recursos do evento
if (event.extendedProps.recursos && event.extendedProps.recursos.length > 0) {
    let recursosHtml = '<i class="fa-solid fa-boxes-stacked"></i> Recursos: <ul>';
    
    event.extendedProps.recursos.forEach(function(recurso) {
        recursosHtml += `<li>${recurso.nome}</li>`;
    });
    
    recursosHtml += '</ul>';
    
    $('#recursos_atribuidos').html(recursosHtml);

} 

    $('#modal_mostra_evento').modal('show');
    // console.log(event);
    // Hidden fields
    $('#id_evento').val(event.id);
    $('#start_evento').val(moment(event.startStr).format('DD/MM/YYYY HH:mm[h]'));
    $('#end_evento').val(moment(event.endStr).format('DD/MM/YYYY HH:mm[h]'));
    $('#id_agenda').val(event.extendedProps.agenda_id);
    $('#owner_id').val(event.extendedProps.owner_id);
    $('#rrule').val(event.extendedProps.rrule);
    // $('#recursos_selecionados').val(event.extendedProps.recursos);

}

// -----------------------------RESETS
$(document).ready(function(){

    //quando o modal aparece
    $('#modal_mostra_evento').on('show.bs.modal', function () { //quando fecha o modal criar evento
        if ( $('#is_recurring').val() === 1 ) {
            $('#editar_evento').hide();
        }
    });
    
    // //quando o modal desaparece
    $('#modal_mostra_evento').on('hidden.bs.modal', function () { //quando fecha o modal criar evento
        $('#eventRecorrency').empty();
        $('#recursos_atribuidos').empty();
        
        if (eventoGlobal && eventoGlobal.extendedProps.rrule) {
            $('#editar_evento').hide();
        } else {
            $('#editar_evento').show(); // Garantir que o botão seja visível para eventos não recorrentes
        }
    });
});
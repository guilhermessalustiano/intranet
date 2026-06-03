$('#excluir_evento').click(function(event) {
    
    const hiddenrruleValue = $('#rrule').val(); // Verifica o valor do campo hidden

    const formData = {
        id: $('#id_evento').val(),
        owner_id: $('#owner_id').val()
    };

    if (hiddenrruleValue) {
        // Se o evento é recorrente, abre o modal de confirmação
        $('#modal_mostra_evento').modal('hide');
        $('#modal_remover_evento').modal('show');
    } else {
        // Se o evento não é recorrente, prossegue com a exclusão normal
        $.ajax({
            url: 'php_js/remove_eventos.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(data) {
                if (data.success) {
                    calendar.refetchEvents();
                    toastr.success('Evento removido com sucesso!');
                } else {
                    toastr.error(data.error);
                }
            },
            error: function(xhr, status, error) {
                toastr.error(error);
            }
        });
        $('#modal_mostra_evento').modal('hide');
    }

});


// CONFIRMACAO EXCLUSAO (EVENTO RECORRENTE)
$('#excluir_evento_confirmacao').click(function(event) {

        var formattedDate = moment($('#start_evento').val(), "DD/MM/YYYY HH:mm[h]").format("YYYYMMDDTHHmmss[Z]");
    
        const formData = {
            tipo_remocao: $('input[name="opcao_exclusao"]:checked').val(),
            id: $('#id_evento').val(),
            owner_id: $('#owner_id').val(),
            ex_date: formattedDate
        };

        $.ajax({
            url: 'php_js/remove_eventos.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(data) {
                if (data.success) {
                    calendar.refetchEvents();
                    toastr.success('Evento removido com sucesso!');
                } else {
                    toastr.error(data.error);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Erro de ajax: ' + error);
            }
        });
        $('#modal_remover_evento').modal('hide');


});






// -----------------------------RESETS

$(document).ready(function(){



});
    

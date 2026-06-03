$('#editar_evento').click(function(event) {
    
    event.preventDefault();

    // Recupera o código do usuário logado do campo hidden
    usuarioCodigoLogado = $('input[id="usuario_codigo_hidden"]').val();

    // Recupera o dono do evento (assumindo que o ID do dono está no campo hidden dentro do modal de exibição)
    ownerIdEvento = $('#modal_mostra_evento #owner_id').val();

    // Verifica se é dono e se evento é recorrente
    if (usuarioCodigoLogado != ownerIdEvento) {
        toastr.error('Não é possível editar eventos de outros usuários.');
        return; 
    }
    if (!($('#modal_mostra_evento #rrule').val() === "")) {
        toastr.error('Não é possível alterar eventos recorrentes. Remova e crie novamente.');
        return; // Impede a execução do restante do código
    }

    // Carrega campos do modal de exibição para o modsetup React-Big-Calendar, you define a localizer. By default, the calendar will display in the local timezone of the browser the user is usinal de edição
    $('#modal_editar_evento #id_evento').val($('#modal_mostra_evento #id_evento').val());
    $('#modal_editar_evento #owner_id').val($('#modal_mostra_evento #owner_id').val());
    $('#modal_editar_evento #nome_evento').val($('#modal_mostra_evento #nome_evento').text());
    $('#modal_editar_evento #start_evento').val($('#modal_mostra_evento #start_evento').val());
    $('#modal_editar_evento #end_evento').val($('#modal_mostra_evento #end_evento').val());

    const idAgenda = $('#modal_mostra_evento #id_agenda').val();
    const idEvento = $('#modal_editar_evento #id_evento').val();
    //var recursosSelecionados = [];

    // Carrega as salas
    $.ajax({
        url: '../../agenda-salas/php_js/obter_salas.php',
        type: 'GET',
        success: function(data) {
            const salas = data;
            const salaSelect = $('#modal_editar_evento #sala');
            salaSelect.empty();
            salas.forEach(sala => {
                const selected = sala.id == idAgenda ? 'selected' : '';
                salaSelect.append(`<option value="${sala.id}" ${selected}>${sala.nome}</option>`);
            });
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao carregar salas: ' + error);
        }
    });

    $.ajax({
        url: '../../agenda-salas/php_js/obter_evento_recurso.php', // URL para seu novo script
        type: 'GET',
        data: { evento_id: idEvento }, // Envia o ID do evento
        success: function(data) {
            const recursosSelecionados = data.map(recurso => recurso.id);
            const recursosContainer = $('#modal_editar_evento #recursosContainer_edit');
            const radioSim = $('#modal_editar_evento input[name="recurso"][value="Sim"]');
            const radioNao = $('#modal_editar_evento input[name="recurso"][value="Não"]');

            recursosContainer.empty();

            // Agora buscar os recursos disponíveis e verificar os selecionados
            $.ajax({
                url: '../../agenda-salas/php_js/recursos/mostra_recurso.php',
                type: 'GET',
                success: function(recursos) {
                    recursos.forEach(function(recurso) {
                        const isChecked = recursosSelecionados.includes(recurso.id.toString());
            
                        // Cria o label com classe do Bootstrap
                        const label = $(`<label for="recurso_${recurso.id}" class="form-check-label"></label>`);
            
                        // Cria o checkbox com classe do Bootstrap
                        const checkbox = $(`<input type="checkbox" class="form-check-input" name="recurso_${recurso.id}" value="${recurso.id}" id="recurso_${recurso.id}" ${isChecked ? 'checked' : ''}>`);
            
                        // Monta o label
                        label.append(checkbox).append(' ').append(recurso.nome);
            
                        // Cria o div do recurso com classe do Bootstrap
                        const recursoDiv = $('<div class="form-check"></div>').append(label);
            
                        // Adiciona ao container
                        recursosContainer.append(recursoDiv);
                    });
                },
                error: function(xhr, status, error) {
                    toastr.error('Erro ao carregar recursos: ' + error);
                }
            });
            

            if (recursosSelecionados.length > 0) {
                // Marca 'Sim' e exibe os checkboxes dos recursos
                radioSim.prop('checked', true);
                $('#recurso_adicional_edit').show();
                
            } else {
                // Marca 'Não' e oculta a seleção de recursos
                radioNao.prop('checked', true);
                $('#recurso_adicional_edit').hide();
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao carregar os recursos selecionados: ' + error);
        }
    });

    // Exibe o modal de edição
    $('#modal_mostra_evento').modal('hide');
    $('#modal_editar_evento').modal('show');
});

$('#form_editar_evento').submit(function(event) {

    event.preventDefault(); // Impede o envio do formulário padrão

    var startEventoDate = moment( $('#modal_editar_evento #start_evento').val(), 'DD/MM/YYYY HH:mm[h]').format('YYYY-MM-DD HH:mm:ss');
    var endEventoDate = moment( $('#modal_editar_evento #end_evento').val(), 'DD/MM/YYYY HH:mm[h]').format('YYYY-MM-DD HH:mm:ss');

    var recursos = [];

    if ($('#sim_recurso_edit').is(':checked')) {

        $('#recursosContainer_edit input[type="checkbox"]:checked').each(function() {
            recursos.push($(this).val());
        });

    }
    // console.log('Array de recursos selecionados:', recursos);
    const formData = {
        id: $('#modal_editar_evento #id_evento').val(),
        eventName: $('#modal_editar_evento #nome_evento').val(),
        startDate: startEventoDate,
        endDate: endEventoDate,
        id_agenda: $('#modal_editar_evento #sala').val(),
        id_recurso: recursos
    };

    const allEvents = calendar.getEvents();

    allEvents.forEach(function(e){
        if (e.id === event.id){
            return;
        }
    });



    // ------------------------------------------------------


    // Criar um objeto temporário para verificar sobreposição
    // var tempEvent = {
    //     start: startEventoDate,
    //     end: endEventoDate,
    //     extendedProps: {
    //         agenda_id: formData.id_agenda,
    //         recursos: recursos
    //     }
    // };

    // console.log(tempEvent);
    
    // Verifique sobreposição antes de enviar o evento
    // if (!verificarSobreposicaoEvento2(tempEvent)) {
    //     return; // Se houver sobreposição, cancelar a criação
    // }

    // console.log(tempEvent);


    // console.log(formData);
    
    //envia pra salvar
    $.ajax({
        url: '../../agenda-salas/php_js/editar_eventos.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) { // Se o script PHP retornou success
                calendar.refetchEvents();
                $('#modal_editar_evento').modal('hide');
                $('#recursosContainer').empty();
                toastr.success('Evento alterado com sucesso!');
            } else { // Se o script PHP retornou false
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

});

$('input[name="recurso"]').change(function() {
    if ($(this).val() === 'Sim') {
        $('#recurso_adicional_edit').show();
    } else {
        $('#recurso_adicional_edit').hide();
    }
});

// -----------------------------RESETS
$(document).ready(function(){
    //quando fecha o modal 'criar evento'
    $('#modal_editar_evento').on('hidden.bs.modal', function () { 
        $('#modal_editar_evento #recursosContainer_edit').empty();
        // recursosContainer.empty();
    });


});
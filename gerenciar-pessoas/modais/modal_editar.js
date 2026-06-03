// -----------------------------RESETS
$(document).ready(function(){


});
// ----------------------EVENTOS
$('#form_editar_pessoa').submit(function(e){

    e.preventDefault();

    const formData = {
        codigo_pessoa: $('#modal_editar_pessoa #codigo_pessoa').val(),
        nome_pessoa: $('#modal_editar_pessoa #nome_pessoa').val(),
        tipo_pessoa: $('#modal_editar_pessoa #tipo_pessoa').val(),
        email_pessoa: $('#modal_editar_pessoa #email_pessoa').val(),
        matricula_pessoa: $('#modal_editar_pessoa #matricula_pessoa').val(),
        endereco_pessoa: $('#modal_editar_pessoa #endereco_pessoa').val(),
        telefone_pessoa: $('#modal_editar_pessoa #telefone_pessoa').val(),
        cep_pessoa: $('#modal_editar_pessoa #cep_pessoa').val(),
        municipio: $('#modal_editar_pessoa #municipio').val(),
        estado_sigla: $('#modal_editar_pessoa #estado_sigla').val(),
        nacionalidade: $('#modal_editar_pessoa #nacionalidade').val(),
        cpf_pessoa: $('#modal_editar_pessoa #cpf_pessoa').val(),
        user: $('#modal_editar_pessoa #user').val(),
        administrador: $('#modal_editar_pessoa #administrador').val()

    }

    $.ajax({
        url: '../../gerenciar-pessoas/php_js/atualizar_pessoa.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) { // Se o script PHP retornou success
                $('#lista_pessoas').DataTable().ajax.reload();
                $('#modal_editar_pessoa').modal('hide');
                toastr.success('Cadastro alterado com sucesso!');
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

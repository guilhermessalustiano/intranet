// -----------------------------RESETS
$(document).ready(function(){


});

// ------------------------ EVENTOS

$('#confirmDeleteBtn').on('click', function(e){

    e.preventDefault();
    let id = $('#idPessoaShow').val();
    console.log(id);

    formData = {id: id};

    $.ajax({
        url: 'php_js/excluir_pessoa.php',
        type: 'POST',
        async: false,
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) {
                $('#confirmDeleteModal').modal('hide'); // Fecha o modal após a exclusão
                $('#lista_pessoas').DataTable().ajax.reload();
                toastr.success('Pessoa removida com sucesso!');
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });

});

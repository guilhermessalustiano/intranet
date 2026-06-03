<?php
global $_GET, $_POST;



require("header.php");



$username = $_SESSION['username'];
if (!checkIsAdmin($username)) {
    echo "<div style='text-align:center;'>Área restrita.";
    include("footer.php");
    exit();
}


?>


<!-- recursos disponíveis -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recursos Disponíveis</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody id="recursos-body">
                            <!-- Os dados serão inseridos aqui via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!----------------------  BEGIN CRIAR RECURSO ----------------------->

<div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Criar Recurso</h5 >
          </div>
          <div class="card-body">
          <form id="criar_recurso" method="post">
            <div class="form-row form-group">
              <label class="col-sm-2 col-form-label" for="nome_recurso">Nome:</label>
              <input class="form-control col-sm-6" required type="text" id="nome_recurso" name="nome_recurso">
            </div>
            <div class="form-row form-group">
              <label class="col-sm-2 col-form-label" for="desc_recurso">Descrição:</label>
              <textarea class="form-control col-sm-6" id="desc_recurso" name="desc_recurso"></textarea>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_criar_recurso">Criar</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<!----------------------  END CRIAR RECURSO ----------------------->

<!----------------------  BEGIN MODAL CONFIRMACAO EXCLUSAO ----------------------->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este recurso?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
            </div>
        </div>
    </div>
</div>
<!----------------------  BEGIN MODAL CONFIRMACAO EXCLUSAO ----------------------->

<script>
function mostrar_recursos() {
    $.ajax({
        url: 'php_js/recursos/mostra_recurso.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const tableBody = $('#recursos-body'); // Corrigido para recursos-body
            tableBody.empty(); // Limpa o conteúdo da tabela antes de adicionar os novos dados

            // Itera sobre os dados recebidos e adiciona na tabela
            data.forEach(function(item) {
                var row = '<tr>' +
                          '<td><input type="text" class="form-control-plaintext" value="' + item.nome + '" readonly></td>' +
                          '<td><input type="text" class="form-control-plaintext" value="' + item.descricao + '" readonly></td>' +
                          '<td class="text-center" style="width: 80px;">' +
                          '<button class="btn btn-primary editar-btn" data-id="' + item.id + '"><i class="fas fa-edit"></i></button> ' +
                          '</td>' +
                          '<td class="text-center" style="width: 80px;">' +
                          '<button class="btn btn-danger excluir-btn" data-id="' + item.id + '" data-toggle="modal" data-target="#confirmDeleteModal"><i class="fas fa-trash-alt"></i></button>' +
                          '</td>' +
                          '</tr>';
                tableBody.append(row);
            });

            // Adiciona evento de clique para os botões de editar
            $('.editar-btn').on('click', function() {
                var id = $(this).data('id');
                var row = $(this).closest('tr'); // Obtém a linha correspondente
                editarRecurso(row, id);
            });

            // Adiciona evento de clique para os botões de excluir (abre o modal de confirmação)
            $('.excluir-btn').on('click', function() {
                var id = $(this).data('id');
                $('#confirmDeleteBtn').data('id', id); // Define o ID do recurso no botão de confirmação do modal
            });

            // Evento de confirmação de exclusão ao clicar no botão de confirmar dentro do modal
            $('#confirmDeleteBtn').on('click', function() {
                excluirRecurso($(this).data('id'));
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao obter recursos:', error);
        }
    });
}

function editarRecurso(row, id) {
    // Habilita os campos de entrada para edição
    row.find('input').prop('readonly', false).removeClass('form-control-plaintext').addClass('form-control');

    // Altera o botão de editar para salvar
    var editarBtn = row.find('.editar-btn');
    editarBtn.removeClass('btn-primary editar-btn').addClass('btn-success salvar-btn').html('<i class="fas fa-save"></i>');

    // Adiciona evento de clique para o botão de salvar
    editarBtn.off('click').on('click', function() {
        salvarRecurso(row, id);
    });
}

function salvarRecurso(row, id) {
    // Coleta os dados editados
    var nome = row.find('input').eq(0).val();
    var descricao = row.find('input').eq(1).val();

    var formData = {
        id: id,
        nome_recurso: nome,
        desc_recurso: descricao
    };

    $.ajax({
        url: 'php_js/recursos/salvar_recurso.php',
        type: 'POST',
        async: false,
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) {
                toastr.success('Recurso atualizado com sucesso!');
                mostrar_recursos(); // Atualiza a tabela após salvar
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });
}

function excluirRecurso(id) {
    var formData = { id: id };

    $.ajax({
        url: 'php_js/recursos/excluir_recurso.php',
        type: 'POST',
        async: false,
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) {
                $('#confirmDeleteModal').modal('hide'); // Fecha o modal após a exclusão
                mostrar_recursos();
                toastr.success('Recurso removido com sucesso!');
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });
}

function criar_recurso() {
    const formData = {
        nome_recurso: $('#nome_recurso').val(),
        desc_recurso: $('#desc_recurso').val()
    };

    $.ajax({
        url: 'php_js/recursos/criar_recurso.php',
        type: 'POST',
        async: false,
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) {
                toastr.success('Recurso criado com sucesso!');
                mostrar_recursos(); // Atualiza a tabela após criar
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });
}

  // page load
document.addEventListener('DOMContentLoaded', function() {
    mostrar_recursos();
  });

  // form submit
  $('#criar_recurso').submit(function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário
    criar_recurso();
    mostrar_recursos();
  });
</script>





<?php

include("footer.php");

?>
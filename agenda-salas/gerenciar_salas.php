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
  <!-- agendas disponíveis -->
  <div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Agendas Disponíveis</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cor</th>
                                <th>Descrição</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody id="agenda-body">
                            <!-- Os dados serão inseridos aqui via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!----------------------  BEGIN CRIAR AGENDA ----------------------->

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Criar Agenda</h5 >
          </div>
          <div class="card-body">

          <form id="criar_agenda"  method="post">
            <div class="form-row form-group">
              <label class="col-sm-2 col-form-label"  for="nome_agenda">Nome:</label>
              <input  class="form-control col-sm-6" required type="text" class="form-control" id="nome_agenda" name="nome_agenda">
            </div>

            <div class="form-row form-group">
              <label class="col-sm-2 col-form-label"  for="cor_agenda">Cor:</label>
              <input  class="form-control col-sm-1" value="#cccccc" required type="color" id="cor_agenda" name="cor_agenda" >
            </div>

            <div class="form-row form-group">
              <label class="col-sm-2 col-form-label"  for="desc_agenda">Descrição:</label>
              <textarea  class="form-control col-sm-6"  id="desc_agenda" name="desc_agenda" ></textarea>
            </div>

            <div class="modal-footer">
            <button type="submit" class="btn btn-primary" name="submit_criar_evento">Criar</button>
            </div>
          </form>

          </div>
        </div>
      </div>
    </div>
  </div>

<!----------------------  END CRIAR AGENDA ----------------------->

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
                Tem certeza que deseja excluir esta agenda?
                <br><br>
                <b>Excluir esta agenda apagará todos os eventos relacionados!</b>
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

function mostrar_agendas() {
    $.ajax({
        url: 'php_js/mostra_agenda.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            const tableBody = $('#agenda-body');
            tableBody.empty(); // Limpa o conteúdo da tabela antes de adicionar os novos dados

            // Itera sobre os dados recebidos e adiciona na tabela
            data.forEach(function(item) {
                var row = '<tr>' +
                          '<td><input type="text" class="form-control-plaintext" value="' + item.nome + '" readonly></td>' +
                          '<td style="text-align: center; height: 50px; vertical-align: middle;">' +
                          '<input  style="width: 50px; height: 50px;" type="color" class="form-control-plaintext" value="' + item.backgroundColor + '" disabled>' +

                          '</td>' +
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
                editarAgenda(id);
            });

            // Adiciona evento de clique para os botões de excluir (abre o modal de confirmação)
            $('.excluir-btn').on('click', function() {
                var id = $(this).data('id');
                $('#confirmDeleteBtn').data('id', id); // Define o ID da agenda no botão de confirmação do modal
            });

            // Remove event handlers anteriores e adiciona um novo evento de clique para o botão de confirmação de exclusão
            $('#confirmDeleteBtn').off('click').on('click', function() {
                excluirAgenda($(this).data('id'));
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao obter agendas:', error);
        }
    });
}

function editarAgenda(id) {
    // Seleciona a linha inteira baseada no ID do botão
    var row = $('button[data-id="' + id + '"]').closest('tr');

    // Converte `row` em um objeto jQuery se não for
    row = $(row);

    // Habilita os campos de entrada para edição
    row.find('input').prop('readonly', false).removeClass('form-control-plaintext').addClass('form-control');
    
    // Habilita o campo de input de cor específico para edição
    row.find('input[type="color"]').prop('disabled', false); // Adicionado para habilitar o input de cor

    // Altera o botão de editar para salvar
    var editarBtn = row.find('.editar-btn');
    editarBtn.removeClass('btn-primary editar-btn').addClass('btn-success salvar-btn').html('<i class="fas fa-save"></i>');

    // Adiciona evento de clique para o botão de salvar
    editarBtn.off('click').on('click', function() {
        salvarAgenda(row, id);
    });
}

function salvarAgenda(row, id) {
    // Coleta os dados editados
    var nome = row.find('input[type="text"]').val(); // Obtém o valor do input de texto (Nome)
    var cor = row.find('input[type="color"]').val(); // Obtém o valor do input de cor (Cor)


    


    var descricao = row.find('textarea, input[type="text"]').eq(1).val(); // Obtém o valor da descrição

    var formData = {
        id: id,
        nome_agenda: nome,
        cor_agenda: cor, // Inclui a cor no formData
        desc_agenda: descricao
    };

    $.ajax({
        url: 'php_js/salvar_agenda.php',
        type: 'POST',
        async: false,
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) {
                toastr.success('Agenda atualizada com sucesso!');
                mostrar_agendas(); // Atualiza a tabela após salvar
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });
}
function excluirAgenda(id) {

    var formData = {
        id: id
    };

    $.ajax({
          url: 'php_js/excluir_agenda.php',
          type: 'POST',
          async: false,
          contentType: 'application/json',
          data: JSON.stringify(formData),
          success: function(data) {
              if (data.success) {
                $('#confirmDeleteModal').modal('hide'); // Fecha o modal após a exclusão
                mostrar_agendas();
                toastr.success('Agenda removida com sucesso!');
              } else {
                  toastr.error('Erro: ' + data.error);
              }
          },
          error: function(xhr, status, error) {
              toastr.error('Erro ao comunicar com o servidor!');
          }
      });
}

function criar_agenda() {
    const formData = {
        nome_agenda: $('#nome_agenda').val(),
        cor_agenda: $('#cor_agenda').val(),
        desc_agenda: $('#desc_agenda').val()
    };

    $.ajax({
        url: 'php_js/criar_agenda.php',
        type: 'POST',
        async: false,
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) {
                
                toastr.success('Agenda criada com sucesso!');


            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });
}

// --------------------------------

    //page load
    document.addEventListener('DOMContentLoaded', function() {
      mostrar_agendas();
    });

    // form submit
    $('#criar_agenda').submit(function(event) {
      event.preventDefault(); // Evita o envio padrão do formulário
      criar_agenda();
      mostrar_agendas();

    });


  </script>



<?php

include("footer.php");

?>
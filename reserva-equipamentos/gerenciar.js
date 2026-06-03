// --------------CRIAR EQUIPAMENTO
function criarEquipamento() {
    const formData = {
      patrimonio: $('#patrimonio_equipamento').val() === '' ? null : $('#patrimonio_equipamento').val(),
      nome: $('#nome_equipamento').val(),      
      descricao: $('#desc_equipamento').val(),
      ativo: $('#ativo_equipamento').val(),
      obs: $('#obs_equipamento').val()
    };
    $.ajax({
      url: 'php_js/criar_equipamento.php',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(data) {
        if (data.success) {
          toastr.success('Equipamento criado com sucesso!');
          $('#equipamentos').DataTable().ajax.reload();
        } else {
          toastr.error('Erro: ' + data.error);
        }
      },
      error: function(xhr, status, error) {
        toastr.error('Erro ao comunicar com o servidor!');
      }
    });
}
// --------------EXCLUIR EQUIPAMENTO
function excluirEquipamento(id) {
    const formData = { id: id };
    $.ajax({
        url: 'php_js/excluir_equipamento.php',
        type: 'POST',
        async: false,
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(data) {
            if (data.success) {
                $('#confirmDeleteModal').modal('hide'); // Fecha o modal após a exclusão
                $('#equipamentos').DataTable().ajax.reload();
                toastr.success('Equipamento removido com sucesso!');
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });
}
// --------------EDITAR EQUIPAMENTO
function editarEquipamento(id) {
    let tabela = $('#equipamentos').DataTable();

    // Encontra a linha que está sendo editada
    let tr = $('#equipamentos tbody button.salvar-btn[data-id="' + id + '"]').closest('tr');

    // Cria o objeto com os dados
    let formData = {};

    // Pega todos os inputs com 'name' dentro da linha
    tr.find('input[name], select[name]').each(function () { 
        let nomeCampo = $(this).attr('name');
        let valorCampo = $(this).val();
        formData[nomeCampo] = valorCampo;
    });

    //patrimonio sem texto recebe NULL senao dá duplicado "" string vazia (usado para sem patrimonio)
    if (formData['patrimonio'].trim() === '') {
        formData['patrimonio'] = null;
    }
    
    // Adiciona o ID (chave primária)
    formData['id'] = id;

    // Envia como JSON
    $.ajax({
        url: 'php_js/editar_equipamento.php',
        type: 'POST',
        contentType: 'application/json',  // <- ESSENCIAL para usar JSON no PHP
        data: JSON.stringify(formData),
        success: function (data) {
            if (data.success) {
              tabela.ajax.reload(null, false);
              toastr.success('Equipamento editado com sucesso!');
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function () {
            alert('Erro ao salvar.');
        }
    });
}

// DOCUMENT READY
// -----------
$(document).ready(function() {

  let tabela = new DataTable('#equipamentos', {
    ajax: {
        url: 'php_js/mostrar_equipamentos.php',
        data: function (d) {
        d.filtro = 'gerenciar'; // ou 'todos' ou outro valor
        }
    },
    processing: true,
    serverSide: true,
    scrollX: true,

    columns: [
        { data: 'id', visible: false,  searchable: false}, // oculto mas disponível
        { data: 'nome' },
        { data: 'patrimonio' },
        { data: 'descricao' },
        { 
          data: 'ativo',
          render: function(data, type, row) {
            return data == 1 ? 'Sim' : 'Não';
        }
          
         },
        { data: 'obs' },

        //BOTAO DELETAR
        { 
            data: null, 
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `<button class="btn btn-primary editar-btn" data-id="${row.id}"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger excluir-btn" data-target="#confirmDeleteModal" data-toggle="modal" data-id="${row.id}"><i class="fas fa-trash-alt"></i></button>`;  
                
            }
        }

    ]
});
  

// EVENTOS
// -------

  $('#criar_equipamento').on('submit', function(e) {
    console.log(e);
    e.preventDefault(); // Evita o comportamento padrão do formulário
    criarEquipamento();
  });

// Quando clicar no botão de deletar da tabela, preencher o botão do modal com o ID
$('#equipamentos').on('click', '.excluir-btn', function () { 
    $('#confirmDeleteBtn').data('id', $(this).data('id')); // passa o ID para o botão de confirmação
});

  // Evento de confirmação de exclusão ao clicar no botão de confirmar dentro do modal
$('#confirmDeleteBtn').on('click', function() {
    excluirEquipamento($(this).data('id'));
});


$('#equipamentos tbody').on('click', '.editar-btn', function () {
    let tr = $(this).closest('tr');
    let row = tabela.row(tr);
    let data = row.data();

    // Evita editar novamente se já está editando
    if (tr.hasClass('editando')) return;
    tr.addClass('editando');

    // Pega a definição das colunas
    const colDefs = tabela.settings().init().columns;

    // Para cada célula da linha...
    tr.find('td').each(function () {
        // Índice real da coluna, mesmo se oculta
        let colIndex = tabela.column(this).index();
        let nomeCampo = colDefs[colIndex].data;

        // Ignora colunas sem campo (como botões)
        if (!nomeCampo || nomeCampo === null) return;

        let valor = $(this).text().trim();

        // Se o campo for "ativo", troca por <select>
        if (nomeCampo === 'ativo') {
            let select = `
                <select class="form-control input-editar" name="${nomeCampo}">
                    <option value="1" ${valor === "Sim" ? "selected" : ""}>Sim</option>
                    <option value="0" ${valor === "Não" ? "selected" : ""}>Não</option>
                </select>
            `;
            $(this).html(select);
        } else {
            // Demais campos continuam como <input>
            $(this).html(`<input type="text" class="form-control input-editar" name="${nomeCampo}" value="${valor}">`);
        }
    });

    // Troca o botão de editar por salvar
    $(this)
        .removeClass('btn-primary editar-btn')
        .addClass('btn-success salvar-btn')
        .html('<i class="fas fa-save"></i>');
});

$('#equipamentos tbody').on('click', '.salvar-btn', function () {
    editarEquipamento($(this).data('id'));
});


});






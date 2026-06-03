
// FUNCOES
// -----------

function reservarEquipamento(formData) {

    $.ajax({
      url: 'php_js/criar_reserva.php',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(data) {
        if (data.success) {
                  window.location.href = 'https://intranet.labjor.unicamp.br/reserva-equipamentos/reservas.php?success=1';
        } else {
          toastr.error('Erro: ' + data.message);
        }
      },
      error: function(xhr, status, error) {
        toastr.error(error.message);
      }
    });
  }





// DOCUMENT READY
// -----------

$(document).ready(function() {

// Inicializa tabela EQUIPAMENTOS
  let tabela = new DataTable('#equipamentos', {
    ajax: {
        url: 'php_js/mostrar_equipamentos.php',
        data: function (d) {
            d.filtro = 'reservar'; // ou 'todos' ou outro valor
        }
    },
    processing: true,
    serverSide: true,
    scrollX: true,
    pageLength: 6,
    info: false,

    columns: [
      { data: 'id', visible: false, searchable: false },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          const disabled = Number(row.ativo) !== 1 ? 'disabled' : '';
          return `<input type="checkbox" class="selecionar-equipamento" data-id="${row.id}" ${disabled}>`;
        }
      },
      { data: 'nome' },
      { data: 'patrimonio' },
      { data: 'descricao' },
      {
        data: 'ativo',
        render: function (data, type, row) {
          return Number(data) === 1 ? 'Sim' : 'Não';
        }
      },
      { data: 'obs' }
    ]
  });

// Inicializa tabela PESSOA
let tabela_pessoa = new DataTable('#pessoas', {
  ajax: 'php_js/mostrar_pessoas.php',
  processing: true,
  serverSide: true,
  scrollX: true,
  pageLength: 6,
  info: false,
  columns: [
    // Coluna do input radio (seleção única)
    {
      data: null,
      orderable: false,
      searchable: false,
      render: function (data, type, row) {
        return `<input type="radio" name="selecionar_pessoa" class="selecionar-pessoa" value="${row.codigo}">`;
      }
    },
    { data: 'codigo', visible: false, searchable: false },
    { data: 'nome' },
    { data: 'isDocente', visible: false, searchable: false },
    { data: 'isAluno', visible: false, searchable: false },
    { data: 'isFuncionario', visible: false, searchable: false },


    // Coluna do input radio (seleção única)
    {
      data: null,
      orderable: false,
      searchable: false,
      render: function (data, type, row) {
        if (row.isDocente == 1) {
          return 'Docente';
        } else if (row.isAluno == 1) {
          return 'Aluno';
        } else if (row.isFuncionario == 1) {
          return 'Funcionário';
        } else {
          return 'Outro';
        }
      }
    },

    { data: 'matricula' },
    { data: 'email' },
    { data: 'telefone' },
    { data: 'cpf' },


  ]
});
});



// EVENTOS
// -----------
  $(document).on('click', '.reservar-btn', function () {
    // Pegar o ID do equipamento selecionado (checkbox marcado)


    const idsEquipamentos = $('#equipamentos .selecionar-equipamento:checked')
      .map(function () {
        return $(this).data('id');
      })
      .get(); // transforma em array real


    // Pegar o ID da pessoa selecionada (radio marcado)
    const pessoaSelecionada = $('#pessoas .selecionar-pessoa:checked');
    const idPessoa = pessoaSelecionada.val();
    const obs_emp = $('#obs_emp').val();

    // console.log(obs_emp);
    // return; // DEBUG
    let dtfim = $('#dtfim').val();
    const dtfim_compacta = dtfim.replace(/-/g, ''); 
    const dthoje = new Date();
    const dthoje_compacta = dthoje.getFullYear()+String(dthoje.getMonth() + 1).padStart(2, '0')+String(dthoje.getDate()).padStart(2, '0');

    // Validações
    if (!idPessoa) { toastr.warning('Selecione uma pessoa.');return;}
    if (idsEquipamentos.length === 0) {toastr.warning('Selecione pelo menos um equipamento disponível.');return;}
    if (!dtfim) { toastr.warning('Selecione a data de entrega.');return;}
    if (dtfim_compacta < dthoje_compacta) { toastr.warning('A data de entrega não pode ser anterior ao dia de hoje.');return;}


    dtfim = dtfim+' 17:30:00';

    // Montar objeto e chamar função
    const formData = {
      ids_equipamentos: idsEquipamentos,
      codigo_pessoa: idPessoa,
      obs_emp : obs_emp,
      dtfim : dtfim
    };

    reservarEquipamento(formData);
  });
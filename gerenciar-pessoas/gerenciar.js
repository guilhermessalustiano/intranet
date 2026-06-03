// FUNCOES

function verificarTipo(){

    let tipoPessoa = $('#tipo').val();
    let isAluno = 0;
    let isDocente = 0;
    let isFuncionario = 0;
    let isExterno = 0;
    
    if (tipoPessoa === 'Aluno'){
        isAluno = 1;
    } else if (tipoPessoa === 'Docente'){
        isDocente = 1;
    } else if (tipoPessoa === 'Funcionario'){
        isFuncionario = 1;
    } else {
        isExterno = 1;
    }

    return { isAluno, isDocente, isFuncionario, isExterno };
}




// CADASTRAR PESSOA - AJAX
// -----------------------------------------------------------------------------
function cadastrarPessoa() {

    let { isAluno, isDocente, isFuncionario, isExterno } = verificarTipo();
    let user_temp = "";

    if ($('#usuario').val()=== "") {
        user_temp = null;
    }
    else {
        user_temp = $('#usuario').val();
    }

    const formData = { //comum a todas pessoas
        tipo: $('#tipo').val(),
        nome: $('#nome').val(),
        email: $('#email').val(),      
        endereco: $('#endereco').val(),
        telefone: $('#telefone').val(),
        cep: $('#cep').val(),
        cidade: $('#cidade_pessoa').val(),
        // estado: $('#estado').val(),
        estado: $('#estado').val() === '' ? null : $('#estado').val(),
        pais: $('#pais_pessoa option:selected').text(),
        cpf: $('#cpf').val(),
        data_cadastro: obterDataAtual(),
        acesso_sistema: $('#acesso_sistema').val() === "1" ? 1 : 0, // converte para 1 ou 0
        is_admin: $('#is_admin').val() === "1" ? 1 : 0 // converte para 1 ou 0
    };
    if (isExterno === 1) {
        formData.is_docente = 0;
        formData.is_admin = 0;
        formData.is_aluno = 0;
        formData.is_funcionario = 0;
        formData.is_externo = 1;
        formData.vinculo = $('#responsavel').val();
        formData.matricula = null;
    }
    if (isDocente === 1) {
        formData.matricula = $('#matricula').val();
        formData.is_docente = 1;
        formData.is_aluno = 0;
        formData.is_funcionario = 0;
        formData.is_externo = 0;
        formData.vinculo=null;
    }
    if (isFuncionario === 1) {
        formData.matricula = $('#matricula').val();
        formData.is_docente = 0;
        formData.is_aluno = 0;
        formData.is_funcionario = 1;
        formData.is_externo = 0;
        formData.vinculo=null;
    }
    if (isAluno === 1) {
        formData.matricula = $('#matricula').val();
        formData.is_docente = 0;
        formData.is_admin = 0;
        formData.is_aluno = 1;
        formData.is_funcionario = 0;
        formData.is_externo = 0;
        formData.vinculo=null;
    }
    // defini acesso ao sistema
    if ($('#acesso_sistema').val() === "1") { // SIM
        formData.usuario = $('#usuario').val();
        formData.senha = $('#senha').val();
        formData.modulos = modulos_selecionados;
    } else {
        formData.usuario = null;    
        formData.senha = null;    
    }

    $.ajax({
      url: 'php_js/cadastrar_pessoa.php',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(data) {
        if (data.success) {
          toastr.success('Pessoa cadastrada com sucesso!');
          // Reload data from the AJAX source
          $('#lista_pessoas').DataTable().ajax.reload();
        } else {
          toastr.error('Erro: ' + data.error);
        }
      },
      error: function(xhr, status, error) {
        toastr.error('Erro ao comunicar com o servidor!');
      }
    });
}


// -----------------------------------------------------------------------------






let globalPais = "";

function carregarPaises(paisSelecionado) {
    $.ajax({
        url: "php_js/consulta_pais.php",
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#pais_pessoa').empty().append('<option value="">Selecione</option>');
            
            $.each(data, function(index, item) {
                let option = $('<option>', {
                    value: item.codigo,
                    text: item.pais
                });

                // Seleciona se for o país da pessoa
                if (item.pais === paisSelecionado) {
                    option.prop('selected', true);
                }

                $('#pais_pessoa').append(option);
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar países:', error);
        }
    });
}

function carregarCidades(uf, cidadeSelecionada) {
    $.ajax({
        type: "GET",
        url: "php_js/consulta_cidades.php",
        dataType: "json",
        data: { uf: uf },
        success: function(response) {
            $('#cidade_pessoa').empty();
            $('#cidade_pessoa').append('<option value=""></option>');

            $.each(response, function(key, item) {
                let option = $('<option>', {
                    value: item.nome,
                    text: item.nome
                });

                if (item.nome === cidadeSelecionada) {
                    option.prop('selected', true);
                }
                $('#cidade_pessoa').append(option);
            });
        },
        error: function(request, status, error) {
            console.error(request.responseText);
        }                
    });
}

    function obterResponsavel(){
        $.ajax({
            type: "GET",
            url: "php_js/consulta_responsavel.php",
            dataType: "json",
            success: function(response){
                let $select = $('#responsavel');
                $select.empty();
                $select.append('<option value="">Selecione</option>');

                $.each(response, function(index, item){
                    $select.append(
                        $('<option>', {
                            value: item.codigo,
                            text: item.nome
                        })
                    );
                });
            },
            error: function(request, status, error){
                console.error(request.responseText);
            }
        })
    }


function obterDataAtual(){
    let hoje = new Date();

    let ano = hoje.getFullYear();
    let mes = String(hoje.getMonth() + 1).padStart(2,'0'); //  +1 porque janeiro = 0
    let dia = String(hoje.getDate()).padStart(2, '0');

    return `${ano}-${mes}-${dia}`;
}

// controle de acesso ao sistema
function controlarAcessoSistema() {
    let acesso = $('#acesso_sistema').val();

    if (acesso === "1") { // SIM
        $('#campo_admin').show();
        $('#campo_username').show();
        $('#camposenha').show();
        $('#campo_modulos').show();

        $('#usuario').prop('required', true);
        $('#senha').prop('required', true);

    } else { // NÃO
        $('#campo_admin').hide();
        $('#campo_username').hide();
        $('#camposenha').hide();
        $('#campo_modulos').hide();

        $('#usuario').val('');
        $('#senha').val('');
        $('#is_admin').val('0'); // já que admin não faz sentido sem acesso        

        $('#usuario').prop('required', false);
        $('#senha').prop('required', false);
    }
}



function obterModulos(){
    $.ajax({
            type: "GET",
            url: "php_js/obter_modulos.php",
            dataType: "json",
            success: function(response){
                let $container = $('#campo_modulos');
                $container.empty();

                $.each(response, function(index, item){
                    let checkbox = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="modulo_${item.id}" name="modulos[]" value="${item.id}">
                        <label class="form-check-label" for="modulo_${item.id}">${item.nome}</label>
                    </div>
                `;
                $container.append(checkbox);

                });
            },
            error: function(request, status, error){
                console.error(request.responseText);
            }
    });

}





// DOCUMENT READY
$(document).ready(function() {
  
    let tabela_pessoas = new DataTable('#lista_pessoas', {
    ajax: 'mostrar_pessoa.php',
    processing: true,
    serverSide: true,
    scrollX: false,
    columns: [
        // { data: 'id', visible: false }, // oculto mas disponível
        { data: 'codigo', visible: false,  searchable: false}, // oculto mas disponível
        { data: 'nome' },
        { data: 'tipo' },
        { data: 'email' },
        { data: 'matricula' },
        { data: 'telefone' },
        { data: 'usuario' },
        { 
          data: 'is_admin',
          render: function(data, type, row) {
            return data == 1 ? 'Sim' : 'Não';
            }
          
        },
        //BOTAO DELETAR
        { 
            data: null, 
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `<button class="btn btn-primary mostrar_mais_pessoa" data-id="${row.codigo}"><i class="fas fa-plus-circle" ></i></button>`; 
            }
        }

    ],
  });

  carregarPaises("Brasil");

  $('#estado').on('change', function(){
    let uf = $(this).val();
    carregarCidades(uf, null);
  });


    // estado inicial: sempre começa com "Não"
$('#acesso_sistema').val("0");
controlarAcessoSistema();

// evento para alternar quando muda
$('#acesso_sistema').on('change', function() {
    controlarAcessoSistema();
    obterModulos();
});





});

dadosFullPessoa = "";

// EVENTOS
// -------------------------------------------------
$('#cadastrar_pessoa').on('submit', function(e) {
    e.preventDefault(); // Evita o comportamento padrão do formulário
    cadastrarPessoa();
});


$('#pais_pessoa').on('change', function() {
    globalPais = $(this).find("option:selected").text();

    if (globalPais !== "Brasil") {
        // limpa e esconde campos relacionados ao Brasil
        $('#estado, #cidade_pessoa, #cep, #endereco')
            .val("")               // limpa valor (null/vazio)
            .prop("disabled", true); // opcional: desabilita

        $('#campo_estado, #campo_cidade,  #campo_end, #campo_cep' ).hide();
    } else {
        // reabilita e mostra novamente
        $('#estado, #cidade_pessoa, #cep, #endereco')
            .val("")
            .prop("disabled", false);

        $('#campo_estado, #campo_cidade,  #campo_end, #campo_cep' ).show();

        carregarCidades();
    }
});




$('#tipo').on('change', function () {
    verificarTipo();

    let tipo = $(this).val();
    let responsavelDiv = $('#responsavelDiv');

    // controla exibição da seção de acesso ao sistema
    if (tipo === 'Docente' || tipo === 'Funcionario') {
        $('#secao_acesso_sistema').show();
    } else {
        $('#secao_acesso_sistema').hide();
        // zera valores ao esconder para evitar lixo no form
        $('#acesso_sistema').val("0");
        controlarAcessoSistema();
    }

    if (tipo === 'Externo') {
        if (responsavelDiv.length === 0) {
            let campoResponsavel = `
              <div class="form-group row" id="responsavelDiv">
                  <label for="responsavel" class="col-sm-2 col-form-label">Responsável:</label>
                  <div class="col-sm-6">
                      <select id="responsavel" name="responsavel" class="form-control" required></select>
                  </div>
              </div>
            `;
            obterResponsavel();
            $('#tipo').closest('.form-group').after(campoResponsavel);
        }
        $('#campo_matricula').hide();
        $('#matricula').prop('required', false);

    } else if (tipo === 'Aluno') {
        responsavelDiv.remove(); // 🔥 garante que some aqui também
        $('#campo_matricula').show();
        $('#matricula').prop('required', true);

    } else {
        // docente ou funcionario
        responsavelDiv.remove();
        $('#campo_matricula').show();
        $('#matricula').prop('required', true);
    }
});

$(document).on('click', '.mostrar_mais_pessoa', function(){
  let idPessoa = $(this).data('id');
  $('#modal_mostra_pessoa').modal('show');
  $.ajax({
    url: 'php_js/mostrar_full_pessoa.php',
    type: 'POST',
    dataType: 'json',
    data: { id_pessoa: idPessoa },
    success: function (resposta) {
        dadosFullPessoa = resposta;
        ModalShowPessoa();
        // fazer algo com os dados
    },
    error: function (xhr, status, error) {
        console.error("Erro AJAX:", status, error);
    }
  });
});


let modulos_selecionados = [];
// captura mudança em qualquer checkbox dentro do container
$(document).on('change', '#campo_modulos input[type="checkbox"]', function(){
    
    modulos_selecionados = [];

    $('#campo_modulos input[type="checkbox"]:checked').each(function(){
        modulos_selecionados.push($(this).val());
    });

    console.log("Módulos selecionados: ", modulos_selecionados);

  
});
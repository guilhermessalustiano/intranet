function mostrarOuEsconder(campoID, valor){
    if (valor && valor.trim()!==''){
        $('#'+campoID).text(valor).parent().show();
    } else{
        $('#'+campoID).parent().hide();
    }
}


function ModalShowPessoa() {

    let admin = (dadosFullPessoa.isAdmin == 1) ? "Sim" : "Não";
    let data_cadastro = dadosFullPessoa.datacadastro || '';
    let data_formatada = data_cadastro ? moment(data_cadastro).format("DD/MM/YYYY"): '';


    $('#nomeMostrar').text(dadosFullPessoa.nome);
    // $('#tipopessoaMostrar').text(dadosFullPessoa.tipopessoa);
    // $('#emailMostrar').text(dadosFullPessoa.email);
    // $('#matriculaMostrar').text(dadosFullPessoa.matricula);
    // $('#enderecoMostrar').text(dadosFullPessoa.endereco || '');
    // $('#telefoneMostrar').text(dadosFullPessoa.telefone || '');
    // $('#cepMostrar').text(dadosFullPessoa.cep || '');
    // $('#cidadeMostrar').text(dadosFullPessoa.cidade || '');
    // $('#estadoMostrar').text(dadosFullPessoa.estado || '');
    // $('#paisMostrar').text(dadosFullPessoa.pais || '');
    // $('#cpfMostrar').text(dadosFullPessoa.cpf || '');
    // $('#dataMostrar').text(data_formatada);
    // $('#usuarioMostrar').text(dadosFullPessoa.usuario);
    // $('#adminMostrar').text(admin);
    
    mostrarOuEsconder('tipopessoaMostrar', dadosFullPessoa.tipopessoa);
    mostrarOuEsconder('responsavelMostrar', dadosFullPessoa.nome_vinculo);
    mostrarOuEsconder('emailMostrar', dadosFullPessoa.email);
    mostrarOuEsconder('matriculaMostrar', dadosFullPessoa.matricula);
    mostrarOuEsconder('telefoneMostrar', dadosFullPessoa.telefone);
    mostrarOuEsconder('cpfMostrar', dadosFullPessoa.cpf);
    mostrarOuEsconder('cepMostrar', dadosFullPessoa.cep);
    mostrarOuEsconder('enderecoMostrar', dadosFullPessoa.endereco);
    mostrarOuEsconder('cidadeMostrar', dadosFullPessoa.cidade);
    mostrarOuEsconder('estadoMostrar', dadosFullPessoa.estado);
    mostrarOuEsconder('paisMostrar', dadosFullPessoa.pais);
    mostrarOuEsconder('dataMostrar', data_formatada);
    mostrarOuEsconder('usuarioMostrar', dadosFullPessoa.usuario);
    mostrarOuEsconder('adminMostrar', admin);
    
    $('#idPessoaShow').val(dadosFullPessoa.codigo);
}

// -----------------------------RESETS
$(document).ready(function(){


});



// ----------------------------- EVENTOS
$('.excluirpessoaBtn').on('click', function(){

    $('#modal_mostra_pessoa').modal('hide');
    $('#confirmDeleteModal').modal('show');

});

$('.editarpessoaBtn').on('click', function(){

    $('#modal_mostra_pessoa').modal('hide');

    // Preenche o modal de editar com dadosFullPessoa
    $('#nome_pessoa').val(dadosFullPessoa.nome);
    $('#tipo_pessoa').val(dadosFullPessoa.tipopessoa);
    $('#email_pessoa').val(dadosFullPessoa.email);
    $('#matricula_pessoa').val(dadosFullPessoa.matricula);
    $('#telefone_pessoa').val(dadosFullPessoa.telefone || '');
    $('#cpf_pessoa').val(dadosFullPessoa.cpf || '');
    $('#cep_pessoa').val(dadosFullPessoa.cep || '');
    $('#endereco_pessoa').val(dadosFullPessoa.endereco || '');
    $('#cidade').val(dadosFullPessoa.cidade || '');
    $('#estado_sigla').val(dadosFullPessoa.estado || '');
    $('#pais').val(dadosFullPessoa.pais || '');
    $('#user').val(dadosFullPessoa.usuario);
    $('#administrador').val(dadosFullPessoa.isAdmin);
    $('#codigo_pessoa').val(dadosFullPessoa.codigo);

    carregarPaises();

    $('#estado_sigla').on('change', function(){
        let uf = $(this).val();
        carregarCidades(uf, null);
    });




    $('#modal_editar_pessoa').modal('show');

});
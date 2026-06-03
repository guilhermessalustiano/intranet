// DOCUMENT READY
$(document).ready(function() {

    // Tabela emprestimos ativos
    new DataTable('#lista_emprestimos', {
        ajax: {
            url: 'php_js/mostrar_reserva.php',
            type: 'GET', 
            data: function (d) {
            d.status = 'pendente'; // ou 'devolvido'
            }
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: 'id', visible: false,  searchable: false}, // oculto mas disponível
            { data: 'nome' },
            {
                data: 'equipamentos',
                orderable: false,
                searchable: false,
                className: 'equipamentos-col',
                render: function(data, type, row) {
                    if (!data) return "Sem equipamentos";
                    
                    let itens = data.split(';'); // separa por ;
                    let html = "";
                    let flag=1;
                    itens.forEach(item => {
                        html += ` <b>${flag})</b> ${item}<br>`;
                        flag++;
                    });
                    return html;
                }
            },               
            { data: 'tipopessoa'},
            { data: 'email'},
            { 
                data: 'telefone',
                width: "150px"  


            },
            { data: 'dt_inicio'},
            {
            data: 'dt_fim',
            render: function (data, type, row) {
                if (!data) return "";
                let partes = data.split("-");
                return partes[2] + "/" + partes[1] + "/" + partes[0]; // DD/MM/YYYY
            }
            },
            { data: 'obs_emp', visible: true,  searchable: false, ClassName: 'obs-col' }, // oculto mas disponível
            {
                data: null, 
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<button class="btn btn-primary btn-devolver" data-id="${row.id}"><i class="fa-solid fa-arrow-rotate-left" ></i></button>`; 
                }
            },
            { 
                data: null, 
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<a class="btn btn-primary" href="/reserva-equipamentos/pdfs/emprestimos/${row.id}.pdf" target="_blank"><i class="fa-solid fa-download"></i></a>`;
                }
            },
            { 
                data: null, 
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<button class="btn-primary btn  excluir-btn" data-target="#confirmDeleteModal" data-toggle="modal" data-id="${row.id}"><i class="fas fa-trash-alt"></i></button>`;    
                }
            }            
        ]
        ,
        createdRow: function(row, data, dataIndex) {
            // Pega a data de fim vinda do servidor
            let dtFim = data.dt_fim; // Ex: "2025-08-15"
            let hoje = new Date().toISOString().split('T')[0]; // "2025-08-18"

            console.log(dtFim);
            console.log(hoje);
            if (dtFim < hoje) {
                $(row).addClass('table-danger'); 
            }
        }            
    });

    // Tabela emprestimos finalizados
    new DataTable('#lista_emprestimos_finalizados', {
    ajax: {
        url: 'php_js/mostrar_reserva.php',
        type: 'GET', 
        data: function (d) {
        // Adiciona o parâmetro que será enviado para o PHP
        d.status = 'devolvido'; // ou 'devolvido'
        }
    },
    processing: true,
    serverSide: true,
    scrollX: true,
    columns: [
        { data: 'id', visible: false,  searchable: false}, // oculto mas disponível
        { data: 'nome' },
            {
                data: 'equipamentos',
                orderable: false,
                searchable: false,
                className: 'equipamentos-col',
                render: function(data, type, row) {
                    if (!data) return "Sem equipamentos";
                    
                    let itens = data.split(';'); // separa por ;
                    let html = "";
                    let flag=1;
                    itens.forEach(item => {
                        html += ` <b>${flag})</b> ${item}<br>`;
                        flag++;
                    });
                    return html;
                }
            },            
        { data: 'tipopessoa'},
        { data: 'email'},
        { data: 'telefone', visible: true,  searchable: true,  className: 'telefone-col'}, // oculto mas disponível     
        { data: 'dt_inicio'},
            {
            data: 'dt_fim',
            render: function (data, type, row) {
                if (!data) return "";
                // data vem como "2025-08-15"
                let partes = data.split("-");
                return partes[2] + "/" + partes[1] + "/" + partes[0]; // DD/MM/YYYY
            }
            },
        { data: 'dt_devol'},
        { data: 'obs_devol', visible: true,  searchable: false, className: 'obs-col' }, // oculto mas disponível     
        {
            data: null, 
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `<a class="btn btn-primary" href="/reserva-equipamentos/pdfs/emprestimos/${row.id}.pdf" target="_blank"><i class="fa-solid fa-download"></i></a>`;
            }
        },
        {
            data: null, 
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `<a class="btn btn-primary" href="/reserva-equipamentos/pdfs/devolucoes/${row.id}.pdf" target="_blank"><i class="fa-solid fa-download"></i></a>`;
            }
        }             
    ]    
    });
});



$(document).on('click', '#btnConfirmarDevolucao', function () {


    let arquivo = $('#documento_devolucao')[0].files[0];
    if (!arquivo) {
        toastr.error('Por favor, selecione o termo assinado (PDF).');
        return; // Impede o envio
    }

    let formData = new FormData();
    formData.append('id', $('#devolver_emprestimo_id').val());
    formData.append('documento', $('#documento_devolucao')[0].files[0]);
    formData.append('obs_devol', $('#obs_devol').val());

    $.ajax({
        url: 'php_js/devolver.php',
        type: 'POST',
        data: formData,
        processData: false, // não processa o formData
        contentType: false, // deixa o jQuery definir o content-type automaticamente
        success: function(data) {
            if (data.success) {
                $('#modalEncerrar').modal('hide'); // Fecha o modal após a exclusão
                $('#lista_emprestimos').DataTable().ajax.reload();
                $('#lista_emprestimos_finalizados').DataTable().ajax.reload();
                toastr.success('Empréstimo finalizado com sucesso!');
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function () {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });
});

$(document).on('click', '#btnConfirmarExclusao', function () {


    let id = $('#excluir_emprestimo_id').val();
    $.ajax({
        url: 'php_js/excluir_reserva.php',
        type: 'POST',
        data: id,
        processData: false, // não processa o formData
        contentType: false, // deixa o jQuery definir o content-type automaticamente
        success: function(data) {
            if (data.success) {
                $('#modalExclusao').modal('hide'); // Fecha o modal após a exclusão
                $('#lista_emprestimos').DataTable().ajax.reload();
                $('#lista_emprestimos_finalizados').DataTable().ajax.reload();
                toastr.success('Empréstimo removido com sucesso!');
            } else {
                toastr.error('Erro: ' + data.error);
            }
        },
        error: function () {
            toastr.error('Erro ao comunicar com o servidor!');
        }
    });
});

// Ao clicar no botão de devolução
$(document).on('click', '.btn-devolver', function () {
    const id = $(this).data('id');
    $('#devolver_emprestimo_id').val(id);
    $('#documento_devolucao').val(''); // Limpa input de arquivo
    $('#modalEncerrar').modal('show');
});

// Botao upload 
document.getElementById('custom-file-button').addEventListener('click', function() {
  document.getElementById('documento_devolucao').click();
});

document.getElementById('documento_devolucao').addEventListener('change', function(e) {
  const fileName = e.target.files[0]?.name || "Nenhum arquivo selecionado";
  document.getElementById('file-custom-name').value = fileName;
});


// Ao clicar no botão de devolução
$(document).on('click', '.excluir-btn', function () {
    const id = $(this).data('id');
    $('#excluir_emprestimo_id').val(id);
    $('#modalExclusao').modal('show');
});


// Ao clicar no botão de devolução
$(document).on('click', '.excluir-btn', function () {
    const id = $(this).data('id');
    $('#excluir_emprestimo_id').val(id);
    $('#modalDevolucao').modal('show');
});


<?php
require('header.php');
?>

<div class="col-md-11" id="tabela_emprestimos">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Empréstimos Ativos</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        
                    </div>
                    <br>
                    <table class="table table-bordered" id="lista_emprestimos">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Pessoa</th>
                                <th>Equipamentos</th>
                                <th>Vinculo</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Data de Empréstimo</th>
                                <th>Data para Devolução</th>
                                <th>Obs.</th>
                                <th>Encerrar</th>
                                <th>Termo Empréstimo</th>
                                <th>Excluir</th>

                            </tr>
                        </thead>
                        <tbody id="emprestimos-body">
                            <!-- Os dados serão inseridos aqui via JavaScript -->
                        </tbody>
                    </table>
                    <!-- Paginação -->
                    <nav>
                        <ul class="pagination justify-content-center" id="pagination">
                            <!-- Botões de paginação serão inseridos aqui via JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <br>
    <!-- <hr></hr> -->
    <br>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Empréstimos Finalizados</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        
                    </div>
                    <br>
                    <table class="table table-bordered" id="lista_emprestimos_finalizados">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Pessoa</th>
                                <th>Equipamentos</th>
                                <th>Vinculo</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Data de Empréstimo</th>
                                <th>Data para Devolução</th>
                                <th>Devolução Realizada</th>
                                <!-- <th>Obs. Empréstimo</th> -->
                                <th>Obs. Devolução</th>
                                <th>Termo Empréstimo</th>
                                <th>Termo Devolução</th>
                            </tr>
                        </thead>
                        <tbody id="emprestimos-body">
                            <!-- Os dados serão inseridos aqui via JavaScript -->
                        </tbody>
                    </table>
                    <!-- Paginação -->
                    <nav>
                        <ul class="pagination justify-content-center" id="pagination">
                            <!-- Botões de paginação serão inseridos aqui via JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>



</div>



<!-- --------------------  BEGIN MODAL ENCERRAR EMPRESTIMO ----------------------->
<div class="modal fade " id="modalEncerrar" tabindex="-1" role="dialog" aria-labelledby="modal_editar_eventoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_editar_eventoLabel">Encerrar Empréstimo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form method="post" id="form_editar_evento">

            <input type="hidden" id="devolver_emprestimo_id">

            <div class="form-group">
                <label for="documento_devolucao" class="form-label">Termo assinado:</label>
                <div class="input-group">
                    <!-- Input escondido (real) -->
                    <input 
                    type="file" 
                    class="form-control d-none" 
                    id="documento_devolucao" 
                    accept="application/pdf"
                    >
                    <!-- Input fake (customizado) -->
                    <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Nenhum arquivo selecionado" 
                    id="file-custom-name" 
                    readonly
                    >
                    <button class="btn btn-outline-secondary" type="button" id="custom-file-button">
                    <i class="bi bi-upload"></i> Selecionar
                    </button>
                </div>
                <small class="text-muted">Arquivo PDF.</small>
            </div>

            <div class="form-group">
                <label for="obs_devol">Observação:</label>
                <input type="text" class="form-control" id="obs_devol" name="obs_devol" >
            </div>
          
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-success" id="btnConfirmarDevolucao" name="">Confirmar</button>
          </div>
        </form>
      </div>  
      </div>  
    </div>
  </div>
</div>
<!-- --------------------  END MODAL ENCERRAR EMPRESTIMO ----------------------->



<!-- --------------------  BEGIN MODAL EXCLUIR EMPRESTIMO ----------------------->
<div class="modal fade " id="modalExclusao" tabindex="-1" role="dialog" aria-labelledby="modal_editar_eventoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_editar_eventoLabel">Confirmação</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Tem certeza que deseja excluir este empréstimo?</p>
        <form method="post" id="">
            <input type="hidden" id="excluir_emprestimo_id">
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-success" id="btnConfirmarExclusao" name="">Confirmar</button>
          </div>
        </form>
      </div>  
      </div>  
    </div>
  </div>
</div>
<!-- --------------------  END MODAL EXCLUIR EMPRESTIMO ----------------------->

<style>
  #lista_emprestimos {
    border-style: solid;
    border-width: 1px
  }

  #tabela_emprestimos{
    margin: auto;
  }

  #pesquisa{
    text-align: right;
  }




</style>


<script src="reservas.js"></script>


<?php
require('footer.php');
?>

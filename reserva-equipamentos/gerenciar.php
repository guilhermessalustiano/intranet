<?php
require('header.php');
?>





<!------------------------- CRIAR EQUIPAMENTO -------------------------------->
<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5  class="card-title">Cadastrar Equipamento</h5>
        </div>
        <div class="card-body">
          <form id="criar_equipamento" method="post">
            <div class="form-group row">
              <label for="patrimonio_equipamento" class="col-sm-2 col-form-label">Patrimônio:</label>
              <div class="col-sm-6">
                <input type="text" id="patrimonio_equipamento" name="patrimonio_equipamento" class="form-control">
              </div>
            </div>               

            <div class="form-group row">
              <label for="nome_equipamento" class="col-sm-2 col-form-label">Nome:</label>
              <div class="col-sm-6">
                <input type="text" id="nome_equipamento" name="nome_equipamento" class="form-control" required>
              </div>
            </div>
  
            <div class="form-group row">
              <label for="desc_equipamento" class="col-sm-2 col-form-label">Descrição:</label>
              <div class="col-sm-6">
                <textarea id="desc_equipamento" name="desc_equipamento" class="form-control"></textarea>
              </div>
            </div>               
            
              <div class="form-group row">
                <label for="ativo_equipamento" class="col-sm-2 col-form-label">Ativo:</label>
                <div class="col-sm-2">
                  <select id="ativo_equipamento" name="ativo_equipamento" class="form-control">
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                  </select>
                </div>
            </div>     

              <div class="form-group row">
                <label for="obs_equipamento" class="col-sm-2 col-form-label">Obs:</label>
                <div class="col-sm-6">
                  <textarea id="obs_equipamento" name="obs_equipamento" class="form-control"></textarea>
                </div>
            </div>    


            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_criar_equipamento">Cadastrar</button>
            </div>

            
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!----------------------- END CRIAR EQUIPAMENTO ------------------------------------>

<!----------------------- TABELA EQUIPAMENTOS ------------------------------------>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Equipamentos Disponíveis</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered stripe" id="equipamentos">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Nome</th>
                                <th>Patrimônio</th>
                                <th>Descrição</th>
                                <th>Ativo</th>
                                <th>Observação</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                            <tbody id="equipamentos-body">
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
<!----------------------- END TABELA EQUIPAMENTOS ------------------------------------>
<br>






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
                Tem certeza? O equipamento não irá ser mais emprestável!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
            </div>
        </div>
    </div>
</div>
<!----------------------  BEGIN MODAL CONFIRMACAO EXCLUSAO ----------------------->





<script src="gerenciar.js"></script>


<?php
require('footer.php');
?>

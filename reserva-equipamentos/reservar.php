<?php
require('header.php');
?>


<!-- SOLICITANTES -->
<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">

        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">Reservar</h5>
        </div>


        <div class="card-body">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Solicitante</h5>
            <a href="#cadastrar_pessoa"> 
              <button type="submit" class="btn btn-primary" name=""><i class="fas fa-plus-circle"></i></button>
            </a>
          </div>


          <table class="table table-bordered stripe" id="pessoas">
            <thead>
              <tr>
                <th></th>
                <th>codigo</th>
                <th>Nome</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Matrícula</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>CPF</th>
              </tr>
            </thead>
            <tbody id="pessoas-body">
            <!-- Os dados serão inseridos aqui via JavaScript -->
            </tbody>
          </table>
          <!-- Paginação -->
          
          <nav>
              <ul class="pagination justify-content-center" id="pagination">
                  <!-- Botões de paginação serão inseridos aqui via JavaScript -->
              </ul>
          </nav>



          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Equipamento disponíveis</h5>

            <a href="#cadastrar_pessoa">
              <button type="submit" class="btn btn-primary" name=""><i class="fas fa-plus-circle"></i></button>
            </a>

          </div>

          <table class="table table-bordered stripe" id="equipamentos">
            <thead>
              <tr>
                <th>id</th>
                <th></th>
                <th>Nome</th>
                <th>Patrimônio</th>
                <th>Descrição</th>
                 <th>Ativo</th> 
                <th>Observação</th>
              </tr>
            </thead>
              <tbody id="equipamentos-body">
              <!-- Os dados serão inseridos aqui via JavaScript -->
            </tbody>
          </table>
          <nav>
              <ul class="pagination justify-content-center" id="pagination">
              </ul>
          </nav>

            <div class="form-group row">
              <label for="dtfim" class="col-sm-2 col-form-label">Data de entrega:</label>
              <div class="col-sm-6">
                <input type="date" id="dtfim" name="dtfim" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <label for="obs_emp" class="col-sm-2 col-form-label">Observação:</label>
              <div class="col-sm-6">
                <input type="text" id="obs_emp" name="obs_emp" class="form-control">
              </div>
            </div>


          <div class="modal-footer">
            <button type="submit" class="btn btn-primary reservar-btn" name="submit_reservar"><i class="fas fa-clipboard-list"></i> Reservar</button>
          </div>


        </div>

        
      </div>




      
    </div>




  </div>
</div>


<br>





<!----------------------- END CRIAR EQUIPAMENTO ------------------------------------>

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
                Tem certeza? TODOS os empréstimos deste equipamento serão excluídos!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
            </div>
        </div>
    </div>
</div>
<!----------------------  BEGIN MODAL CONFIRMACAO EXCLUSAO ----------------------->





<script src="reservar.js"></script>


<?php
require('footer.php');
?>

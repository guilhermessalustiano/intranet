<?php

require('header.php');

?>


<div class="col-md-11" id="tabela_pessoas">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Lista de Pessoas</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        
                    </div>
                    <br>
                    <table class="table table-bordered" id="lista_pessoas">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Email</th>
                                <th>Matrícula</th>
                                <th>Telefone</th>
                                <th>Usuário</th>
                                <th>Admin</th>
                                <th></th>

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
                </div>
            </div>
        </div>
    </div>
</div>




<!------------------------- CADASTRAR PESSOA -------------------------------->
<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div id="cadastrar_pessoa" class="card">
            <div class="card-header">
              <h5>Cadastrar Pessoa</h5>
            </div>
        <div class="card-body">

          <h5>Dados</h5>
          <hr></hr>

          <form id="cadastrar_pessoa" method="post">


            <div class="form-group row">
                <label for="tipo" class="col-sm-2 col-form-label">Tipo *</label>
                <div class="col-sm-2">
                  <select id="tipo" name="tipo" class="form-control">
                    <option value="Docente">Docente</option>
                    <option value="Aluno">Aluno</option>
                    <option value="Externo">Externo</option>
                    <option value="Funcionario">Funcionário</option>
                  </select>
                </div>
            </div>

            <div class="form-group row">
              <label for="nome" class="col-sm-2 col-form-label">Nome *</label>
              <div class="col-sm-6">
                <input type="text" id="nome" name="nome" class="form-control" required placeholder="João da Silva">
              </div>
            </div>               

  
            <div class="form-group row">
              <label for="email" class="col-sm-2 col-form-label">Email *</label>
              <div class="col-sm-6">
                <input type="email" id="email" name="email" class="form-control" required placeholder="joaodasilva@unicamp.br">
              </div>
            </div>               
            
            <div id="campo_matricula" class="form-group row">
                <label for="matricula" class="col-sm-2 col-form-label">Matrícula *</label>
                <div class="col-sm-2">
                  <input type="text" id="matricula" name="matricula" class="form-control" placeholder="123456" required>
                </div>
            </div>     

            <div class="form-group row">
                <label for="telefone" class="col-sm-2 col-form-label">Telefone *</label>
                <div class="col-sm-3">
                  <input type="tel" id="telefone" name="telefone" class="form-control" placeholder="(19)98765-4321" required>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="cpf" class="col-sm-2 col-form-label">CPF</label>
                <div class="col-sm-3">
                  <input type="text" id="cpf" name="cpf" class="form-control" placeholder="12345678901">
                </div>
            </div>


            <br>
            <h5>Endereço</h5>
            <hr></hr>



            <div class="form-group row" id="campo_end">
                <label for="endereco" class="col-sm-2 col-form-label">Rua, Nº, Bairro</label>
                <div class="col-sm-6">
                  <input type="text" id="endereco" name="endereco" class="form-control" placeholder="Rua X, 1234 - Barão Geraldo">
                </div>
            </div>

            <!-- países a serem inseridos por javascript -->
            <div class="form-group row">
                <label for="pais_pessoa" class="col-sm-2 col-form-label">País</label>
                <div class="col-sm-3">
                  <select id="pais_pessoa" name="pais_pessoa" class="form-control" required>
                  </select>
                </div>
            </div>

            <!-- estados a serem inseridos por javascript -->
            <div class="form-group row"  id="campo_estado">
                <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-1">
                  <select id="estado" name="estado" class="form-control" required>
                    <option value=""></option>
                    <option value="SP">SP</option>
                    <option value="RJ">RJ</option>
                    <option value="MG">MG</option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AM">AM</option>
                    <option value="AP">AP</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MS">MS</option>
                    <option value="MT">MT</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="PR">PR</option>
                    <option value="RN">RN</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="RS">RS</option>
                    <option value="SC">SC</option>
                    <option value="SE">SE</option>
                    <option value="TO">TO</option>
                  </select>
                </div>
            </div>

            <!-- cidades a serem inseridas por javascript -->
            <div class="form-group row"  id="campo_cidade">
                <label for="cidade_pessoa" class="col-sm-2 col-form-label">Cidade</label>
                <div class="col-sm-4">
                  <select id="cidade_pessoa" name="cidade_pessoa" class="form-control" required>
                  </select>
                </div>
            </div>

            <div class="form-group row" id="campo_cep">
                <label for="cep" class="col-sm-2 col-form-label">CEP</label>
                <div class="col-sm-2">
                  <input type="text" id="cep" name="cep" class="form-control" placeholder="12345-678">
                </div>
            </div>            

            <br>
            <div id="secao_acesso_sistema">
                
              <h5>Sistema</h5>
              <hr></hr>

              <div class="form-group row" id="campo_acesso_sistema">
                  <label for="acesso_sistema" class="col-sm-2 col-form-label">Acesso ao sistema</label>
                  <div class="col-sm-2">
                    <select id="acesso_sistema" name="acesso_sistema" class="form-control">
                      <option value="0">Não</option>
                      <option value="1">Sim</option>
                    </select>
                  </div>
              </div>

              <div class="form-group row" id="campo_admin">
                  <label for="is_admin" class="col-sm-2 col-form-label">Admin</label>
                  <div class="col-sm-2">
                    <select id="is_admin" name="is_admin" class="form-control">
                      <option value="0">Não</option>
                      <option value="1">Sim</option>
                    </select>
                  </div>
              </div>

              <div class="form-group row" id="campo_username">
                  <label for="usuario" class="col-sm-2 col-form-label">Usuário *</label>
                  <div class="col-sm-3">
                    <input type="text" id="usuario" name="usuario" class="form-control" >
                  </div>
              </div>

              <div class="form-group row" id="campo_modulos">
                  <!-- <label for="modulos" class="col-sm-2 col-form-label">Módulos *</label> -->
                  <div class="col-sm-8">

                </div>
              </div>
          </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit_cadastrar_pessoa">Cadastrar</button>
            </div>


            
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<br>


<style>
  #lista_pessoas {
    border-style: solid;
    border-width: 1px;
    text-align: center;
  }

  #tabela_pessoas{
    margin: auto;
    text-align: center;
  }

  #pesquisa{
    text-align: right;
  }
</style>



<?php
require('footer.php');
require('modais/modal_mostrar.php');
require('modais/modal_editar.php');
require('modais/modal_remover.php');

?>

<script src="gerenciar.js"></script> 
<script src="modais/modal_mostrar.js"></script> 
<script src="modais/modal_editar.js"></script>
<script src="modais/modal_remover.js"></script> 
<!-- MODAL EDITAR PESSOA -->
<div class="modal " id="modal_editar_pessoa" tabindex="-1" role="dialog" aria-labelledby="modal_editar_pessoaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_editar_pessoaLabel">Editar Cadastro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="form_editar_pessoa">
          <div class="form-group row">
              <label for="nome_pessoa" class="col-sm-3 col-form-label">Nome:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="nome_pessoa" name="pessoaName" required>
              </div>
          </div>
          <div class="form-group row">
                <label for="tipo_pessoa" class="col-sm-3 col-form-label">Tipo:</label>
                <div class="col-sm-4">
                  <select id="tipo_pessoa" name="tipo_pessoa" class="form-control">
                    <option value="Docente">Docente</option>
                    <option value="Aluno">Aluno</option>
                    <option value="Funcionario">Funcionário</option>
                    <option value="Externo">Externo</option>
                  </select>
                </div>
          </div>
          <div class="form-group row">
              <label for="email_pessoa" class="col-sm-3 col-form-label">Email:</label>
              <div class="col-sm-8">
                <input type="email_pessoa" id="email_pessoa" name="email_pessoa" class="form-control" required>
              </div>
          </div>  
          <div class="form-group row">
                <label for="matricula_pessoa" class="col-sm-3 col-form-label">Matrícula:</label>
                <div class="col-sm-4">
                  <input type="text" id="matricula_pessoa" name="matricula_pessoa" class="form-control" required>
                </div>
          </div>     
          <div class="form-group row">
                <label for="telefone_pessoa" class="col-sm-3 col-form-label">Telefone:</label>
                <div class="col-sm-6">
                  <input type="tel" id="telefone_pessoa" name="telefone_pessoa" class="form-control">
                </div>
          </div>
          <div class="form-group row">
                <label for="cep_pessoa" class="col-sm-3 col-form-label">CEP:</label>
                <div class="col-sm-4">
                  <input type="text" id="cep_pessoa" name="cep_pessoa" class="form-control" placeholder="12345-678">
                </div>
          </div>

          <div class="form-group row">
                <label for="endereco_pessoa" class="col-sm-3 col-form-label">Endereço:</label>
                <div class="col-sm-9">
                  <input type="text" id="endereco_pessoa" name="endereco_pessoa" class="form-control" placeholder="Rua X, 1234">
                </div>
          </div>

          <!-- países a serem inseridos por javascript -->
          <div class="form-group row">
                <label for="pais" class="col-sm-3 col-form-label">País:</label>
                <div class="col-sm-6">
                  <select id="pais" name="pais" class="form-control">
                  </select>
                </div>
          </div>

          <!-- estados a serem inseridos por javascript -->
          <div class="form-group row">
                <label for="estado_sigla" class="col-sm-3 col-form-label">Estado:</label>
                <div class="col-sm-3">
                  <select id="estado_sigla" name="estado_sigla" class="form-control">
                    <option value=""></option>
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
                    <option value="MG">MG</option>
                    <option value="MS">MS</option>
                    <option value="MT">MT</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="PR">PR</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="RS">RS</option>
                    <option value="SC">SC</option>
                    <option value="SE">SE</option>
                    <option value="SP">SP</option>
                    <option value="TO">TO</option>
                  </select>
                </div>
          </div>

          <!-- cidades a serem inseridas por javascript -->
          <div class="form-group row">
                <label for="cidade" class="col-sm-3 col-form-label">Cidade:</label>
                <div class="col-sm-8">
                  <select id="cidade" name="cidade" class="form-control">
                  </select>
                </div>
          </div>

          <div class="form-group row">
                <label for="cpf_pessoa" class="col-sm-3 col-form-label">CPF:</label>
                <div class="col-sm-5">
                  <input type="text" id="cpf_pessoa" name="cpf_pessoa" class="form-control" placeholder="12345678901">
                </div>
          </div>

          <div class="form-group row">
                <label for="user" class="col-sm-3 col-form-label">Usuário:</label>
                <div class="col-sm-6">
                  <input type="text" id="user" name="user" class="form-control" disabled>
                </div>
          </div>

          <div class="form-group row">
                <label for="administrador" class="col-sm-3 col-form-label">Admin:</label>
                <div class="col-sm-3">
                  <select id="administrador" name="administrador" class="form-control">
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                  </select>
                </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-success" id="submit_editar_pessoa" name="submit_editar_pessoa">Atualizar</button>
              <input type="hidden" id="codigo_pessoa" value="">
          </div>
      </form>
      </div>  
    </div>
  </div>
</div>
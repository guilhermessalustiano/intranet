<!-- MODAL MOSTRAR PESSOA -->
<div class="modal " id="modal_mostra_pessoa" tabindex="-1" role="dialog" aria-labelledby="modal_mostra_pessoaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_mostra_pessoaLabel">
          <i class="fa-regular fa-user"></i>  <span id="nomeMostrar"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p><b>Tipo de Pessoa: </b><span id="tipopessoaMostrar"></span></p>
        <p><b>Responsável: </b><span id="responsavelMostrar"></span></p>
        <p><i class="fa-solid fa-envelope">  </i><span id="emailMostrar"></span></p>
        <p><b>Matrícula: </b><span id="matriculaMostrar"></span></p>
        <p><i class="fa-solid fa-phone">  </i><span id="telefoneMostrar"></span></p>
        <p><b>CPF: </b><span id="cpfMostrar"></span></p>
        <p><b>CEP: </b><span id="cepMostrar"></span></p>
        <p><i class="fa-solid fa-location-dot">  </i><span id="enderecoMostrar"></span></p>
        <p><b>Cidade: </b><span id="cidadeMostrar"></span></p>
        <p><b>Estado: </b><span id="estadoMostrar"></span></p>
        <p><b>País: </b><span id="paisMostrar"></span></p>
        <p><b>Data de Cadastro: </b><span id="dataMostrar"></span></p>
        <p><b>Usuário: </b><span id="usuarioMostrar"></span></p>
        <p><b>Administrador: </b><span id="adminMostrar"></span></p>

      </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-secondary editarpessoaBtn">Editar</button>
        <button type="button" class="btn btn-danger excluirpessoaBtn">Excluir</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
        <input type="hidden" id="idPessoaShow" value="">
        <!-- <input type="hidden" id="tipopessoaShow" value="">
        <input type="hidden" id="nomeShow" value="">
        <input type="hidden" id="emailShow" value="">
        <input type="hidden" id="matriculaShow" value="">
        <input type="hidden" id="enderecoShow" value="">
        <input type="hidden" id="telefoneShow" value="">
        <input type="hidden" id="cepShow" value="">
        <input type="hidden" id="cidadeShow" value="">
        <input type="hidden" id="estadoShow" value="">
        <input type="hidden" id="paisShow" value="">
        <input type="hidden" id="cpfShow" value="">
        <input type="hidden" id="dataShow" value="">
        <input type="hidden" id="usuarioShow" value="">
        <input type="hidden" id="adminShow" value=""> -->
      </div>
    </div>
  </div>
</div>


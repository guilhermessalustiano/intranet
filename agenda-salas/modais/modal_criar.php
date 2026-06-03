<style>
/* Estilo para as bolinhas (checkbox) */
.checkbox-bolinha {
    display: inline-block;
    width: 24px;
    height: 24px;
    background-color: #fff;
    border-radius: 50%;
    border: 2px solid #c4c4c4;
    margin: 5px;
    cursor: pointer;
    text-align: center;
    line-height: 24px;
    font-weight: bold;
    color: #c4c4c4;
}

.checkbox-bolinha.checked {
    background-color: #4285f4; /* Cor azul do Google */
    color: #fff;
    border-color: #4285f4;
}

.checkbox-bolinha:hover {
    border-color: #4285f4;
}

.weekday span {
    display: inline-block;
}

</style>


<!-- MODAL CRIAR EVENTO -->
<div class="modal " id="modal_criar_evento" tabindex="-1" role="dialog" aria-labelledby="modal_criar_eventoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_criar_eventoLabel">Criar Evento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <form method="post" id="form_criar_evento">

        <!-- Exemplo de como corrigir outros campos -->
        <div class="form-group">
            <label for="eventName">Título:</label>
            <input type="text" class="form-control" id="eventName" name="eventName" required autofocus>
        </div>        


        <div class="form-group row">
          <label for="eventStart" class="col-sm-2 col-form-label">Início:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="eventStart"  name="eventStart" required >
          </div>
        </div>        


        <div class="form-group row">
          <label for="eventEnd" class="col-sm-2 col-form-label">Fim:</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="eventEnd"  name="eventEnd" required > 
          </div>
        </div>      


        <!-- recorrencia com select -->
        <!-- Repetir -->
        <div class="form-group row">
          <label for="recorrencia_select" class="col-sm-2 col-form-label">Repetir:</label>
          <div class="col-sm-10">
            <select class="form-control" id="recorrencia_select">
              <option selected>Não se repete</option>
              <option>Diariamente</option>
              <option>Semanalmente</option>
            </select>
            <div id="box_weekday">
            </div>
          </div>
        </div>

        <!-- Sala -->
        <div class="form-group row">
          <label for="sala" class="col-sm-2 col-form-label">Sala:</label>
          <div class="col-sm-10">
            <select class="form-control" id="sala" name="sala">
              <!-- Adicione as opções aqui -->
            </select>
          </div>
        </div>


        <!-- link reuniao-->
        <!-- <div class="form-group row">
          <label for="link_reuniao" class="col-sm-3 col-form-label">Link reunião:</label>
          <div class="col-sm-9">
          <input type="url" class="form-control" id="link_reuniao" name="link_reuniao" autofocus>
          </div>
        </div> -->
          
        <div class="form-group">
          <label for="sn_recurso">Necessita de Recursos?</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="recurso" id="sim_recurso" value="Sim">
            <label class="form-check-label" for="sim_recurso">Sim</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="recurso" id="nao_recurso" value="Não">
            <label class="form-check-label" for="nao_recurso">Não</label>
          </div>
                  <!-- Conteúdo adicional que será exibido quando "Sim" for selecionado -->
          <!-- <div id="recurso_adicional" style="display: none;"> -->
            
            <div class="form-group">
              <div id="recursosContainer">
                
                <!-- Checkboxes will be loaded here -->
              </div>
            </div>

        <!-- </div> -->
        

      </div>

      
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>

            <button type="submit" class="btn btn-success" id="submit_criar_evento" name="submit_criar_evento">Criar</button>
        </div>
      </form>

      </div>
    </div>
  </div>
</div>
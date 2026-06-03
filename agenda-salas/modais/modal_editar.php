<!-- MODAL EDITAR EVENTO -->
<div class="modal " id="modal_editar_evento" tabindex="-1" role="dialog" aria-labelledby="modal_editar_eventoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_editar_eventoLabel">Editar Evento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="form_editar_evento">
          <div class="form-group">
              <label for="nome_evento">Título:</label>
              <input type="text" class="form-control" id="nome_evento" name="eventName" required>
          </div>
          <div class="form-group row">
            <label for="start_evento" class="col-sm-2 col-form-label">Início:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="start_evento"  name="eventStart" required>
            </div>
          </div> 
          <div class="form-group row">
            <label for="end_evento" class="col-sm-2 col-form-label">Fim:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="end_evento" name="eventEnd" required>
            </div>
          </div>
          <div class="form-group">
            <label for="sala">Agenda:</label>
            <select class="form-control" id="sala" name="sala"></select>
          </div>
          <!-- link reuniao-->
          <!-- <div class="form-group row">
            <label for="link_reuniao" class="col-sm-3 col-form-label">Link reunião:</label>
            <div class="col-sm-9">
            <input type="url" class="form-control" id="link_reuniao" name="link_reuniao" autofocus>
            </div> -->
          </div>
          <div class="form-group">
            <label for="sn_recurso">Necessita de Recursos?</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="recurso" id="sim_recurso_edit" value="Sim">
              <label class="form-check-label" for="sim_recurso_edit">Sim</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="recurso" id="nao_recurso_edit" value="Não">
              <label class="form-check-label" for="nao_recurso_edit">Não</label>
            </div>
            <div id="recurso_adicional_edit" style="display: none;">
              <div class="form-group">
                <label>Recursos:</label>
                <div id="recursosContainer_edit">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-success" id="submit_editar_evento" name="submit_editar_evento">Salvar</button>
              <input type="hidden" id="id_evento" value="">
              <input type="hidden" id="owner_id" value="">
          </div>
      </form>
      </div>  
    </div>
  </div>
</div>
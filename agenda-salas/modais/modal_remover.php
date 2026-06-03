<!-- MODAL REMOVER EVENTO -->
<div class="modal " id="modal_remover_evento" tabindex="-1" role="dialog" aria-labelledby="modal_remover_eventoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header"><h5 class="modal-title" id="modal_remover_eventoLabel">Excluir evento recorrente</h5></div>

      <div class="modal-body">
        <div class="custom-control custom-radio">
        <input type="radio" id="este_evento" name="opcao_exclusao" class="custom-control-input" checked value="este_evento">
          <label class="custom-control-label" for="este_evento" >Este evento</label>
        </div>

        <div class="custom-control custom-radio">
        <input type="radio" id="todos_eventos" name="opcao_exclusao" class="custom-control-input" value="todos_eventos">
        <label class="custom-control-label" for="todos_eventos">Todos os eventos</label>
        </div>
      </div>  

      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" id="editar_evento">Editar</button> -->
        <button type="button" class="btn btn-danger" id="excluir_evento_confirmacao">Confirmar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>

    </div>
  </div>
</div>
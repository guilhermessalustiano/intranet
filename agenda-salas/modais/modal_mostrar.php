<!-- MODAL MOSTRAR EVENTO -->
<div class="modal " id="modal_mostra_evento" tabindex="-1" role="dialog" aria-labelledby="modal_mostra_eventoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_mostra_eventoLabel">
          <div class="eventColor"></div>
          <span id="nome_evento"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><span id="eventDateTime"></span></p>
        <p><span id="eventRecorrency"></span></p>
        <p><span id="nome_agenda"></span></p>
        <!-- <p><span id="url_reuniao"></span></p> -->
        <p><span id="owner_evento"></span></p>
        <p><span id="recursos_atribuidos"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="editar_evento">Editar</button>
        <button type="button" class="btn btn-danger" id="excluir_evento">Excluir</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
        <input type="hidden" id="id_evento" value="">
        <input type="hidden" id="start_evento" value="">
        <input type="hidden" id="end_evento" value="">
        <input type="hidden" id="id_agenda" value="">
        <input type="hidden" id="owner_id" value="">
        <input type="hidden" id="rrule" value="">
        <input type="hidden" id="recursos_selecionados" value="">
        <!-- <input type="hidden" id="link_reuniao" value=""> -->
      </div>
    </div>
  </div>
</div>


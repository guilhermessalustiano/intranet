<!----------------------  BEGIN MODAL CONFIRMACAO EXCLUSAO ----------------------->
<div class="modal" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir esta pessoa?
                <br><br>
                <b>Excluir esta pessoa apagará todos os eventos (agenda, processo seletivo, empréstimos) relacionados, bem como não permitirá mais seu acesso ao sistema!</b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancelar" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
            </div>
        </div>
    </div>
</div>
<!----------------------  BEGIN MODAL CONFIRMACAO EXCLUSAO ----------------------->
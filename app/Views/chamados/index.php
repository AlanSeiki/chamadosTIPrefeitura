


<!-- <div class="collapse mb-3" id="collapseExample">
    <div class="card card-body">
      <button type="button" class="btn-close" aria-label="Close"></button>
    Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
  </div>
</div> -->
<div class="row mb-3">
    <div class="col-md-12 text-end">
    <!-- <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        <i class="fas fa-search-plus"></i> Filtros
    </button> -->
        <button type="button" class="btn btn-success" onclick="window.location.href='form_criar_chamado'"><i class="fa fa-plus"></i> Novo Chamado</button>
    </div>    
</div>
<div class="pagina-com-tabela">
    <table id="table-contatos" class="table table-striped" style="width:100%"></table>
</div>


<!-- Modal -->
<div class="modal fade" id="modal-confirmacao">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="titulo-modal-confirmacao">Cancelar Chamado</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <form id="form-confirmacao-chamado">
                <label for="motivo" class="form-label" id="label-motivo">Motivo do Cancelamento</label>
                <textarea class="form-control" placeholder="Descrição sobre o motivo" name="motivo-modal" id="motivo-modal" maxlength="255" rows="3"></textarea>
                <input type="hidden" name="acao-modal" id="acao-modal" value="">
                <input type="hidden" name="chamado_id" id="chamado_id" value="">
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default float-start" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="salvar-modal-confirmacao">Salvar</button>
      </div>
    </div>
  </div>
</div>
<script src="/assets/js/chamados.js"></script>
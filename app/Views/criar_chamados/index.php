<div class="card shadow elevated bg-white rounded-sm p-4 overflow-auto">
    <h2 class="mb-4">Abertura de Chamado</h2>
    <form id="form-chamado" enctype="multipart/form-data">
        <!-- Descrição do Problema -->
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição *</label>
            <textarea id="summernote" name="descricao" required></textarea>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="tipo_incidencia" class="form-label">Tipo de Incidente *</label>
                <select class="form-select select-tipo-incidente" id="tipo_incidencia" name="tipo_incidencia" required>
                    <option value="">Selecione</option>
                    <!-- Opções virão do backend -->
                </select>
            </div>
            <div class="col-md-6">
                <label for="formFileMultiple" class="form-label">Anexos *</label>
                <input class="form-control" type="file" id="formFileMultiple" name="arquivos[]" multiple required>
                <small class="form-text text-muted">Adicione um ou mais arquivos.</small>
            </div>
        </div>

        <!-- Lista de Contatos -->
        <h5>Contatos</h5>
        <div class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="nome_contato" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome_contato" placeholder="Nome">
                </div>
                <div class="col-md-3">
                    <label for="numero_contato" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="numero_contato" placeholder="Telefone">
                </div>
                <div class="col-md-4">
                    <label for="observacao_contato" class="form-label">Observação</label>
                    <input type="text" class="form-control" id="observacao_contato" placeholder="Observação">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary mt-2" id="adicionar-contato">Adicionar</button>
                </div>
            </div>

        </div>

        <!-- Tabela de Contatos -->
        <table class="table table-bordered" id="table-contatos-chamados">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Observação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contatos adicionados aparecerão aqui -->
            </tbody>
        </table>

        <!-- Botão de Submissão -->
        <button type="button" id="salvar-chamado" class="btn btn-success">Abrir Chamado</button>
    </form>
</div>


<link rel="stylesheet" href="/assets/vendor/summernote/summernote-bs5.css">
<script src="/assets/vendor/summernote/summernote-bs5.js"></script>
<script src="/assets/js/criar_chamados.js"></script>
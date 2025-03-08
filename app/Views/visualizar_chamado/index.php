<div class="max-w-3xl mx-auto p-6 space-y-6 rounded">
    <input type="hidden" id="id_chamado" value="<?= $chamado['id_chamado'] ?>">
    <!-- Card do Chamado -->
    <div class="bg-white p-4 rounded-top shadow-lg border">
        <div class="row">
            <div class="col-md-8 col-4">
                <h2 class="text-xl font-semibold text-gray-800">Chamado</h2>
            </div>
            <div class="col-md-4 col-8 text-end">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#anexoModal"><i class="fas fa-paperclip"></i> Anexos</button>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#contatoModal"><i class="fas fa-users"></i> Contatos</button>
            </div>
        </div>
        <div class="mt-1 text-sm text-gray-500">
            <label class="form-label mt-1">Descrição</label>
            <?php echo html_entity_decode($chamado['descricao']); ?>
        </div>
        <div class="mt-1 text-sm text-gray-500">
            <label class="form-label mt-1">Tipo de Incidencia</label>
            <p><?php echo $chamado['tipo_incidencia'] ?></p>
        </div>
    </div>

    <div class="bg-white p-4 shadow-lg border max-h-96 overflow-y-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Histórico do Chamado</h3>

        <?php foreach ($chamado['historico'] as $msg) : ?>
            <div class="flex items-start space-x-3 mb-4">
                <div class="row">
                    <div class="col-sm-1 text-center">
                        <img class="min-photo-user" width="40" height="40" src="<?=  isset($msg['foto']) ? $msg['foto'] : '/assets/img/no-user.png' ?>" alt="<?= $msg['nome'] ?>" style="border-radius: 50%;object-fit: cover;">
                    </div>
                    <div class="col-sm-11 d-flex align-items-center">
                        <div class="text-xs text-gray-500"><?= $msg['nome'] ?> • <?= date("d/m/Y H:i:s", strtotime($msg['criado_em'])) ?></div>
                    </div>
                </div>
                <div>
                    <label class="form-label mt-1">Descrição</label>
                    <div class="bg-gray-100 p-1 rounded-lg">
                        <?php echo html_entity_decode($msg['alteracao']); ?>
                        <!-- <p class="text-gray-700"><?= $msg['alteracao'] ?></p> -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Campo de Resposta -->

    <div class="bg-white p-4 rounded-bottom shadow-lg border flex items-center space-x-3 <?php if ($chamado['status'] == 'Cancelado' || $chamado['status'] == 'Finalizado') {
                                                                                                echo 'visually-hidden';
                                                                                            } ?>">
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Resposta *</label>
            </div>
            <div class="col-md-9 row">
                <div class="col-md-3 d-md-flex justify-content-center">
                    <label for="formFileMultiple" class="form-label mb-md-0">Anexar Arquivos</label>
                </div>
                <div class="col-md-9">
                    <div class="input-group" onclick="document.getElementById('formFileMultiple').click()">
                        <input type="file" class="form-control d-none" id="formFileMultiple" multiple require>
                        <button class="btn btn-primary rounded-start" type="button">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <span id="fileLabel" class="form-control">Nenhum arquivo selecionado</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="summernote"></div>
        <div class="mt-2 text-end">
            <button type="button" class="btn btn-success" id="salvar-chamado">Salvar</button>
        </div>
    </div>
</div>

<!-- anexos adicionados -->

<div class="modal fade" id="anexoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Anexos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <?php foreach ($chamado['anexos'] as $anexo) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= $anexo['nome_arquivo'] ?>
                            <a href="data:application/octet-stream;base64,<?= $anexo['conteudo_base64'] ?>"
                                download="<?= $anexo['nome_arquivo'] ?>"
                                class="btn btn-primary btn-sm">
                                <i class="fas fa-download"></i> Baixar
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- lista de contatos -->

<div class="modal fade" id="contatoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Lista de Contatos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto">
                <table class="table table-striped nowrap">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamado['contatos'] as $contato) : ?>
                            <tr>
                                <td class="text-break"><?= $contato['nome'] ?></td>
                                <td><?= $contato['telefone'] ?></td>
                                <td><?= $contato['observacao'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/assets/vendor/summernote/summernote-bs5.css">
<script src="/assets/vendor/summernote/summernote-bs5.js"></script>
<script src="/assets/js/visualizar_chamado.js"></script>
<div class="container-padrao d-flex flex-column justify-content-center align-items-center">
    <div class="row w-100 p-md-4 p-1">
        <!-- Formulário de Login -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="form-signin w-100 m-auto elevated bg-white rounded p-4 text-center">
                <div class="d-flex justify-content-center gap-2">
                    <h3>eChamado</h3>
                    <img src="../../assets/img/comment-dots-regular-black.png" alt="Ícone de Chamado" width="16" height="16">
                </div>
                <h6>Preencha seus dados para entrar</h6>
                <form id="form-login" method="POST" novalidate>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" name="senha" id="senha" placeholder="Password" required>
                        <label for="senha">Senha</label>
                        <button type="button" id="togglePassword" class="btn position-absolute"
                                style="top: 50%; right: 10px; transform: translateY(-50%); border: none; background: transparent;">
                            <img id="eye_open" src="../../assets/img/eye-fill.svg" alt="Ícone de olho" width="16" height="16">
                            <img class="hide" id="eye_close" src="../../assets/img/eye-slash.svg" alt="Ícone de olho" width="16" height="16">
                        </button>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mb-2">Entrar</button>
                    <div class="d-flex mb-2">
                        <hr class="w-45"><span class="w-10 d-flex justify-content-center align-items-center" style="height: 32.08px;">ou</span>
                        <hr class="w-45">
                    </div>
                    <button type="button" class="btn btn-primary w-100" onclick="window.location.href='cadastro_usuario'">Cadastrar-se</button>
                </form>
            </div>
        </div>

        <!-- Descrição do Projeto -->
        <div class="col-lg-8 col-md-12">
            <h2 class="mb-4">📌 Como Utilizar o Sistema</h2>
            <div class="accordion" id="accordionExample">
                <!-- Item 1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            1️⃣ Acesso ao Sistema
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul>
                                <li>Faça login com suas credenciais.</li>
                                <li>Usuários comuns podem registrar e acompanhar seus chamados.</li>
                                <li>Administradores têm acesso a todos os chamados e estatísticas.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            2️⃣ Abertura de Chamado
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul>
                                <li>Na tela de <strong>Chamados</strong>, clique em <strong>"Novo Chamado"</strong>.</li>
                                <li>Preencha as informações, incluindo descrição, tipo de ocorrência, anexos e contatos.</li>
                                <li>Envie o chamado para que a equipe de suporte possa analisá-lo.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            3️⃣ Acompanhamento e Atualização
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul>
                                <li>Acesse a aba <strong>"Chamados"</strong> para visualizar a lista de solicitações.</li>
                                <li>O status do chamado pode ser <strong>Aberto, Em Andamento, Finalizado ou Cancelado</strong>.</li>
                                <li><button type="button" class="btn btn-sm btn-primary"><i class="far fa-comments"></i></button>
                                    Ao clicar no chats você irá ver todos os dados do chamado podendo incluir novos anexos e descrições.                                    
                                </li>
                                <li>
                                    Quando tiver marcador com número significa que existem chats referente a esse chamado que não foram lidos.
                                    <button dataid="1" class="btn btn-sm btn-primary visualizar-btn position-relative" title="Visualizar">
                                    <i class="far fa-comments"></i>
                                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">3</span>
                                    </button>                                
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                            4️⃣ Gerenciamento Administrativo (Apenas Administradores)
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul>
                                <li>Acesse a tela de <strong>Chamados</strong> para visualizar e gerenciar todas as solicitações.</li>
                                <li>Monitore os chamados em andamento e finalize aqueles que já foram resolvidos.</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <p class="mt-4"><strong>🚀 Dica:</strong> Mantenha suas informações sempre atualizadas para um suporte mais eficiente.</p>
        </div>

    </div>
</div>

<script src="/assets/js/login.js"></script>
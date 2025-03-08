<div class="container-padrao d-flex flex-column justify-content-center align-items-center">
  <form class="col col-md-8 col-xl-6 needs-validation elevated bg-white rounded-sm p-4 overflow-auto" id="form-usuario" method="POST" novalidate>
    <h3 class="text-center mb-4">Criar Conta</h3>
    <div class="row">
      <!-- Imagem do usuário -->
      <div class="col-md-4 col-12 mb-3 text-center">
        <img id="profileImage" src="/assets/img/no-user.png" class="rounded mx-auto d-block" height="160" style="max-width: 180px;">
        <input type="file" id="fileInput" accept="image/*" class="d-none">
        <div class="mt-2">
          <button id="openFile" type="button" class="btn btn-sm btn-primary"><i class="fas fa-folder"></i> Escolher Foto</button>
          <button id="resetImage" type="button" class="btn btn-sm btn-danger d-none"><i class="fas fa-trash-alt"></i></button>
        </div>
      </div>

      <!-- Inputs -->
      <div class="col-md-8 col-12">
        <div class="row">
          <div class="form-group col-12 mb-3">
            <label for="nome" class="form-label">Nome Completo *</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
          </div>

          <div class="form-group col-12 mb-3">
            <label for="data_nascimento" class="form-label">Data de Nascimento *</label>
            <input type="text" class="form-control" id="data_nascimento" name="data_nascimento" required>
          </div>

          <div class="form-group col-12 mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" class="form-control" id="email" name="email" autocomplete="username" pattern=".+@.+\.com" required>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-md-6 mb-3">
        <label for="telefone" class="form-label">Telefone *</label>
        <input type="text" class="form-control" id="telefone" name="telefone" required>
      </div>

      <div class="form-group col-md-6 mb-3">
        <label for="whatsapp" class="form-label">WhatsApp *</label>
        <input type="text" class="form-control" id="whatsapp" name="whatsapp" required>
      </div>

      <div class="form-group col-md-6 mb-3 position-relative">
        <label for="senha" class="form-label">Senha *</label>
        <input type="password" class="form-control-t" id="senha" name="senha" autocomplete="new-password">
        <button type="button" id="togglePassword" class="btn position-absolute button-eye">
          <img id="eye_open" src="../../assets/img/eye-fill.svg" alt="Ícone de olho" width="16" height="16">
          <img class="hide" id="eye_close" src="../../assets/img/eye-slash.svg" alt="Ícone de olho" width="16" height="16">
        </button>
      </div>

      <div class="form-group col-md-6 mb-3 position-relative">
        <label for="confirmar_senha" class="form-label">Confirmar Senha *</label>
        <input type="password" class="form-control-t" id="confirmar_senha" name="confirmar_senha" autocomplete="new-password">
        <button type="button" id="toggleConfirmPassword" class="btn position-absolute button-eye">
          <img id="eye_open_confirm" src="../../assets/img/eye-fill.svg" alt="Ícone de olho" width="16" height="16">
          <img class="hide" id="eye_close_confirm" src="../../assets/img/eye-slash.svg" alt="Ícone de olho" width="16" height="16">
        </button>
      </div>
      
      <div class="form-group col-md-6 mb-3">
        <label for="estado" class="form-label">Estado *</label>
        <select class="select-estado form-control" id="estado" name="estado" required>
          <option value="">Selecione o Estado</option>
        </select>
      </div>

      <div class="form-group col-md-6 mb-3">
        <label for="cidade" class="form-label">Cidade *</label>
        <select class="select-cidade form-control" id="cidade" name="cidade" required>
          <option value="">Selecione a Cidade</option>
        </select>
      </div>
    </div>

    <div class="mt-4 col-md-12">
      <button class="btn btn-success float-end" type="submit">Cadastrar</button>
      <button class="btn btn-default" type="button" onclick="window.location.href='/'">Cancelar</button>
    </div>
  </form>
</div>

<script src="/assets/js/cadastro_usuario.js"></script>
<script src="/assets/js/select.js"></script>

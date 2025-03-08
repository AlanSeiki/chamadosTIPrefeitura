<form class="needs-validation elevated bg-white rounded-sm p-4 overflow-auto" id="form-perfil" method="POST" novalidate>
  <h3 class="text-center mb-4">Meu Perfil</h3>

  <div class="row">
    <!-- Imagem do usuário -->
    <div class="col-md-4 col-12 mb-3 text-center">
      <img id="profileImage" src="/assets/img/no-user.png" class="rounded mx-auto d-block" height="160" style="max-width: 180px;">
      <input type="file" id="fileInput" accept="image/*" class="d-none">
      <div class="mt-2">
        <button id="openFile" type="button" class="btn btn-sm btn-primary" disabled><i class="fas fa-folder"></i> Alterar Foto</button>
        <button id="resetImage" type="button" class="btn btn-sm btn-danger d-none"><i class="fas fa-trash-alt"></i></button>
      </div>
    </div>

    <!-- Campos de entrada -->
    <div class="col-md-8 col-12">
      <div class="row">
        <div class="form-group col-12 mb-3">
          <label for="nome" class="form-label">Nome Completo *</label>
          <input type="text" class="form-control" id="nome" name="nome" disabled required>
        </div>

        <div class="form-group col-12 mb-3">
          <label for="data_nascimento" class="form-label">Data de Nascimento *</label>
          <input type="text" class="form-control" id="data_nascimento" name="data_nascimento" disabled required>
        </div>

        <div class="form-group col-12 mb-3">
          <label for="email" class="form-label">Email *</label>
          <input type="email" class="form-control" id="email" name="email" autocomplete="username" disabled required>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-6 mb-3">
      <label for="telefone" class="form-label">Telefone *</label>
      <input type="text" class="form-control" id="telefone" name="telefone" disabled required>
    </div>

    <div class="form-group col-md-6 mb-3">
      <label for="whatsapp" class="form-label">WhatsApp *</label>
      <input type="text" class="form-control" id="whatsapp" name="whatsapp" disabled required>
    </div>

    <div class="form-group col-md-6 mb-3">
      <label for="estado" class="form-label">Estado *</label>
      <select class="select-estado form-control" id="estado" name="estado" disabled required>
        <option value="">Selecione o Estado</option>
      </select>
    </div>

    <div class="form-group col-md-6 mb-3">
      <label for="cidade" class="form-label">Cidade *</label>
      <select class="select-cidade form-control" id="cidade" name="cidade" disabled required>
        <option value="">Selecione a Cidade</option>
      </select>
    </div>

    <div class="mt-4 col-md-12">
      <button id="editarPerfil" class="btn btn-warning float-start" type="button">Editar</button>
      <button id="salvarPerfil" class="btn btn-success float-end d-none" type="submit">Salvar</button>
    </div>
  </div>
</form>

<script src="/assets/js/perfil_usuario.js"></script>
var usuarioOriginal = {}; 
$(document).ready(function () {
    const editarPerfilBtn = $('#editarPerfil');
    const salvarPerfilBtn = $('#salvarPerfil');
    const cancelarPerfilBtn = $('<button type="button" id="cancelarPerfil" class="btn btn float-start d-none">Cancelar</button>');
    editarPerfilBtn.after(cancelarPerfilBtn);
    
    const openFileBtn = $('#openFile');
    const formInputs = $('#form-perfil input, #form-perfil select');
   // Para armazenar os dados originais

    editarPerfilBtn.on("click", function () {
        formInputs.prop("disabled", false);
        openFileBtn.prop("disabled", false);
        editarPerfilBtn.addClass("d-none");
        salvarPerfilBtn.removeClass("d-none");
        cancelarPerfilBtn.removeClass("d-none");
    });

    // 🔹 Cancela a edição e restaura os dados originais
    cancelarPerfilBtn.on("click", function () {
        $('#nome').val(usuarioOriginal.nome);
        $('#data_nascimento').val(formatDate(usuarioOriginal.data_nascimento));
        $('#email').val(usuarioOriginal.email);
        $('#telefone').val(usuarioOriginal.telefone);
        $('#whatsapp').val(usuarioOriginal.whatsapp);
        $('#profileImage').attr('src', usuarioOriginal.foto || "/assets/img/no-user.png");

        var estadoSelecionado = {
            id: usuarioOriginal.id_estado,
            text: usuarioOriginal.estado
        }

        $(".select-estado").select2("trigger", "select", {
            data: estadoSelecionado
        });

        var estadoCidade = {
            id: usuarioOriginal.id_cidade,
            text: usuarioOriginal.cidade
        }

        $(".select-cidade").select2("trigger", "select", {
            data: estadoCidade
        });

        formInputs.prop("disabled", true);
        openFileBtn.prop("disabled", true);
        editarPerfilBtn.removeClass("d-none");
        salvarPerfilBtn.addClass("d-none");
        cancelarPerfilBtn.addClass("d-none");
    });

    // 🔹 Simulação de envio dos dados ao salvar
    salvarPerfilBtn.on("click", function (e) {
        e.preventDefault();

        let formData = new FormData($('#form-perfil')[0]); // Captura TODOS os campos do formulário automaticamente

        let file = $("#fileInput")[0].files[0]; // Obtém o arquivo selecionado
        if (file) {
          formData.append("foto", file); // Adiciona a imagem ao FormData
        }        
        $.ajax({
            url: "/atualizar_usuario",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                callToast('success', response.mensagem);
                setTimeout(() => {
                    location.reload(true);
                }, 500);
            },
            error: function () {
                callToast('error',"Erro ao conectar com o servidor.");
            }
        });
    });

    // 🔹 Simulação de alteração de foto
    $('#fileInput').on("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#profileImage').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
    criarSelect();
    criarFuncionalidadeFoto();
    controleInputDataNascimento();
    carregarDadosUsuario();
});

function formatDate(dateString) {
    // Divide a data em partes (ano, mês, dia) usando o "-"
    const [year, month, day] = dateString.split("-");
    
    // Retorna a data no formato "dd/mm/yyyy"
    return `${day}/${month}/${year}`;
}

function carregarDadosUsuario() {
    $.ajax({
        url: "/get_usuario",
        type: "GET",
        dataType: "json",
        success: function (response) {
                const usuario = response.data;
                
                usuarioOriginal = { ...usuario }; // Salva os dados originais para restauração

                $('#nome').val(usuario.nome);
                $('#data_nascimento').val(formatDate(usuario.data_nascimento));
                $('#email').val(usuario.email);
                $('#telefone').val(usuario.telefone);
                $('#whatsapp').val(usuario.whatsapp);
                $('#profileImage').attr('src', usuario.foto || "/assets/img/no-user.png");
                
                var estadoSelecionado = {
                    id: usuario.id_estado,
                    text: usuario.estado
                }

                $(".select-estado").select2("trigger", "select", {
                    data: estadoSelecionado
                });

                var estadoCidade = {
                    id: usuario.id_cidade,
                    text: usuario.cidade
                }

                $(".select-cidade").select2("trigger", "select", {
                    data: estadoCidade
                });
               
        },
        error: function () {
            alert("Erro ao carregar os dados do usuário!");
        }
    });
}

function criarSelect() {
    $(".select-estado").select2({
        language: "pt-BR",
        placeholder: "Selecione o Estado",
        ajax: {
            url: "/get_estados",
            dataType: "json",
            delay: 250,
            data: function (params) {
                var query = {
                  nome: params.term,
                };
                return query;
              },
            processResults: function (data) {
                return {
                    results: data.data.map(estado => ({
                        id: estado.id_estado,
                        text: estado.nome
                    }))
                };
            }
        }
    });

    $(".select-cidade").select2({
        language: "pt-BR",
        placeholder: "Selecione a Cidade",
        ajax: {
            url: function () {
                let estadoId = $("#estado").val();
                return estadoId ? `/get_cidades?estado_id=${estadoId}` : null;
            },
            dataType: "json",
            delay: 250,
            data: function (params) {
                var query = {
                  nome: params.term,
                };
                return query;
              },
            processResults: function (data) {
                return {
                    results: data.data.map(cidade => ({
                        id: cidade.id_cidade,
                        text: cidade.nome
                    }))
                };
            }
        }
    });

    $("#estado").on("change", function () {
        $(".select-cidade").val(null).trigger("change");
    });
}

function controleInputDataNascimento() {
    $("#data_nascimento").on("input", function () {
      let value = $(this).val().replace(/\D/g, ""); // Remove tudo que não for número
      let formatted = "";
  
      if (value.length > 0) formatted = value.substring(0, 2);
      if (value.length > 2) formatted += "/" + value.substring(2, 4);
      if (value.length > 4) formatted += "/" + value.substring(4, 8);
  
      $(this).val(formatted);
    });
  
    $("#data_nascimento").on("blur", function () {
      const regexData = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/;
      if (!regexData.test($(this).val())) {
        callToast('warning',"Data inválida! Digite no formato DD/MM/AAAA.");
        $(this).val("");
      }
    });
  }

  function criarFuncionalidadeFoto() {
    const fileInput = document.getElementById("fileInput");
    const profileImage = document.getElementById("profileImage");
    const openFile = document.getElementById("openFile");
    const resetImage = document.getElementById("resetImage");
  
    // Abrir a seleção de arquivos ao clicar no botão
    openFile.addEventListener("click", () => fileInput.click());
  
    // Trocar a imagem ao selecionar um arquivo
    fileInput.addEventListener("change", (event) => {
      const file = event.target.files[0];
  
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          profileImage.src = e.target.result;
          resetImage.classList.remove("d-none"); // Exibir o botão de reset
        };
        reader.readAsDataURL(file);
      }
    });
    resetImage.addEventListener("click", () => {
        profileImage.src = "/assets/img/no-user.png";
        fileInput.value = "";
        resetImage.classList.add("d-none");
      });
}
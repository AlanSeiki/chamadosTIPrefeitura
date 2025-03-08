$(document).ready(function () {
  controleInputEstadoCidade();
  ativarFuncionalidadeVerSenha();
  controleInputDataNascimento();
  criarFuncionalidadeFoto();

  $("#senha, #confirmar_senha").on("input", function () {
    validarSenhas();
  });

  $("#telefone, #whatsapp").on("input", function () {
    let numero = $(this).val().replace(/\D/g, "");

    if (numero.length > 11) {
      numero = numero.slice(0, 11);
    }

    if (numero.length > 10) {
      numero = numero.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
    } else if (numero.length > 6) {
      numero = numero.replace(/^(\d{2})(\d{1,4})(\d{1,4})$/, "($1) $2-$3");
    } else if (numero.length > 2) {
      numero = numero.replace(/^(\d{2})(\d{1,4})$/, "($1) $2");
    } else if (numero.length > 0) {
      numero = numero.replace(/^(\d{0,2})$/, "($1");
    }

    $(this).val(numero);
  });

  $("#form-usuario").on("submit", async function ($event) {
    $event.preventDefault();

    this.classList.add("was-validated");
    let idadeStatus = validarIdade();
    let senhaStatus = validarSenhas();

    validarSelect2("#estado");
    validarSelect2("#cidade");

    if (!idadeStatus || !senhaStatus || !this.checkValidity()) {
      return false;
    }

    let formData = new FormData(this); // Captura TODOS os campos do formulário automaticamente

    let file = $("#fileInput")[0].files[0]; // Obtém o arquivo selecionado
    if (file) {
      formData.append("foto", file); // Adiciona a imagem ao FormData
    }

    $.ajax({
      url: "/cadastro_usuario/create",
      type: "POST",
      data: formData,
      processData: false, // Impede o jQuery de processar os dados automaticamente
      contentType: false, // Permite o envio correto de arquivos
      success: function (response) {
        callToast("success", response.mensagem);
        setTimeout(() => {
          window.location.href = "/";
        }, 1300);
      },
      error: function (xhr) {
        var response = JSON.parse(xhr.responseText);
        callToast("error", response.mensagem);
      },
    });
  });
});

function validarSelect2(selector) {
    let select2Container = $(selector).next(".select2-container").find(".select2-selection");
    if ($(selector).val()) {
      select2Container.removeClass("is-invalid").addClass("is-valid");
    } else {
      select2Container.removeClass("is-valid").addClass("is-invalid");
    }
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

  // Restaurar a imagem original
  resetImage.addEventListener("click", () => {
    profileImage.src = "/assets/img/no-user.png";
    fileInput.value = "";
    resetImage.classList.add("d-none");
  });
}

function validarIdade() {
  let dataNascimento = new Date($("#data_nascimento").val());
  let hoje = new Date();

  let idade = hoje.getFullYear() - dataNascimento.getFullYear();
  let mesAtual = hoje.getMonth();
  let mesNascimento = dataNascimento.getMonth();
  let diaAtual = hoje.getDate();
  let diaNascimento = dataNascimento.getDate();

  if (
    mesAtual < mesNascimento ||
    (mesAtual === mesNascimento && diaAtual < diaNascimento)
  ) {
    idade--;
  }

  if (idade < 18) {
    $("#data_nascimento")
      .removeClass("was-validated is-valid")
      .addClass("is-invalid");
    callToast("error", "É necessário ser maior de idade para continuar.");
    return false;
  } else if (
    $("#data_nascimento").val() != undefined &&
    $("#data_nascimento").val() != ""
  ) {
    $("#data_nascimento")
      .removeClass("was-validated is-invalid")
      .addClass("is-valid");
    return true;
  } else {
    $("#data_nascimento")
      .removeClass("was-validated is-valid")
      .addClass("is-invalid");
    return false;
  }
}

function validarSenhas() {
  let senha = $("#senha").val();
  let confirmarSenha = $("#confirmar_senha").val();

  if (confirmarSenha !== senha || senha.length === 0) {
    $("#senha, #confirmar_senha")
      .removeClass("was-validated is-valid")
      .addClass("is-invalid");
    return false;
  } else {
    $("#senha, #confirmar_senha")
      .removeClass("was-validated is-invalid")
      .addClass("is-valid");
    return true;
  }
}

function ativarFuncionalidadeVerSenha() {
  $("#togglePassword").on("click", function () {
    if ($("#senha").attr("type") == "password") {
      $("#eye_open").addClass("hide");
      $("#eye_close").removeClass("hide");
      $("#senha").attr("type", "text");
    } else {
      $("#eye_open").removeClass("hide");
      $("#eye_close").addClass("hide");
      $("#senha").attr("type", "password");
    }
  });
  $("#toggleConfirmPassword").on("click", function () {
    if ($("#confirmar_senha").attr("type") == "password") {
      $("#eye_open_confirm").addClass("hide");
      $("#eye_close_confirm").removeClass("hide");
      $("#confirmar_senha").attr("type", "text");
    } else {
      $("#eye_open_confirm").removeClass("hide");
      $("#eye_close_confirm").addClass("hide");
      $("#confirmar_senha").attr("type", "password");
    }
  });
}

function controleInputEstadoCidade() {
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

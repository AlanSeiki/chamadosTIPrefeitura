var tableNoAjax;
$(document).ready(function () {
  criarSummerNote();
  criarAnexo();

  $('#salvar-chamado').click(() => {

    let formData = verificarCampos();
    if (formData === true) {
      callToast("error", 'Campos com * são obrigatorios');
      return;
    }
    $.ajax({
      url: "/atualiza_chamado", // Ajuste para o endpoint correto
      type: "POST",
      data: formData,
      processData: false,  // Importante para FormData funcionar
      contentType: false,  // Importante para enviar arquivos
      success: function (res) {
        callToast("success", res.mensagem);
        setTimeout(() => {
          window.location.href = "/chamados";
        }, 1300);
      },
      error: function (err) {
        var response = JSON.parse(err.responseText);
        callToast("error", response.mensagem);
      },
    });
  });

});


function verificarCampos() {
  // Verifica se o editor está vazio
  if ($('#summernote').summernote('code') == '<p><br></p>') {
    return true;
  } 

  let formData = new FormData();

  formData.append("alteracao", $('#summernote').summernote('code'));

  formData.append("id_chamado", $('#id_chamado').val());

  if ($('#formFileMultiple')[0].files.length > 0) {
    let arquivos = $("#formFileMultiple")[0].files;
    for (let i = 0; i < arquivos.length; i++) {
      formData.append("arquivos[]", arquivos[i]);
    }
  }

  return formData;
}



function criarAnexo() {
  document
    .getElementById("formFileMultiple")
    .addEventListener("change", function () {
      let fileLabel = document.getElementById("fileLabel");
      let files = this.files;
      if (files.length > 0) {
        fileLabel.textContent =
          files.length > 1
            ? `${files.length} arquivos selecionados`
            : files[0].name;
      } else {
        fileLabel.textContent = "Nenhum arquivo selecionado";
      }
    });
}

function criarSummerNote() {
  $("#summernote").summernote({
    placeholder: "Descrição sobre o incidente",
    height: 200,
    maxHeight: 300,
    lang: "pt-BR",
    toolbarPosition: "top",
    toolbar: [
      ["style", ["style"]],
      ["font", ["bold", "underline", "clear"]],
      ["fontname", ["fontname"]],
      ["color", ["color"]],
      ["para", ["ul", "ol", "paragraph"]],
      ["table", ["table"]],
      ["insert", ["link", "picture", "video"]],
      ["view", ["help", "fullscreen"]],
    ],
  });
}


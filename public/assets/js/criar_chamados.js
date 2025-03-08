var tableNoAjax;
$(document).ready(function () {
  var indexTabelaContatos = 1;
  criarTabela();
  criarSummerNote();
  criarSelect();

  $("#adicionar-contato").click(function () {
    $("#form-lista-contato").addClass("was-validated");

    if (
      validarTelefone($("#numero_contato").val()) ||
      $("#nome_contato").is(":invalid") ||
      $("#observacao_contato").is(":invalid")
    ) {
      return false;
    }
    $("#numero_contato").removeClass("was-validated is-valid is-invalid");    

    tableNoAjax.addRow({
      nome: `<input type="hidden" name="contato-lista[${indexTabelaContatos}][nome]" value="${$("#nome_contato").val()}"/>` + $("#nome_contato").val(),
      telefone: `<input type="hidden" name="contato-lista[${indexTabelaContatos}][telefone]" value="${$("#numero_contato").val()}"/>` + $("#numero_contato").val(),
      observacao: `<input type="hidden" name="contato-lista[${indexTabelaContatos}][observacao]" value="${$("#observacao_contato").val()}"/>` + $("#observacao_contato").val(),
      acao: 
        $('<div>').append($('<button>').attr({
          class: 'btn btn-sm btn-danger remover-item-contato',
          title: 'Remover'
        }).append(
          $('<i>').addClass('far fa-trash-alt')
        )).html(),
    });
    indexTabelaContatos ++;
    //limpar os campos
    $("#form-lista-contato").removeClass("was-validated");
    $("#nome_contato").val("");
    $("#numero_contato").val("");
    $("#observacao_contato").val("");
  });

  $("#numero_contato").on("input", function () {
    let numero = $(this).val().replace(/\D/g, "");

    $(this).removeClass("was-validated is-valid is-invalid");
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

  $(document).on("click", ".remover-item-contato", function () {
    let linha = $(this).closest("tr");
    let index = linha.index();
    tableNoAjax.removeRow(index);
    linha.remove();
  });
  
  $('#salvar-chamado').click(() => {

    let formData = verificarCampos();
    if (formData === true) {
      callToast("error", 'Campos com * são obrigatorios');
      return;
    }
    $.ajax({
      url: "/criar_chamados", // Ajuste para o endpoint correto
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
  // Verifica se o tipo de incidente foi selecionado
  else if ($('.select-tipo-incidente').val() == null) {
    return true;
  } 
  // Verifica se pelo menos um arquivo foi anexado
  else if ($('#formFileMultiple')[0].files.length === 0) {
    return true;
  } 
  // Verifica se há pelo menos um contato na tabela
  else if ($("#table-contatos-chamados tbody tr").length === 0) {
    return true;
  }

  // Criando um objeto FormData para armazenar os dados do formulário
  let formData = new FormData();

  // Adiciona a descrição do incidente
  formData.append("descricao", $('#summernote').summernote('code'));

  // Adiciona o tipo de incidente
  formData.append("tipo_incidencia", $('.select-tipo-incidente').val());

  // Adiciona os arquivos ao FormData
  let arquivos = $("#formFileMultiple")[0].files;
  for (let i = 0; i < arquivos.length; i++) {
    formData.append("arquivos[]", arquivos[i]);
  }

  // Captura os dados da tabela de contatos e adiciona ao FormData
  $("#table-contatos-chamados tbody tr").each(function (index) {
    let nome = $(this).find('input[name^="contato-lista["][name$="[nome]"]').val();
    let telefone = $(this).find('input[name^="contato-lista["][name$="[telefone]"]').val();
    let observacao = $(this).find('input[name^="contato-lista["][name$="[observacao]"]').val();
    formData.append(`contatos[${index}][nome]`, nome);
    formData.append(`contatos[${index}][telefone]`, telefone);
    formData.append(`contatos[${index}][observacao]`, observacao);
  });
  $('#table-contatos-chamados').find(`input[name="contato-lista[1][nome]]`).val()
  return formData;
}


function criarSelect() {
  $(".select-tipo-incidente").select2({
    language: "pt-BR",
    placeholder: "Selecione",
    ajax: {
      url: "/get_tipo_incidentes",
      data: function (params) {
        var query = {
          nome: params.term,
        };
        return query;
      },
      processResults: function (data) {
        var results = [];
        $.each(data.data, function (index, tipo_incidencia) {
          results.push({
            id: tipo_incidencia.id_tipo_incidencia,
            text: tipo_incidencia.nome,
          });
        });

        return {
          results: results,
        };
      },
    },
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

function criarTabela() {
  tableNoAjax = new DynamicTable({
    selector: "#table-contatos-chamados",
    options: {
      columns: [
        { title: "", data: "collapse", defaultContent: "" },
        { title: "Nome", data: "nome" },
        { title: "Telefone", data: "telefone" },
        { title: "Observação", data: "observacao" },
        {
          title: "Ações",          
          data: "acao",
          width: 150,
        },
      ],
      scrollY: "150px",
      scrollCollapse: true,
      paging: false,
      searching: true,
      ordering: true,
      columnDefs: [
        { className: "dtr-control", orderable: false, targets: 0 },
        { responsivePriority: 1, targets: [1, 4] },
        { orderable: false, targets: [4] },
      ],
      responsive: {
        details: {
          type: "column", // Adiciona um botão na primeira coluna
          target: 0, // Define que o botão aparece na primeira coluna
        },
      },
    },
  });
}

function validarTelefone(numero_contato) {
  if (numero_contato.length < 14) {
    $("#numero_contato")
      .removeClass("was-validated is-valid")
      .addClass("is-invalid");
    return true;
  } else {
    $("#numero_contato")
      .removeClass("was-validated is-invalid")
      .addClass("is-valid");
    return false;
  }
}

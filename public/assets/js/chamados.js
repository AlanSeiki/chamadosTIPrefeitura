var tableNoAjax;
$(document).ready(function () {
  criarTabela();

  $(document).on("click", ".finalizar-btn", function () {
    $("#titulo-modal-confirmacao").text("Finalizar Chamado");
    $("#label-motivo").text("Motivo da Finalização");
    $("#acao-modal").val("F");
    $("#chamado_id").val($(this).attr("dataid"));
    $("#motivo-modal").val("");
    $("#modal-confirmacao").modal("show");
    setTimeout(() => {
      $("#motivo-modal").focus();
    }, 300);
  });

  $(document).on("click", ".cancelar-btn", function () {
    $("#titulo-modal-confirmacao").text("Cancelar Chamado");
    $("#label-motivo").text("Motivo do Cancelamento");
    $("#acao-modal").val("C");
    $("#chamado_id").val($(this).attr("dataid"));
    $("#motivo-modal").val("");
    $("#modal-confirmacao").modal("show");
    setTimeout(() => {
      $("#motivo-modal").focus();
    }, 300);
  });

  $(document).on("click", ".visualizar-btn", function () {
    let chamado_id = $(this).attr("dataid");
    window.location.href = `/visualizar_chamado?chamado_id=${chamado_id}`;
  });

  $(document).on("click", "#salvar-modal-confirmacao", () => {
    $.ajax({
      url: "/mudar_status_chamado",
      type: "POST",
      data: $("#form-confirmacao-chamado").serialize(),
      success: (response) => {
        if ($("#acao-modal").val() == "C") {
          callToast("success", "Chamado Cancelado");
        } else {
          callToast("success", "Chamado Finalizado");
        }
        $("#table-contatos").DataTable().ajax.reload();
        $("#modal-confirmacao").modal("hide");
      },
      error: (xhr) => {
        var response = JSON.parse(xhr.responseText);
        callToast("error", response.mensagem);
      },
    });
  });
});

function criarTabela() {
  tableNoAjax = new DynamicTable({
    selector: "#table-contatos",
    ajaxUrl: "/get_chamados",
    options: {
      order: [[1, "asc"]],
      columns: [
        { title: "", data: "collapse", defaultContent: "" },
        {
          title: "Status",
          class: "text-center",
          width: 150,
          className: "text-center",
          name: "chamados.status",
          data: "status",
          render: function (data, type, row) {
            return statusShow(row.status);
          },
        },
        {
          title: "Tipo de Incidencia",
          data: "nome",
          name: "tipos_incidencia.nome",
        },
        {
          title: "Data de Criação",
          data: "criado_em",
          name: "chamados.criado_em",
          render: function (data, type, row) {
            return formatarDataBr(row.criado_em);
          },
        },
        {
          title: "Ações",
          data: "acao",
          width: 160,
          orderable: false,
          render: function (data, type, row) {
            let editar = $("<button>")
              .attr({
                dataid: row.id_chamado,
                class:
                  "btn btn-sm btn-primary visualizar-btn position-relative",
                title: "Visualizar",
              })
              .append(
                $("<i>").addClass("far fa-comments"),
                row.nao_lidos > 0
                  ? $("<span>")
                      .addClass(
                        "badge bg-danger position-absolute top-0 start-100 translate-middle"
                      )
                      .text(row.nao_lidos)
                  : ""
              );

            let finalizar = $("<button>")
              .attr({
                dataid: row.id_chamado,
                class: "btn btn-sm btn-success finalizar-btn",
                title: "Finalizar",
                disabled:
                  row.status == "Cancelado" || row.status == "Finalizado",
              })
              .append($("<i>").addClass("fas fa-check-circle"));
            let cancelar = $("<button>")
              .attr({
                dataid: row.id_chamado,
                class: "btn btn-sm btn-danger cancelar-btn",
                title: "Cancelar",
                disabled:
                  row.status == "Cancelado" || row.status == "Finalizado",
              })
              .append($("<i>").addClass("fas fa-times-circle"));
            return $("<div>")
              .append(
                $("<div>")
                  .addClass("d-flex gap-1")
                  .append(editar)
                  .append(finalizar)
                  .append(cancelar)
              )
              .html();
          },
        },
      ],
      scrollY:
        screen.height >= 1080
          ? "700px"
          : screen.height >= 768
          ? "600px"
          : "400px", // Define o scroll no tbody
      scrollCollapse: true,
      paging: true,
      searching: true,
      ordering: true,
      processing: true,
      serverSide: true,
      columnDefs: [
        { className: "dtr-control", orderable: false, targets: 0 },
        { responsivePriority: 1, targets: [2, 4] },
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

function statusShow(status) {
  switch (status) {
    case "Aberto":
      return '<span class="badge status rounded-pill text-bg-primary">Aberto</span>';
    case "Em andamento":
      return '<span class="badge status rounded-pill text-bg-warning">Em andamento</span>';
    case "Finalizado":
      return '<span class="badge status rounded-pill text-bg-success">Finalizado</span>';
    case "Cancelado":
      return '<span class="badge status rounded-pill text-bg-danger">Cancelado</span>';
    default:
      return '<span class="badge status rounded-pill text-bg-warning">Em andamento</span>';
  }
}

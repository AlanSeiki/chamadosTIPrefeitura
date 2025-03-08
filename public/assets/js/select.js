class DropdownSelect {
    constructor({ id, placeholder, url, filtros = {}, idKey = "id_estado", labelKey = "nome" }) {
      this.$inputHidden = $(`#${id}`);
      this.$inputSearch = $(`#buscar-select-${id}`);
      this.$dropdownMenu = $(`#${id}_values`);
      this.url = url;
      this.filtros = filtros;
      this.idKey = idKey; // Chave do ID (ex: id_estado, id_cidade)
      this.labelKey = labelKey; // Chave do nome exibido (ex: nome, cidade)
      this.placeholder = placeholder || "Selecione...";
  
      if (!this.$inputHidden.length || !this.$inputSearch.length || !this.$dropdownMenu.length) {
        console.error("Erro: Elementos não encontrados.");
        return;
      }
  
      this.$inputSearch.attr("placeholder", this.placeholder);
  
      // Abre o dropdown ao focar no input
      this.$inputSearch.on("focus", () => this.toggleDropdown(true));
  
      // Impede fechamento ao clicar no input
      this.$inputSearch.on("click", (e) => {
        e.stopPropagation();
        this.toggleDropdown(true);
      });
  
      // Valida seleção ao perder o foco
      this.$inputSearch.on("blur", () => setTimeout(() => this.validarSelecao(), 200));
  
      // Fecha dropdown ao clicar fora
      $(document).on("click", (e) => this.fecharDropdown(e));
  
      this.carregarDados();
    }
  
    carregarDados() {
      $.ajax({
        url: this.url,
        type: "GET",
        data: this.filtros,
        success: (response) => {
          this.$dropdownMenu.empty();
          response.data.forEach((v) => {
            let $item = $("<li>")
              .text(v[this.labelKey]) // Exibe o nome correto (ex: nome ou cidade)
              .attr({
                "data-value": v[this.idKey], // Usa o ID correto (ex: id_estado ou id_cidade)
              })
              .addClass("dropdown-item")
              .on("click", () => this.selecionarItem(v));
  
            this.$dropdownMenu.append($item);
          });
        },
        error: () => {
          callToast("error", "Erro ao carregar dados");
        },
      });
    }
  
    mudarFiltro(novoFiltro) {
      this.filtros = novoFiltro;
      this.carregarDados();
    }
  
    selecionarItem(item) {
      this.$inputHidden.val(item[this.idKey]).trigger("change"); // Usa a chave dinâmica
      this.$inputSearch.val(item[this.labelKey]).attr("value", item[this.labelKey]).attr("selecionado", true);
      this.toggleDropdown(false);
    }
  
    validarSelecao() {
      if (this.$inputSearch.attr("selecionado") == 'false') {
        this.$inputSearch.val("");
      }
      this.$inputSearch.attr("selecionado", false);
    }
  
    toggleDropdown(abre) {
      this.$dropdownMenu.css("display", abre ? "block" : "none");
    }
  
    fecharDropdown(e) {
      if (!this.$inputSearch.is(e.target) && !this.$dropdownMenu.has(e.target).length) {
        this.toggleDropdown(false);
      }
    }
  }
  
<?php


class SelectController extends Controller {

    private $cidadeModel;
    private $estadoModel;
    private $tipoIncidenciaModel;

    public function __construct() {
        $this->cidadeModel = new Cidades();
        $this->estadoModel = new Estados();
        $this->tipoIncidenciaModel = new TipoIncidencia();
    }


    function cidades() {
        $query = $this->cidadeModel->query();
        $query->select('cidades', 'id_cidade, nome');

        if ($estado_id = filter_input(INPUT_GET, 'estado_id', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $query->where('estado_id', '=' ,$estado_id);
        }

        if ($nome = filter_input(INPUT_GET, 'nome', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $query->whereILike('nome', $nome);
        }

        $query->limit(10)->get();
        $this->retornoData($query->get(), 200);     
    }

    function estados() {
        $query = $this->estadoModel->query();
        $query->select('estados', 'id_estado, nome');

        if ($nome = filter_input(INPUT_GET, 'nome', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $query->whereILike('nome', $nome);
        }

        $query->limit(10)->get();
        $this->retornoData($query->get(), 200);   
    }

    function tipo_incidentes() {
        $query = $this->tipoIncidenciaModel->query();
        $query->select('tipos_incidencia', 'id_tipo_incidencia, nome');

        if ($nome = filter_input(INPUT_GET, 'nome', FILTER_SANITIZE_SPECIAL_CHARS)) {
            $query->whereILike('nome', $nome);
        }

        $query->limit(10)->get();
        $this->retornoData($query->get(), 200);   
    }
    
}
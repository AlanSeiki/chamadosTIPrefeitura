<?php

require_once(DIRETORIO . '/app/Helpers/functions.php');

class ChamadosController extends Controller
{
    private $chamadoModel;
    private $chamadoContatoModel;
    private $chamadoAnexoModel;
    private $chamadoHistoricoModel;
    private $auth;

    public function __construct()
    {
        $this->chamadoModel = new Chamado();
        $this->chamadoContatoModel = new ContatoChamado();
        $this->chamadoAnexoModel = new AnexoChamado();
        $this->chamadoHistoricoModel = new HistoricoChamado();
        $this->auth = new Auth();
    }

    public function index()
    {
        $this->view('chamados', 'default');
    }

    public function get()
    {

        $query = $this->chamadoModel->query();

        // Seleciona os campos
        $query->select('chamados', '
            chamados.id_chamado,
            tipos_incidencia.nome,
            chamados.status,
            chamados.criado_em,
            COUNT(historico_chamado.id_historico) AS nao_lidos
        ');

        // LEFT JOIN com tipos_incidencia
        $query->leftJoin('tipos_incidencia', 'tipos_incidencia.id_tipo_incidencia = chamados.tipo_incidencia_id');

        // LEFT JOIN com historico_chamado usando condições adicionais
        $query->leftJoinWithCondition('historico_chamado', function (&$condition, &$bindings) {
            $condition = 'historico_chamado.chamado_id = chamados.id_chamado 
                  AND historico_chamado.lido = 0 
                  AND historico_chamado.usuario_id != :current_user_id';
            $bindings[':current_user_id'] = $this->auth->user()['id'];
        });

        // Aplica filtro para usuários tipo "N"
        if ($this->auth->user()['tipo_user'] == 'N') {
            $query->where('chamados.usuario_id', '=', $this->auth->user()['id']);
        }

        // Agrupa resultados para contabilizar nao_lidos corretamente
        $query->groupBy('chamados.id_chamado, tipos_incidencia.nome, chamados.status, chamados.criado_em');

        // Ordenação e Paginação
        if (isset($_GET['order'][0]['name'])) {
            $result = $query->paginate($_GET['length'], $_GET['start'], $_GET['order'][0]['name'], $_GET['order'][0]['dir']);
        } else {
            $result = $query->paginate($_GET['length'], $_GET['start'], 'chamados.status', 'desc');
        }


        $this->retornoPaginado($result, count($result), count($result));
    }

    public function getById()
    {
        if (isset($_GET['chamado_id'])) {
            $chamado_id = $_GET['chamado_id'];

            $chamadoQuery = $this->chamadoModel->query();

            // Buscar chamado principal e tipo de incidência
            $chamado = $chamadoQuery->select('chamados', 'chamados.id_chamado, chamados.status, chamados.descricao, tipos_incidencia.descricao as tipo_incidencia, tipos_incidencia.id_tipo_incidencia')
                ->leftJoin('tipos_incidencia', 'tipos_incidencia.id_tipo_incidencia = chamados.tipo_incidencia_id')
                ->where('chamados.id_chamado', '=', $chamado_id)
                ->first();

            $contatoQuery = $this->chamadoContatoModel->query();
            // Buscar contatos relacionados
            $contatos = $contatoQuery->select('contatos_chamado', '*')
                ->where('chamado_id', '=', $chamado_id)
                ->get();

            $anexoQuery = $this->chamadoAnexoModel->query();
            // Buscar anexos relacionados
            $anexos = $anexoQuery->select('anexos_chamado', '*')
                ->where('chamado_id', '=', $chamado_id)
                ->get();

            $historicoQuery = $this->chamadoHistoricoModel->query();
            // Buscar anexos relacionados
            $historico = $historicoQuery->select('historico_chamado', '*')
                ->leftJoin('usuarios', 'usuarios.id_usuario = historico_chamado.usuario_id')
                ->where('chamado_id', '=', $chamado_id)
                ->get();

            // Combinar os dados
            $chamado['contatos'] = $contatos;
            $chamado['anexos'] = $anexos;
            $chamado['historico'] = $historico;

            // Enviar para a view
            $parametros = ['chamado' => $chamado];
            $this->view('visualizar_chamado', 'default', $parametros);
        }
    }

    public function formCriarCHamado()
    {
        $this->view('criar_chamados', 'default');
    }

    public function updated()
    {
        //fazendo que historico seja a continuação do chamado carregando novos arquivos e adicionando descrições novas
        $idChamado = $_POST['id_chamado'];
        if (isset($_FILES['arquivos'])) {
            foreach ($_FILES['arquivos']['tmp_name'] as $index => $tmpFile) {
                $conteudoBase64 = base64_encode(file_get_contents($tmpFile));
                $nomeArquivo = $_FILES['arquivos']['name'][$index];

                $dadosAnexo = [
                    'chamado_id' => $idChamado,
                    'nome_arquivo' => $nomeArquivo,
                    'conteudo_base64' => $conteudoBase64
                ];
                $this->chamadoAnexoModel->query()->create('anexos_chamado', $dadosAnexo);
            }
        }
        /* um chamado está ligado diretamente a um usuario então quando alguem adiciona algo 
        novo e criado um lido 0 referese que não foi lido por usuario atentende ou requisitante*/
        $data = [
            "lido" => 1,
        ];

        $where = [
            "lido" => 0,
            'chamado_id' => $idChamado
        ];

        $this->chamadoHistoricoModel->query()->update('historico_chamado', $data, $where);

        $dadosHistorico = [
            'chamado_id' => $idChamado,
            'alteracao' => teste_input($_POST['alteracao']),
            'usuario_id' => $this->auth->user()['id'],
            'lido' => 0
        ];
        $this->chamadoHistoricoModel->query()->create('historico_chamado', $dadosHistorico);


        $result = $this->chamadoModel->query()->select('chamados', 'usuario_id')->where('id_chamado', '=', $idChamado)->first();

        if ($result['usuario_id'] != $this->auth->user()['id']) {
            $data = [
                "status" => 'Em andamento',
            ];

            $where = [
                "id_chamado" => $idChamado
            ];
            $this->chamadoModel->query()->update('chamados', $data, $where);
        }
        return $this->retornoMessage('Chamado atualizado com sucesso!', 200);
    }

    public function create()
    {
        try {
            // criando chamado
            $data = [
                'tipo_incidencia_id' => teste_input($_POST["tipo_incidencia"]),
                'descricao' => teste_input($_POST["descricao"]),
                'status' => 'Aberto',
                'usuario_id' => $this->auth->user()['id']
            ];
            $this->chamadoModel->query()->create('chamados', $data);

            //pegando o chamado criado pelo usuario
            $chamado = $this->chamadoModel->query()->select('chamados', 'id_chamado')->where('usuario_id', '=', $this->auth->user()['id'])->last('id_chamado');

            $idChamado = $chamado['id_chamado'];

            //Inserir os contatos do chamado

            foreach ($_POST['contatos'] as $contato) {
                $dadosContato = [
                    'chamado_id' => $idChamado,
                    'nome' => teste_input($contato['nome']),
                    'telefone' => teste_input($contato['telefone']),
                    'observacao' => teste_input($contato['observacao'])
                ];
                $this->chamadoContatoModel->query()->create('contatos_chamado', $dadosContato);
            }


            // Processar os arquivos anexados

            foreach ($_FILES['arquivos']['tmp_name'] as $index => $tmpFile) {
                $conteudoBase64 = base64_encode(file_get_contents($tmpFile));
                $nomeArquivo = $_FILES['arquivos']['name'][$index];

                $dadosAnexo = [
                    'chamado_id' => $idChamado,
                    'nome_arquivo' => $nomeArquivo,
                    'conteudo_base64' => $conteudoBase64
                ];
                $this->chamadoAnexoModel->query()->create('anexos_chamado', $dadosAnexo);
            }


            // Inserir histórico do chamado
            $dadosHistorico = [
                'chamado_id' => $idChamado,
                'alteracao' => 'Chamado Criado',
                'usuario_id' => $this->auth->user()['id']
            ];
            $this->chamadoHistoricoModel->query()->create('historico_chamado', $dadosHistorico);

            return $this->retornoMessage('Chamado criado com sucesso!', 201);
        } catch (\Throwable $th) {
            return $this->retornoMessage('Erro ao criar o chamado: ' . $th->getMessage(), 500);
        }
    }

    public function change_status()
    {
        //função para alterar o status do chamado.
        try {
            $idChamado = teste_input($_POST["chamado_id"]);
            $motivo = teste_input($_POST["motivo-modal"]);

            $data = [
                "status" => $_POST["acao-modal"] == 'F' ? 'Finalizado' : 'Cancelado',
            ];

            $where = [
                "id_chamado" => $idChamado
            ];
            $this->chamadoModel->query()->update('chamados', $data, $where);
            //cria um ultimo historico de fechamento do chamado
            $dadosHistorico = [
                'chamado_id' => $idChamado,
                'alteracao' => $_POST["acao-modal"] == 'F' ? 'Chamado Finalizado motivo: ' . $motivo : 'Chamado Cancelado motivo: ' . $motivo,
                'usuario_id' => $this->auth->user()['id']
            ];
            $this->chamadoHistoricoModel->query()->create('historico_chamado', $dadosHistorico);
            //zerando os chats abertos
            $data = [
                "lido" => 1,
            ];
    
            $where = [
                "lido" => 0,
                'chamado_id' => $idChamado
            ];
    
            $this->chamadoHistoricoModel->query()->update('historico_chamado', $data, $where);
            return $this->retornoMessage('Chamado Atualizado com sucesso!', 201);
        } catch (\Throwable $th) {
            return $this->retornoMessage('Erro ao atualizar chamado: ' . $th->getMessage(), 500);
        }
    }
}

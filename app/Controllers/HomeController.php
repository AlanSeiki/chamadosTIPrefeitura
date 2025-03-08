<?php

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home', 'default');
    }

    public function dashboard()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $userType = $_SESSION['user_type'] ?? 'N'; // 'N' = Normal, 'A' = Admin, 'M' = Manager        
        $query = new QueryBuilder();

        // Total de chamados
        $totalChamados = $query->select('chamados', 'COUNT(*) as total')
            ->where($userType == 'N' ? 'usuario_id' : '1', '=', $userType == 'N' ? $userId : 1)
            ->first();

        // Chamados por status
        $chamadosPorStatus = $query->select('chamados', 'status, COUNT(*) as total')
            ->where($userType == 'N' ? 'usuario_id' : '1', '=', $userType == 'N' ? $userId : 1)
            ->groupBy('status')
            ->get();

        // Chamados por tipo de incidência
        $chamadosPorTipo = $query->select('chamados', 'tipos_incidencia.nome, COUNT(chamados.tipo_incidencia_id) as total')
            ->leftJoin('tipos_incidencia', 'chamados.tipo_incidencia_id = tipos_incidencia.id_tipo_incidencia')
            ->where($userType == 'N' ? 'usuario_id' : '1', '=', $userType == 'N' ? $userId : 1)
            ->groupBy('nome')
            ->get();

        // Chamados nos últimos 30 dias
        $chamadosUltimos30Dias = $query->select('chamados', 'DATE(criado_em) as dia, COUNT(*) as total')
            ->whereBetween('criado_em', date('Y-m-d', strtotime('-30 days')), date('Y-m-d 23:59:59'))
            ->where($userType == 'N' ? 'usuario_id' : '1', '=', $userType == 'N' ? $userId : 1)
            ->groupBy('dia')
            ->get();

        // Retorno JSON
        $this->retornoData([
            'totalChamados' => $totalChamados['total'] ?? 0,
            'chamadosPorStatus' => $chamadosPorStatus,
            'chamadosPorTipo' => $chamadosPorTipo,
            'chamadosUltimos30Dias' => $chamadosUltimos30Dias
        ]);
    }
}

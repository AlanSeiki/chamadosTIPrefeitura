$(document).ready(function() {
    // Função para carregar os dados do dashboard via Ajax
    function loadDashboardData() {
        $.ajax({
            url: '/dashboard', // URL do arquivo PHP que retorna os dados
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var data = response.data;
                
                // Dados do Total de Chamados
                $('#totalChamadosNumber').text(data.totalChamados);

                // Dados de Chamados nos Últimos 30 Dias
                $('#ultimos30Number').text(data.chamadosUltimos30Dias.reduce(function(sum, item) {
                    return sum + parseInt(item.total);
                }, 0));

                // Gráfico de Chamados por Status
                let statusLabels = data.chamadosPorStatus.map(item => item.status);
                let statusData = data.chamadosPorStatus.map(item => item.total);

                // Gráfico de Chamados por Tipo de Incidência
                let tipoLabels = data.chamadosPorTipo.map(item => item.nome);
                let tipoData = data.chamadosPorTipo.map(item => item.total);

                // Gráfico de Chamados por Status
                new Chart(document.getElementById('statusChart'), {
                    type: 'bar',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            label: 'Chamados por Status',
                            data: statusData,
                            backgroundColor: '#4e73df',
                            borderColor: '#4e73df',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Gráfico de Chamados por Tipo de Incidência
                new Chart(document.getElementById('tipoChart'), {
                    type: 'polarArea',
                    data: {
                        labels: tipoLabels,
                        datasets: [{
                            label: 'Chamados por Tipo de Incidência',
                            data: tipoData,
                            backgroundColor: getRandomLightColors(tipoData.length),
                        }]
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar os dados: ' + error);
            }
        });
    }

    // Carregar os dados ao carregar a página
    loadDashboardData();
});

function getRandomLightColor() {
    const r = Math.floor(Math.random() * 128) + 127;
    const g = Math.floor(Math.random() * 128) + 127;
    const b = Math.floor(Math.random() * 128) + 127;
    return `rgb(${r},${g},${b})`;
}

function getRandomLightColors(count) {
    let colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(getRandomLightColor());
    }
    return colors;
}
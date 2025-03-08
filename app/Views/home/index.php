<div class="elevated bg-white rounded-sm p-4 overflow-auto">
    <h2>Dashboard de Chamados</h2>
    <div class="row">

        <!-- Card de Total de Chamados -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Total de Chamados</h5>
                    <h3 id="totalChamadosNumber">0</h3>
                </div>
            </div>
        </div>

        <!-- Card de Chamados nos Últimos 30 Dias -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Chamados nos Últimos 30 Dias</h5>
                    <h3 id="ultimos30Number">0</h3>
                </div>
            </div>
        </div>

        <!-- Gráfico de Chamados por Status -->
        <div class="col-md-6 mb-4">
            <h4>Chamados por Status</h4>
            <div class="d-flex justify-content-center" style="max-height: 500px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Gráfico de Chamados por Tipo de Incidência -->
        <div class="col-md-6 mb-4" >
            <h4>Chamados por Tipo de Incidência</h4>
            <div class="d-flex justify-content-center" style="max-height: 500px;">                
                <canvas id="tipoChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Inclusão do script do Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/assets/js/home.js"></script>

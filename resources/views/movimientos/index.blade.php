@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="color: #8E805E">Movimientos Financieros</h1>
            <a href="{{ route('movimientos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar movimiento
            </a>
        </div>

        @php
            $minDate = $movimientos->count() ? \Carbon\Carbon::parse($movimientos->min('fecha'))->toDateString() : null;
            $maxDate = now()->toDateString();
        @endphp

        <!-- Formulario de filtros al inicio -->
        <div class="card mb-3">
            <div class="card-body">
                <form id="filterForm" class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" id="filterTipo" class="form-control">
                            <option value="">Todos</option>
                            <option value="ingreso" {{ request('tipo')=='ingreso' ? 'selected' : '' }}>Ingresos</option>
                            <option value="egreso" {{ request('tipo')=='egreso' ? 'selected' : '' }}>Egresos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" id="filterCategoria" class="form-control">
                            <option value="">Todas</option>
                            @php $cats = ['ventas','confecciones','gastos_operativos','salarios','insumos','otros']; @endphp
                            @foreach($cats as $c)
                                <option value="{{ $c }}" {{ request('categoria')==$c ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$c)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Desde</label>
                        <input type="date" name="from" id="filterFrom" class="form-control" value="{{ request('from') ?: $minDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="to" id="filterTo" class="form-control" value="{{ request('to') ?: $maxDate }}" max="{{ $maxDate }}">
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btn-apply-filters" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Aplicar filtros
                        </button>
                        <button type="button" id="btn-clear-filters" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-eraser"></i> Limpiar filtros
                        </button>
                        <button type="button" id="btn-print" class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#pdfOptionsModal">
                            <i class="fas fa-print"></i> Imprimir
                        </button>

                    </div>
                </form>
                <div class="mt-2">
                    <small class="text-muted">Período: Desde <strong id="periodDesde">{{ request('from') ?: ($minDate ?: 'Sin datos') }}</strong> hasta <strong id="periodHasta">{{ request('to') ?: $maxDate }}</strong></small>
                </div>
            </div>
        </div>

        <!-- Modal para opciones de PDF -->
        <div class="modal fade" id="pdfOptionsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="pdfOptionsForm" method="GET" action="{{ route('movimientos.print') }}" target="_blank" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Opciones de Exportación PDF</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>Seleccione los gráficos que desea incluir en el PDF:</p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ingresos_categoria" id="chart_ingresos_categoria" name="charts[]" checked>
                            <label class="form-check-label" for="chart_ingresos_categoria">Ingresos por categoría</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="egresos_categoria" id="chart_egresos_categoria" name="charts[]" checked>
                            <label class="form-check-label" for="chart_egresos_categoria">Egresos por categoría</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ingresos_egresos_line" id="chart_ingresos_egresos_line" name="charts[]">
                            <label class="form-check-label" for="chart_ingresos_egresos_line">Ingresos vs Egresos (línea)</label>
                        </div>
                        <hr>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="include_analysis" name="include_analysis" checked>
                            <label class="form-check-label" for="include_analysis">Incluir análisis de punto de equilibrio</label>
                        </div>
                        <!-- Hidden inputs para pasar filtros actuales -->
                        <input type="hidden" name="from" id="pdf_from">
                        <input type="hidden" name="to" id="pdf_to">
                        <input type="hidden" name="tipo" id="pdf_tipo">
                    <input type="hidden" name="categoria" id="pdf_categoria">
                    <!-- Incluir flag print para que la vista auto-dispare window.print() -->
                    <input type="hidden" name="print" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Generar PDF</button>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stat-card ingreso mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Ingresos</h5>
                        <p class="card-text">Bs. {{ number_format($totales['ingresos'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card egreso mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Egresos</h5>
                        <p class="card-text">Bs. {{ number_format($totales['egresos'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card balance mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Balance</h5>
                        <p class="card-text">Bs. {{ number_format($totales['balance'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ingresos vs Egresos (Últimos 7 días)</h5>
                        <canvas id="ingresosEgresosChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Movimientos por Categoría</h5>
                        <canvas id="categoriasPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Categoría</th>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Empleado</th>
                                <th>Referencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movimientos as $mov)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($mov->tipo == 'ingreso')
                                            <span class="badge-ingreso">{{ ucfirst($mov->tipo) }}</span>
                                        @else
                                            <span class="badge-egreso">{{ ucfirst($mov->tipo) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $mov->categoria)) }}</td>
                                    <td>{{ $mov->concepto }}</td>
                                    <td>Bs. {{ number_format($mov->monto, 2) }}</td>
                                    <td>{{ $mov->empleado->nombre ?? 'N/A' }}</td>
                                    <td>{{ $mov->referencia ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>

        <style>
            /* Estadísticas con paleta del proyecto */
            .stat-card { background: #fff; border: 1px solid #e6e2d9; }
            .stat-card .card-title { color: #8E805E; }
            .stat-card.ingreso { border-left: 6px solid #8E805E; }
            .stat-card.egreso { border-left: 6px solid #C1BAA2; }
            .stat-card.balance { border-left: 6px solid #6f42c1; }
            .btn-main { background-color: #8E805E; color: #fff; border: none; }
            .btn-main:hover { background-color: #a79a82; }
            .badge-ingreso { background-color: #8E805E; color: #fff; padding: 4px 8px; border-radius: 4px; }
            .badge-egreso { background-color: #C14444; color: #fff; padding: 4px 8px; border-radius: 4px; }
        </style>
    </div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Botón de limpiar filtros
    document.getElementById('btn-clear-filters').addEventListener('click', function() {
        document.getElementById('filterTipo').value = '';
        document.getElementById('filterCategoria').value = '';
        document.getElementById('filterFrom').value = '{{ $minDate }}';
        document.getElementById('filterTo').value = '{{ $maxDate }}';
        document.getElementById('btn-apply-filters').click();
    });

    // Abrir modal de PDF: rellenar campos ocultos con filtros actuales
    document.getElementById('pdfOptionsModal')?.addEventListener('show.bs.modal', function () {
        document.getElementById('pdf_from').value = document.getElementById('filterFrom').value || '';
        document.getElementById('pdf_to').value = document.getElementById('filterTo').value || '';
        document.getElementById('pdf_tipo').value = document.getElementById('filterTipo').value || '';
        document.getElementById('pdf_categoria').value = document.getElementById('filterCategoria').value || '';
    });

    // Polling cada 10 segundos para actualizar tabla y totales (usa filtros si están aplicados)
    async function fetchLatest() {
        try {
            const params = new URLSearchParams(new FormData(document.getElementById('filterForm'))).toString();
            const url = "{{ route('movimientos.latest') }}" + (params ? ('?' + params) : '');
            const res = await fetch(url);
            if (!res.ok) return;
            const data = await res.json();
            const movimientos = data.movimientos;
            const totales = data.totales;

            // actualizar totales (usar las tarjetas estáticas)
            const ingresoCard = document.querySelector('.stat-card.ingreso .card-text');
            const egresoCard = document.querySelector('.stat-card.egreso .card-text');
            const balanceCard = document.querySelector('.stat-card.balance .card-text');
            if (ingresoCard) ingresoCard.textContent = 'Bs. ' + Number(totales.ingresos || 0).toFixed(2);
            if (egresoCard) egresoCard.textContent = 'Bs. ' + Number(totales.egresos || 0).toFixed(2);
            if (balanceCard) balanceCard.textContent = 'Bs. ' + Number(totales.balance || 0).toFixed(2);

            // actualizar tabla
            const tbody = document.querySelector('table.table tbody');
            tbody.innerHTML = '';
            movimientos.forEach(m => {
                const row = document.createElement('tr');
                const fecha = new Date(m.fecha).toLocaleDateString();
                row.innerHTML = `
                    <td>${fecha}</td>
                    <td>${m.tipo == 'ingreso' ? '<span class="badge-ingreso">' + (m.tipo.charAt(0).toUpperCase() + m.tipo.slice(1)) + '</span>' : '<span class="badge-egreso">' + (m.tipo.charAt(0).toUpperCase() + m.tipo.slice(1)) + '</span>'}</td>
                    <td>${(m.categoria || '').replace(/_/g,' ')}</td>
                    <td>${m.concepto}</td>
                    <td>Bs. ${Number(m.monto).toFixed(2)}</td>
                    <td>${m.empleado && m.empleado.nombre ? m.empleado.nombre : 'N/A'}</td>
                    <td>${m.referencia ?? 'N/A'}</td>
                `;
                tbody.appendChild(row);
            });
        } catch (e) {
            console.error('fetchLatest error', e);
        }
    }

    // Inicializar gráficos con los datos
    const movimientos = <?php echo json_encode($movimientos); ?>;
    const fechas = [...new Set(movimientos.map(m => m.fecha.split(' ')[0]))].sort().slice(-7);
    
    // Preparar datos para el gráfico de líneas
    const ingresosData = fechas.map(fecha => 
        movimientos.filter(m => m.fecha.startsWith(fecha) && m.tipo === 'ingreso')
                  .reduce((sum, m) => sum + parseFloat(m.monto), 0)
    );
    const egresosData = fechas.map(fecha => 
        movimientos.filter(m => m.fecha.startsWith(fecha) && m.tipo === 'egreso')
                  .reduce((sum, m) => sum + parseFloat(m.monto), 0)
    );

    // Gráfico de líneas: Ingresos vs Egresos
    new Chart(document.getElementById('ingresosEgresosChart'), {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'Ingresos',
                data: ingresosData,
                borderColor: '#198754',
                backgroundColor: '#19875420',
                fill: true
            }, {
                label: 'Egresos',
                data: egresosData,
                borderColor: '#dc3545',
                backgroundColor: '#dc354520',
                fill: true
            }]
        },
        options: {
            responsive: true,
            events: [], // desactivar interacción
            interaction: { mode: null },
            plugins: { tooltip: { enabled: false }, legend: { display: true } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => 'Bs. ' + value.toFixed(2)
                    }
                }
            }
        }
    });

    // Preparar datos para el gráfico de torta
    const categorias = {};
    movimientos.forEach(m => {
        if (!categorias[m.categoria]) categorias[m.categoria] = 0;
        categorias[m.categoria] += parseFloat(m.monto);
    });

    // Gráfico de torta: Movimientos por Categoría
    new Chart(document.getElementById('categoriasPieChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(categorias),
            datasets: [{
                data: Object.values(categorias),
                backgroundColor: [
                    '#198754', '#dc3545', '#0d6efd', '#ffc107', 
                    '#6f42c1', '#20c997', '#0dcaf0'
                ]
            }]
        },
        options: {
            responsive: true,
            events: [], // desactivar interacción
            interaction: { mode: null },
            plugins: {
                tooltip: { enabled: false },
                legend: {
                    position: 'right'
                }
            }
        }
    });

    fetchLatest();
    setInterval(fetchLatest, 10000);
    // Manejar filtros y botones de export
    document.getElementById('btn-apply-filters').addEventListener('click', function(){
        fetchLatest();
        // actualizar gráficos (simplemente recargar la página para recalcular gráficos con servidor)
        location.search = new URLSearchParams(new FormData(document.getElementById('filterForm'))).toString();
    });


</script>
@endsection

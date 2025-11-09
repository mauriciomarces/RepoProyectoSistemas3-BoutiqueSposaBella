<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Financiero - Boutique Sposa Bella</title>
    <style>
        /* Si desea usar Playfair Display en DomPDF, instale la fuente en storage/fonts y regístrela en config/dompdf.php */
        /* usamos la familia declarada para navegadores; DomPDF usará la fuente configurada en config/dompdf.php si está disponible */
        body {
            font-family: 'Playfair Display', serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .header {
            position: relative;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #C1BAA2;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            width: 120px;
            height: auto;
        }

        .report-title {
            color: #8E805E;
            font-size: 24px;
            margin: 0;
            text-align: center;
            flex-grow: 1;
            padding-right: 120px; /* Para compensar el logo y centrar el título */
        }

        .meta-info {
            margin-top: 15px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }

        .filters-info {
            background-color: #EDEEE8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .filters-info strong {
            color: #8E805E;
        }

        .summary-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .summary-box {
            flex: 1;
            margin: 0 10px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #C1BAA2;
            border-radius: 8px;
            text-align: center;
        }

        .summary-box h3 {
            color: #8E805E;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .summary-box .amount {
            font-size: 20px;
            font-weight: bold;
        }

        .amount.positive { color: #28a745; }
        .amount.negative { color: #dc3545; }

        .charts-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .table-section {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        th {
            background-color: #8E805E;
            color: #EDEEE8;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #C1BAA2;
        }

        tr:nth-child(even) {
            background-color: #EDEEE8;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: #fff;
        }

        .badge-ingreso {
            background-color: #8E805E;
        }

        .badge-egreso {
            background-color: #C14444;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #C1BAA2;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        @page {
            margin: 2cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo" style="max-width: 120px; height: auto;">
            <h1 class="report-title">Reporte de Movimientos Financieros</h1>
        </div>
        <div class="meta-info">
            <div>Generado el: {{ now()->format('d/m/Y H:i') }}</div>
            <div>Por: {{ session('empleado_nombre') }}</div>
        </div>
    </div>

    <?php
        // asegurar variables y valores por defecto para evitar errores si no se pasan desde el controlador
        $selected = $selected_charts ?? (request()->has('charts') ? (array) request('charts') : []);
        $includeAnalysis = $include_analysis ?? (request()->has('include_analysis') ? boolval(request('include_analysis')) : true);

        $movimientos = isset($movimientos) ? collect($movimientos) : collect();

        $totales = $totales ?? ['ingresos' => 0, 'egresos' => 0, 'balance' => 0];

        $analisis = $analisis ?? [
            'costos_fijos' => 0,
            'costos_variables' => 0,
            'ventas' => 0,
            'unidades_vendidas' => 0,
            'punto_equilibrio' => 0,
            'punto_equilibrio_valor' => 0
        ];

        $categorias = $categorias ?? ['ingresos' => [], 'egresos' => []];

        // preparar series de fechas (últimos 7 días) para gráfico línea
        $fechas = [];
        if($movimientos->count()){
            $fechasRaw = $movimientos->map(function($m){ return \Carbon\Carbon::parse(isset($m->fecha) ? $m->fecha : null)->format('Y-m-d'); })->unique()->sort()->values();
            $fechas = $fechasRaw->slice(-7)->values()->toArray();
        }

        // preparar datos por fecha
        $ingresosPorFecha = [];
        $egresosPorFecha = [];
        foreach($fechas as $f){
            $ingresosPorFecha[$f] = $movimientos->where('tipo','ingreso')->filter(function($m) use($f){ return \Carbon\Carbon::parse(isset($m->fecha) ? $m->fecha : null)->format('Y-m-d') == $f; })->sum('monto');
            $egresosPorFecha[$f] = $movimientos->where('tipo','egreso')->filter(function($m) use($f){ return \Carbon\Carbon::parse(isset($m->fecha) ? $m->fecha : null)->format('Y-m-d') == $f; })->sum('monto');
        }
    ?>

    @if(request()->filled(['from', 'to', 'tipo', 'categoria']) || true)
    <div class="filters-info">
        <strong>Filtros aplicados:</strong><br>
        @if(request('from') && request('to'))
            Periodo: {{ \Carbon\Carbon::parse(request('from'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('to'))->format('d/m/Y') }}<br>
        @endif
        @if(request('tipo'))
            Tipo: {{ ucfirst(request('tipo')) }}<br>
        @endif
        @if(request('categoria'))
            Categoría: {{ ucfirst(str_replace('_', ' ', request('categoria'))) }}
        @endif
    </div>
    @endif

    <div class="summary-grid">
        <div class="summary-box">
            <h3>Ingresos Totales</h3>
            <div class="amount positive">Bs. {{ number_format($totales['ingresos'], 2) }}</div>
        </div>
        <div class="summary-box">
            <h3>Egresos Totales</h3>
            <div class="amount negative">Bs. {{ number_format($totales['egresos'], 2) }}</div>
        </div>
        <div class="summary-box">
            <h3>Balance Neto</h3>
            <div class="amount {{ $totales['balance'] >= 0 ? 'positive' : 'negative' }}">
                Bs. {{ number_format($totales['balance'], 2) }}
            </div>
        </div>
    </div>

    @if($includeAnalysis && ($analisis['punto_equilibrio'] > 0 || $analisis['punto_equilibrio_valor'] > 0))
    <div class="summary-box" style="margin-bottom: 30px;">
        <h3>Análisis de Punto de Equilibrio</h3>
        <div style="text-align: left; font-size: 14px;">
            <p>Costos Fijos: Bs. {{ number_format($analisis['costos_fijos'], 2) }}</p>
            <p>Costos Variables: Bs. {{ number_format($analisis['costos_variables'], 2) }}</p>
            <p>Ventas Totales: Bs. {{ number_format($analisis['ventas'], 2) }}</p>
            <p>Unidades Vendidas: {{ number_format($analisis['unidades_vendidas']) }}</p>
            <p>Punto de Equilibrio (unidades): {{ number_format($analisis['punto_equilibrio'], 2) }}</p>
            <p>Punto de Equilibrio (ventas necesarias en valor): Bs. {{ number_format($analisis['punto_equilibrio_valor'], 2) }}</p>
            <hr>
            <p><strong>Interpretación:</strong> El punto de equilibrio indica cuántas unidades debe vender el negocio para cubrir sus costos fijos y variables. Si vende menos que esa cantidad, la operación es deficitaria; si vende más, obtiene beneficio. Use este valor para evaluar objetivos de ventas y fijación de precios.</p>
        </div>
    </div>
    @endif

    <div class="table-section">
        <h2 style="color: #8E805E; margin-bottom: 20px;">Detalle de Movimientos</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th class="text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $movimiento)
                <tr>
                    <td>{{ $movimiento->fecha->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $movimiento->tipo }}">
                            {{ ucfirst($movimiento->tipo) }}
                        </span>
                    </td>
                    <td>{{ ucfirst(str_replace('_', ' ', $movimiento->categoria)) }}</td>
                    <td>{{ $movimiento->descripcion }}</td>
                    <td class="text-right">Bs. {{ number_format($movimiento->monto, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Gráficos SVG generados en servidor (cada uno en su propia página) --}}
    @if(!empty($selected))
        @foreach($selected as $idx => $chart)
            @if($idx > 0)
            <div style="page-break-before: always; margin-top:20px;">
            @else
            <div style="margin-top:20px;">
            @endif
                @if($chart == 'ingresos_categoria')
                    <h3 style="color:#8E805E;">Ingresos por Categoría</h3>
                    <?php
                        $data = $categorias['ingresos'] ?? [];
                        $total = array_sum($data) ?: 1;
                        $cx = 120; $cy = 100; $r = 80;
                        // Paleta intuitiva: verdes para ingresos
                        $palette = ['#28a745','#20c997','#17a2b8','#6f42c1','#e83e8c','#fd7e14','#ffc107'];
                    ?>
                    <svg width="280" height="220" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0" y="0" width="100%" height="100%" fill="#ffffff" />
                        <circle cx="<?php echo $cx;?>" cy="<?php echo $cy;?>" r="<?php echo $r;?>" fill="#f7f7f7" stroke="#e0e0e0" />
                        <?php
                            $startAngle = 0;
                            $i = 0;
                            foreach($data as $label => $value) {
                                $percentage = $value / $total;
                                $endAngle = $startAngle + ($percentage * 360);

                                // Convertir ángulos a radianes
                                $startRad = deg2rad($startAngle - 90);
                                $endRad = deg2rad($endAngle - 90);

                                // Calcular puntos
                                $x1 = $cx + cos($startRad) * $r;
                                $y1 = $cy + sin($startRad) * $r;
                                $x2 = $cx + cos($endRad) * $r;
                                $y2 = $cy + sin($endRad) * $r;

                                // Determinar si el arco es mayor a 180 grados
                                $largeArcFlag = $endAngle - $startAngle > 180 ? 1 : 0;

                                $color = $palette[$i % count($palette)];

                                // Crear el path para la sección del pie
                                $d = "M{$cx},{$cy} L{$x1},{$y1} A{$r},{$r} 0 {$largeArcFlag},1 {$x2},{$y2} Z";

                                echo "<path d=\"{$d}\" fill=\"{$color}\" stroke=\"white\" stroke-width=\"1\"/>";

                                $startAngle = $endAngle;
                                $i++;
                            }
                        ?>
                    </svg>
                    <div style="font-size:12px; margin-top:8px;">
                        @php $i = 0; @endphp
                        @foreach($data as $label => $value)
                            @php $color = $palette[$i % count($palette)]; @endphp
                            <div style="display:flex;align-items:center;margin-bottom:4px;">
                                <svg width="12" height="12" style="margin-right:8px;" xmlns="http://www.w3.org/2000/svg"><rect width="12" height="12" rx="2" ry="2" fill="{{ $color }}" /></svg>
                                {{ ucfirst(str_replace('_',' ',$label)) }}: Bs. {{ number_format($value,2) }}
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                @elseif($chart == 'egresos_categoria')
                    <h3 style="color:#8E805E;">Egresos por Categoría</h3>
                    <?php
                        $data = $categorias['egresos'] ?? [];
                        $total = array_sum($data) ?: 1;
                        $cx = 120; $cy = 100; $r = 80;
                        // Paleta intuitiva: rojos y naranjas para egresos
                        $palette = ['#dc3545','#fd7e14','#ffc107','#e83e8c','#6f42c1','#17a2b8','#20c997'];
                    ?>
                    <svg width="280" height="220" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0" y="0" width="100%" height="100%" fill="#ffffff" />
                        <circle cx="<?php echo $cx;?>" cy="<?php echo $cy;?>" r="<?php echo $r;?>" fill="#f7f7f7" stroke="#e0e0e0" />
                        <?php
                            $startAngle = 0;
                            $i = 0;
                            foreach($data as $label => $value) {
                                $percentage = $value / $total;
                                $endAngle = $startAngle + ($percentage * 360);

                                // Convertir ángulos a radianes
                                $startRad = deg2rad($startAngle - 90);
                                $endRad = deg2rad($endAngle - 90);

                                // Calcular puntos
                                $x1 = $cx + cos($startRad) * $r;
                                $y1 = $cy + sin($startRad) * $r;
                                $x2 = $cx + cos($endRad) * $r;
                                $y2 = $cy + sin($endRad) * $r;

                                // Determinar si el arco es mayor a 180 grados
                                $largeArcFlag = $endAngle - $startAngle > 180 ? 1 : 0;

                                $color = $palette[$i % count($palette)];

                                // Crear el path para la sección del pie
                                $d = "M{$cx},{$cy} L{$x1},{$y1} A{$r},{$r} 0 {$largeArcFlag},1 {$x2},{$y2} Z";

                                echo "<path d=\"{$d}\" fill=\"{$color}\" stroke=\"white\" stroke-width=\"1\"/>";

                                $startAngle = $endAngle;
                                $i++;
                            }
                        ?>
                    </svg>
                    <div style="font-size:12px; margin-top:8px;">
                        @php $i = 0; @endphp
                        @foreach($data as $label => $value)
                            @php $color = $palette[$i % count($palette)]; @endphp
                            <div style="display:flex;align-items:center;margin-bottom:4px;">
                                <svg width="12" height="12" style="margin-right:8px;" xmlns="http://www.w3.org/2000/svg"><rect width="12" height="12" rx="2" ry="2" fill="{{ $color }}" /></svg>
                                {{ ucfirst(str_replace('_',' ',$label)) }}: Bs. {{ number_format($value,2) }}
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                @elseif($chart == 'ingresos_egresos_line')
                    <h3 style="color:#8E805E;">Ingresos vs Egresos (Últimos 7 días)</h3>
                    <?php
                        $labels = array_values($fechas);
                        $ing = array_values($ingresosPorFecha);
                        $eg = array_values($egresosPorFecha);
                        // simple line chart svg
                        $w = 500; $h = 200; $pad = 30; $max = max(max($ing ?: [0]), max($eg ?: [0]), 1);
                        if (!function_exists('svg_point')) {
                            function svg_point($i,$v,$count,$w,$h,$pad,$max){
                                $x = $pad + ($i * (($w - $pad*2)/max(1,$count-1)));
                                $y = $h - $pad - ($v/$max)*($h - $pad*2);
                                return [$x,$y];
                            }
                        }
                    ?>
                    <svg width="520" height="240" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0" y="0" width="100%" height="100%" fill="#fff" />
                        <?php $count = count($labels); if($count):
                            // Leyenda con colores intuitivos
                            echo "<rect x='".($w-150)."' y='10' width='12' height='12' fill='#28a745' />";
                            echo "<text x='".($w-130)."' y='20' font-size='12' fill='#333'>Ingresos</text>";
                            echo "<rect x='".($w-150)."' y='30' width='12' height='12' fill='#dc3545' />";
                            echo "<text x='".($w-130)."' y='40' font-size='12' fill='#333'>Egresos</text>";

                            // grid lines y valores
                            for($g=0;$g<=4;$g++){
                                $yy = $pad + ($g * (($h - $pad*2)/4));
                                $val = number_format(($max/4) * (4-$g), 0);
                                echo "<line x1='".$pad."' y1='".$yy."' x2='".($w-$pad)."' y2='".$yy."' stroke='#eee' />";
                                echo "<text x='".($pad-5)."' y='".($yy+4)."' font-size='10' text-anchor='end' fill='#666'>$".$val."</text>";
                            }

                            // ingresos polyline y puntos con color verde
                            $pointsI = [];
                            for($i=0;$i<$count;$i++){
                                $p=svg_point($i,$ing[$i] ?? 0,$count,$w,$h,$pad,$max);
                                $pointsI[] = implode(',',$p);

                                // Círculo para cada punto
                                echo "<circle cx='".$p[0]."' cy='".$p[1]."' r='4' fill='#28a745' stroke='white' stroke-width='2'/>";

                                // Valor sobre el punto
                                if($ing[$i] > 0) {
                                    echo "<text x='".$p[0]."' y='".($p[1]-10)."' font-size='10' text-anchor='middle' fill='#28a745'>$".number_format($ing[$i],0)."</text>";
                                }
                            }
                            echo "<polyline fill='none' stroke='#28a745' stroke-width='2' points='".implode(' ',$pointsI)."' />";

                            // egresos polyline y puntos con color rojo
                            $pointsE = [];
                            for($i=0;$i<$count;$i++){
                                $p=svg_point($i,$eg[$i] ?? 0,$count,$w,$h,$pad,$max);
                                $pointsE[] = implode(',',$p);

                                // Círculo para cada punto
                                echo "<circle cx='".$p[0]."' cy='".$p[1]."' r='4' fill='#dc3545' stroke='white' stroke-width='2'/>";

                                // Valor sobre el punto
                                if($eg[$i] > 0) {
                                    echo "<text x='".$p[0]."' y='".($p[1]+20)."' font-size='10' text-anchor='middle' fill='#dc3545'>$".number_format($eg[$i],0)."</text>";
                                }
                            }
                            echo "<polyline fill='none' stroke='#dc3545' stroke-width='2' points='".implode(' ',$pointsE)."' />";

                            // labels en eje x
                            for($i=0;$i<$count;$i++){
                                $p=svg_point($i,0,$count,$w,$h,$pad,$max);
                                echo "<text x='".$p[0]."' y='".($h-$pad+15)."' font-size='10' text-anchor='middle' fill='#666'>".\Carbon\Carbon::parse($labels[$i])->format('d/m')."</text>";
                            }
                        endif; ?>
                    </svg>
                @endif
            </div>
        @endforeach
    @endif

    <div class="footer">
        <p>Boutique Sposa Bella - Reporte generado automáticamente</p>
    </div>
    @if(request()->query('print'))
    <script>
        // Si la vista se abrió con ?print=1, intentar abrir el diálogo de impresión
        window.addEventListener('load', function(){
            setTimeout(function(){ try { window.print(); } catch(e) { /* noop */ } }, 500);
        });
    </script>
    @endif
</body>
</html>
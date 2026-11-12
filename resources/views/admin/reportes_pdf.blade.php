<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1e293b; font-size: 12px; }
        .header { background-color: #064e3b; color: #ffffff; padding: 20px; border-radius: 6px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; font-size: 11px; color: #a7f3d0; }
        .kpi-table { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin-bottom: 20px; }
        .kpi-box { background: #f8fafc; border: 1px solid #cbd5e1; padding: 12px; border-radius: 6px; text-align: center; width: 33%; }
        .kpi-title { font-size: 10px; text-transform: uppercase; color: #64748b; }
        .kpi-value { font-size: 18px; font-weight: bold; color: #064e3b; margin-top: 5px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { background-color: #064e3b; color: white; padding: 8px; text-align: left; font-size: 11px; }
        .data-table td { border-bottom: 1px solid #e2e8f0; padding: 8px; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 10px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Jardín Bike — Reporte Financiero Oficial</h1>
        <p>Periodo auditado: {{ $fechaInicio }} al {{ $fechaFin }} | Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="kpi-table">
        <tr>
            <td class="kpi-box">
                <div class="kpi-title">Viajes Completados</div>
                <div class="kpi-value">{{ $data->total_viajes_realizados ?? 0 }}</div>
            </td>
            <td class="kpi-box">
                <div class="kpi-title">Ingresos Totales</div>
                <div class="kpi-value">${{ number_format($data->ingresos_generados ?? 0, 0, ',', '.') }}</div>
            </td>
            <td class="kpi-box">
                <div class="kpi-title">Ticket Promedio</div>
                <div class="kpi-value">${{ number_format($data->costo_promedio_viaje ?? 0, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <h3>Detalle Histórico de Operaciones</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Viajes Realizados</th>
                <th>Ticket Promedio</th>
                <th class="text-right">Ingresos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historico as $fila)
            <tr>
                <td>{{ \Carbon\Carbon::parse($fila->fecha)->format('d/m/Y') }}</td>
                <td>{{ $fila->viajes }}</td>
                <td>${{ number_format($fila->ticket_promedio, 0, ',', '.') }}</td>
                <td class="text-right"><strong>${{ number_format($fila->ingresos, 0, ',', '.') }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema operativo Jardín Bike Operations.
    </div>
</body>
</html>
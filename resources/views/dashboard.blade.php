<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Tickets API</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
        header { background: #4f46e5; color: white; padding: 20px 40px; }
        header h1 { font-size: 24px; }
        header p { font-size: 13px; opacity: 0.8; margin-top: 4px; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .card { background: white; border-radius: 10px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); text-align: center; }
        .card .number { font-size: 42px; font-weight: bold; color: #4f46e5; }
        .card .label { font-size: 13px; color: #64748b; margin-top: 6px; }
        .card.green .number { color: #16a34a; }
        .card.orange .number { color: #ea580c; }
        h2 { font-size: 18px; margin-bottom: 16px; color: #1e293b; }
        table { width: 100%; background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); border-collapse: collapse; overflow: hidden; }
        th { background: #4f46e5; color: white; padding: 12px 16px; text-align: left; font-size: 13px; }
        td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .badge.ticket_created { background: #dbeafe; color: #1d4ed8; }
        .badge.ticket_updated { background: #fef9c3; color: #854d0e; }
        .badge.ticket_deleted { background: #fee2e2; color: #991b1b; }
        .badge.device_assigned { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <header>
        <h1>🎫 Tickets API — Dashboard</h1>
        <p>Panel de métricas y actividad reciente</p>
    </header>

    <div class="container">
        <div class="cards">
            <div class="card">
                <div class="number">{{ $metrics['total_tickets'] }}</div>
                <div class="label">Total Tickets</div>
            </div>
            <div class="card green">
                <div class="number">{{ $metrics['open_tickets'] }}</div>
                <div class="label">Tickets Abiertos</div>
            </div>
            <div class="card">
                <div class="number">{{ $metrics['total_devices'] }}</div>
                <div class="label">Total Dispositivos</div>
            </div>
            <div class="card orange">
                <div class="number">{{ $metrics['assigned_devices'] }}</div>
                <div class="label">Dispositivos Asignados</div>
            </div>
            <div class="card">
                <div class="number">{{ $metrics['total_users'] }}</div>
                <div class="label">Usuarios Registrados</div>
            </div>
        </div>

        <h2>📋 Actividad Reciente</h2>
        <table>
            <thead>
                <tr>
                    <th>Acción</th>
                    <th>Usuario</th>
                    <th>Tipo</th>
                    <th>ID Registro</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td><span class="badge {{ $log->action }}">{{ $log->action }}</span></td>
                    <td>{{ $log->user->name ?? 'Sistema' }}</td>
                    <td>{{ class_basename($log->model_type) }}</td>
                    <td>#{{ $log->model_id }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#94a3b8;">No hay actividad registrada</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trilha de Auditoria - SCAV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #004d40; border-bottom: 2px solid #00796b; padding-bottom: 10px; }
        .header-actions { margin-bottom: 20px; }
        .btn { padding: 10px 15px; border-radius: 5px; text-decoration: none; color: white; display: inline-block; }
        .btn-secondary { background-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #e0f2f1; color: #004d40; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .empty-message { text-align: center; color: #777; padding: 20px; }
        .acao { font-weight: bold; color: #00796b; }
        .data { font-size: 0.9em; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Trilha de Auditoria do Sistema</h1>
        
        <div class="header-actions">
            <a href="/scav/public/dashboard" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para o Dashboard</a>
        </div>

        <?php if (empty($logs)): ?>
            <p class="empty-message">Nenhum registro de auditoria encontrado.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Utilizador</th>
                        <th>Ação</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="data"><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($log['dataHora']))) ?></td>
                            <td><?= htmlspecialchars($log['usuario_nome']) ?></td>
                            <td class="acao"><?= htmlspecialchars($log['acao']) ?></td>
                            <td><?= htmlspecialchars($log['detalhes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

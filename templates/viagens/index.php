<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Viagens - SCAV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #004d40; border-bottom: 2px solid #00796b; padding-bottom: 10px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 15px; border-radius: 5px; text-decoration: none; color: white; display: inline-block; border: none; cursor: pointer; }
        .btn-primary { background-color: #00796b; }
        .btn-primary:hover { background-color: #004d40; }
        .btn-secondary { background-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; }
        .btn-cancel { background-color: #d32f2f; padding: 5px 10px; font-size: 0.9em; }
        .btn-cancel:hover { background-color: #c62828; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #e0f2f1; color: #004d40; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .empty-message { text-align: center; color: #777; padding: 20px; }
        .status { padding: 5px 10px; border-radius: 12px; color: white; font-weight: bold; font-size: 0.8em; text-align: center; }
        .status-autorizada { background-color: #1976d2; }
        .status-realizada { background-color: #2e7d32; }
        .status-cancelada { background-color: #757575; }
        .filters { margin: 15px 0; display: flex; justify-content: flex-end; gap: 10px; }
        select { padding: 8px; border-radius: 5px; border: 1px solid #ccc; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { padding: 8px 12px; margin: 0 4px; background: #00796b; color: white; text-decoration: none; border-radius: 4px; }
        .pagination a.active { background: #004d40; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestão de Viagens</h1>
        
        <div class="header-actions">
            <a href="/scav/public/dashboard" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para o Dashboard</a>
            <a href="/scav/public/viagens/novo" class="btn btn-primary"><i class="fas fa-plus"></i> Autorizar Nova Viagem</a>
        </div>

        <form method="GET" class="filters">
            <select name="estado" onchange="this.form.submit()">
                <option value="">Todos os estados</option>
                <option value="Autorizada" <?= isset($_GET['estado']) && $_GET['estado']=='Autorizada' ? 'selected' : '' ?>>Autorizada</option>
                <option value="Cancelada" <?= isset($_GET['estado']) && $_GET['estado']=='Cancelada' ? 'selected' : '' ?>>Cancelada</option>
            </select>
        </form>

        <?php if (empty($viagens)): ?>
            <p class="empty-message">Nenhuma viagem encontrada.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Veículo</th>
                        <th>Motorista</th>
                        <th>Gestor</th>
                        <th>Data Prevista</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viagens as $viagem): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($viagem['codigoAutorizacao']) ?></strong></td>
                            <td><?= htmlspecialchars($viagem['veiculo_modelo'] . ' (' . $viagem['veiculo_placa'] . ')') ?></td>
                            <td><?= htmlspecialchars($viagem['motorista_nome']) ?></td>
                            <td><?= htmlspecialchars($viagem['gestor_nome']) ?></td>
                            <td><?= date('d/m/Y', strtotime($viagem['dataPrevista'])) ?></td>
                            <td>
                                <?php $statusClass = 'status-' . strtolower($viagem['status']); ?>
                                <span class="status <?= $statusClass ?>"><?= htmlspecialchars($viagem['status']) ?></span>
                            </td>
                            <td>
                                <form action="/scav/public/viagens/<?= $viagem['id'] ?>/cancelar" method="POST" style="display: inline;">
                                    <button type="submit" 
                                            class="btn btn-cancel" 
                                            <?= ($viagem['status'] !== 'Autorizada') ? 'disabled title="Apenas viagens autorizadas podem ser canceladas"' : 'title="Cancelar viagem"' ?>>
                                        Cancelar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPaginas > 1): ?>
                <div class="pagination">
                    <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                        <a href="?page=<?= $p ?><?= isset($_GET['estado']) ? '&estado='.$_GET['estado'] : '' ?>" 
                           class="<?= ($p == $paginaAtual) ? 'active' : '' ?>">
                           <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</body>
</html>

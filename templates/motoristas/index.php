<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Motoristas - SCAV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #004d40; border-bottom: 2px solid #00796b; padding-bottom: 10px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 15px; border-radius: 5px; text-decoration: none; color: white; display: inline-block; border: none; cursor: pointer; }
        .btn-primary { background-color: #00796b; }
        .btn-primary:hover { background-color: #004d40; }
        .btn-secondary { background-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; }
        .btn-edit { background-color: #fbc02d; color: #333; padding: 5px 10px; font-size: 0.9em; }
        .btn-edit:hover { background-color: #f9a825; }
        .btn-delete { background-color: #d32f2f; padding: 5px 10px; font-size: 0.9em; }
        .btn-delete:hover { background-color: #c62828; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #e0f2f1; color: #004d40; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .actions { display: flex; gap: 10px; }
        .empty-message { text-align: center; color: #777; padding: 20px; }
        .status { padding: 5px 8px; border-radius: 12px; color: white; font-weight: bold; font-size: 0.8em; text-align: center; }
        .status-ativo { background-color: #2e7d32; }
        .status-inativo { background-color: #757575; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestão de Motoristas</h1>
        
        <div class="header-actions">
            <a href="/scav/public/dashboard" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para o Dashboard</a>
            
            <!-- Botão "Adicionar" visível apenas para o Administrador -->
            <?php if (($perfilUsuario ?? null) === 'Administrador'): ?>
                <a href="/scav/public/motoristas/novo" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Novo Motorista</a>
            <?php endif; ?>
        </div>

        <?php if (empty($motoristas)): ?>
            <p class="empty-message">Nenhum motorista cadastrado ainda.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CNH</th>
                        <th>Status</th>
                        <!-- Coluna "Ações" visível apenas para o Administrador -->
                        <?php if (($perfilUsuario ?? null) === 'Administrador'): ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($motoristas as $motorista): ?>
                        <tr>
                            <td><?= htmlspecialchars($motorista['nome']) ?></td>
                            <td><?= htmlspecialchars($motorista['cnh']) ?></td>
                            <td>
                                <?php if ($motorista['status'] === 'Ativo'): ?>
                                    <span class="status status-ativo">Ativo</span>
                                <?php else: ?>
                                    <span class="status status-inativo">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <?php if (($perfilUsuario ?? null) === 'Administrador'): ?>
                                <td class="actions">
                                    <a href="/scav/public/motoristas/<?= $motorista['id'] ?>/edit" class="btn btn-edit">Editar</a>
                                    <form action="/scav/public/motoristas/<?= $motorista['id'] ?>/delete" method="POST" onsubmit="return confirm('Tem a certeza que deseja excluir este motorista?');">
                                        <button type="submit" class="btn btn-delete">Excluir</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>


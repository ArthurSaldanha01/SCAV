<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Motorista - SCAV</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #004d40; border-bottom: 2px solid #00796b; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input[type="text"], select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; background-color: #fff; }
        .form-actions { margin-top: 25px; }
        .btn { padding: 12px 20px; border-radius: 5px; text-decoration: none; color: white; border: none; cursor: pointer; font-size: 1em; }
        .btn-primary { background-color: #00796b; }
        .btn-primary:hover { background-color: #004d40; }
        .btn-secondary { background-color: #757575; margin-left: 10px; }
        .btn-secondary:hover { background-color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Motorista</h1>

        <form action="/scav/public/motoristas/<?= $motorista['id'] ?>/update" method="POST">
            
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($motorista['nome']) ?>" required>
            </div>

            <div class="form-group">
                <label for="cnh">Nº da CNH:</label>
                <input type="text" id="cnh" name="cnh" value="<?= htmlspecialchars($motorista['cnh']) ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Ativo" <?= $motorista['status'] === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="Inativo" <?= $motorista['status'] === 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="/scav/public/motoristas" class="btn btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>
</body>
</html>

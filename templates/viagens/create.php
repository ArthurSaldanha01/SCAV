<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorizar Nova Viagem - SCAV</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #004d40; border-bottom: 2px solid #00796b; padding-bottom: 10px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 20px; }
        .full-width { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input[type="text"], input[type="date"], select, textarea { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; background-color: #fff; }
        textarea { resize: vertical; min-height: 80px; }
        .form-actions { margin-top: 25px; }
        .btn { padding: 12px 20px; border-radius: 5px; text-decoration: none; color: white; border: none; cursor: pointer; }
        .btn-primary { background-color: #00796b; }
        .btn-primary:hover { background-color: #004d40; }
        .btn-secondary { background-color: #757575; margin-left: 10px; }
        .btn-secondary:hover { background-color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Autorizar Nova Viagem</h1>

        <form action="/scav/public/viagens" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label for="veiculo_id">Veículo:</label>
                    <select id="veiculo_id" name="veiculo_id" required>
                        <option value="">Selecione um veículo</option>
                        <?php foreach ($veiculos as $veiculo): ?>
                            <option value="<?= $veiculo['id'] ?>"><?= htmlspecialchars($veiculo['modelo'] . ' - ' . $veiculo['placa']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="motorista_id">Motorista:</label>
                    <select id="motorista_id" name="motorista_id" required>
                        <option value="">Selecione um motorista</option>
                        <?php foreach ($motoristas as $motorista): ?>
                            <option value="<?= $motorista['id'] ?>"><?= htmlspecialchars($motorista['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="dataPrevista">Data da Viagem:</label>
                    <input type="date" id="dataPrevista" name="dataPrevista" required min="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label for="finalidade">Finalidade:</label>
                    <input type="text" id="finalidade" name="finalidade" placeholder="Ex: Reunião na Reitoria" required>
                </div>

                <div class="form-group full-width">
                    <label for="observacoes">Observações (Opcional):</label>
                    <textarea id="observacoes" name="observacoes" placeholder="Qualquer informação adicional sobre a viagem..."></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Gerar Autorização</button>
                <a href="/scav/public/viagens" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

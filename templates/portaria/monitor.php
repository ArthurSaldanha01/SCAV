<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60"> 
    <title>Monitor de Saídas - Portaria SCAV</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f8f9fa;
            color: #212529;
            margin: 0; 
            padding: 20px; 
            line-height: 1.6;
        }
        .container { 
            max-width: 1000px; 
            margin: 20px auto; 
            background: #ffffff;
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            border: 1px solid #dee2e6;
        }
        h1 { 
            color: #004d40;
            border-bottom: 3px solid #00796b;
            padding-bottom: 15px; 
            margin-bottom: 20px;
            font-size: 2em;
        }
        h2 {
            color: #495057;
            font-size: 1.4em;
            margin-bottom: 25px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        th, td { 
            padding: 15px;
            border: 1px solid #ced4da; 
            text-align: left; 
            font-size: 1.1em;
            vertical-align: middle;
        }
        th { 
            background-color: #e9ecef;
            color: #343a40;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) { 
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e2e6ea;
        }
        .empty-message { 
            text-align: center; 
            color: #6c757d;
            padding: 40px; 
            font-size: 1.2em;
            border: 1px dashed #ced4da;
            margin-top: 20px;
            background-color: #f8f9fa;
        }
        strong {
            font-weight: 600;
        }
        .code {
            font-family: monospace;
            font-size: 1.2em;
            color: #004d40;
            background-color: #e0f2f1;
            padding: 3px 6px;
            border-radius: 4px;
        }
        /* Estilos para a coluna de observações */
        .obs {
            font-size: 0.95em;
            color: #495057;
            font-style: italic;
            max-width: 250px; /* Evita que observações longas quebrem o layout */
            white-space: pre-wrap; /* Respeita quebras de linha */
            word-wrap: break-word;
        }
        .obs-none {
            color: #adb5bd;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Monitorar Saídas - Portaria</h1>
        <h2>Viagens Autorizadas para: <strong><?= htmlspecialchars($dataAtual) ?></strong></h2>

        <?php if (empty($viagensHoje)): ?>
            <p class="empty-message">Nenhuma viagem autorizada para hoje até o momento.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Código de Autorização</th>
                        <th>Veículo</th>
                        <th>Placa</th>
                        <th>Motorista</th>
                        <th>Observações</th> <!-- COLUNA ADICIONADA -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viagensHoje as $viagem): ?>
                        <tr>
                            <td><strong class="code"><?= htmlspecialchars($viagem['codigoAutorizacao']) ?></strong></td>
                            <td><?= htmlspecialchars($viagem['veiculo_modelo']) ?></td>
                            <td><?= htmlspecialchars($viagem['veiculo_placa']) ?></td>
                            <td><?= htmlspecialchars($viagem['motorista_nome']) ?></td>
                            <!-- CÉLULA ADICIONADA -->
                            <td>
                                <?php if (!empty($viagem['observacoes'])): ?>
                                    <span class="obs"><?= htmlspecialchars($viagem['observacoes']) ?></span>
                                <?php else: ?>
                                    <span class="obs-none">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="/scav/public/js/monitor.js"></script>
</body>
</html>

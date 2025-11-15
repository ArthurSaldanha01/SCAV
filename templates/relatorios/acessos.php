<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Relatório de Acessos - SCAV</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
  body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f4f4f9; color:#333; margin:0; padding:20px; }
  .container { max-width: 1100px; margin:auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
  h1 { color:#004d40; border-bottom:2px solid #00796b; padding-bottom:10px; margin-top:0; }
  .header-actions { display:flex; justify-content:space-between; align-items:center; gap:12px; margin:14px 0 20px; flex-wrap:wrap; }
  .btn { padding:10px 15px; border-radius:5px; text-decoration:none; color:#fff; display:inline-block; border:0; cursor:pointer; }
  .btn-primary { background:#00796b; }
  .btn-primary:hover { background:#005e55; }
  .btn-secondary { background:#6c757d; }
  .btn-secondary:hover { background:#5a6268; }
  .filters { display:flex; gap:12px; align-items:end; flex-wrap:wrap; }
  .field { display:flex; flex-direction:column; gap:6px; }
  .field input[type="date"] { padding:9px 10px; border:1px solid #ddd; border-radius:6px; min-width:180px; }
  .cards { display:grid; grid-template-columns: repeat(3, 1fr); gap:12px; margin-top:12px; }
  .card { background:#e0f2f1; border:1px solid #c7e7e5; border-radius:8px; padding:14px; }
  .card h3 { margin:0 0 6px; color:#004d40; font-size:1rem; }
  .card .value { font-size:1.4rem; font-weight:600; }
  table { width:100%; border-collapse:collapse; margin-top:18px; }
  th, td { padding:12px; border:1px solid #ddd; text-align:left; vertical-align:top; }
  th { background:#e0f2f1; color:#004d40; }
  tr:nth-child(even) { background:#fafafa; }
  .badge { padding:4px 8px; border-radius:12px; font-weight:600; font-size:.85rem; color:#fff; display:inline-block; }
  .badge-entry { background:#2e7d32; }
  .badge-exit { background:#d32f2f; }
  .empty-message { text-align:center; color:#777; padding:20px; }
  .section-title { margin-top:24px; margin-bottom:6px; color:#004d40; }
  .nowrap { white-space:nowrap; }
  .viagem-info { font-size:0.9rem; line-height:1.3; }
  .viagem-info strong { display:block; }
  .search-bar { margin-top:10px; margin-bottom:6px; display:flex; justify-content:flex-end; }
  .search-bar input { padding:8px 10px; border-radius:6px; border:1px solid #ccc; min-width:260px; }
</style>
</head>
<body>
  <div class="container">
    <h1>Relatório de Acessos</h1>

    <div class="header-actions">
      <a href="/scav/public/dashboard" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
      <form class="filters" method="get" action="/scav/public/relatorios/acessos">
        <div class="field">
          <label for="de">De</label>
          <input type="date" id="de" name="de" value="<?= htmlspecialchars(substr($DE,0,10)) ?>">
        </div>
        <div class="field">
          <label for="ate">Até</label>
          <input type="date" id="ate" name="ate" value="<?= htmlspecialchars(substr($ATE,0,10)) ?>">
        </div>
        <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Filtrar</button>
        <a class="btn btn-secondary" href="/scav/public/relatorios/acessos.csv?de=<?= urlencode(substr($DE,0,10)) ?>&ate=<?= urlencode(substr($ATE,0,10)) ?>"><i class="fas fa-file-csv"></i> Exportar CSV</a>
      </form>
    </div>

    <?php
      $totalEntry = 0; $totalExit = 0;
      foreach ($RESUMO as $r) {
        if ($r['tipo']==='ENTRY') $totalEntry += (int)$r['qtd'];
        if ($r['tipo']==='EXIT')  $totalExit  += (int)$r['qtd'];
      }
      $totalAll = $totalEntry + $totalExit;
    ?>
    <div class="cards">
      <div class="card">
        <h3>Total no período</h3>
        <div class="value"><?= (int)$totalAll ?></div>
      </div>
      <div class="card">
        <h3>Entradas</h3>
        <div class="value"><?= (int)$totalEntry ?></div>
      </div>
      <div class="card">
        <h3>Saídas</h3>
        <div class="value"><?= (int)$totalExit ?></div>
      </div>
    </div>

    <h3 class="section-title">Resumo por dia</h3>
    <?php if (empty($RESUMO)): ?>
      <p class="empty-message">Sem registros no período.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Dia</th>
            <th>Tipo</th>
            <th>Qtd</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($RESUMO as $r): ?>
            <tr>
              <td class="nowrap">
                <?= htmlspecialchars(\DateTime::createFromFormat('Y-m-d', $r['dia'])->format('d/m/Y')) ?>
              </td>
              <td>
                <?php if ($r['tipo']==='ENTRY'): ?>
                  <span class="badge badge-entry">Entrada</span>
                <?php else: ?>
                  <span class="badge badge-exit">Saída</span>
                <?php endif; ?>
              </td>
              <td><?= (int)$r['qtd'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <h3 class="section-title">Registros do período</h3>

    <?php if (!empty($DETALHES)): ?>
      <div class="search-bar">
        <input
          type="text"
          id="busca-registros"
          placeholder="Buscar por placa, código, veículo, motorista..."
          oninput="filtrarRegistros()"
        >
      </div>
    <?php endif; ?>

    <?php if (empty($DETALHES)): ?>
      <p class="empty-message">Nenhum registro encontrado.</p>
    <?php else: ?>
      <table id="tabela-registros">
        <thead>
          <tr>
            <th>Data/Hora</th>
            <th>Placa</th>
            <th>Tipo</th>
            <th>Viagem</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($DETALHES as $d): ?>
            <tr>
              <td class="nowrap">
                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($d['dataHora']))) ?>
              </td>
              <td><?= htmlspecialchars($d['placaDetectada']) ?></td>
              <td>
                <?php if ($d['tipo']==='ENTRY'): ?>
                  <span class="badge badge-entry">Entrada</span>
                <?php else: ?>
                  <span class="badge badge-exit">Saída</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($d['viagem_id'])): ?>
                  <div class="viagem-info">
                    <?php if (!empty($d['codigoAutorizacao'])): ?>
                      <strong>Cód: <?= htmlspecialchars($d['codigoAutorizacao']) ?></strong>
                    <?php endif; ?>

                    <?php
                      $modelo = $d['veiculo_modelo'] ?? '';
                      $placa  = $d['veiculo_placa']  ?? '';
                      $linhaVeic = trim($modelo . ' ' . ($placa ? '(' . $placa . ')' : ''));
                    ?>
                    <?php if ($linhaVeic): ?>
                      <div><?= htmlspecialchars($linhaVeic) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($d['motorista_nome'])): ?>
                      <div>Motorista: <?= htmlspecialchars($d['motorista_nome']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($d['dataPrevista'])): ?>
                      <div>Data prevista: <?= htmlspecialchars(date('d/m/Y', strtotime($d['dataPrevista']))) ?></div>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <script>
    function filtrarRegistros() {
      const input = document.getElementById('busca-registros');
      if (!input) return;
      const filtro = input.value.toLowerCase();
      const tabela = document.getElementById('tabela-registros');
      const linhas = tabela.querySelectorAll('tbody tr');

      linhas.forEach(linha => {
        const texto = linha.textContent.toLowerCase();
        linha.style.display = texto.includes(filtro) ? '' : 'none';
      });
    }
  </script>
</body>
</html>

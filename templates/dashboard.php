<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SCAV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/scav/public/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <p>INSTITUTO FEDERAL<br><span>Baiano</span></p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="/scav/public/dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="/scav/public/viagens"><i class="fas fa-route"></i> Viagens</a></li>
                    <li><a href="/scav/public/veiculos"><i class="fas fa-car"></i> Veículos</a></li>
                    <li><a href="/scav/public/motoristas"><i class="fas fa-id-card"></i> Motoristas</a></li>
                    <li><a href="/scav/public/portaria/monitor" target="_blank"><i class="fas fa-tv"></i> Monitorar Saídas</a></li>

                    <?php 
                        $perfil = $perfilUsuario ?? null; 
                    ?>

                    <?php if ($perfil === 'Administrador' || $perfil === 'Gestor'): ?>
                        <li><a href="/scav/public/relatorios"><i class="fas fa-chart-line"></i> Relatórios</a></li>
                    <?php endif; ?>

                    <?php if ($perfil === 'Administrador'): ?>
                        <li><a href="/scav/public/usuarios"><i class="fas fa-users"></i> Utilizadores</a></li>
                    <?php endif; ?>

                    <?php if ($perfil === 'Administrador'): ?>
                        <li><a href="/scav/public/auditoria"><i class="fas fa-history"></i> Trilha de Auditoria</a></li>
                    <?php endif; ?>

                    <li><a href="/scav/public/logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Sair do Sistema</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
           <header class="main-header">
                <h2>Bem-vindo de volta, <?= htmlspecialchars($nomeUsuario ?? 'Usuário') ?>!</h2>
                <p>Aqui está o resumo das atividades do pátio.</p>
            </header>
            
            <div class="widget-grid">
                 <div class="widget">
                    <div class="widget-icon"><i class="fas fa-route"></i></div>
                    <div class="widget-info">
                        <h3>Viagens Hoje</h3>
                        <p><?= htmlspecialchars($stats['viagens_hoje'] ?? 0) ?></p>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-icon"><i class="fas fa-car-side"></i></div>
                    <div class="widget-info">
                        <h3>Total de Veículos</h3>
                        <p><?= htmlspecialchars($stats['veiculos_total'] ?? 0) ?></p>
                    </div>
                </div>

                <div class="widget large-widget">
                    <h3>Próximas Viagens Agendadas</h3>
                    <ul>
                        <?php if (empty($proximasViagens)): ?>
                            <li>Nenhuma viagem agendada.</li>
                        <?php else: ?>
                            <?php foreach ($proximasViagens as $viagem): ?>
                                <li>
                                    <strong><?= htmlspecialchars(date('d/m/Y', strtotime($viagem['dataPrevista']))) ?></strong> 
                                    - <?= htmlspecialchars($viagem['veiculo_modelo'] ?? 'Veículo') ?> (<?= htmlspecialchars($viagem['veiculo_placa'] ?? 'Placa') ?>)
                                    - Destino: <?= htmlspecialchars($viagem['finalidade'] ?? 'N/D') ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </main>
    </div>
</body>
</html>

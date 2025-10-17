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
                    
                    <?php if (($perfilUsuario ?? null) === 'Administrador'): ?>
                        <li><a href="/scav/public/usuarios"><i class="fas fa-users"></i> Utilizadores</a></li>
                    <?php endif; ?>

                    <li class="nav-item-dropdown">
                        <a href="#" id="config-btn">
                            <i class="fas fa-cog"></i> Configuração <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown-menu" id="config-menu">
                            <li><a href="#"><i class="fas fa-user-circle"></i> Meu Perfil</a></li>
                            <li><a href="/scav/public/logout"><i class="fas fa-sign-out-alt"></i> Sair do Sistema</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
           <header class="main-header">
                <h2>Bem-vindo de volta, <?= htmlspecialchars($nomeUsuario) ?>!</h2>
                <p>Aqui está o resumo das atividades do pátio.</p>
            </header>
            <div class="widget-grid">
                 <div class="widget">
                    <div class="widget-icon"><i class="fas fa-route"></i></div>
                    <div class="widget-info">
                        <h3>Viagens Hoje</h3>
                        <p>12</p>
                    </div>
                </div>
                <div class="widget">
                    <div class="widget-icon"><i class="fas fa-car-side"></i></div>
                    <div class="widget-info">
                        <h3>Veículos Ativos</h3>
                        <p>34 / 40</p>
                    </div>
                </div>
                <div class="widget">
                    <div class="widget-icon"><i class="fas fa-clock"></i></div>
                    <div class="widget-info">
                        <h3>Próxima Saída</h3>
                        <p>14:30 - Placa XYZ-1234</p>
                    </div>
                </div>
                <div class="widget">
                    <div class="widget-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="widget-info">
                        <h3>Alertas</h3>
                        <p>2</p>
                    </div>
                </div>
                <div class="widget large-widget">
                    <h3>Próximas Viagens Agendadas</h3>
                    <ul>
                        <li><strong>14:30</strong> - Fiat Cronos (XYZ-1234) - Destino: Reitoria</li>
                        <li><strong>16:00</strong> - Renault Kwid (ABC-5678) - Destino: Almoxarifado Central</li>
                        <li><strong>Amanhã 08:00</strong> - Ônibus (DEF-9012) - Destino: Visita Técnica</li>
                    </ul>
                </div>
                <div class="widget large-widget">
                    <h3>Status da Frota</h3>
                    <p><strong>Disponíveis:</strong> 28 veículos</p>
                    <p><strong>Em Viagem:</strong> 6 veículos</p>
                    <p><strong>Em Manutenção:</strong> 6 veículos</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const configBtn = document.getElementById('config-btn');
            const configMenu = document.getElementById('config-menu');
            const arrowIcon = configBtn.querySelector('.dropdown-arrow');

            configBtn.addEventListener('click', function(event) {
                event.preventDefault();
                const isExpanded = configMenu.classList.toggle('show');
                arrowIcon.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
            });

            document.addEventListener('click', function(event) {
                if (!configBtn.contains(event.target) && !configMenu.contains(event.target)) {
                    configMenu.classList.remove('show');
                    arrowIcon.style.transform = 'rotate(0deg)';
                }
            });
        });
    </script>
</body>
</html>
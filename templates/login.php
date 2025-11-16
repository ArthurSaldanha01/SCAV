<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SCAV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/scav/public/css/login.css">
</head>
<body>
    <main class="login-container">
        <div class="logo">
            <p>INSTITUTO FEDERAL<br>Baiano</p>
        </div>
        <h1>SISTEMA DE CONTROLE<br>E ACESSO VEICULAR</h1>
        <p>Preencha os campos abaixo para acessar</p>

        <form action="/scav/public/login" method="POST">
            <div class="input-group">
                <label for="usuario" class="input-label">Usuário</label>
                <div class="field">
                    <input type="text" id="usuario" name="usuario" required>
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                </div>
            </div>

            <div class="input-group">
                <label for="senha" class="input-label">Senha</label>
                <div class="field">
                    <input type="password" id="senha" name="senha" required>
                    <span class="toggle-password" data-target="#senha"><i class="fas fa-eye"></i></span>
                </div>
            </div>

            <button type="submit" class="btn-submit">ENTRAR</button>
        </form>
    </main>

    <script src="/scav/public/js/login.js"></script>
</body>
</html>

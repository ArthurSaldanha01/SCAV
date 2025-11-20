<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Utilizador - SCAV</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #004d40; border-bottom: 2px solid #00796b; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input[type="text"], input[type="email"], input[type="password"], select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; background-color: #fff; }
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
        <h1>Criar Novo Utilizador</h1>

        <form action="/scav/public/usuarios" method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha Inicial:</label>
                <input type="password" id="senha" name="senha" required>
            </div>

            <div class="form-group">
                <label for="perfil">Perfil de Acesso:</label>
                <select id="perfil" name="perfil" required>
                    <option value="">Selecione um perfil</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Gestor">Gestor</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Criar Utilizador</button>
                <a href="/scav/public/usuarios" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

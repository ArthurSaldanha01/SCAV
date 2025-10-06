<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Veículo - SCAV</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #004d40; border-bottom: 2px solid #00796b; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input[type="text"], select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; }
        #placa { text-transform: uppercase; }
        input[type="checkbox"] { margin-right: 10px; }
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
        <h1>Cadastrar Novo Veículo</h1>
        <form action="/scav/public/veiculos" method="POST">
            <div class="form-group">
                <label for="placa">Placa do Veículo:</label>
                <input type="text" id="placa" name="placa" placeholder="Ex: ABC1D23" required maxlength="7">
            </div>

            <div class="form-group">
                <label for="marca">Marca:</label>
                <select id="marca" name="marca" required>
                    <option value="">Carregando...</option>
                </select>
            </div>

            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <select id="modelo" name="modelo" required disabled>
                    <option value="">Selecione a marca primeiro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="isOficial">
                    <input type="checkbox" id="isOficial" name="isOficial" checked>
                    É um veículo oficial?
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cadastrar Veículo</button>
                <a href="/scav/public/veiculos" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const placaInput = document.getElementById('placa');
            placaInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            const selectMarca = document.getElementById('marca');
            const selectModelo = document.getElementById('modelo');
            const apiUrl = 'https://parallelum.com.br/fipe/api/v1';

            fetch(`${apiUrl}/carros/marcas`)
                .then(response => response.json())
                .then(data => {
                    selectMarca.innerHTML = '<option value="">Selecione uma marca</option>';
                    data.forEach(marca => {
                        const option = new Option(marca.nome, marca.codigo);
                        selectMarca.add(option);
                    });
                })
                .catch(error => console.error('Erro ao buscar marcas:', error));
            
            selectMarca.addEventListener('change', function() {
                const codigoMarca = this.value;
                selectModelo.innerHTML = '<option value="">Carregando modelos...</option>';
                selectModelo.disabled = true;

                if (!codigoMarca) {
                    selectModelo.innerHTML = '<option value="">Selecione a marca primeiro</option>';
                    return;
                }

                fetch(`${apiUrl}/carros/marcas/${codigoMarca}/modelos`)
                    .then(response => response.json())
                    .then(data => {
                        selectModelo.innerHTML = '<option value="">Selecione um modelo</option>';
                        selectModelo.disabled = false;
                        data.modelos.forEach(modelo => {
                            const valorCompleto = `${selectMarca.options[selectMarca.selectedIndex].text} ${modelo.nome}`;
                            const option = new Option(modelo.nome, valorCompleto);
                            selectModelo.add(option);
                        });
                    })
                    .catch(error => console.error('Erro ao buscar modelos:', error));
            });
        });
    </script>
</body>
</html>
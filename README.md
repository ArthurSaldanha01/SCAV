# SCAV – Sistema de Controle de Acesso Veicular

Sistema web para controle de entrada e saída de veículos no IF Baiano – Campus Catu.
O SCAV registra automaticamente os acessos de veículos detectados pelas câmeras, cruza essas informações com as viagens previamente autorizadas e gera relatórios gerenciais para apoio à gestão de frota.

---

## 🎯 Objetivo

Implantar um sistema que:

* Registre **automaticamente** os eventos de acesso (placa, data, hora) vindos das câmeras.
* Compare o que foi **planejado** (viagens autorizadas) com o que foi **realizado** (detecções reais).
* Aumente a **segurança** e a **transparência** no uso dos veículos oficiais.

---

## 🧩 Escopo do Sistema

Principais funcionalidades:

* **Registro Automático de Acessos**
  Recebe eventos de leitura de placa via API (endpoint `/api/v1/registrar-acesso`) e salva em `registros_acesso`.

* **Gestão de Veículos Oficiais**
  Cadastro de veículos, com indicação de quais são oficiais (apenas veículos oficiais podem receber viagens).

* **Gestão de Motoristas**
  Cadastro, edição e inativação de motoristas autorizados a conduzir veículos oficiais.

* **Autorização de Viagens**
  Cadastro de viagens com veículo oficial, motorista, data prevista, finalidade e observações.

* **Código de Autorização**
  Cada viagem gera um código único, usado na portaria para liberar a saída.

* **Conciliação Planejado x Realizado**
  Associação dos registros de câmera a viagens autorizadas, permitindo analisar divergências.

* **Monitor da Portaria (Saídas de Hoje)**
  Tela em modo somente leitura para a portaria, mostrando viagens autorizadas no dia, com código de autorização para conferência.

* **Relatórios Gerenciais**
  Relatórios por período, tipo de viagem/situação (autorizada, realizada, divergente) e exportação em CSV.

* **Trilha de Auditoria**
  Registro de ações sensíveis (criação, alteração, cancelamento de viagens, marcação de veículos oficiais).

---

## 👤 Perfis de Usuário

* **Administrador**

  * Gerencia usuários (se aplicável).
  * Gerencia motoristas.
  * Marca/desmarca veículos como oficiais.
  * Visualiza relatórios e auditoria.

* **Gestor**

  * Cadastra e cancela viagens.
  * Acompanha relatórios e conciliação.
  * Pode marcar veículos como oficiais (conforme regra).

* **Portaria**

  * Acessa apenas o **monitor de saídas de hoje**, em modo somente leitura.
  * Confere código de autorização apresentado pelo motorista.

---

## 🗂 Modelo de Dados (Visão Geral)

Tabelas principais:

* **usuarios**

  * `id`
  * `nome`
  * `email`
  * `senha_hash`
  * `perfil` (`administrador`, `gestor`, `portaria`, etc.)
  * `created_at`

* **veiculos**

  * `id`
  * `placa`
  * `modelo`
  * `isOficial` (boolean)
  * `created_at`
  * `updated_at`

* **motoristas**

  * `id`
  * `nome`
  * `cnh`
  * `status` (ex.: `ativo` / `inativo`)
  * `created_at`
  * `updated_at`

* **viagens**

  * `id`
  * `dataPrevista`
  * `finalidade`
  * `observacoes`
  * `codigoAutorizacao`
  * `status` (ex.: `Autorizada`, `Cancelada`, `Realizada`, `Divergente`)
  * `gestor_id` → FK `usuarios`
  * `veiculo_id` → FK `veiculos`
  * `motorista_id` → FK `motoristas`
  * `created_at`
  * `updated_at`

* **registros_acesso**

  * `id`
  * `placaDetectada`
  * `dataHora`
  * `tipo` (`ENTRY` / `EXIT`)
  * `viagem_id` (FK opcional para `viagens`)

* **auditoria**

  * `id`
  * `acao`
  * `detalhes`
  * `dataHora`
  * `usuario_id` → FK `usuarios`

---

## 🌐 Arquitetura Geral

* **Backend web**: PHP (Slim ou similar) + MySQL.
* **Cliente de leitura de placas (ALPR)**: aplicativo desktop em Python que:

  * Lê vídeo de webcam ou câmera IP.
  * Usa modelo de reconhecimento de placa (via `fast_alpr`).
  * Envia eventos para o SCAV via HTTP.

---

## 🔌 API – Registro de Acessos (Câmeras)

O cliente de câmeras envia requisições para:

```http
POST {URL_BASE}/api/v1/registrar-acesso
```

Exemplo de `{URL_BASE}` em produção:

```http
http://servidor/scav/public/api/v1
```

Corpo (JSON):

```json
{
  "tipo": "ENTRY",
  "placa": "BRA2E19",
  "timestamp": "2025-11-14T20:14:00-03:00",
  "token": "SEU_TOKEN_AQUI"
}
```

* `tipo`: `ENTRY` (entrada) ou `EXIT` (saída).
* `placa`: placa detectada.
* `timestamp`: data/hora no formato ISO 8601, fuso America/Bahia.
* `token`: token compartilhado entre SCAV e cliente ALPR.

Cabeçalhos:

```http
Authorization: Bearer SEU_TOKEN_AQUI
Content-Type: application/json
```

### Comportamento no backend

Ao receber o evento, o SCAV:

1. Valida o token.
2. Normaliza a placa para o padrão Mercosul.
3. Evita duplicidades muito próximas (mesma placa/tipo em poucos segundos).
4. Tenta localizar uma viagem autorizada para o mesmo veículo na mesma data.
5. Insere o registro em `registros_acesso`, com `viagem_id` definido (quando houver correspondência).

---

## 🖥 Monitor da Portaria ("Saídas de Hoje")

Tela em modo apenas leitura que mostra:

* Viagens autorizadas para o dia.
* Placa e modelo do veículo.
* Nome do motorista.
* Código de autorização.
* Situação da viagem (apoiada pelos registros das câmeras).

Essa tela é usada pelo perfil **Portaria** para conferir o código apresentado pelo motorista antes de liberar a saída.

---

## 📊 Relatórios

Relatórios disponíveis:

* Resumo de acessos por dia e tipo (entrada/saída).
* Lista detalhada de registros dentro de um período (data/hora, placa, tipo, viagem associada).
* Filtros por data.
* Exportação em CSV para análise externa.

---

## ⏱ Regras de Negócio (Resumo)

* O registro de câmera é a fonte de verdade para horários de saída e retorno.
* Apenas veículos marcados como oficiais podem ter viagens autorizadas.
* Cada autorização de viagem é válida para uma única data e representa um ciclo de saída/retorno.
* A Portaria não altera dados: apenas consulta e confere.
* Toda viagem autorizada deve estar associada a um veículo oficial e a um motorista cadastrado.
* Fuso horário padrão: `America/Bahia`.

---

## ⚙️ Requisitos de Ambiente

* PHP 8.x+
* Servidor web (Apache/Nginx) configurado para apontar para o diretório `public/`.
* MySQL ou MariaDB.
* Composer (para gerenciamento de dependências PHP).
* HTTPS em produção (recomendado).

---

## 🚀 Instalação (Visão Geral)

### 1. Clonar o repositório

```bash
git clone https://github.com/SEU-USUARIO/scav.git
cd scav
```

### 2. Instalar dependências PHP

```bash
composer install
```

### 3. Configurar ambiente

Copiar o arquivo de exemplo de configuração (por exemplo, `.env.example` → `.env`, ou arquivo `config.php`, conforme o projeto) e ajustar:

* Credenciais de banco de dados;
* URL base do sistema;
* Timezone (`America/Bahia`);
* Token de integração do cliente ALPR.

### 4. Criar o banco de dados

* Criar o banco no MySQL.
* Executar o script SQL de criação de tabelas (ou migrations, se existirem no projeto).

### 5. Configurar o virtual host / base URL

* Apontar o servidor web para o diretório `public/`.
* Garantir que as rotas `/dashboard`, `/viagens`, `/relatorios/*`, `/api/v1/registrar-acesso` estejam acessíveis.

### 6. Criar usuário administrador

* Inserir manualmente um usuário **Administrador** na tabela `usuarios` (ou via seed/script, se existir no projeto).

---

## 📷 Integração com o Cliente ALPR

A leitura automática de placas é feita por um projeto separado:

* Repositório: `scav-placa-detector`
  (Exemplo de URL: `https://github.com/ArthurSaldanha01/scav-placa-detector`)

Esse cliente:

* Lê vídeo de webcam ou câmera IP (RTSP);
* Reconhece a placa com `fast_alpr`;
* Envia eventos para o endpoint `/api/v1/registrar-acesso` deste sistema;
* Possui interface em Tkinter para facilitar a configuração na portaria.

No README do `scav-placa-detector` estão os detalhes de instalação e uso do cliente.

---

## 📌 Roadmap (Ideias Futuras)

* API pública para integração com outros sistemas de gestão.
* Dashboard mais rico com gráficos de uso de frota.
* Notificações automatizadas em casos de divergência entre planejado e realizado.
* Histórico consolidado por veículo/motorista.

---

## 📄 Licença

Definir a licença desejada para o projeto (por exemplo, MIT, GPL, etc.) e incluir aqui.

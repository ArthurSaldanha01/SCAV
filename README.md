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
  Relatórios por período, tipo de viagem/situação (autorizada e cancelada) e exportação em CSV.

* **Trilha de Auditoria**
  Registro de ações sensíveis (criação, alteração, cancelamento de viagens, marcação de veículos oficiais).

---

## 👤 Perfis de Usuário

* **Administrador**

  * Gerencia usuários.
  * Gerencia motoristas.
  * Marca/desmarca veículos como oficiais.
  * Visualiza relatórios e auditoria.
  * Cadastra e cancela viagens.

* **Gestor**

  * Cadastra e cancela viagens.
  * Acompanha relatórios e conciliação.
  * Acompanha veículos e motoristas.

* **Portaria**

  * Acessa apenas o **monitor de saídas de hoje**, em modo somente leitura.
  * Confere código de autorização apresentado pelo motorista.

---

## 🗂 Modelo de Dados (Visão Geral)

Tabelas principais:

* **usuarios**

  * `id`, `nome`, `email`, `senha_hash`, `perfil` (`Administrador`, `Gestor`, `Portaria`), `created_at`.

* **veiculos**

  * `id`, `placa`, `modelo`, `isOficial`, `created_at`, `updated_at`.

* **motoristas**

  * `id`, `nome`, `cnh`, `status`, `created_at`, `updated_at`.

* **viagens**

  * `id`, `dataPrevista`, `finalidade`, `observacoes`, `codigoAutorizacao`, `status`, `gestor_id`, `veiculo_id`, `motorista_id`, `created_at`, `updated_at`.

* **registros_acesso**

  * `id`, `placaDetectada`, `dataHora`, `tipo`, `viagem_id`, `criado_em`.

* **auditoria**

  * `id`, `acao`, `detalhes`, `dataHora`, `usuario_id`.

---

## 🛢️ Script do Banco de Dados (MySQL)

O banco de dados completo encontra-se no arquivo:

```
database/scav.sql
```

Esse script inclui:

### ✔ Estrutura completa das tabelas

* `usuarios`
* `veiculos`
* `motoristas`
* `viagens`
* `registros_acesso`
* `auditoria`

### ✔ Índices importantes

* Índice combinado em registros_acesso (`placaDetectada`, `dataHora`).
* Chaves únicas: email, placa, cnh, codigoAutorizacao.

### ✔ Foreign Keys

* viagens → usuarios (`gestor_id`)
* viagens → veiculos (`veiculo_id`)
* viagens → motoristas (`motorista_id`)
* auditoria → usuarios (`usuario_id`)

### ✔ Como criar o banco

```sql
CREATE DATABASE scav CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Importar:

```bash
mysql -u SEU_USUARIO -p scav < database/scav.sql
```

---

## 🌐 Arquitetura Geral

* **Backend web**: PHP Slim + MySQL.
* **Cliente ALPR** (Python):

  * Lê webcam / câmera IP.
  * Usa `fast_alpr`.
  * Envia POST para o SCAV.

---

## 🔌 API – Registro de Acessos

```http
POST {URL_BASE}/api/v1/registrar-acesso
```

Exemplo:

```json
{
  "tipo": "ENTRY",
  "placa": "BRA2E19",
  "timestamp": "2025-11-14T20:14:00-03:00",
  "token": "SEU_TOKEN_AQUI"
}
```

### Fluxo interno

1. Valida token.
2. Normaliza placa.
3. Evita duplicidades.
4. Relaciona viagem (quando possível).
5. Salva em `registros_acesso`.

---

## 🖥 Monitor da Portaria

Exibe viagens do dia para conferência.

---

## 📊 Relatórios

* Acessos por período.
* CSV.
* Relação viagem ↔ acessos.

---

## ⏱ Regras de Negócio

* Apenas veículos oficiais podem ter viagens.
* Portaria não altera dados.
* Viagem é válida para um único dia.

---

## 🚀 Instalação

### 1. Clonar

```bash
git clone https://github.com/SEU-USUARIO/scav.git
cd scav
```

### 2. Instalar dependências

```bash
composer install
```

### 3. Configurar ambiente

* Banco de dados
* Token ALPR
* Timezone

### 4. Criar banco

```bash
mysql scav < database/scav.sql
```

### 5. Configurar virtual host

Rotas principais:

* `/login`
* `/dashboard`
* `/veiculos/*`
* `/motoristas/*`
* `/viagens/*`
* `/usuarios/*`
* `/portaria/monitor`
* `/relatorios/*`
* `/auditoria`
* `/api/v1/registrar-acesso`
* `/api/v1/relatorios/acessos`

---

## 📷 Integração com o Cliente ALPR

Repositório:

```
https://github.com/ArthurSaldanha01/scav-placa-detector
```

---

## 🔐 Token de Integração

Durante testes, use:

```
troque
```

Em ambos:

* SCAV (backend)
* Cliente ALPR (script Python)

---

## 📄 Licença

Este projeto foi desenvolvido como trabalho acadêmico e disponibilizado gratuitamente para o IF Baiano – Campus Catu.
O setor de TI da instituição pode:

* Usar o código
* Modificar
* Adaptar
* Integrar

Distribuição pública/comercial exige permissão dos autores.

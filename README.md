# SCAV – Sistema de Controle de Acesso Veicular

Sistema web para controle de entrada e saída de veículos no IF Baiano – Campus Catu. O SCAV registra automaticamente os acessos de veículos detectados pelas câmeras, cruza essas informações com as viagens previamente autorizadas e gera relatórios gerenciais para apoio à gestão de frota.

---

## 🎯 Objetivo

Implantar um sistema que:

* Registre **automaticamente** os eventos de acesso (placa, data, hora) vindos das câmeras.
* Compare o que foi **planejado** (viagens autorizadas) com o que foi **realizado** (detecções reais).
* Aumente a **segurança** e a **transparência** no uso dos veículos oficiais.

---

## 🧩 Escopo do Sistema

### Principais funcionalidades

* **Registro Automático de Acessos** – Recebe eventos via API `/api/v1/registrar-acesso` e salva em `registros_acesso`.
* **Gestão de Veículos Oficiais** – Cadastro de veículos e marcação de veículos oficiais.
* **Gestão de Motoristas** – Cadastro, edição e inativação.
* **Autorização de Viagens** – Viagens com veículo, motorista, data prevista e finalidade.
* **Código de Autorização** – Cada viagem gera um código único.
* **Conciliação Planejado x Realizado** – Relaciona viagens a registros da câmera.
* **Monitor da Portaria** – Lista de viagens autorizadas no dia.
* **Relatórios Gerenciais** – Filtros por período, exportação CSV.
* **Auditoria** – Registro de operações sensíveis.

---

## 👤 Perfis de Usuário

### **Administrador**

* Gerencia usuários e motoristas.
* Marca veículos como oficiais.
* Visualiza relatórios e auditoria.
* Cadastra/cancela viagens.

### **Gestor**

* Cadastra/cancela viagens.
* Analisa conciliação e relatórios.
* Acompanha motoristas e veículos.

### **Portaria**

* Acessa apenas o monitor de saídas do dia.
* Consulta códigos de autorização.

---

## 🗂 Modelo de Dados – Tabelas Principais

### **usuarios**

`id`, `nome`, `email`, `senha_hash`, `perfil`, `created_at`

### **veiculos**

`id`, `placa`, `modelo`, `isOficial`, `created_at`, `updated_at`

### **motoristas**

`id`, `nome`, `cnh`, `status`, `created_at`, `updated_at`

### **viagens**

`id`, `dataPrevista`, `finalidade`, `observacoes`, `codigoAutorizacao`,
`status`, `gestor_id`, `veiculo_id`, `motorista_id`, `created_at`, `updated_at`

### **registros_acesso**

`id`, `placaDetectada`, `dataHora`, `tipo`, `viagem_id`, `criado_em`

### **auditoria**

`id`, `acao`, `detalhes`, `dataHora`, `usuario_id`

---

## 🛢 Script do Banco de Dados

Localizado em:

```text
database/scav.sql
```

Inclui:

* Todas as tabelas
* Índices e chaves únicas
* Foreign keys completas

### Criar o banco:

```sql
CREATE DATABASE scav CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### Importar:

```bash
mysql -u SEU_USUARIO -p scav < database/scav.sql
```

---

## 🌐 Arquitetura Geral

* **Backend**: PHP + Slim Framework + MySQL
* **Cliente ALPR**: Python + fast_alpr + OpenCV
* Comunicação via API REST

---

## 🔌 API – Registro de Acessos

### Endpoint

```
POST {URL_BASE}/api/v1/registrar-acesso
```

### Payload

```json
{
  "tipo": "ENTRY",
  "placa": "BRA2E19",
  "timestamp": "2025-11-14T20:14:00-03:00",
  "token": "SEU_TOKEN_AQUI"
}
```

### Processamento interno

1. Valida token.
2. Normaliza placa.
3. Verifica duplicidade.
4. Tenta vincular à viagem do dia.
5. Salva em `registros_acesso`.

---

## 🖥 Monitor da Portaria

Mostra viagens autorizadas no dia em modo somente leitura.

---

## 📊 Relatórios

* Acessos por período
* Exportação CSV
* Relação viagem ↔ registros de câmera

---

## ⏱ Regras de Negócio

* Apenas veículos oficiais podem receber viagens.
* Portaria não pode alterar dados.
* Viagem é válida somente para um dia.

---

## ✅ Requisitos para Instalação

### **Servidor**

* Apache ou Nginx (recomendado Apache + mod_rewrite)
* PHP 8.x com:

  * `pdo_mysql`
  * `mbstring`
  * `json`
  * `openssl`
* Composer instalado globalmente
* MySQL/MariaDB
* Git instalado

### **Banco de Dados**

* Necessário importar o arquivo `database/scav.sql`

### **Importante**

Se o projeto for baixado com a pasta `vendor/`, **remova a pasta `vendor/`** e rode:

```bash
composer install
```

para garantir dependências corretas.

---

## 🚀 Instalação do Sistema

### 1. Clonar o repositório

```bash
git clone https://github.com/SEU-USUARIO/scav.git
cd scav
```

Ou extraia o ZIP em:

```
/var/www/scav
```

### 2. Instalar dependências PHP

```bash
rm -rf vendor/
composer install
```

### 3. Configurar ambiente

Defina no arquivo `.env` ou `config.php`:

* Host, banco, usuário e senha (MySQL)
* Token ALPR
* Timezone (ex: `America/Bahia`)

### 4. Criar e importar banco de dados

```bash
mysql -u SEU_USUARIO -p -e "CREATE DATABASE scav CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -u SEU_USUARIO -p scav < database/scav.sql
```

### 5. Configurar Virtual Host

Aponte para a pasta:

```
public/
```

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

---

## 📷 Integração com o Cliente ALPR

Repositório oficial:

```
https://github.com/ArthurSaldanha01/scav-placa-detector
```

Responsável por:

* Capturar imagens
* Rodar fast_alpr
* Enviar POST para `/api/v1/registrar-acesso`

---

## 🔐 Token de Integração

Use o **mesmo token** no backend e no cliente ALPR.

Exemplo:

```
SEU_TOKEN_AQUI
```

Configure em:

* Backend PHP
* Cliente ALPR

---

## 📄 Licença

Projeto acadêmico disponibilizado gratuitamente para o IF Baiano – Campus Catu.

O setor de TI pode:

* Usar
* Modificar
* Integrar
* Adaptar

Distribuição pública/comercial requer autorização dos autores.

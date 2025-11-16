-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/11/2025 às 17:42
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `scav`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `acao` varchar(255) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `dataHora` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `auditoria`
--

INSERT INTO `auditoria` (`id`, `acao`, `detalhes`, `dataHora`, `usuario_id`) VALUES
(1, 'CRIACAO_VEICULO', 'Veículo criado. Placa: ASF5RS2, Modelo: Acura Integra GS 1.8. Marcado como: OFICIAL.', '2025-10-30 00:26:36', 1),
(2, 'MARCACAO_VEICULO_OFICIAL', 'Marcação de veículo alterada. Veículo ID: 2 (Placa: ASF5RS2). Novo status: NAO_OFICIAL.', '2025-10-30 00:26:43', 1),
(3, 'CRIACAO_VIAGEM', 'Viagem autorizada. Código: SCAV-6902DB219F5E2. Veículo ID: 2, Motorista ID: 1.', '2025-10-30 00:27:29', 1),
(4, 'CRIACAO_VEICULO', 'Veículo criado. Placa: JAQ1224, Modelo: Fiat Titano Endurance 2.2 16V 4x4 TB Die. Mec. Marcado como: OFICIAL.', '2025-11-04 21:06:27', 1),
(5, 'CRIACAO_VIAGEM', 'Viagem autorizada. Código: SCAV-690A9545790FE. Veículo ID: 3, Motorista ID: 1.', '2025-11-04 21:07:33', 1),
(6, 'CRIACAO_VIAGEM', 'Viagem autorizada. Código: SCAV-690A95E4E74AD. Veículo ID: 2, Motorista ID: 1.', '2025-11-04 21:10:12', 1),
(7, 'CRIACAO_VIAGEM', 'Viagem autorizada. Código: SCAV-6917B6F6EF276. Veículo ID: 2, Motorista ID: 1.', '2025-11-14 20:10:46', 1),
(8, 'CRIACAO_VEICULO', 'Veículo criado. Placa: BRA2E19, Modelo: Audi A3 1.6 3p. Marcado como: OFICIAL.', '2025-11-14 20:12:22', 1),
(9, 'CRIACAO_VIAGEM', 'Viagem autorizada. Código: SCAV-6917B7956F64B. Veículo ID: 4, Motorista ID: 1.', '2025-11-14 20:13:25', 1),
(10, 'CRIACAO_VIAGEM', 'Viagem autorizada. Código: SCAV-6917C3B0B0B22. Veículo ID: 4, Motorista ID: 1.', '2025-11-14 21:05:04', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `motoristas`
--

CREATE TABLE `motoristas` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cnh` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Ativo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `motoristas`
--

INSERT INTO `motoristas` (`id`, `nome`, `cnh`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Carlos Cezar', 'CNH-FICT-000002', 'Ativo', '2025-10-06 01:13:27', '2025-10-06 01:13:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `registros_acesso`
--

CREATE TABLE `registros_acesso` (
  `id` int(11) NOT NULL,
  `placaDetectada` varchar(10) DEFAULT NULL,
  `dataHora` datetime DEFAULT NULL,
  `tipo` varchar(10) DEFAULT NULL,
  `viagem_id` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `registros_acesso`
--

INSERT INTO `registros_acesso` (`id`, `placaDetectada`, `dataHora`, `tipo`, `viagem_id`, `criado_em`) VALUES
(1, 'ABC1D23', '2025-11-11 16:10:00', 'ENTRY', NULL, '2025-11-11 19:25:30'),
(2, 'BRA2E19', '2025-11-11 18:37:59', 'ENTRY', NULL, '2025-11-11 21:37:59'),
(3, 'BRA2E10', '2025-11-11 18:38:01', 'ENTRY', NULL, '2025-11-11 21:38:01'),
(4, 'BRA2L19', '2025-11-11 18:38:02', 'ENTRY', NULL, '2025-11-11 21:38:02'),
(5, 'BRA2E19', '2025-11-11 18:38:06', 'ENTRY', NULL, '2025-11-11 21:38:07'),
(6, 'ARA2E19', '2025-11-11 18:38:10', 'ENTRY', NULL, '2025-11-11 21:38:10'),
(7, 'CRA2E19', '2025-11-11 18:38:10', 'ENTRY', NULL, '2025-11-11 21:38:10'),
(8, 'GRA2E19', '2025-11-11 18:38:21', 'EXIT', NULL, '2025-11-11 21:38:21'),
(9, 'BRA2E19', '2025-11-11 18:38:21', 'EXIT', NULL, '2025-11-11 21:38:21'),
(10, 'BRA2E19', '2025-11-11 18:38:59', 'EXIT', NULL, '2025-11-11 21:38:59'),
(11, 'BRA2E19', '2025-11-11 18:39:00', 'ENTRY', NULL, '2025-11-11 21:39:00'),
(12, 'GRA2E19', '2025-11-11 18:39:00', 'ENTRY', NULL, '2025-11-11 21:39:01'),
(13, 'BIA2E19', '2025-11-11 18:39:01', 'ENTRY', NULL, '2025-11-11 21:39:01'),
(14, 'BRA2L19', '2025-11-11 18:39:01', 'ENTRY', NULL, '2025-11-11 21:39:02'),
(15, 'BRA2E19', '2025-11-11 18:39:29', 'ENTRY', NULL, '2025-11-11 21:39:29'),
(16, 'BRA2E19', '2025-11-11 18:40:23', 'ENTRY', NULL, '2025-11-11 21:40:24'),
(17, 'BRA2L19', '2025-11-11 18:40:24', 'ENTRY', NULL, '2025-11-11 21:40:24'),
(18, 'BIA2E19', '2025-11-11 18:40:27', 'ENTRY', NULL, '2025-11-11 21:40:27'),
(19, 'BRA2E19', '2025-11-11 18:40:30', 'ENTRY', NULL, '2025-11-11 21:40:30'),
(20, 'ARA2E19', '2025-11-11 18:40:32', 'ENTRY', NULL, '2025-11-11 21:40:32'),
(21, 'BRA2E19', '2025-11-11 18:40:35', 'EXIT', NULL, '2025-11-11 21:40:35'),
(22, 'BRA2L19', '2025-11-11 18:40:36', 'EXIT', NULL, '2025-11-11 21:40:36'),
(23, 'BRA2E19', '2025-11-11 18:50:49', 'ENTRY', NULL, '2025-11-11 21:50:49'),
(24, 'BRA2L19', '2025-11-11 18:50:50', 'ENTRY', NULL, '2025-11-11 21:50:51'),
(25, 'BRA2E19', '2025-11-11 18:51:02', 'EXIT', NULL, '2025-11-11 21:51:02'),
(26, 'BRA7S19', '2025-11-14 19:57:58', 'ENTRY', NULL, '2025-11-14 22:57:58'),
(27, 'BRA7S99', '2025-11-14 19:58:14', 'EXIT', NULL, '2025-11-14 22:58:14'),
(28, 'RIO2A18', '2025-11-14 20:01:35', 'ENTRY', NULL, '2025-11-14 23:01:35'),
(29, 'RIO2A18', '2025-11-14 20:01:46', 'EXIT', NULL, '2025-11-14 23:01:47'),
(30, 'RIO2A11', '2025-11-14 20:01:49', 'EXIT', NULL, '2025-11-14 23:01:49'),
(31, 'BRA2E19', '2025-11-14 20:13:48', 'ENTRY', NULL, '2025-11-14 23:13:48'),
(32, 'BRA2E19', '2025-11-14 20:14:04', 'EXIT', NULL, '2025-11-14 23:14:04'),
(33, 'BRA2E19', '2025-11-14 20:28:25', 'ENTRY', NULL, '2025-11-14 23:28:25'),
(34, 'BRA2E19', '2025-11-14 20:28:34', 'EXIT', NULL, '2025-11-14 23:28:34'),
(35, 'BRA2E19', '2025-11-14 20:32:41', 'ENTRY', NULL, '2025-11-14 23:32:41'),
(36, 'BRA2E19', '2025-11-14 20:32:49', 'EXIT', NULL, '2025-11-14 23:32:49'),
(37, 'BRA2L19', '2025-11-14 20:32:51', 'EXIT', NULL, '2025-11-14 23:32:51'),
(38, 'BRA2E19', '2025-11-14 21:05:24', 'ENTRY', 10, '2025-11-15 00:05:24'),
(39, 'BRA2E19', '2025-11-14 21:05:33', 'EXIT', 10, '2025-11-15 00:05:33'),
(40, 'BRA2L19', '2025-11-14 21:05:34', 'EXIT', NULL, '2025-11-15 00:05:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `perfil` enum('Administrador','Gestor','Portaria') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `perfil`, `created_at`) VALUES
(1, 'Admin do Sistema', 'admin@scav.com', '$2y$12$ZavDbQOBVw0UXiyZ4mWZ5.NgsKZFWq22clF6CvLWLvZqgKyN4nsD2', 'Administrador', '2025-09-29 03:06:18'),
(2, 'Arthur Saldanha', 'arthursaldanha17@gmail.com', '$2y$12$U7Qal4tXi3Xt0LyH3Q4KK.7e7n5kjnCVCGiZExL7gkzKKIqCfByQa', 'Gestor', '2025-10-17 18:17:17');

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `isOficial` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `veiculos`
--

INSERT INTO `veiculos` (`id`, `placa`, `modelo`, `isOficial`, `created_at`, `updated_at`) VALUES
(1, 'ROI55AS', 'Fiat Uno Turbo 1.4 i.e. 2p', 1, '2025-10-06 00:32:28', '2025-10-06 00:37:58'),
(2, 'ASF5RS2', 'Acura Integra GS 1.8', 0, '2025-10-30 03:26:36', '2025-10-30 03:26:43'),
(3, 'JAQ1224', 'Fiat Titano Endurance 2.2 16V 4x4 TB Die. Mec', 1, '2025-11-05 00:06:27', '2025-11-05 00:06:27'),
(4, 'BRA2E19', 'Audi A3 1.6 3p', 1, '2025-11-14 23:12:22', '2025-11-14 23:12:22');

-- --------------------------------------------------------

--
-- Estrutura para tabela `viagens`
--

CREATE TABLE `viagens` (
  `id` int(11) NOT NULL,
  `dataPrevista` date NOT NULL,
  `finalidade` varchar(255) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `codigoAutorizacao` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Autorizada',
  `gestor_id` int(11) NOT NULL,
  `veiculo_id` int(11) NOT NULL,
  `motorista_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `viagens`
--

INSERT INTO `viagens` (`id`, `dataPrevista`, `finalidade`, `observacoes`, `codigoAutorizacao`, `status`, `gestor_id`, `veiculo_id`, `motorista_id`, `created_at`, `updated_at`) VALUES
(1, '2025-10-20', 'Reunião na Reitoria', 'Informação adicional', '767597', 'Cancelada', 2, 1, 1, '2025-10-17 18:25:45', '2025-10-29 03:48:12'),
(2, '2025-10-29', 'Compra de material', '', '190689', 'Cancelada', 1, 1, 1, '2025-10-29 03:35:33', '2025-10-29 03:48:10'),
(3, '2025-10-29', 'Compra de material', 'Compra de insumos como papel A4', '695775', 'Cancelada', 1, 1, 1, '2025-10-29 03:59:37', '2025-10-30 03:10:24'),
(4, '2025-10-31', 'Visita técnica', '', '329664', 'Cancelada', 1, 1, 1, '2025-10-30 02:31:33', '2025-10-30 03:10:22'),
(5, '2025-10-31', 'Reunião na Reitoria', '', '574900', 'Autorizada', 1, 2, 1, '2025-10-30 03:27:29', '2025-10-30 03:27:29'),
(6, '2025-11-06', 'Visita técnica', 'Essa viagem vai ser para Salvador.', '558889', 'Autorizada', 1, 3, 1, '2025-11-05 00:07:33', '2025-11-05 00:07:33'),
(7, '2025-11-05', 'Compra de material', 'Compra de folha A5', '596066', 'Autorizada', 1, 2, 1, '2025-11-05 00:10:12', '2025-11-05 00:10:12'),
(8, '2025-11-15', 'Visita técnica', '', '541268', 'Autorizada', 1, 2, 1, '2025-11-14 23:10:46', '2025-11-14 23:10:46'),
(9, '2025-11-15', 'Compra de material', '', '421293', 'Autorizada', 1, 4, 1, '2025-11-14 23:13:25', '2025-11-14 23:13:25'),
(10, '2025-11-14', 'Reunião na Reitoria', '', '722367', 'Autorizada', 1, 4, 1, '2025-11-15 00:05:04', '2025-11-15 00:05:04');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `motoristas`
--
ALTER TABLE `motoristas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnh` (`cnh`);

--
-- Índices de tabela `registros_acesso`
--
ALTER TABLE `registros_acesso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ra_placa_data` (`placaDetectada`,`dataHora`),
  ADD KEY `idx_ra_tipo` (`tipo`),
  ADD KEY `idx_ra_viagem` (`viagem_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `placa` (`placa`);

--
-- Índices de tabela `viagens`
--
ALTER TABLE `viagens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigoAutorizacao` (`codigoAutorizacao`),
  ADD KEY `gestor_id` (`gestor_id`),
  ADD KEY `veiculo_id` (`veiculo_id`),
  ADD KEY `motorista_id` (`motorista_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `motoristas`
--
ALTER TABLE `motoristas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `registros_acesso`
--
ALTER TABLE `registros_acesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `viagens`
--
ALTER TABLE `viagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `viagens`
--
ALTER TABLE `viagens`
  ADD CONSTRAINT `viagens_ibfk_1` FOREIGN KEY (`gestor_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `viagens_ibfk_2` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`),
  ADD CONSTRAINT `viagens_ibfk_3` FOREIGN KEY (`motorista_id`) REFERENCES `motoristas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

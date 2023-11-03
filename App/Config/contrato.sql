-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Ago-2023 às 23:05
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `contrato`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ativo`
--

CREATE TABLE `ativo` (
  `id` int(11) NOT NULL,
  `modelo` varchar(64) NOT NULL,
  `fabricante` varchar(16) NOT NULL,
  `num_serie` varchar(256) NOT NULL,
  `num_telefone` varchar(15) DEFAULT NULL,
  `plano_dados` varchar(64) DEFAULT NULL,
  `imei` varchar(256) DEFAULT NULL,
  `pat` varchar(32) NOT NULL,
  `idStatus` int(11),
  `idCategoria` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `ativo`
--

INSERT INTO `ativo` (`id`, `modelo`, `fabricante`, `num_serie`, `num_telefone`, `plano_dados`, `imei`, `pat`, `idStatus`, `idCategoria`) VALUES
(1, 'S256-16IWL', 'LENOVO', 'FOXTROT12', NULL, NULL, NULL, '056', 1, 2),
(2, 'LATITUDE 15-3510', 'DELL', 'PAPA4567', NULL, NULL, NULL, '034', 1, 2),
(3, 'LATITUDE 15-3510', 'DELL', 'ECHO6541', '', NULL, '', '035', 1, 2),
(4, 'LATITUDE 15-3510', 'DELL', 'MIKE5478', NULL, NULL, NULL, '037', 1, 2),
(5, 'R898-12-22K8', 'ACER', 'NXAX0AL005679BRAZILP00', '', NULL, '', '014', 1, 2);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `ativo_view`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `ativo_view` (
`id` int(11)
,`modelo` varchar(64)
,`num_serie` varchar(256)
,`pat` varchar(32)
,`estado` varchar(32)
,`categoria` varchar(32)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`) VALUES
(1, 'Smartphone'),
(2, 'Notebook');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato`
--

CREATE TABLE `contrato` (
  `id` int(11) NOT NULL,
  `dataExpedicao` date NOT NULL,
  `dataDevolucao` date NOT NULL DEFAULT '0000-01-01',
  `relato` varchar(255) DEFAULT NULL,
  `idAtivo` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idTipo` int(11) NOT NULL,
  `cancelado` tinyint(1) DEFAULT NULL,
  `assinado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `contrato`
--

INSERT INTO `contrato` (`id`, `dataExpedicao`, `dataDevolucao`, `relato`, `idAtivo`, `idUsuario`, `idTipo`, `cancelado`, `assinado`) VALUES
(1, '2023-09-01', '2023-09-01', '0', 5, 9, 1, 0, 1),
(2, '2023-09-05', '0000-01-01', '0', 1, 9, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `contrato_view`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `contrato_view` (
`id` int(11)
,`dataExpedicao` date
,`dataDevolucao` date
,`relato` varchar(255)
,`pat` varchar(32)
,`nome` varchar(256)
,`apelido` varchar(32)
,`cancelado` tinyint(1)
,`assinado` tinyint(1)
,`idAtivo` int(11)
,`idUsuario` int(11)
,`idTipo` int(11)
);

--
-- Estrutura da tabela `departamento`
--

CREATE TABLE `departamento` (
  `id` int(11) NOT NULL,
  `nome` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `departamento`
--

INSERT INTO `departamento` (`id`, `nome`) VALUES
(1, 'INFRAESTRUTURA'),
(2, 'RH'),
(3, 'FINANCEIRO');

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `estado` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `status`
--

INSERT INTO `status` (`id`, `estado`) VALUES
(1, 'Em estoque'),
(2, 'Em uso'),
(3, 'Com defeito');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo`
--

CREATE TABLE `tipo` (
  `id` int(11) NOT NULL,
  `razaoSocial` varchar(128) NOT NULL,
  `apelido` varchar(32) NOT NULL,
  `cnpj` varchar(32) NOT NULL,
  `caminho` varchar(256) NOT NULL,
  `name` varchar(32) NOT NULL,
  `type` varchar(256) NOT NULL,
  `size` int(11) NOT NULL,
  `adress` varchar(256) NOT NULL,
  `obsoleto` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tipo`
--

INSERT INTO `tipo` (`id`, `razaoSocial`, `apelido`, `cnpj`, `caminho`, `name`, `type`, `size`, `adress`) VALUES
(1, 'TECNOLOGIA LTDA', 'Contrato de Comodato Comum*', '29.112.790/0001-89', './App/Contratos/29112790000189_Contrato_de_Comodato_Teste/contrato.html', 'contrato.html', 'text/html', 10146, './App/Contratos/29112790000189_Contrato_de_Comodato_Teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `nome` varchar(256) DEFAULT NULL,
  `rg` varchar(20) NOT NULL,
  `desabilitado` tinyint(1) NOT NULL,
  `idDepartamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `cpf`, `nome`, `rg`, `desabilitado`, `idDepartamento`) VALUES
(1, '435.958.041-04', 'Ana Castro Santos Junior', '65.151.671-7', 0, 3),
(2, '111.111.111-11', 'Vanessa', '11111111', 0, 1),
(5, '123.456.789-01', 'Evelyn Ribeiro Dias', '25.874.894-0', 0, 2),
(6, '989.689.627-58', 'Leonor Pereira Ferreira', '54.414.038-2', 0, 1),
(7, '279.094.497-04', 'Alice Cunha Cardoso', '43.266.571-7', 0, 1),
(8, '174.715.441-55', 'Miguel Souza Dias', '44.373.747-2', 0, 2),
(9, '313.813.511-07', 'Antônio Azevedo Lima', '51.013.241-8', 0, 3),
(10, '897.980.116-51', 'Breno Oliveira Costa', '34.014.278-9', 0, 2),
(11, '297.250.521-29', 'Kauan Alves Ribeiro', '68.633.413-9', 0, 3),
(12, '473.411.051-43', 'Rebeca Fernandes Gomes', '44.572.683-0', 0, 2);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `usuario_view`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `usuario_view` (
`id` int(11)
,`nome` varchar(256)
,`cpf` varchar(20)
,`rg` varchar(20)
,`desabilitado` varchar(3)
,`DP` varchar(32)
);

-- --------------------------------------------------------

--
-- Estrutura para vista `ativo_view`
--
DROP TABLE IF EXISTS `ativo_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ativo_view`  
AS SELECT 
`ativo`.`id` AS `id`, 
`ativo`.`modelo` AS `modelo`, 
`ativo`.`num_serie` AS `num_serie`, 
`ativo`.`pat` AS `pat`, 
`status`.`estado` AS `estado`, 
IFNULL(`categoria`.`nome`, 'N/A') AS `categoria` 
FROM ((`ativo` join `status`) LEFT OUTER JOIN `categoria` ON `ativo`.`idCategoria` = `categoria`.`id`) WHERE `ativo`.`idStatus` = `status`.`id` ORDER BY `ativo`.`id` ASC  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `contrato_view`
--
DROP TABLE IF EXISTS `contrato_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `contrato_view`  
AS SELECT 
`contrato`.`id` AS `id`, 
`contrato`.`dataExpedicao` AS `dataExpedicao`, 
`contrato`.`dataDevolucao` AS `dataDevolucao`, 
`contrato`.`relato` AS `relato`, 
`ativo`.`pat` AS `pat`, 
`usuario`.`nome` AS `nome`, 
`tipo`.`apelido` AS `apelido`, 
`cancelado` AS `cancelado`, 
`assinado` AS `assinado`, 
`idAtivo` AS `idAtivo`, 
`idUsuario` AS `idUsuario`, 
`idTipo` AS `idTipo` 
FROM `contrato` 
left join `ativo` on`contrato`.`idAtivo` = `ativo`.`id` 
left join `usuario` on `contrato`.`idUsuario` = `usuario`.`id`
left join `tipo` on `contrato`.`idTipo` = `tipo`.`id`
ORDER BY `contrato`.`id` ASC  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `usuario_view`
--
DROP TABLE IF EXISTS `usuario_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usuario_view`  AS SELECT `usuario`.`id` AS `id`, `usuario`.`nome` AS `nome`, `usuario`.`cpf` AS `cpf`, `usuario`.`rg` AS `rg`, CASE WHEN `usuario`.`desabilitado` = '0' THEN 'NÃO' ELSE 'SIM' END AS `desabilitado`, `departamento`.`nome` AS `DP` FROM (`usuario` join `departamento`) WHERE (`usuario`.`nome` like '%%' OR `usuario`.`cpf` like '%%' OR `usuario`.`rg` like '%%' OR `departamento`.`nome` like '%%') AND `usuario`.`idDepartamento` = `departamento`.`id` ORDER BY `usuario`.`id` ASC  ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `ativo`
--
ALTER TABLE `ativo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_contrato_2` (`idAtivo`),
  ADD KEY `FK_contrato_3` (`idUsuario`),
  ADD KEY `FK_contrato_4` (`idTipo`);

--
-- Índices para tabela `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `FK_usuario_2` (`idDepartamento`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `ativo`
--
ALTER TABLE `ativo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tipo`
--
ALTER TABLE `tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `ativo`
--
ALTER TABLE `ativo`
  ADD CONSTRAINT `FK_ativo_2` FOREIGN KEY (`idStatus`) REFERENCES `status` (`id`) ON DELETE SET NULL
 ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ativo_3` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`id`) ON DELETE SET NULL
 ON UPDATE CASCADE;

--
-- Limitadores para a tabela `contrato`
--
ALTER TABLE `contrato`
  ADD CONSTRAINT `FK_contrato_2` FOREIGN KEY (`idAtivo`) REFERENCES `ativo` (`id`),
  ADD CONSTRAINT `FK_contrato_3` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `FK_contrato_4` FOREIGN KEY (`idTipo`) REFERENCES `tipo` (`id`);

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `FK_usuario_2` FOREIGN KEY (`idDepartamento`) REFERENCES `departamento` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

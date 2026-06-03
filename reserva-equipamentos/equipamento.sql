SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dados: `intranet`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `mre_emprestimo`
--
USE `intranet`;

DROP TABLE IF EXISTS `mre_emprestimo_equipamento`;
DROP TABLE IF EXISTS `mre_emprestimo`;
DROP TABLE IF EXISTS `mre_equipamento`;

CREATE TABLE `mre_emprestimo` (
  `id` int(11) NOT NULL,
  `pessoa` int(11) NOT NULL,
  `dt_inicio` datetime NOT NULL,
  `dt_fim` datetime NOT NULL,
  `dt_devol` datetime DEFAULT NULL,
  `obs_emp` varchar(300) DEFAULT NULL,
  `obs_devol` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mre_emprestimo_equipamento`
--

CREATE TABLE `mre_emprestimo_equipamento` (
  `id_emprestimo` int(11) NOT NULL,
  `id_equipamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mre_equipamento`
--

CREATE TABLE `mre_equipamento` (
  `id` int(11) NOT NULL,
  `patrimonio` varchar(255) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `emprestado` tinyint(1) DEFAULT NULL,
  `obs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `mre_equipamento`
--

INSERT INTO `mre_equipamento` (`id`, `patrimonio`, `nome`, `descricao`, `ativo`, `emprestado`, `obs`) VALUES
(1, 'ECR123422341', 'Projetor Epson', 'Projetor para apresentações', 1, 0, 'Equipamento novo'),
(2, 'ECR12342341', 'Projetor X', 'Projetor para apreasdadassentações', 1, 0, 'Equipamento novo'),
(3, 'ECR102342341', 'TV 32', 'Projetor para apreasdadassentações', 1, 1, 'Equipamento novo'),
(4, 'ECR10234/06', 'DVD player', 'muito ruim', 1, 0, 'equipamento faiando');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `mre_emprestimo`
--
ALTER TABLE `mre_emprestimo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pessoa` (`pessoa`);

--
-- Índices para tabela `mre_emprestimo_equipamento`
--
ALTER TABLE `mre_emprestimo_equipamento`
  ADD PRIMARY KEY (`id_emprestimo`,`id_equipamento`),
  ADD KEY `id_equipamento` (`id_equipamento`);

--
-- Índices para tabela `mre_equipamento`
--
ALTER TABLE `mre_equipamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patrimonio` (`patrimonio`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `mre_emprestimo`
--
ALTER TABLE `mre_emprestimo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de tabela `mre_equipamento`
--
ALTER TABLE `mre_equipamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `mre_emprestimo`
--
ALTER TABLE `mre_emprestimo`
  ADD CONSTRAINT `fk_pessoa` FOREIGN KEY (`pessoa`) REFERENCES `pessoa` (`codigo`);

--
-- Limitadores para a tabela `mre_emprestimo_equipamento`
--
ALTER TABLE `mre_emprestimo_equipamento`
  ADD CONSTRAINT `mre_emprestimo_equipamento_ibfk_1` FOREIGN KEY (`id_emprestimo`) REFERENCES `mre_emprestimo` (`id`),
  ADD CONSTRAINT `mre_emprestimo_equipamento_ibfk_2` FOREIGN KEY (`id_equipamento`) REFERENCES `mre_equipamento` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

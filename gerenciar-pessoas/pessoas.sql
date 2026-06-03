/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
USE intranet;

DROP TABLE IF EXISTS pessoa;
DROP TABLE IF EXISTS msi_usuario;

CREATE TABLE `pessoa` (
  `codigo` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `tipopessoa` varchar(17) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `matricula` varchar(6) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL, -- sigla
  `pais` varchar(50) DEFAULT NULL,
  `cpf` varchar(12) DEFAULT NULL,
  `dataAdmissao` date DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `isDocente` int DEFAULT '0',
  `isAdmin` int DEFAULT '0',
  'isAluno' int DEFAULT '0',
  'isFuncionario' int DEFAULT '0',
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


LOCK TABLES `pessoa` WRITE;

UNLOCK TABLES;


-- USUARIOS
CREATE TABLE `msi_usuario` (
  `codigo` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(20) DEFAULT NULL,
  `pessoa` int DEFAULT NULL,
  `isAdmin` BOOLEAN DEFAULT FALSE,
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=1221 DEFAULT CHARSET=latin1;

LOCK TABLES `msi_usuario` WRITE;

UNLOCK TABLES;
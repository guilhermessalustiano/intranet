USE `intranet`;

DROP TABLE IF EXISTS `mas_evento_recurso`;
DROP TABLE IF EXISTS `mas_recursos`;
DROP TABLE IF EXISTS `mas_eventos`;
DROP TABLE IF EXISTS `mas_agenda_visualizacao_usuario`;
DROP TABLE IF EXISTS `mas_agendas`;


CREATE TABLE mas_agendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) UNIQUE,
    backgroundColor VARCHAR(7) NOT NULL, -- formato hexadecimal ex: #FF0000
    descricao VARCHAR(255) NOT NULL -- você deve primeiro convertê-la para o formato aceito pelo MySQL, que é YYYY-MM-DD
);

CREATE TABLE mas_agenda_visualizacao_usuario (
    id_usuario INT NOT NULL,
    id_agenda INT NOT NULL,
    is_visible BOOLEAN DEFAULT TRUE,
     CONSTRAINT fk_agenda FOREIGN KEY (id_agenda) REFERENCES mas_agendas(id) ON DELETE CASCADE
);

CREATE TABLE mas_eventos (
     id INT AUTO_INCREMENT PRIMARY KEY,
     title VARCHAR(255),  -- Título do evento
     start DATETIME DEFAULT NULL,  -- Início do evento simples ou da primeira instância de um evento recorrente
     end DATETIME DEFAULT NULL,  -- Término do evento simples ou da primeira instância de um evento recorrente
     rrule TEXT DEFAULT NULL,  -- Regra de recorrência no formato rrule
     rrule_dtstart DATETIME DEFAULT NULL,  -- Regra de recorrência no formato rrule
     rrule_until DATETIME DEFAULT NULL,  -- Regra de recorrência no formato rrule
     exdate VARCHAR(255) DEFAULT NULL,  -- Início do evento simples ou da primeira instância de um evento recorrente
     duration VARCHAR(255) DEFAULT NULL,  -- Regra de recorrência no formato rrule
     url_reuniao VARCHAR(255) DEFAULT NULL -- link de reunião
     allDay BOOLEAN DEFAULT FALSE,  -- Indica se o evento é o dia todo
     id_agenda INT NOT NULL,  -- Relacionamento com a agenda
     id_usuario INT,  -- Relacionamento com o usuário
     CONSTRAINT fk_agenda2 FOREIGN KEY (id_agenda) REFERENCES mas_agendas(id) ON DELETE CASCADE
);

CREATE TABLE mas_recursos (
     id INT AUTO_INCREMENT PRIMARY KEY,
     nome VARCHAR(255), 
     descricao VARCHAR (255)

);

CREATE TABLE mas_evento_recurso (
     id_evento INT NOT NULL,
     id_recurso INT NOT NULL,
     CONSTRAINT fk_eventorecurso1 FOREIGN KEY (id_evento) REFERENCES mas_eventos(id) ON DELETE CASCADE,
     CONSTRAINT fk_eventorecurso2 FOREIGN KEY (id_recurso) REFERENCES mas_recursos(id) ON DELETE CASCADE
);


LOCK TABLES `mas_eventos` WRITE;
UNLOCK TABLES;



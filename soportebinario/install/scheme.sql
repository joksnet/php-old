
DROP TABLE IF EXISTS config;
CREATE TABLE IF NOT EXISTS config (
  name varchar(32) NOT NULL DEFAULT '' ,
  value varchar(96) NOT NULL DEFAULT '' ,
  PRIMARY KEY (name),
  UNIQUE KEY name (name)
);

INSERT INTO config (name, value) VALUES("siteName", "Soporte Binario");
INSERT INTO config (name, value) VALUES("siteTagline", "Servicio de Atención al Cliente por Humanos");
INSERT INTO config (name, value) VALUES("siteTheme", "default");

DROP TABLE IF EXISTS empresas;
CREATE TABLE IF NOT EXISTS empresas (
  id mediumint(8) unsigned NOT NULL auto_increment,
  nombre varchar(100) NOT NULL DEFAULT '' ,
  abbr varchar(100) NOT NULL DEFAULT '' ,
  slogan varchar(100) NOT NULL DEFAULT '' ,
  logo varchar(200) NOT NULL DEFAULT '' ,
  descripcion text NOT NULL DEFAULT '' ,
  web varchar(100) NOT NULL DEFAULT '' ,
  activo tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  fecha int(14) unsigned NOT NULL DEFAULT '0' ,
  PRIMARY KEY (id)
);

# ALTER TABLE empresas ADD abbr VARCHAR(100)  NOT NULL AFTER nombre;

DROP TABLE IF EXISTS productos;
CREATE TABLE IF NOT EXISTS productos (
  id mediumint(8) unsigned NOT NULL auto_increment,
  id_empresa mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  nombre varchar(100) NOT NULL DEFAULT '' ,
  logo varchar(200) NOT NULL DEFAULT '' ,
  admite_preguntas tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  admite_ideas tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  admite_problemas tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  activo tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  fecha int(14) unsigned NOT NULL DEFAULT '0' ,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS temas;
CREATE TABLE IF NOT EXISTS temas (
  id mediumint(8) unsigned NOT NULL auto_increment,
  id_empresa mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  id_producto mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  id_usuario mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  tipo enum('Q','I','P') NOT NULL DEFAULT 'Q' ,
  titulo varchar(200) NOT NULL DEFAULT '' ,
  descripcion text NOT NULL DEFAULT '' ,
  activo tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  fecha int(14) unsigned NOT NULL DEFAULT '0' ,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS temas_respuestas;
CREATE TABLE IF NOT EXISTS temas_respuestas (
  id mediumint(8) unsigned NOT NULL auto_increment,
  id_tema mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  id_usuario mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  respuesta text NOT NULL DEFAULT '' ,
  activo tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  fecha int(14) unsigned NOT NULL DEFAULT '0' ,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS usuarios (
  id mediumint(8) unsigned NOT NULL auto_increment,
  id_empresa mediumint(8) unsigned ,
  nombre varchar(100) NOT NULL DEFAULT '' ,
  apellido varchar(100) NOT NULL DEFAULT '' ,
  usuario varchar(32) NOT NULL DEFAULT '' ,
  clave varchar(32) NOT NULL DEFAULT '' ,
  email varchar(100) NOT NULL DEFAULT '' ,
  ip varchar(15) NOT NULL DEFAULT '' ,
  pregunta varchar(100) NOT NULL DEFAULT '' ,
  respuesta varchar(100) NOT NULL DEFAULT '' ,
  activo tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  fecha int(14) unsigned NOT NULL DEFAULT '0' ,
  PRIMARY KEY (id),
  UNIQUE KEY usuario (usuario)
);

DROP TABLE IF EXISTS horarios;
CREATE TABLE IF NOT EXISTS horarios (
  id mediumint(8) unsigned NOT NULL auto_increment,
  nombre varchar(100) NOT NULL DEFAULT '' ,
  horario tinyint(3) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (id)
);

INSERT INTO horarios (id, nombre, horario) VALUES("1", "Buenos Aires", "-3");
INSERT INTO empresas (id, nombre, abbr, slogan, logo, descripcion, web, activo, fecha) VALUES (1, 'Sonico', 'sonico', 'Amigos Conectados', 'sonico/sonico.gif', 'Sonico es una herramienta que te permite compartir información con tus amigos y familia de una forma segura y divertida.', 'http://sonico.com', 1, 1211219081);
INSERT INTO productos (id, id_empresa, nombre, logo, admite_preguntas, admite_ideas, admite_problemas, activo, fecha) VALUES (1, 1, 'Sonico', 'sonico/sonico.gif', 1, 1, 1, 1, 1211219151);
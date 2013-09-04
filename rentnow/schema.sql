
CREATE TABLE `configuracion` (
  `idioma` VARCHAR( 2 ) NOT NULL DEFAULT '',
  `nombre` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `valor` VARCHAR( 255 ) NOT NULL DEFAULT ''
);

CREATE TABLE `personas` (
  `persona_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR( 20 ) NOT NULL DEFAULT '',
  `nombre` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `contrasena` VARCHAR( 32 ) NOT NULL DEFAULT '',
  `inscripcion` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'
);

INSERT INTO `personas` (`persona_id`, `usuario`, `nombre`, `contrasena`, `inscripcion`) VALUES (NULL, 'joksnet', 'Juan M Martínez', 'f531bfff76fa1f662de7b8c6b0db413e', UNIX_TIMESTAMP());

CREATE TABLE `inmuebles` (
  `inmueble_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inmueble_pagina_id_inicio` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
  `inmueble_pagina_id_lateral` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
  `codigo` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `diseno` VARCHAR( 20 ) NOT NULL DEFAULT '',
  `activo` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1'
);

CREATE TABLE `inmuebles_contenidos` (
  `inmueble_contenido_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inmueble_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
  `idioma` VARCHAR( 2 ) NOT NULL DEFAULT '',
  `titulo` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `nombre` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `url` VARCHAR( 200 ) NOT NULL DEFAULT '',
  `descripcion` TEXT NOT NULL DEFAULT ''
);

CREATE TABLE `inmuebles_fotos` (
  `inmueble_foto_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inmueble_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
  `codigo` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `nombre` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `posicion` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'
);

CREATE TABLE `inmuebles_paginas` (
  `inmueble_pagina_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inmueble_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
  `codigo` VARCHAR( 20 ) NOT NULL DEFAULT '',
  `tipo` VARCHAR( 20 ) NOT NULL DEFAULT '',
  `menu` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
  `posicion` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'
);

CREATE TABLE `inmuebles_paginas_contenidos` (
  `inmueble_pagina_contenido_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inmueble_pagina_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
  `idioma` VARCHAR( 2 ) NOT NULL DEFAULT '',
  `nombre` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `titulo` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `url` VARCHAR( 200 ) NOT NULL DEFAULT ''
);

CREATE TABLE `inmuebles_paginas_contenidos_datos` (
  `inmueble_pagina_contenido_dato_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inmueble_pagina_contenido_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
  `nombre` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `contenido` TEXT NOT NULL DEFAULT ''
);

CREATE TABLE `inmuebles_paginas_datos` (
  `inmueble_pagina_dato_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inmueble_pagina_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
  `nombre` VARCHAR( 80 ) NOT NULL DEFAULT '',
  `contenido` TEXT NOT NULL DEFAULT ''
);
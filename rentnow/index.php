<?php

error_reporting(-1);

ini_set('display_errors', 1);
ini_set('safe_mode', 0);

$url = array(
    '/' => 'Welcome',

    '/sitemap'     => 'Sitemap',
    '/sitemap.xml' => 'SitemapXML',

    '/([\w\d\.]+)'                         => 'Inmueble',
    '/([\w\d\.]+)/([\w\d\.]+)'             => 'Inmueble_Pagina',
  # '/([\w\d\.]+)/([\w\d\.]+)/([\w\d\.]+)' => 'Inmueble_Pagina',

    '/admin'            => 'Admin_Principal',
    '/admin/ayuda'      => 'Admin_Ayuda',
    '/admin/ayuda/wiki' => 'Admin_Ayuda_Wiki',

    '/admin/configuracion'        => 'Admin_Configuracion',
    '/admin/configuracion/(\w\w)' => 'Admin_Configuracion_Contenido',

    '/admin/personas'                             => 'Admin_Personas_Principal',
    '/admin/personas/([\w\d]+)'                   => 'Admin_Personas_Ver',
    '/admin/personas/([\w\d]+)/editar'            => 'Admin_Personas_Editar',
    '/admin/personas/([\w\d]+)/editar/contrasena' => 'Admin_Personas_Editar_Contrasena',
    '/admin/personas/([\w\d]+)/eliminar'          => 'Admin_Personas_Eliminar',
    '/admin/personas/agregar'                     => 'Admin_Personas_Agregar',

    '/admin/inmuebles'                                                     => 'Admin_Inmuebles_Principal',
    '/admin/inmuebles/([\w\d]+)'                                           => 'Admin_Inmuebles_Ver',
    '/admin/inmuebles/([\w\d]+)/paginas'                                   => 'Admin_Inmuebles_Paginas',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)'                         => 'Admin_Inmuebles_Paginas_Ver',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/menu'                    => 'Admin_Inmuebles_Paginas_Menu',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/mover'                   => 'Admin_Inmuebles_Paginas_Mover',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/mover([+-][\d]+)'        => 'Admin_Inmuebles_Paginas_Posicion',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/editar'                  => 'Admin_Inmuebles_Paginas_Editar',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/editar/html'             => 'Admin_Inmuebles_Paginas_Editar_HTML',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/editar/html/(\w\w)'      => 'Admin_Inmuebles_Paginas_Editar_HTML_Contenido',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/editar/fotos'            => 'Admin_Inmuebles_Paginas_Editar_Fotos',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/editar/ubicacion'        => 'Admin_Inmuebles_Paginas_Editar_Ubicacion',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/editar/ubicacion/(\w\w)' => 'Admin_Inmuebles_Paginas_Editar_Ubicacion_Contenido',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/editar/contacto'         => 'Admin_Inmuebles_Paginas_Editar_Contacto',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/editar/contacto/(\w\w)'  => 'Admin_Inmuebles_Paginas_Editar_Contacto_Contenido',
    '/admin/inmuebles/([\w\d]+)/paginas/([\w\d]+)/eliminar'                => 'Admin_Inmuebles_Paginas_Eliminar',
    '/admin/inmuebles/([\w\d]+)/paginas/agregar'                           => 'Admin_Inmuebles_Paginas_Agregar',
    '/admin/inmuebles/([\w\d]+)/fotos'                                     => 'Admin_Inmuebles_Fotos',
    '/admin/inmuebles/([\w\d]+)/fotos/([\w\d]+)'                           => 'Admin_Inmuebles_Fotos_Ver',
    '/admin/inmuebles/([\w\d]+)/fotos/([\w\d]+)/mover'                     => 'Admin_Inmuebles_Fotos_Mover',
    '/admin/inmuebles/([\w\d]+)/fotos/([\w\d]+)/mover([+-][\d]+)'          => 'Admin_Inmuebles_Fotos_Posicion',
    '/admin/inmuebles/([\w\d]+)/fotos/([\w\d]+)/eliminar'                  => 'Admin_Inmuebles_Fotos_Eliminar',
    '/admin/inmuebles/([\w\d]+)/fotos/agregar'                             => 'Admin_Inmuebles_Fotos_Agregar',
    '/admin/inmuebles/([\w\d]+)/activo'                                    => 'Admin_Inmuebles_Activo',
    '/admin/inmuebles/([\w\d]+)/configuracion'                             => 'Admin_Inmuebles_Configuracion',
    '/admin/inmuebles/([\w\d]+)/editar'                                    => 'Admin_Inmuebles_Editar',
    '/admin/inmuebles/([\w\d]+)/editar/(\w\w)'                             => 'Admin_Inmuebles_Editar_Contenido',
    '/admin/inmuebles/([\w\d]+)/eliminar'                                  => 'Admin_Inmuebles_Eliminar',
    '/admin/inmuebles/agregar'                                             => 'Admin_Inmuebles_Agregar',

    '/admin/ingresar' => 'Admin_Ingresar',
    '/admin/salir'    => 'Admin_Salir'
);

include_once 'library/Db.php';
include_once 'library/Url.php';
include_once 'library/Router.php';
include_once 'library/Request.php';
include_once 'library/Response.php';
include_once 'library/Session.php';
include_once 'library/Validate.php';
include_once 'library/Translate.php';
include_once 'library/Configuration.php';
include_once 'library/DefinedMail.php';
include_once 'library/Image.php';
include_once 'library/Wiki.php';

include_once 'library/Model.php';
include_once 'library/View.php';
include_once 'library/Controller.php';

include_once 'models/Personas.php';
include_once 'models/Inmuebles.php';
include_once 'models/Inmuebles/Contenidos.php';
include_once 'models/Inmuebles/Paginas.php';
include_once 'models/Inmuebles/Paginas/Contenidos.php';
include_once 'models/Inmuebles/Paginas/Contenidos/Datos.php';
include_once 'models/Inmuebles/Paginas/Datos.php';
include_once 'models/Inmuebles/Fotos.php';

Db::connect(
    require_once 'config.php'
);

// Si se corre desde `localhost` y no se quiere hace la redireccion de
// dominio, hay que comentar la primera parte del siguiente condicional y
// assignar el idioma de traduccion a la mano.
//Translate::locale('es');

if ( !( ( $idioma = Router::dispatchHostname() ) === true ) )
    Url::redirectIdioma($idioma);
else
{
    foreach ( $url as $route => $class )
        Router::add($route, $class);

    Router::dispatch();
}

Response::dispatch();

Db::disconnect();

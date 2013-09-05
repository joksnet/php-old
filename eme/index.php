<?php

define('DEBUG', true);

$root = realpath( dirname( __FILE__ ) );
$urls = array(
    '^/$' => 'Principal',

    '^/ingresar$'         => 'Ingresar',
    '^/ingresar/amnesia$' => 'IngresarAmnesia',

    '^/personas$'              => 'Personas',
    '^/personas/(\d+)$'        => 'PersonasVer',
    '^/personas/(\d+)/editar$' => 'PersonasEditar',

    '^/empresas$'              => 'Empresas',
    '^/empresas/agregar$'      => 'EmpresasAgregar',
    '^/empresas/(\d+)$'        => 'EmpresasVer',
    '^/empresas/(\d+)/editar$' => 'EmpresasEditar',

    '^/empresas/(\d+)/personas$'         => 'EmpresasPersonas',
    '^/empresas/(\d+)/personas/agregar$' => 'EmpresasPersonasAgregar',

    '^/configuracion$'            => 'Configuracion',
    '^/configuracion/apariencia$' => 'ConfiguracionApariencia',

    '^/salir$' => 'Salir'
);

include_once "$root/config.php";

if ( DEBUG )
    error_reporting(E_ALL);

include_once "$root/lib/Request.php";
include_once "$root/lib/Response.php";

include_once "$root/lib/Web.php";
include_once "$root/lib/WebBase.php";

include_once "$root/lib/Cookies.php";
include_once "$root/lib/Validation.php";

include_once "$root/lib/String.php";
include_once "$root/lib/Lang.php";

include_once "$root/lib/Db.php";

include_once "$root/lib/Config.php";
include_once "$root/lib/Session.php";

Db::open($dbConfig);

Cookies::$prefix = 'EME_';
Session::start();

Web::errorRegister(404, 'Error404');
Web::dispatch($urls);

Db::close();
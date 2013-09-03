<?php

$url = array(
    '/' => 'Welcome',

    '/admin' => 'Admin_Welcome',
    '/admin/synchronize' => 'Admin_Synchronize',

    '/admin/categories'              => 'Admin_Categories_List',
    '/admin/categories/add'          => 'Admin_Categories_Add',
    '/admin/categories/empty'        => 'Admin_Categories_Empty',
    '/admin/categories/(\d+)'        => 'Admin_Categories_View',
    '/admin/categories/(\d+)/edit'   => 'Admin_Categories_Edit',
    '/admin/categories/(\d+)/delete' => 'Admin_Categories_Delete',

    '/admin/businesses'     => 'Admin_Businesses_List',
    '/admin/businesses/add' => 'Admin_Businesses_Add',

    '/admin/login'  => 'Admin_Auth_Login',
    '/admin/logout' => 'Admin_Auth_Logout'

);

require_once 'library/Db.php';
require_once 'library/Query.php';
require_once 'library/Controller.php';
require_once 'library/Model.php';
require_once 'library/Property.php';
require_once 'library/PropertyIdentifier.php';
require_once 'library/PropertyRelation.php';
require_once 'library/PropertyInteger.php';
require_once 'library/PropertyBoolean.php';
require_once 'library/PropertyString.php';
require_once 'library/PropertyDate.php';
require_once 'library/View.php';
require_once 'library/Translate.php';
require_once 'library/Session.php';
require_once 'library/Request.php';
require_once 'library/Response.php';
require_once 'library/Router.php';
require_once 'library/Url.php';

$db = Db::getInstance();
$db->connect(
    require_once 'application/config.php'
);

$router = new Router($url);
$router->dispatch();

$db->disconnect();

Response::getInstance()
    ->dispatch();
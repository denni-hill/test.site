<?php
require FRAMEWORK_DIR . '/Core/System/Base_Controller.php';
require FRAMEWORK_DIR . '/Core/DataBase/RedBeanORM.php';
require FRAMEWORK_DIR . '/Core/System/DB_Controller.php';

DB_Controller::setup();

define("CONTROLLERS_DIR", BASE_DIR . '/Controllers');
define("MODELS_DIR", BASE_DIR . '/Models');
define("TEMPLATES_DIR", BASE_DIR . '/Templates');

if( file_exists( FRAMEWORK_DIR . '/Core/Composer/vendor/autoload.php' ) )
{
    require FRAMEWORK_DIR . '/Core/Composer/vendor/autoload.php';
}

$modules = glob( FRAMEWORK_DIR . '/Modules/*.module.php' );
foreach ($modules as $module) {
    require $module;
}
unset($modules);

require FRAMEWORK_DIR . '/Core/System/iController.php';
require FRAMEWORK_DIR . '/Core/System/iModel.php';

require FRAMEWORK_DIR . '/Core/System/_IndexController.php';

require FRAMEWORK_DIR . '/Core/Bootstrap.php';

session_start();
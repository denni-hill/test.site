<?php
    define("BASE_DIR", getcwd());
    define("FRAMEWORK_DIR", BASE_DIR . '/Framework');

    require FRAMEWORK_DIR . '/Starter.php';

    $app = new MVCEngine( require APPS_DIR . '/Routes.php' );
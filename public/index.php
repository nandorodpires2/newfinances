<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to emails layouts
defined('EMAILS_SITE')
    || define('EMAILS_SITE', realpath(dirname(__FILE__) . '/../application/modules/site/views/emails'));

// Define path to emails layouts
defined('LAYOUT_SITE_DEFAULT')
    || define('LAYOUT_SITE_DEFAULT', 'site');

// Define path to emails layouts
defined('EMAILS_PATH')
    || define('EMAILS_PATH', realpath(dirname(__FILE__) . '/../application/modules/cliente/views/emails'));

// Define path to crons directory
defined('CRONS_PATH')
    || define('CRONS_PATH', realpath(dirname(__FILE__) . '/../public/crons'));

// Define path to public directory
defined('PUBLIC_PATH') || define('PUBLIC_PATH', realpath(dirname(__FILE__)));

// Define path to public directory
defined('SERVER_NAME') || define('SERVER_NAME', $_SERVER['SERVER_NAME']);

/*
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../public/'));
*/

$server = $_SERVER['SERVER_NAME'];

// verifica em qual base a aplicacao esta
$status = ($server == 'localhost') ? 'development' : 'production';

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $status));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();
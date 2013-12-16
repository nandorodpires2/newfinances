<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

$server = $_SERVER['SERVER_NAME'];

// verifica em qual base a aplicacao esta
$status = ($server == 'localhost') ? 'development' : 'production';

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $status));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../../library'),
    // realpath(APPLICATION_PATH . '/models'),
    get_include_path(),
)));

require_once 'Zend/Application.php';
require_once 'Zend/Loader/Autoloader.php';

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

$server = $_SERVER['SERVER_NAME'];

// verifica em qual base a aplicacao esta
$status = ($server == 'localhost') ? 'development' : 'production';

$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $status);

$mail_config = array(
    'auth'     => $config->mail->auth,
    'username' => $config->mail->username,
    'password' => $config->mail->password
);
$transport = new Zend_Mail_Transport_Smtp($config->mail->host, $mail_config);
Zend_Registry::set('mail_transport', $transport);

try {
    $db = Zend_Db::factory($config->resources->db->adapter, $config->resources->db->params->toArray());         
} catch (Zend_Db_Adapter_Exception $e) {
    echo "erro1";
    exit;
} catch (Zend_Exception $e) {
    echo "erro2";
    exit;
}
<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    const LAYOUT_DEFAULT = "index";

    protected function _initRegistry()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        Zend_Registry::set('config', $config);
        
        $mail_config = array(            
            'auth' => $config->mail->auth,        
            'username' => $config->mail->username,
            'password' => $config->mail->password
        );
        $transport = new Zend_Mail_Transport_Smtp($config->mail->host, $mail_config);
        Zend_Registry::set('mail_transport', $transport);
    }
    
    /**
     * Cria uma instancia do Autoloader
     */
    protected function _initAutoloader() {
     
        $autoloader = new Zend_Application_Module_Autoloader(array(
           'namespace'  => '',
           'basePath'   => APPLICATION_PATH
        ));
        
        $autoloader->addResourceTypes(array(
            'actionhelper' => array(
                'path' => 'helpers/actions',
                'namespace' => 'Controller_Helper'
            ),
            'viewhelper' => array(
                'path' => 'helpers/views',
                'namespace' => 'View_Helper'
            )
        ));
        
    }
    
    /**
     * _initController
     * 
     * Configura o controller
     */
    protected function _initController() {
    	$controller = Zend_Controller_Front::getInstance();           
        $controller->registerPlugin(new Plugin_Auth());      
        $controller->registerPlugin(new Plugin_Movimentacao);                    
        $controller->registerPlugin(new Plugin_Saldos());
        $controller->registerPlugin(new Plugin_Acl());
    }
       
    /**
     * Definindo a configuracao de Layout
     */
    protected function _initLayout() {
        
        $configs = array(
            'layout' => self::LAYOUT_DEFAULT,
            'layoutPath' => APPLICATION_PATH . '/layouts'
        );
        // inicia o componente
        Zend_Layout::startMvc($configs);
        
    }
    
    /**
     * Zend Locale
     */
    public function _initLocale() {
        //instancia o componente usando  pt-BR como padr�o
        $locale = new Zend_Locale('pt_BR');
        //salva o memso no Zend_Registry
        Zend_Registry::set('Zend_Locale', $locale);
    }    

    /**
     * Inclui os javascripts comuns da aplicacao no header
     *
     * @return Zend_View
     */
    protected function _initJavascript() {

    }
    
}


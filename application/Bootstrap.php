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
     * 
     * @return \Zend_View
     */
    protected function _initView() {
        //Initialize view
        $view = new Zend_View();  

        
        
        //set css includes    
        /*
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/site.css');
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/style.css');
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/styles.css');
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/default.css');
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/jquery-ui-1.10.3.css');
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/menu_1.css');
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/menu-site.css');
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/index/index.css');    
        $view->headLink()->appendStylesheet(PUBLIC_PATH . '/views/css/table.css');        
        */
        
        // seta o caminho base
        $prefix = "";
        if (SERVER_NAME === 'localhost') {
            $prefix = "/newfinances/public/";
        } else {
            $prefix = "/";
        }
        
        //add javascript files          
        $view->headScript()->appendFile($prefix . 'views/js/jquery-2.0.3.js');
        $view->headScript()->appendFile($prefix . 'views/js/jquery-ui-1.10.3.js');
        $view->headScript()->appendFile($prefix . 'views/js/jquery.ui.datepicker-pt-BR.js');
        $view->headScript()->appendFile($prefix . 'views/js/jquery.maskMoney.js');
        $view->headScript()->appendFile($prefix . 'views/js/jquery.maskedinput.js');        
        $view->headScript()->appendFile($prefix . 'views/js/application.js');
        $view->headScript()->appendFile($prefix . 'views/js/facebook.js');        
        
        //add it to the view renderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'ViewRenderer');
        $viewRenderer->setView($view);

        //Return it, so that it can be stored by the bootstrap
        return $view;
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContatoController
 *
 * @author Fernando Rodrigues
 */
class Site_ContatoController extends Application_Controller {

    public function init() {
        parent::init();
        $this->_helper->layout()->setLayout('site');
    }
    
    public function indexAction() {
        
        // envia o form de contato para a view
        $this->view->formContato = $this->_formContatoContato;
        
        if ($this->_request->isPost()) {
            $dadosContato = $this->_request->getPost();
            if ($this->_formContatoContato->isValid($dadosContato)) {
                $dadosContato = $this->_formContatoContato->getValues();
                
                // grava o contato no banco
                $this->_modelContato->insert($dadosContato);
                
                // envia um e-mail de resposta para o visitante
                
                $data = date("d/m/Y H:i:s");
                // envia um e-mail para o gestor                
                $bodyText = "
                    <p><b>Novo Mensagem de Contato enviado:</b></p>
                    <p><b>Data: </b>{$data}</p>
                    <p><b>Nome: </b>{$dadosContato['nome']}</p>
                    <p><b>E-mail: </b>{$dadosContato['email']}</p>
                    <p><b>Assnto: </b>{$dadosContato['assunto']}</p>
                    <p><b>Mensagem: </b></p>
                    <p>{$dadosContato['texto']}</p>
                ";
                
                // envia os emails para os usuarios
                $mail = new Zend_Mail('utf-8');
                $mail->setBodyHtml($bodyText);
                $mail->setFrom('noreply@newfinances.com.br', 'NewFinances - Controle Financeiro');
                $mail->addTo("nandorodpires@gmail.com");                          
                $mail->setSubject("Contato enviado");
                
                $mail->send(Zend_Registry::get('mail_transport'));

                
            }
        }
        
    }
    
}


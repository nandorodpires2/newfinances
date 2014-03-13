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
        $this->view->messages = Controller_Helper_Messeges::getMesseges();
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
                
                // data da mensagem
                $data = date("d/m/Y H:i:s");
                
                // envia um e-mail de resposta para o visitante
                $html = new Zend_View();
                $html->setScriptPath(EMAILS_SITE . '/contato/');

                // assign values
                $html->assign('nome', $dadosContato['nome']);
                $html->assign('data', $data);
                $html->assign('assunto', $dadosContato['assunto']);
                $html->assign('mensagem', $dadosContato['texto']);
                                
                // render view
                $bodyText = $html->render('contato.phtml');
                
                // envia os emails para os usuarios
                $mail = new Zend_Mail('utf-8');
                $mail->setBodyHtml($bodyText);
                $mail->setFrom('noreply@newfinances.com.br', 'NewFinances - Controle Financeiro');
                $mail->addTo($dadosContato['email']);                          
                $mail->setSubject("Contato recebido");
                
                $mail->send(Zend_Registry::get('mail_transport'));
                
                // envia um e-mail para o gestor                
                $body = "
                    <p><b>Nova Mensagem de Contato enviado:</b></p>
                    <p><b>Data: </b>{$data}</p>
                    <p><b>Nome: </b>{$dadosContato['nome']}</p>
                    <p><b>E-mail: </b>{$dadosContato['email']}</p>
                    <p><b>Assnto: </b>{$dadosContato['assunto']}</p>
                    <p><b>Mensagem: </b></p>
                    <p>{$dadosContato['texto']}</p>
                ";
                
                // envia os emails para os usuarios
                $mail = new Zend_Mail('utf-8');
                $mail->setBodyHtml($body);
                $mail->setFrom('noreply@newfinances.com.br', 'NewFinances - Controle Financeiro');
                $mail->addTo("nandorodpires@gmail.com");                          
                $mail->setSubject("Contato enviado");
                
                $mail->send(Zend_Registry::get('mail_transport'));

                
                $messeges = array(
                    'success' => "Mensagem enviada com sucesso. Em até dois dias você será respondido. Obrigado."
                );
                Controller_Helper_Messeges::setMesseges($messeges);
                
                $this->_redirect("site/contato");
                
            }
        }
        
    }
    
}


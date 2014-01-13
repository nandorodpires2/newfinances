<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChamadosController
 *
 * @author Fernando Rodrigues
 */
class Gestor_ChamadosController extends Application_Controller {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        
        $chamados = $this->_modelChamado->getChamados();
        $this->view->chamados = $chamados;
        
    }
    
    /**
     * ver chamados
     */
    public function verChamadoAction() {
        
        // recuperando o id do chamado
        $id_chamado = $this->_getParam("id_chamado");
        
        // buscando os dados do chamado
        $dadosChamado = $this->_modelChamado->getDadosChamado($id_chamado);
        $this->view->dadosChamado = $dadosChamado;
        
        // buscando as resposta para o chamado
        $respostasChamado = $this->_modelChamadoResposta->respostasChamado($id_chamado);
        $this->view->respostasChamado = $respostasChamado;
                
        // populando os campos hidden do form
        $this->_formChamadosResponder->id_chamado->setValue($id_chamado);
        $this->_formChamadosResponder->id_usuario->setValue($this->_session->id_usuario);
        
        // envia o form de resposta pra view
        $this->view->formChamadosResponder = $this->_formChamadosResponder;
        
        if ($this->_request->isPost()) {
            $dadosResponderChamado = $this->_request->getPost();
            if ($this->_formChamadosResponder->isValid($dadosResponderChamado)) {
                $dadosResponderChamado = $this->_formChamadosResponder->getValues();
                
                // gravando na tabela
                try {
                    $this->_modelChamadoResposta->insert($dadosResponderChamado);
                                        
                    // alterando o status do chamado para fechado
                    $status['status'] = "Fechado";
                    $whereStatus = "id_chamado = " . $id_chamado;
                    $this->_modelChamado->update($status, $whereStatus);
                    
                    // envia o email para o usuario
                    $mail = new Zend_Mail('utf-8');

                    $message = "
                        <p>Olá {$dadosChamado->nome_completo}</p>
                        <p>O seu chamado foi respondido!</p>
                        <p>Assunto: {$dadosChamado->assunto}</p>                    
                        <p>Mensagem: {$dadosResponderChamado['resposta']}</p>                    
                        <p>
                            Caso a resposta não tenha sido satisfatório, acesse 
                            sua conta e envia uma nova mensagem para reabrir o
                            chamado.
                        </p>
                        <p>Obrigado. Equipe NewFinances.</p>
                    ";

                    $mail->setBodyHtml($message);
                    $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
                    $mail->addTo($dadosChamado->email_usuario);
                    $mail->setSubject('Chamado Respondido');

                    $mail->send(Zend_Registry::get('mail_transport'));
                    
                    $this->_redirect("gestor/chamados/ver-chamado/id_chamado/" . $id_chamado);
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                            
            }
        }
                
    }
    
    /**
     * responder chamado
     */
    public function responderAction() {
        
        // recuperando o id do chamado
        $id_chamado = $this->_getParam("id_chamado");
        
        // buscando os dados do chamado
        $dadosChamado = $this->_modelChamado->getDadosChamado($id_chamado);
        $this->view->dadosChamado = $dadosChamado;
        
        // populando os campos hidden do form
        $this->_formChamadosResponder->id_chamado->setValue($id_chamado);
        $this->_formChamadosResponder->id_usuario->setValue($this->_session->id_usuario);
        
        // envia o form de resposta pra view
        $this->view->formChamadosResponder = $this->_formChamadosResponder;
        
        if ($this->_request->isPost()) {
            $dadosResponderChamado = $this->_request->getPost();
            if ($this->_formChamadosResponder->isValid($dadosResponderChamado)) {
                $dadosResponderChamado = $this->_formChamadosResponder->getValues();
                
                // gravando na tabela
                try {
                    $this->_modelChamadoResposta->insert($dadosResponderChamado);
                    $this->_redirect("gestor/chamados/");
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                            
            }
        }
        
    }
    
}


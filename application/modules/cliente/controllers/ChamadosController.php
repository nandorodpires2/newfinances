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
class ChamadosController extends Application_Controller {
 
    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        
        // busca os chamados e envia para a view
        $chamados = $this->_modelChamado->fetchAll("id_usuario = {$this->_session->id_usuario}");
        $this->view->chamados = $chamados;
        
    }
    
    /**
     * Abrir novo chamado
     */
    public function novoChamadoAction() {
        
        //setando o valor do id_usuario
        $this->_formChamadosChamado->id_usuario->setValue($this->_session->id_usuario);
        
        // enviando o form de chamado para a view        
        $this->view->formChamadosChamado = $this->_formChamadosChamado;
        
        if ($this->_request->isPost()) {
            $dadosChamado = $this->_request->getPost();
            if ($this->_formChamadosChamado->isValid($dadosChamado)) {
                $dadosChamado = $this->_formChamadosChamado->getValues();
                
                // inserir na pagina de chamado
                $this->_modelChamado->insert($dadosChamado);
                                
                // disparar e-mail para usuario sobre o recebimento 
                $mail = new Zend_Mail('utf-8');

                $message = "
                    <p>Olá {$this->_session->nome_completo}</p>
                    <p>O seu chamado foi aberto com sucesso!</p>
                    <p>Assunto: {$dadosChamado['assunto']}</p>                    
                    <p>Mensagem: {$dadosChamado['mensagem']}</p>                    
                    <p>Em breve nossa equipe irá responder seu chamado.</p>
                    <p>Obrigado.</p>
                ";

                $mail->setBodyHtml($message);
                $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
                $mail->addTo($this->_session->email_usuario);
                $mail->setSubject('Chamado Aberto');

                $mail->send(Zend_Registry::get('mail_transport'));
                
                // disparar e-mail gestor sobre novo chamado
                $mail = new Zend_Mail('utf-8');

                $message = "
                    <p>Novo chamado aberto</p>
                    <p>Usuário: {$this->_session->nome_completo}</p>                    
                    <p>Assunto: {$dadosChamado['assunto']}</p>                    
                    <p>Mensagem: {$dadosChamado['mensagem']}</p>    
                ";

                $mail->setBodyHtml($message);
                $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
                $mail->addTo("nandorodpires@gmail.com");
                $mail->setSubject("Chamado Aberto");

                $mail->send(Zend_Registry::get('mail_transport'));
                
                $this->_redirect("chamados/");
                
            }
        }
        
    }
    
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
                    
                    // alterando o status do chamado para aberto
                    $status['status'] = "Aberto";
                    $whereStatus = "id_chamado = " . $id_chamado;
                    $this->_modelChamado->update($status, $whereStatus);
                    
                    // envia o email para o usuario
                    $mail = new Zend_Mail('utf-8');

                    $message = "
                        <p>Olá {$this->_session->nome_completo}</p>
                        <p>O chamado {$dadosChamado->id_chamado} foi reaberto com sucesso!</p>
                        <p>Assunto: {$dadosChamado->assunto}</p>                    
                        <p>Mensagem: {$dadosResponderChamado['resposta']}</p>                    
                        <p>Em breve nossa equipe irá responder seu chamado.</p>
                        <p>Obrigado.</p>
                    ";

                    $mail->setBodyHtml($message);
                    $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
                    $mail->addTo($this->_session->email_usuario);
                    $mail->setSubject('Chamado Reaberto');

                    $mail->send(Zend_Registry::get('mail_transport'));

                    // disparar e-mail gestor sobre novo chamado
                    $mail = new Zend_Mail('utf-8');

                    $message = "
                        <p>O chamado {$dadosChamado->id_chamado} foi reaberto</p>
                        <p>Usuário: {$this->_session->nome_completo}</p>                    
                        <p>Assunto: {$dadosChamado->assunto}</p>                    
                        <p>Mensagem: {$dadosResponderChamado['resposta']}</p>    
                    ";

                    $mail->setBodyHtml($message);
                    $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
                    $mail->addTo("nandorodpires@gmail.com");
                    $mail->setSubject("Chamado Reaberto");

                    $mail->send(Zend_Registry::get('mail_transport'));
                    
                    $this->_redirect("chamados/ver-chamado/id_chamado/" . $id_chamado);
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                            
            }
        }
    }
    
}

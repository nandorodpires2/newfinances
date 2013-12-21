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
                
                // faz o upload do anexo caso exista
                // o nome do arquivo sera definido por id_chamado, id_usuario,
                // data_abertura
                
                // disparar e-mail para usuario sobre o recebimento 
                
                // disparar e-mail gestor sobre novo chamado
                
                $this->_redirect("chamados/");
                
            }
        }
        
    }
    
}

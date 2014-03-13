<?php

class QuestionarioController extends Application_Controller {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        
        // setando o layout
        $this->setLayout('site');
        
        // pegando o id do usuario
        $id_usuario = $this->_getParam('id', 1);
        
        // envia o form para a view
        $this->_formUsuariosQuestionario->id_usuario->setValue($id_usuario);
        $this->view->formQuestionario = $this->_formUsuariosQuestionario;
        
        // salvando o questionario
        if ($this->_request->isPost()) {
            $dadosQuestionario = $this->_request->getPost();
            if ($this->_formUsuariosQuestionario->isValid($dadosQuestionario)) {
                $dadosQuestionario = $this->_formUsuariosQuestionario->getValues();
                
                try {
                    $this->_modelQuestionario->insert($dadosQuestionario);        
                    $this->_redirect('questionario/');
                } catch (Zend_Db_Table_Exception $error) {
                    echo $error->getMessage();
                }
                
            }
        }
        
    }

}


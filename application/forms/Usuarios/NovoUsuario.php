<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NovoUsuario
 *
 * @author Realter
 */
class Form_Usuarios_NovoUsuario extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', 'formNovoUsuario')
                ->setMethod('post');        
        
        // nome_complete
        $this->addElement("text", "nome_completo", array(
            'label' => 'Nome Completo: ',
            'size' => '50px',
            'required' => true
        ));
        
        // email_usuario
        $this->addElement("text", "email_usuario", array(
            'label' => 'E-mail: ',
            'size' => '50px',
            'required' => true
        ));        
        
        // cpf_usuario
        $this->addElement("text", "cpf_usuario", array(
            'label' => 'CPF: ',
            'size' => '15px',
            'required' => true
        ));
        // data_nascimento
        $this->addElement("text", "data_nascimento", array(
            'label' => 'Data Nascimento: ',
            'size' => '10px'
        ));
        
        // cidade
        $this->addElement("text", "cidade", array(
            'label' => 'Cidade: ',
            'required' => true
        ));
        
        // id_estado
        $this->addElement("select", "id_estado", array(
            'label' => 'Estado: ',
            'multioptions' => $formDefault->getEstados(),
            'required' => true
        ));
        
        // senha_usuario
        $this->addElement("password", "senha_usuario", array(
            'label' => 'Senha: ',
            'required' => true
        ));
        
        // confirma_senha
        $this->addElement("password", "confirma_senha", array(
            'label' => 'Confirma senha: ',
            'required' => true
        ));
        
        // politica privacidade
        $this->addElement("checkbox", 'politica', array(
            'required' => true,
            'value' => '1',
            'label' => "Li e concordo com a <a id='info_politica' href='#'>Política de Privacidade</a> e <a id='info_termo' href='#'>Termo de Uso</a>"
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            'label' => 'Continuar',
            'class' => 'submit'
        ));
        
    }
    
}


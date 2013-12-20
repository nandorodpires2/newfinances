<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NovaFuncionalidade
 *
 * @author Realter
 */
class Form_Funcionalidades_NovaFuncionalidade extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', "formFuncionalidades")
                ->setMethod("post");
        
        // module
        $this->addElement("text", "module", array(
            'label' => 'Módulo:',
            'required' => true
        ));
        
        // controller
        $this->addElement("text", "controller", array(
            'label' => 'Controle:',
            'required' => true
        ));
        
        // action
        $this->addElement("text", "action", array(
            'label' => 'Ação:',
            'required' => true
        ));
        
        // titulo
        $this->addElement("text", "titulo", array(
            'label' => 'Título:',
            'required' => true
        ));
        
        // titulo
        $this->addElement("text", "descricao_permissao", array(
            'label' => 'Título Permissão:',
            'required' => true
        ));
        
        // titulo
        $this->addElement("text", "subtitulo", array(
            'label' => 'Sub-título:'
        ));
        
        // ativo
        $this->addElement("select", "ativo", array(
            'label' => 'Funcionalidade ativa:',
            'multioptions' => array(
                0 => 'Não',
                1 => 'Sim'
            ),
            'required' => true
        ));
        
        // auth
        $this->addElement("select", "auth", array(
            'label' => 'Necessário logar:',
            'multioptions' => array(
                0 => 'Não',
                1 => 'Sim'
            ),
            'required' => true
        ));
        
        // id_posicao_menu
        $this->addElement("select", "id_menu_posicao", array(
            'label' => 'Posição do menu:',
            'multioptions' => $formDefault->getPosicoesMenu(),
            'required' => true
        ));
        
        // menu
        $this->addElement("text", "texto_menu", array(
            'label' => 'Texto menu:'
        ));
        
        // ativo menu
        $this->addElement("select", "ativo_menu", array(
            'label' => 'Menu ativo:',       
            'multioptions' => array(
                0 => 'Não',
                1 => 'Sim'
            ),
            'required' => true
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            'label' => 'Enviar',
            'class' => 'submit'
        ));
        
        
    }
    
}


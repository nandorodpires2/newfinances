<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Meta
 *
 * @author Realter
 */
class Form_Metas_Meta extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        
        $this->setAttrib('id', 'formMetasMeta')
                ->setMethod('post');
        
        // id_usuario
        $this->addElement("hidden", "id_usuario", array('value' => $formDefault->_session->id_usuario));
        
        // mes
        $this->addElement("hidden", "mes_meta", array('value' => date('m')));
        
        // ano
        $this->addElement("hidden", "ano_meta", array('value' => date('Y')));
        
        // id_categoria
        $this->addElement("select", "id_categoria", array(
            'label' => 'Categoria: ',
            'multioptions' => $formDefault->getCategoriasMeta()
        ));
        
        // valor_meta
        $this->addElement("text", "valor_meta", array(
            'label' => 'Meta: '            
        ));
        
        // repetir mes
        $this->addElement("checkbox", "repetir", array(
            'label' => 'Repetir: '
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            'label' => 'Cadastrar',
            'class' => 'submit'
        ));
        
    }
    
}


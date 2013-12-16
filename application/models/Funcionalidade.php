<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Funcionalidade
 *
 * @author Realter
 */
class Model_Funcionalidade extends Zend_Db_Table {

    protected $_name = "funcionalidade";
    
    protected $_primary = "id_funcionalidade";
    
    public function getFuncionalidades() {
        $select = $this->select()
                ->from(array('f' => $this->_name), array('f.*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('mp' => 'menu_posicao'), 'f.id_menu_posicao = mp.id_menu_posicao', array(
                    'mp.posicao'
                ))
                ->order("controller asc");
        
        return $this->fetchAll($select);
    }
    
}


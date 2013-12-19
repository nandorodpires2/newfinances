<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CupomDesconto
 *
 * @author Realter
 */
class Model_CupomDesconto extends Zend_Db_Table {

    protected $_name = "cupom_desconto";
    
    protected $_primary = "id_cupom_desconto";
    
    
    public function getDescontoCupom($cupom, $id_usuario) {
        
        if ($this->validaCupom($cupom, $id_usuario)) {
            $select = $this->select()
                ->from(array('cd' => $this->_name), array(
                    'valido' => '*'
                ))
                ->where("cd.id_usuario = ?", $id_usuario)
                ->where("cd.cupom = ?", $cupom);
        
            return $this->fetchRow($select);
        } else {
            return false;
        }
        
    }
    
    /**
     * verifica se existe cupom
     */
    public function getCupom($id_usuario) {
        $select = $this->select()
                ->from(array('cd' => $this->_name), array(
                    '*'
                ))
                ->where("cd.id_usuario = ?", $id_usuario)
                ->where("cd.utilizado = ?", 0)
                ->where("cd.validade >= now()")
                ->order("cd.id_cupom_desconto desc");
                        
        return $this->fetchRow($select);
    }

    /**
     * valida cupom
     */
    protected function validaCupom($cupom, $id_usuario) {
        
        $select = $this->select()
                ->from(array('cd' => $this->_name), array(
                    'valido' => 'count(*)'
                ))
                ->where("cd.id_usuario = ?", $id_usuario)
                ->where("cd.utilizado = ?", 0)
                ->where("cd.validade >= now()")
                ->where("cd.cupom = ?", $cupom);
                
        $query = $this->fetchRow($select);
        
        if ($query->valido > 0) {
            return true;
        }
        return false;
        
    }
    
}


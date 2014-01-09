<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagamento
 *
 * @author Realter
 */
class Model_Pagamento extends Zend_Db_Table {
    
    protected $_name = "pagamento";
    
    protected $_primary = "id_pagamento";
    
    /**
     * retorna algum pagamento pendente
     */
    public function getPagamento($id_pagamento) {
        
        $select = $this->select()
                ->from(array('pag' => $this->_name), array('*'))
                ->where('pag.id_pagamento = ?', $id_pagamento);                
        
        return $this->fetchRow($select);
        
    }
    
    /**
     * recupera o ultimo id inserido
     */
    public function getLastId() {
        
        $select = $this->select()
                ->from($this->_name, array(
                    'last_id' => 'last_insert_id(id_pagamento)'
                ))
                ->order("id_pagamento desc")
                ->limit(1);
        
        $query = $this->fetchRow($select);
        
        return $query->last_id;
        
    }
    
}


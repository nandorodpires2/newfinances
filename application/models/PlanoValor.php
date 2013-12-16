<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlanoValor
 *
 * @author Fernando Rodrigues
 */
class Model_PlanoValor extends Zend_Db_Table {

    protected $_name = "plano_valor";
    
    protected $_primary = "id_plano_valor";
    
    /**
     * retorna o valor do plano 
     */
    public function getPlanoValorUsuario($id_plano) {
        
        $select = $this->select()
                ->from(array('pv' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('p' => 'plano'), 'pv.id_plano = p.id_plano', array('*'))
                ->where("pv.id_plano = ? ", $id_plano)
                ->where("pv.usuario = ? ", 1);
        
        return $this->fetchRow($select);
        
    }
    
    /**
     * retorna os valores cadastrados para os planos
     */
    public function getValoresPlanos() {
        $select = $this->select()
                ->from(array('pv' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('p' => 'plano'), 'pv.id_plano = p.id_plano', array('*'));                
                
        
        return $this->fetchAll($select);
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlanoFuncionalidade
 *
 * @author Realter
 */
class Model_PlanoFuncionalidade extends Zend_Db_Table {

    protected $_name = "plano_funcionalidade";
    
    protected $_primary = "id_plano_funcionalidade";
    
    /**
     * busca os recursos 
     
    public function getResourcesPlano($id_plano) {
        $select = $this->select()
                ->distinct()
                ->from(array('pf' => $this->_name), array(
                    'resource' => "concat(f.module,':',f.controller)"
                ))
                ->setIntegrityCheck(false)                
                ->joinInner(array('f' => 'funcionalidade'), 'pf.id_funcionalidade = f.id_funcionalidade', array());                

        return $this->fetchAll($select);
    }
    */
    
    /**
     * retorna as funcionalidades do plano
     */
    public function getFuncionalidadesPlano($id_plano) {
        
        $select = $this->select()
                ->from(array('pf' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('p' => 'plano'), 'pf.id_plano = p.id_plano', array('*'))
                ->joinInner(array('f' => 'funcionalidade'), 'pf.id_funcionalidade = f.id_funcionalidade', array('*'))
                ->where("pf.id_plano = ?", $id_plano);
                
        return $this->fetchAll($select);
        
    }
    
}


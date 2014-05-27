<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VwMetasLancamentos
 *
 * @author Fernando Rodrigues
 */
class Model_VwMetasCategorias extends Zend_Db_Table {
    
    protected $_name = "vw_metas_categorias";
    
    protected $_primary = "id_usuario";
    
    public function getMetasCategoriasMes($id_usuario, $mes = null, $ano = null) {
        
        $select = $this->select()
                ->from($this->_name, array('*'))
                ->where("id_usuario = ?", $id_usuario);
        
        if ($mes && $ano) {
            $select->where("ano_meta = ?", $ano);
            $select->where("mes_meta = ?", $mes);
        } else {
            $select->where("ano_meta = year(now())");
            $select->where("mes_meta = month(now())");
        }
        
        return $this->fetchAll($select);
        
    }
    
}

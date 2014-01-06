<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VwRelatorioAnual
 *
 * @author Realter
 */
class Model_VwRelatorioAnual extends Zend_Db_Table {

    protected $_name = "vw_relatorio_anual";
    
    protected $_primary = "id_usuario";
    
    /**
     * busca o total do relatorio anual
     */
    public function getTotalRelatorioAnual($id_usuario, $ano) {
        
        $select = $this->select()
                ->from(array('vra' => $this->_name), array(
                    "total_receita" => "sum(vra.receita)",
                    "total_receita_prev" => "sum(vra.receita_prev)",
                    "total_despesa" => "sum(vra.despesa)",
                    "total_despesa_prev" => "sum(vra.despesa_prev)",
                    "total_saldo" => "sum(vra.saldo)",
                    "total_saldo_prev" => "sum(vra.saldo_prev)"
                ))
                ->setIntegrityCheck(false)
                ->where("vra.id_usuario = ?", $id_usuario)
                ->where("vra.ano = ?", $ano)
                ->group("vra.receita")
                ->group("vra.receita_prev")
                ->group("vra.despesa")
                ->group("vra.despesa_prev")
                ->group("vra.saldo")
                ->group("vra.saldo_prev");			
        
        return $this->fetchRow($select);
        
    }
    
}


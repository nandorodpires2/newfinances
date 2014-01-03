<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VwMovimentacao
 *
 * @author Fernando Rodrigues
 */
class Model_VwMovimentacao extends Zend_Db_Table {
    
    protected $_name = "vw_movimentacao";
    
    protected $_primary = "id_movimentacao";

    /**
     * 
     * Retorna as que tiveram movimentacao de acordo com o mes
     * 
     * @param type $mes
     * @return type
     */
    public function getDatasMes($ano, $mes, $id_usuario, $conta = null) {
        $select = $this->select()
                ->from($this->_name, array(
                    'data_movimentacao',
                    'dia' => 'day(data_movimentacao)'
                ))
                ->where("year(data_movimentacao) = ?", $ano)
                ->where("month(data_movimentacao) = ?", $mes)                
                ->where("id_usuario = ?", $id_usuario)                
                ->group("data_movimentacao");
        
        // caso seja filtrado por conta
        if ($conta) {
            $select->where("id_conta = ?", $conta);
        }
                
        return $this->fetchAll($select);
        
    }
    
    public function getMovimentacoesData($data, $id_usuario, $conta = null) {
        
        $select = $this->select()
                ->from(array('mov' => $this->_name), array(
                    '*'
                ))
                ->where("mov.data_movimentacao = ?", $data)                
                ->where("mov.id_usuario = ?", $id_usuario)
                ->order("mov.data_inclusao asc");
        
        if ($conta) {
            $select->where("mov.id_conta = ?", $conta);                
        }
        
        return $this->fetchAll($select);
        
    } 
    
    /**
     * Busca o saldo previsto
     */
    public function getSaldoPrevisto($data_fim, $id_usuario, $data_ini) {
        
        // total realizado
        $select = $this->select()
                ->from(array('mov' => $this->_name), array(
                    'saldo_previsto' => 'ifnull(sum(mov.valor_movimentacao), 0)'
                ))
                ->where("mov.id_usuario = ?", $id_usuario)
                ->where("mov.data_movimentacao between '{$data_ini}' and '{$data_fim}'")                
                ->where("mov.id_tipo_movimentacao in (1,2,5)");
        
        $saldoPrevisto = $this->fetchRow($select);        
        
        return $saldoPrevisto->saldo_previsto;
        
    }
    
    /**
     * Busca o saldo realizado
     */
    public function getSaldoRealizado($data_fim, $id_usuario, $data_ini) {
        
        // total realizado
        $select = $this->select()
                ->from(array('mov' => $this->_name), array(
                    'saldo_realizado' => 'ifnull(sum(mov.valor_movimentacao), 0)'
                ))
                ->where("mov.id_usuario = ?", $id_usuario)
                ->where("mov.data_movimentacao between '{$data_ini}' and '{$data_fim}'")                
                ->where("mov.realizado = ?", 1)                
                ->where("mov.id_tipo_movimentacao in (1,2,5)");
                        
        $saldoRealizado = $this->fetchRow($select);        
        
        return $saldoRealizado->saldo_realizado;
        
    }
    
}


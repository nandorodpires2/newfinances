<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Relatorios
 *
 * @author Realter
 */
class Model_Relatorios extends Zend_Db_Table {

    protected $_name = "movimentacao";
    
    protected $_primary = "id_movimentacao";
    
    protected $_ano;
    
    protected $_usuario;

    /**
     * relatorio anual
     */
    public function getRelatorioAnual($ano, $id_usuario) {
        
        $relatorios = array();
        
        $relatorios['total_receita'] = 0;
        $relatorios['total_receita_prev'] = 0;
        $relatorios['total_despesa'] = 0;
        $relatorios['total_despesa_prev'] = 0;
        $relatorios['total_saldo'] = 0;
        $relatorios['total_saldo_prev'] = 0;
        
        $meses = $this->getMesesRelatorioAnual($ano, $id_usuario);
        
        foreach ($meses as $key => $mes) {
            $relatorios['dados'][$key]['mes'] = $mes->mes;            
            // receita realizado
            $relatorios['dados'][$key]['receita'] = $this->getValorRelatorioMesAno($ano, $mes->mes, 1, 1, $id_usuario);
            // receita previsto
            $relatorios['dados'][$key]['receita_prev'] = $this->getValorRelatorioMesAno($ano, $mes->mes, 1, 0, $id_usuario);
            // despesa relizado
            $relatorios['dados'][$key]['despesa'] = $this->getValorRelatorioMesAno($ano, $mes->mes, 2, 1, $id_usuario);
            // despesa previsto
            $relatorios['dados'][$key]['despesa_prev'] = $this->getValorRelatorioMesAno($ano, $mes->mes, 2, 0, $id_usuario);
            // saldo realizado
            $relatorios['dados'][$key]['saldo'] = $relatorios['dados'][$key]['receita'] + $relatorios['dados'][$key]['despesa'];
            // saldo previsto
            $relatorios['dados'][$key]['saldo_prev'] = $relatorios['dados'][$key]['receita_prev'] + $relatorios['dados'][$key]['despesa_prev'];
            
            // totais
            $relatorios['total_receita'] += $relatorios['dados'][$key]['receita'];
            $relatorios['total_receita_prev'] += $relatorios['dados'][$key]['receita_prev'];
            $relatorios['total_despesa'] += $relatorios['dados'][$key]['despesa'];
            $relatorios['total_despesa_prev'] += $relatorios['dados'][$key]['despesa_prev'];
            $relatorios['total_saldo'] += $relatorios['dados'][$key]['saldo'];
            $relatorios['total_saldo_prev'] += $relatorios['dados'][$key]['saldo_prev'];
        }
                
        return $relatorios;
            
    }
    
    /**
     * retorna os meses
     */
    protected function getMesesRelatorioAnual($ano, $id_usuario) {
        
        $select = $this->select()
                ->from(array('mov' => $this->_name), array(
                    "mes" => "month(mov.data_movimentacao)"                    
                ))
                ->setIntegrityCheck(false)
                ->where("mov.id_usuario = ?", $id_usuario)
                ->where("year(mov.data_movimentacao) = ?", $ano)
                ->where("mov.id_tipo_movimentacao in (1,2)")
                ->group("month(mov.data_movimentacao)")                
                ->order("month(mov.data_movimentacao) asc");
                
        return $this->fetchAll($select);
        
    }
    
    protected function getValorRelatorioMesAno($ano, $mes, $tipo_movimentacao, $realizado = null, $id_usuario) {
        $select = $this->select()
                ->from(array('mov' => $this->_name), array(
                    "total" => "ifnull(sum(mov.valor_movimentacao),0)"                    
                ))
                ->setIntegrityCheck(false)
                ->where("mov.id_usuario = ?", $id_usuario)
                ->where("year(mov.data_movimentacao) = ?", $ano)
                ->where("month(mov.data_movimentacao) = ?", $mes)
                ->where("mov.id_tipo_movimentacao = ?", $tipo_movimentacao)                          
                ->order("month(mov.data_movimentacao) asc");
        
        if ($realizado) {
            $select->where("mov.realizado = ?", $realizado);
        }
        
        $query = $this->fetchRow($select);
        return $query->total;
    }
    
}


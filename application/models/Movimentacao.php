<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Movimentacao
 *
 * @author Realter
 */
class Model_Movimentacao extends Zend_Db_Table {

    protected $_name = 'movimentacao';
    
    protected $_primary = 'id_movimentacao';
    
    /**
     *  busca os dados de uma movimentacao
     */
    public function getDadosMovimentacao($idMovimentacao, $id_usuario) {
        
        $select = $this->select()
                ->from(array('mov' => $this->_name), array(
                    '*'
                ))
                ->setIntegrityCheck(false)
                ->joinInner(array('tpm' => 'tipo_movimentacao'), 'mov.id_tipo_movimentacao = tpm.id_tipo_movimentacao', array(
                    'tpm.tipo_movimentacao'
                ))
                ->joinInner(array('cat' => 'categoria'), 'mov.id_categoria = cat.id_categoria', array(
                    'cat.descricao_categoria'
                ))
                ->where("mov.id_usuario = ?", $id_usuario)
                ->where("mov.id_movimentacao = ?", $idMovimentacao);
        
        return $this->fetchRow($select);
        
    }
    
    /**
     * retorna o total de despesa do mes
     */
    public function getTotalDespesaMesAtual($id_usuario) {
        $select = $this->select()
                ->from(array('mov' => $this->_name), array(
                    'total_despesa' => 'sum(mov.valor_movimentacao)'
                ))
                ->setIntegrityCheck(false)
                ->where("mov.id_usuario = ?", $id_usuario)
                ->where("mov.id_tipo_movimentacao in (2,3)")
                ->where("mov.realizado = 1")
                ->where("month(mov.data_movimentacao) = month(now())")
                ->where("year(mov.data_movimentacao) = year(now())");
        
        return $this->fetchRow($select);
    }

    /**
     * retorna o ultimo id
     */
    public function lastInsertId() {
        $select = $this->select()
                ->from($this->_name, array(
                    'last_id' => 'last_insert_id(id_movimentacao)'
                ))
                ->order("id_movimentacao desc")
                ->limit(1);
        
        $query = $this->fetchRow($select);
        
        return (int)$query->last_id;
    }
    
    /**
     * retorna as movimentacoes pendentes
     */
    public function getPendencias($id_usuario) {
        
        $select = $this->select()
                ->from($this->_name, array(
                    '*'
                ))
                ->where("realizado = ?", 0)
                ->where("id_tipo_movimentacao <> ?", 3)
                ->where("id_usuario = ?", $id_usuario)
                ->where("data_movimentacao < date_sub(now(), interval 1 day)")
                ->order("data_movimentacao asc");
        
        return $this->fetchAll($select);
        
    }
    
    public function getProximasReceitas($id_usuario) {
        $select = $this->select()
                ->from($this->_name, array(
                    '*'
                ))
                ->where("realizado = ?", 0)
                ->where("id_usuario = ?", $id_usuario)
                ->where("id_tipo_movimentacao = ?", 1)
                ->where("data_movimentacao >= now()")
                ->order("data_movimentacao asc")
                ->limit(3);
                
        return $this->fetchAll($select);
    }
    
    public function getProximasDespesas($id_usuario) {
        $select = $this->select()
                ->from($this->_name, array(
                    '*'
                ))
                ->where("realizado = ?", 0)
                ->where("id_usuario = ?", $id_usuario)
                ->where("id_tipo_movimentacao = ?", 2)
                ->where("data_movimentacao >= now()")
                ->order("data_movimentacao asc")
                ->limit(5);
                
        return $this->fetchAll($select);
    }
    
    /**
     * get media movimentacao categoria
     */
    public function getMediaMovimentacaoCategoria($id_categoria, $id_usuario) {
        
        $select = $this->select()
                ->from(array('mov' => $this->_name), array(
                    'mes' => 'month(mov.data_movimentacao)',
                    'ano' => 'year(mov.data_movimentacao)',
                    'total' => 'sum(mov.valor_movimentacao)'
                ))
                ->where("id_categoria = ?", $id_categoria)
                ->where("id_usuario = ?", $id_usuario)
                ->where("mov.id_tipo_movimentacao in (2,3)")
                ->group("month(mov.data_movimentacao)");
        
        return $this->fetchAll($select);			
        
    }
    
}


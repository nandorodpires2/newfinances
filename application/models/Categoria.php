<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Categoria
 *
 * @author Realter
 */
class Model_Categoria extends Zend_Db_Table {

    protected $_name = "categoria";
    
    protected $_primary = "id_categoria";
 
    /**
     * 
     */
    public function getCategoriasCadastradasMes($id_usuario) {
        
        $select = $this->select()
                ->from(array('cat' => $this->_name), array('*'))
                ->where("
                    cat.id_categoria not in (
                        select	met.id_categoria
                        from	meta met
                        where   met.id_usuario = {$id_usuario}
                                and met.mes_meta = month(now())
                                and met.ano_meta = year(now())
                                and met.id_categoria = cat.id_categoria
                    )
                ")
                ->where("cat.ativo_categoria = ?", 1)
                ->order("cat.descricao_categoria asc");
        
        return $this->fetchAll($select);
        
    }
    
    /**
     * retorna os gastos por categoria no mes
     */
    public function getGastosCategoriasMes($id_usuario) {
        $select = $this->select()
                ->from(array('cat' => 'categoria'), array(
                    'cat.id_categoria',
                    'cat.descricao_categoria'
                ))                
                ->setIntegrityCheck(false)
                ->joinInner(array('mov' => 'movimentacao'), 'cat.id_categoria = mov.id_categoria', array(
                    'total' => 'sum(mov.valor_movimentacao)'
                ))
                ->where("mov.id_usuario = ?", $id_usuario)                
                ->where("mov.realizado = ?", 1)
                ->where("mov.id_tipo_movimentacao in (2,3)")
                ->where("month(mov.data_movimentacao) = month(now())")
                ->where("year(mov.data_movimentacao) = year(now())")
                ->group("cat.id_categoria")
                ->order("sum(mov.valor_movimentacao) asc");
                
        return $this->fetchAll($select); 
    }
    
    /**
     * retorna os gastos por categoria no mes
     */
    public function getGastosCategoriaMes($id_usuario, $id_categoria) {
        $select = $this->select()
                ->from(array('cat' => 'categoria'), array(
                    'cat.descricao_categoria'
                ))                
                ->setIntegrityCheck(false)
                ->joinInner(array('mov' => 'movimentacao'), 'cat.id_categoria = mov.id_categoria', array(
                    '*'
                ))
                ->joinInner(array('tmv' => 'tipo_movimentacao'), 'mov.id_tipo_movimentacao = tmv.id_tipo_movimentacao', array(
                    'tmv.tipo_movimentacao'
                ))
                ->where("mov.id_usuario = ?", $id_usuario)
                ->where("mov.id_categoria = ?", $id_categoria)
                ->where("mov.realizado = ?", 1)
                ->where("mov.id_tipo_movimentacao in (2,3)")
                ->where("month(mov.data_movimentacao) = month(now())")
                ->where("year(mov.data_movimentacao) = year(now())")        
                ->order("mov.data_movimentacao asc");
        
        return $this->fetchAll($select); 
    }
    
    public function getTotalGastoCategoriaMes($id_categoria) {
        
        $id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario; 
        
        $select = $this->select()
                ->from(array('cat' => 'categoria'), array(
                    'cat.descricao_categoria'
                ))                
                ->setIntegrityCheck(false)
                ->joinInner(array('mov' => 'movimentacao'), 'cat.id_categoria = mov.id_categoria', array(
                    'total' => 'sum(mov.valor_movimentacao)'
                ))
                ->where("mov.id_usuario = ?", $id_usuario)                
                ->where("mov.realizado = ?", 1)
                ->where("mov.id_tipo_movimentacao in (2,3)")
                ->where("cat.id_categoria = ?", $id_categoria)
                ->where("month(mov.data_movimentacao) = month(now())")
                ->where("year(mov.data_movimentacao) = year(now())");
                
        return $this->fetchRow($select); 
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MovimentacaoRepeticao
 *
 * @author Realter
 */
class Model_MovimentacaoRepeticao extends Zend_Db_Table {

    protected $_name = "movimentacao_repeticao";
    
    protected $_primary = "id_movimentacao_repeticao";
 
    /**
     * 
     */
    public function getMovimentacoesProcessar($idUsuario) {
        $select = $this->select()
                ->from(array('mvr' => $this->_name), array(
                    'mvr.id_movimentacao_repeticao',
                    'mvr.id_movimentacao',
                    'mvr.tipo',
                    'mvr.modo',			
                ))
                ->setIntegrityCheck(false)
                ->joinInner(array('mov' => 'movimentacao'), 'mvr.id_movimentacao = mov.id_movimentacao', array(
                    'mov.descricao_movimentacao',
                    'mov.id_usuario',
                    'mov.realizado',
                    'mov.data_movimentacao',
                    'mov.data_inclusao',
                    'mov.id_tipo_movimentacao'
                ))
                ->where("mvr.processado = 0")
                ->where("mov.id_usuario = ?", $idUsuario);                
        
        return $this->fetchAll($select);
    }
    
    /**
     * recupera o ultimo id inserido
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
    
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conta
 *
 * @author Realter
 */
class Model_Conta extends Zend_Db_Table {

    protected $_name = "conta";
    
    protected $_primary = "id_conta";
    
    public function getContasUsuario($idUsuario) {
        $select = $this->select()
                ->from(array('ct' => $this->_name), array(
                    'ct.id_conta',
                    'ct.descricao_conta',
                    'ct.saldo_inicial'
                ))
                ->setIntegrityCheck(false)
                ->joinInner(array('tct' => 'tipo_conta'), 'ct.id_tipo_conta = tct.id_tipo_conta', array(
                    'tct.descricao_tipo_conta',
                ))
                ->joinLeft(array('ban' => 'banco'), 'ct.id_banco = ban.id_banco', array(
                    'ban.nome_banco'
                ))
                ->where("ct.ativo_conta = ?", 1)
                ->where("ct.id_usuario = ?", $idUsuario);
        
        return $this->fetchAll($select);
        
    }
    
    public function getLastId() {
        
        $select = $this->select()
                ->from($this->_name, array(
                    'last_id' => "last_insert_id(id_conta)"
                ))
                ->order("id_conta desc")
                ->limit(1);
                
        $query = $this->fetchRow($select);
        
        return $query->last_id;
        
    }
    
    /**
     * retorna o saldo das contas
     */
    public function getSaldoContas($id_usuario) {
        $select = $this->select()
                ->from(array('ct' => $this->_name), array(                    
                    'ct.id_conta',
                    'ct.descricao_conta'                    
                ))                
                ->setIntegrityCheck(false)
                ->joinLeft(array('mov' => 'movimentacao'), 'mov.id_conta = ct.id_conta and mov.id_usuario = ct.id_usuario', array(
                    'saldo' => "ifnull(sum(mov.valor_movimentacao), 0) + ct.saldo_inicial"
                ))                
                ->joinInner(array('tc' => 'tipo_conta'), 'ct.id_tipo_conta = tc.id_tipo_conta', array(
                    'tc.descricao_tipo_conta'
                ))
                ->where("mov.realizado <> 0 or mov.realizado is null")
                ->where("ct.id_usuario = ?", $id_usuario)
                ->where("ct.ativo_conta = 1")
                ->group("ct.id_conta")
                ->order("ct.descricao_conta asc");
        
        return $this->fetchAll($select);
    }
}


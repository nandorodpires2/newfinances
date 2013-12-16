<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VwLancamentoCartao
 *
 * @author Realter
 */
class Model_VwLancamentoCartao extends Zend_Db_Table {

    protected $_name = 'vw_lancamento_cartao';
    
    protected $_primary = 'id_cartao';
    
    /**
     * fatura(s) atual
     */
    public function getFaturasAtual($id_usuario) {
        
        $select = $this->select()
                ->from(array('vlc' => $this->_name), array(
                    'vlc.id_cartao',
                    'vlc.descricao_cartao',
                    'vencimento_fatura' => 'date(vlc.vencimento_fatura)',
                    'valor_fatura' => 'sum(vlc.valor_movimentacao)'
                ))                
                ->where("vlc.id_usuario = ?", $id_usuario)
                ->where("now() between vlc.inicio_fatura and vlc.fim_fatura")
                ->group("vlc.id_cartao")
                ->group("vlc.vencimento_fatura");
        
        return $this->fetchAll($select);
        
    }
    
    /**
     * fatura(s) atual
     */
    public function getProximaFaturaAtual($id_usuario) {
        
        $select = $this->select()
                ->from(array('vlc' => $this->_name), array(
                    'vlc.id_cartao',
                    'vlc.descricao_cartao',
                    'vencimento_fatura' => 'date(vlc.vencimento_fatura)',
                    'valor_fatura' => 'sum(vlc.valor_movimentacao)'
                ))                
                ->where("vlc.id_usuario = ?", $id_usuario)
                ->where("month(vlc.vencimento_fatura) = month(now()) + 1")
		->where("year(vlc.vencimento_fatura) = year(now())")
                ->group("vlc.id_cartao")
                ->group("vlc.vencimento_fatura");
        
        return $this->fetchAll($select);
        
    }
    
}


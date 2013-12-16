<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Plano
 *
 * @author Realter
 */
class Model_Plano extends Zend_Db_Table {

    protected $_name = "plano";
    
    protected $_primary = "id_plano";
    
    /**
     * retorna os planos
     */
    public function getPlanosUsuario() {
        
        $select = $this->select()
                ->from(array('p' => $this->_name), array(
                    'p.id_plano',
                    'p.descricao_plano',
                    'p.tempo_plano',
                    'p.unidade_tempo_plano',                   
                    'p.usuario_plano',
                    'pv.usuario',
                    'valor_mes' => "case
                        when (p.unidade_tempo_plano = 'meses') then
                            pv.valor_plano / p.tempo_plano
                        else 
                            pv.valor_plano / (12 * p.tempo_plano)
                    end"
                ))
                ->setIntegrityCheck(false)
                ->joinLeft(array('pv' => 'plano_valor'), 'p.id_plano = pv.id_plano', array(
                    'pv.valor_plano'
                ))
                ->where('p.usuario_plano = ?', 1)
                ->where('p.ativo_plano = ?', 1)
                ->where('pv.usuario = ?', 1);
        
        return $this->fetchAll($select);
        
    }
    
}


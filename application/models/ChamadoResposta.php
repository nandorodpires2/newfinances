<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChamadoResposta
 *
 * @author Fernando Rodrigues
 */
class Model_ChamadoResposta extends Zend_Db_Table {

    protected $_name = "chamado_resposta";
    
    protected $_primary = "id_chamado_resposta";
    
    /**
     * retorna as respostas do chamado
     */
    public function respostasChamado($id_chamado) {
        
        $select = $this->select()
                ->from(array('ch' => $this->_name), array(
                    '*'
                ))
                ->setIntegrityCheck(false)
                ->joinInner(array('u' => 'usuario'), 'ch.id_usuario = u.id_usuario', array(
                    'nome_completo'
                ))
                ->where('ch.id_chamado = ?', $id_chamado);
        
        return $this->fetchAll($select);
        
    }
    
}


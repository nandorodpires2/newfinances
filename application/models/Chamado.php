<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Chamado
 *
 * @author Fernando Rodrigues
 */
class Model_Chamado extends Zend_Db_Table {

    protected $_name = "chamado";
    
    protected $_primary = "id_chamado";
    
    /**
     * retorna os chamados
     */
    public function getChamados() {
            $select = $this->select()
                ->from(array('c' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('tc' => 'tipo_chamado'), 'c.id_tipo_chamado = tc.id_tipo_chamado', array('*'))
                ->joinInner(array('u' => 'usuario'), 'c.id_usuario = u.id_usuario', array(
                    'u.nome_completo',
                    'u.email_usuario',
                    'u.cpf_usuario'
                ))
                ->order("c.status asc");
        
        return $this->fetchAll($select);
    }

    /**
     * busca dados dos chamado
     */
    public function getDadosChamado($id_chamado) {
        
        $select = $this->select()
                ->from(array('c' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('tc' => 'tipo_chamado'), 'c.id_tipo_chamado = tc.id_tipo_chamado', array('*'))
                ->joinInner(array('u' => 'usuario'), 'c.id_usuario = u.id_usuario', array(
                    'u.nome_completo',
                    'u.email_usuario',
                    'u.cpf_usuario'
                ))
                ->where("c.id_chamado = ?", $id_chamado);
        
        return $this->fetchRow($select);
        
    }
    
}


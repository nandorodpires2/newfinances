<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlanoUsuario
 *
 * @author Realter
 */
class Model_UsuarioPlano extends Zend_Db_Table {

    protected $_name = "usuario_plano";
    
    protected $_primary = array(
        'id_usuario',
        'id_plano'
    );
    
    /**
     * qtde de usuarios por plano
     */
    public function getUsuariosAtivosPlano() {
        $select = $this->select()
                ->from(array('up' => $this->_name), array(''))
                ->setIntegrityCheck(false)
                ->joinInner(array('p' => 'plano'), 'up.id_plano = p.id_plano', array(
                    'p.descricao_plano'
                ))
                ->joinInner(array('u' => 'usuario'), 'up.id_usuario = u.id_usuario', array(
                    'nome_completo',
                    'email_usuario'
                ))
                ->joinInner(array('pv' => 'plano_valor'), 'up.id_plano_valor = pv.id_plano_valor', array(
                    'pv.valor_plano'
                ))
                ->where("up.ativo_plano = ?", 1);                
        
        return $this->fetchAll($select);
    }
    
    /**
     * retorno o plano atual do usuario
     */
    public function getPlanoAtual($id_usuario) {
        $select = $this->select()
                ->from(array('up' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('p' => 'plano'), 'up.id_plano = p.id_plano', array('*'))
                ->where("id_usuario =  ?", $id_usuario)
                ->where("up.ativo_plano = ?", 1)
                ->order("id_usuario_plano desc")
                ->limit(1);
        
        return $this->fetchRow($select);
    }
    
}


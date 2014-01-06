<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioLogin
 *
 * @author Realter
 */
class Model_UsuarioLogin extends Zend_Db_Table {

    protected $_name = "usuario_login";
    
    protected $_primary = "id_usuario_login";
    
    /**
     * retorna o total de acessos dos usuarios
     */
    public function getTotalAcessos($id_usuario) {
        
        $select = $this->select()
                ->from(array('usl' => $this->_name), array(
                    'ultimo' => 'max(usl.data)',
                    'total' => 'count(usl.id_usuario_login)'
                ))
                ->setIntegrityCheck(false)
                ->joinInner(array('usu'=> 'usuario'), 'usl.id_usuario = usu.id_usuario', array(
                    'usu.nome_completo'
                ))
                ->where("usl.id_usuario = ?", $id_usuario);
        
        return $this->fetchRow($select);
        
    }
    
}


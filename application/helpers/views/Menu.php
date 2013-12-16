<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu
 *
 * @author Realter
 */
class View_Helper_Menu extends Zend_View_Helper_Abstract {
    
    public static function setViewMenuPosicao($id_posicao) {
    
        $auth = Zend_Auth::getInstance()->getIdentity();        
        $modelFuncionalidade = new Model_Funcionalidade();
        
        $where = "
            id_menu_posicao = {$id_posicao}
            and ativo_menu = 1
        ";
        
        if ($auth->id_plano != 7) {            
            $where .= " and module <> 'gestor'";
        }
        
        $menusPosicao = $modelFuncionalidade->fetchAll($where);
        
        return $menusPosicao;
        
    }
    
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Application
 *
 * @author Realter
 */
class Controller_Helper_Application extends Zend_Controller_Action_Helper_Abstract {

    public static function hasConta() {
     
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $modelConta = new Model_Conta();
            $id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;

            // verifica se tem pelo menos uma conta cadastrada
            $contas = $modelConta->fetchAll("
                ativo_conta = 1 
                and id_usuario = {$id_usuario}
            ");

            if ($contas->count() > 0) {
                return true;
            }
            return false;
        }
        
    }    
    
    /**
     * retorna o cpf sem ponto e virgula
     */
    public static function formatCPF($cpf) {        
        return $cpf_only_number = str_replace(array('.', '-'), '', $cpf);
    }
    
}


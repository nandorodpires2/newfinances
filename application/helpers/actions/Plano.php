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
class Controller_Helper_Plano extends Zend_Controller_Action_Helper_Abstract {

    const PLANO_BASICO = 8;
    const PLANO_30_DIAS = 2;
    const PLANO_TRIMESTRAL = 3;
    const PLANO_SEMESTRAL = 4;
    const PLANO_ANUAL = 5;

    /**
     * verifica se o usuario pode experimentar 30 dias do sistema
     */
    public static function canExperience(Zend_Db_Table_Row $dadosPlanoUsuario) {

        $modelUsuarioPlano = new Model_UsuarioPlano();
        
        // verificar se esta no plano de experiencia
        if ($dadosPlanoUsuario->id_plano == self::PLANO_30_DIAS) {
            return false;
        } else {            
            // verifica se esta em um plano pago
            if (in_array($dadosPlanoUsuario->id_plano, array(self::PLANO_TRIMESTRAL, self::PLANO_SEMESTRAL, self::PLANO_ANUAL))) {
                return false;
            } else {            
                // verifica se ele ja usuou o plano de experiencia
                $experience = $modelUsuarioPlano->fetchRow("
                    id_usuario = {$dadosPlanoUsuario->id_usuario}
                    and id_plano = " . self::PLANO_30_DIAS
                );

                // casos nao tenha usado
                if ($experience === null) {
                    return true;
                } 
                return false;
            }
        }
        
    }
    
}


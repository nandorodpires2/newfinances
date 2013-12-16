<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Messeges
 *
 * @author Realter
 */
class Controller_Helper_Messeges extends Zend_Controller_Action_Helper_Abstract {

    public static function setMesseges(array $messeges) {
        
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $flashMessenger->addMessage($messeges);
        
    }
    
    public static function getMesseges() {
        
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        return $messages;
        
    }
    
}


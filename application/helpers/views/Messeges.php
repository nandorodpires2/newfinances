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
class View_Helper_Messeges extends Zend_View_Helper_Abstract {

    const TYPE_SUCCESS = "success";    
    const TYPE_ALERT = "alert";    
    const TYPE_ERROR = "error";

    public static function getMessages($messages) {
        
        $html_message = "";
        if (is_array($messages)) {
            foreach ($messages as $message) {

                if (array_key_exists(self::TYPE_SUCCESS, $message)) {                
                    $html_message .= "
                        <div class='message-success'>
                            <span>{$message['success']}</span>
                        </div>
                    ";
                }

                if (array_key_exists(self::TYPE_ALERT, $message)) {
                    $html_message .= "
                        <div class='message-alert'>
                            <span>{$message['alert']}</span>
                        </div>
                    ";
                }

                if (array_key_exists(self::TYPE_ERROR, $message)) {
                    $html_message .= "
                        <div class='message-error'>
                            <span>{$message['error']}</span>
                        </div>
                    ";
                }
            }
         
        }
        
        return $html_message;
        
    }
    
}


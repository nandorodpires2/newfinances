<?php

    require 'index.php';
    
    echo "Início: " . date('Y-m-d H:m:s') . "<br />";
    
    try {
        
        // create mail object
       $mail = new Zend_Mail('utf-8');
       $mail->setBodyHtml("Testando o e-mail");
       $mail->setFrom('noreply@newfinances.com.br', 'NewFinances - Controle Financeiro');
       $mail->addTo("nandorodpires@gmail.com");            
       $mail->setSubject('Notificação');
       $mail->send(Zend_Registry::get('mail_transport'));
    
    } catch (Zend_Mail_Exception $mail_error) {
        echo $mail_error->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    
    echo "Fim: " . date('Y-m-d H:m:s') . "<br />";

?>

<?php

    require 'index.php';
    
    $data_inicio = date("d/m/Y H:i:s");
    
    // render view
    $bodyText = "<p>Teste</p>";
    
    try {
        
        // create mail object
       $mail = new Zend_Mail('utf-8');
       $mail->setBodyHtml($bodyText);
       $mail->setFrom('noreply@newfinances.com.br', 'NewFinances - Controle Financeiro');
       $mail->addTo("nandorodpires@gmail.com");            
       $mail->setSubject('Notificação');
       $mail->send(Zend_Registry::get('mail_transport'));
    
    } catch (Zend_Mail_Exception $mail_error) {
        echo $mail_error->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

?>

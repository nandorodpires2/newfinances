<?php

    require 'index.php';
    
    $data_inicio = date("d/m/Y H:i:s");
    
    $html = new Zend_View();
    $html->setScriptPath(APPLICATION_PATH . '/modules/cliente/views/emails/crons/');

    // assign values
    $html->assign('data_inicio', $data_inicio);

    // render view
    $bodyText = $html->render('teste.phtml');
    
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
    
    echo "Fim: " . date('Y-m-d H:m:s') . "<br />";

?>

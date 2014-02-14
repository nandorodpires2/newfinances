<?php

    require 'index.php';
    
    $data_inicio = date("d/m/Y H:i:s");
    
    $html = new Zend_View();
    $html->setScriptPath(APPLICATION_PATH . '/modules/cliente/views/emails/crons/');

    $last_id = 42;
    
    // assign values
    $html->assign('nome_completo', "Fernando Rodrigues");    
    $html->assign('url_ativar', "http://newfinances.com.br/usuarios/ativar-usuario/id_usuario/{$last_id}");

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

?>

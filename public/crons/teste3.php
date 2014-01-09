<?php 

    require 'teste.php';

    $data_inicio = date('Y-m-d H:m:s');
    
    $sql = "select now() as data";
    
    $result = $db->fetchRow($sql);
    
    $data = $result['data'];
    
    // create mail object
    $mail = new Zend_Mail('utf-8');
    $mail->setBodyHtml($data);
    $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
    $mail->addTo("nandorodpires@gmail.com");            
    $mail->setSubject('Teste 3');
    $mail->send(Zend_Registry::get('mail_transport'));
    
?>
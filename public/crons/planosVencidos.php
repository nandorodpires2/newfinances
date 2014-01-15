<?php

    require 'index.php';
    
    $data_inicio = date('Y-m-d H:m:s');

    // busca todos os planos que expiraram
    $sql = "
        select	*
        from	usuario_plano       up
                inner join usuario  u on up.id_usuario = u.id_usuario
        where	up.data_encerramento <= now()
                and ativo_plano = 1
    ";
    
    $planos = $db->fetchAll($sql);
    
    $count = 0;    
    foreach ($planos as $plano) {
                
        // dasativa os planos
        $sql_update = "update usuario_plano set ativo_plano = 0 where id_usuario_plano = {$plano['id_usuario_plano']}";
        $db->query($sql_update);
        
        // altera os planos para o plano basico
        $sql_novo_plano = "
            insert  into usuario_plano
            values  (
                null,
                {$plano['id_usuario']},
                8,
                now(),
                null,
                1,
                9                
            )
        ";

        $db->query($sql_novo_plano);
        
        $body = "
            <p>Prezado(a) {$plano['nome_completo']}</p>
            <p>seu plano expirou e agora você está cadastrado(a) no plano básico</p>
        ";
        
        // envia os emails para os usuarios
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($body);
        $mail->setFrom('noreply@newfinances.com.br', 'NewFinances - Controle Financeiro');
        $mail->addTo($plano['email_usuario']);            
        $mail->setSubject("Plano expirado");
        $mail->send(Zend_Registry::get('mail_transport'));
        
        $count++;
    }
    
    $data_fim = date('Y-m-d H:m:s');
    
    // grava os resultados na tabela de cron
    $sql_insert = "
        insert  into cron
        values  (
            null,
            'cron_planos_vencidos',
            '{$data_inicio}',
            '{$data_fim}',
            {$count}
        )
    ";
            
    $db->query($sql_insert);
    echo "Rows: " . $count . "<br />"; 
    
?>

<?php

    require 'index.php';
    
    $data_inicio = date('Y-m-d H:m:s');
            
    $sql = "
        select	mov.id_movimentacao,
                usu.nome_completo,
                usu.email_usuario,
                date_format(mov.data_movimentacao, '%d/%m/%Y') as data_movimentacao,
                tmv.tipo_movimentacao,
                mov.descricao_movimentacao,
                mov.valor_movimentacao
        from	movimentacao 			mov
                inner join usuario		usu on mov.id_usuario = usu.id_usuario
                inner join tipo_movimentacao	tmv on mov.id_tipo_movimentacao = tmv.id_tipo_movimentacao
        where	mov.realizado = 0
                and mov.data_movimentacao = date(date_add(now(), interval 2 day))
    ";
    
    $notificacoes = $db->fetchAll($sql);
    
    $count = 0;
    $errors = 0;
    foreach ($notificacoes as $notificacao) {
        
        
    
        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/modules/cliente/views/emails/crons/');

        // assign values
        $html->assign('nome_completo', $notificacao['nome_completo']);
        $html->assign('data', $notificacao['data_movimentacao']);
        $html->assign('tipo', $notificacao['tipo_movimentacao']);
        $html->assign('descricao', $notificacao['descricao_movimentacao']);
        $html->assign('valor', $notificacao['valor_movimentacao']);

        // render view
        $bodyText = $html->render('lancamentos.phtml');

        // create mail object
        $mail = new Zend_Mail('utf-8');
        $mail->setBodyHtml($bodyText);
        $mail->setFrom('noreply@newfinances.com.br', 'NewFinances - Controle Financeiro');
        $mail->addTo($notificacao['email_usuario']);            
        $mail->setSubject('Notificação: ' . $notificacao['descricao_movimentacao']);
        $mail->send(Zend_Registry::get('mail_transport'));

        $count++;
        
    }
    
    $data_fim = date('Y-m-d H:m:s');
    
    // grava os resultados na tabela de cron
    $sql_insert = "
        insert  into cron
        values  (
            null,
            'cron_emails_lancamentos_a_vencer',
            '{$data_inicio}',
            '{$data_fim}',
            {$count}
        )
    ";
    $db->query($sql_insert);
            
?>
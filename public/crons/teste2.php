<?php

    require 'teste.php';
    
    $data_inicio = date('Y-m-d H:m:s');
    
    $currency = new Zend_Currency();
    $date = new Zend_Date(null, null, "pt_BR");
    
    $sql = "update movimentacao set notificado = 1 where id_movimentacao = 993";
        
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
    foreach ($notificacoes as $key => $notificacao) {
        
        $valor = $currency->toCurrency($notificacao['valor_movimentacao']);
        
        $body = "
            <p><h3>NOTIFICAÇÃO DE LANÇAMENTO</h3></p>
            <p>Dados:</p>
            Data: {$notificacao['data_movimentacao']}<br/>
            Tipo: {$notificacao['tipo_movimentacao']}<br/>
            Descrição: {$notificacao['descricao_movimentacao']}<br/>
            Valor: {$valor}<br/>
        ";
    
        try {    
            // create mail object
            $mail = new Zend_Mail('utf-8');
            $mail->setBodyHtml($body);
            $mail->setFrom('email@portal.redemorar.com.br', 'NewFinances - Controle Financeiro');
            $mail->addTo($notificacao['email_usuario']);            
            $mail->setSubject('Notificação');
            $mail->send(Zend_Registry::get('mail_transport'));
            
            $count++;
                
        } catch (Exception $error) {
            echo $error->getMessage();
        }
    
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
    echo "success";
        
?>
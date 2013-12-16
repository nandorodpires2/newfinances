<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Plano
 *
 * @author Realter
 */
class Plugin_Plano extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        // verifica se existe um usuario logado
        if (Zend_Auth::getInstance()->hasIdentity()) {
            
            $id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;
            // verifica se o plano do usuario expirou
            $modelUsuarioPlano = new Model_UsuarioPlano();
            $modelPlano = new Model_Plano();
            
            $planoUsuario = $modelUsuarioPlano->fetchRow("id_usuario = {$id_usuario} and ativo_plano = 1", "id_usuario_plano desc");
            $plano = $modelPlano->fetchRow("id_plano = {$planoUsuario->id_plano} and ativo_plano = 1");
            
            // caso tenha expirado inativa o plano atual e cadastra no plano básico
            if ($this->verificaDataEncerramento($planoUsuario->data_encerramento)) {                
                $dadosInativacaoPlano['ativo_plano'] = 0;
                $where_inativacao = "id_usuario_plano = " . $planoUsuario->id_usuario_plano;
                $modelUsuarioPlano->update($dadosInativacaoPlano, $where_inativacao);
                
                // insere o usuario no plano basico
                $dadosPlanoBasico['id_usuario'] = $id_usuario;
                $dadosPlanoBasico['id_plano'] = 8;
                $dadosPlanoBasico['data_aderido'] = Controller_Helper_Date::getDatetimeNowDb();
                $dadosPlanoBasico['data_encerramento'] = null;
                $dadosPlanoBasico['ativo_plano'] = 1;
                $dadosPlanoBasico['id_plano_valor'] = 9;
                
                try {
                    $modelUsuarioPlano->insert($dadosPlanoBasico);   
                    
                    // envia o email informando a troca do plano
                    $mail = new Zend_Mail('utf-8');

                    $mail->setBodyHtml("O seu plano expirou! Agora você estpa cadastrado no plano básico");
                    $mail->setFrom('email@portal.redemorar.com.br', 'NewFinances - Controle Financeiro');
                    $mail->addTo("nandorodpires@gmail.com");
                    //$mail->addTo('tiago@realter.com.br');
                    //$mail->setReplyTo('email@portal.redemorar.com.br');
                    $mail->setSubject('Plano expirado');

                    $mail->send(Zend_Registry::get('mail_transport'));
                    
                    // atualiza o plano no Auth
                    Zend_Auth::getInstance()->getIdentity()->id_plano = 8;                    
                    Zend_Auth::getInstance()->getIdentity()->descricao_plano = $plano->descricao_plano;
                    
                    
                } catch (Zend_Exception $error) {
                    echo $error->getMessage();
                    die();
                }
            }
        }
        
    }
    
    /**
     * verifica se o plano ainda esta ativo
     * 
     * @param type $data_encerramento
     * @return boolean
     */
    protected function verificaDataEncerramento($data_encerramento) {
        
        $zendDate = new Zend_Date();
        
        if ($data_encerramento && $zendDate->isLater($data_encerramento)) {
            return true;
        }
        return false;
        
    }
    
}


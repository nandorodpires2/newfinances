<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dia
 *
 * @author Realter
 */
class Form_Dia extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formDia')
                ->setMethod('post');
        
        // dia
        $this->addElement("select", "dia", array(
            'label' => 'Ir para o dia: ',
            'multioptions' => $this->getDiasMes(),
            'decorators' => array(
                'ViewHelper',
                'Description',
                'Errors',                
                array(array('td' => 'HtmlTag'), array('tag' => 'td')),
                array('Label', array('tag' => 'td')),
            )
        ));
        
    }
    
    /**
     * retorna os dias do mes
     */
    protected function getDiasMes() {
        $zendDate = new Zend_Date();
        $qtde_dias_mes = (int)$zendDate->get(Zend_Date::MONTH_DAYS);
        
        $dias_mes = array();
        for ($i = 1; $i <= $qtde_dias_mes; $i++) {
            $dias_mes[$i] = $i;
        }
        
        return $dias_mes;
    }
    
}


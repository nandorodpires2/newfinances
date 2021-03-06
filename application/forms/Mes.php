<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mes
 *
 * @author Realter
 */
class Form_Mes extends Zend_Form {

    public function init() {
        
        $this->setAttrib('id', 'formMes')
                ->setMethod("post");
        
        // ano
        $this->addElement("select", "ano", array(
            'label' => 'Ano: ',
            'multioptions' => array(
                '2012' => '2012',
                '2013' => '2013',
                '2014' => '2014'
            ),
            'value' => date('Y'),
            'decorators' => array(
                'ViewHelper',
                'Description',
                'Errors',                
                array(array('td' => 'HtmlTag'), array('tag' => 'td')),
                array('Label', array('tag' => 'td')),
            )
        ));
        
        // mes
        $this->addElement("select", "mes", array(
            'label' => 'Mês: ',
            'multioptions' => $this->getMonthsYear(),
            'decorators' => array(
                'ViewHelper',
                'Description',
                'Errors',                
                array(array('td' => 'HtmlTag'), array('tag' => 'td')),
                array('Label', array('tag' => 'td')),
            )
        ));
        
    }
    
    protected function getMonthsYear() {
        
        $multiOptions = array(
            '01' => 'janeiro',
            '02' => 'fevereiro',
            '03' => 'março',
            '04' => 'abril',
            '05' => 'maio',
            '06' => 'junho',
            '07' => 'julho',
            '08' => 'agosto',
            '09' => 'setembro',
            '10' => 'outubro',
            '11' => 'novembro',
            '12' => 'dezembro'
        );      
        
        return $multiOptions;
    }
    
}


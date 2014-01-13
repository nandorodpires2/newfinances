<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Despesa
 *
 * @author Realter
 */
class Form_Movimentacoes_Despesa extends Zend_Form {

    public function init() {
        
        $formDefault = new Form_Default();
        $zendDate = new Zend_Date();
        
        $this->setAttrib('id', 'form_movimentacoes_despesa')
            ->setMethod('post');        
        
        
        // id_usuario (hidden)
        $this->addElement("hidden", "id_usuario", array(
            'value' => $formDefault->id_usuario
        ));
        
        // descricao
        $this->addElement("text", "descricao_movimentacao", array(
            'label' => 'Descrição: ',
            'required' => true
        ));
        
        // valor
        $this->addElement("text", "valor_movimentacao", array(
            'label' => 'Valor: ',
            'required' => true
        ));
        
        // data
        $this->addElement("text", "data_movimentacao", array(
            'label' => 'Data: ',
            'value' => $zendDate->get(Zend_Date::DATE_MEDIUM),
            'required' => true
        ));
        
        // tipo pagamento
        $this->addElement("radio", "tipo_pgto", array(
            'label' => 'Pagamento: ',
            'multioptions' => array(
                'conta' => 'Conta',
                'cartao' => 'Cartão de Crédito'
            ),
            'required' => true
        ));
        
        // conta
        $this->addElement("select", "id_conta", array(
            'label' => 'Conta: ',
            'multioptions' => $formDefault->getContasUsuario(1)
        ));
        
        // cartao credito
        $this->addElement("select", "id_cartao", array(
            'label' => 'Cartão: ',
            'multioptions' => $formDefault->getCartoesUsuario(1)
        ));
        
        // categoria
        $this->addElement("select", "id_categoria", array(
            'label' => 'Categoria',
            'multioptions' => $formDefault->getCategorias(),
            'value' => 9,
            'required' => true
        ));
        
        // option parcelar
        $this->addElement("checkbox", "opt_repetir", array(
            'label' => 'Repetir: '
        ));        
        
        // modo repeticao
        $this->addElement("radio", "modo_repeticao", array(
            'label' => 'Tipo: ',
            'multioptions' => array(
                'fixo' => 'despesa fixa',
                'parcelado' => 'compra parcelada'
            )
        ));        
        
        // parcelas
        $this->addElement("select", "parcelas", array(
            'label' => 'Parcelas: ',
            'multioptions' => array(                
                2 => '2X',
                3 => '3X',
                4 => '4X',
                5 => '5X',
                6 => '6X',
                7 => '7X',
                8 => '8X',
                9 => '9X',
                10 => '10X',
                11 => '11X',
                12 => '12X',
                360 => '360X'
            )
        ));
        
        // repetir                
        $this->addElement("select", "repetir", array(
            'label' => 'Modo: ',
            'multioptions' => array(
                'day' => 'Diário',
                'week' => 'Semanal',
                'month' => 'Mensal',
                'year' => 'Anual'
            )
        ));
        
        // submit
        $this->addElement("submit", "submit", array(
            'label' => 'Salvar',            
            'class' => 'submit'
        ));        
        
    }
    
    protected function decorator() {
        
        return array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('td' => 'HtmlTag'), array('tag' => 'td')),
            array('Label', array('tag' => 'td')),
        );
    }
    
}


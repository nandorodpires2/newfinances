<?php

    if (isset ($_POST['submit'])) {
        
        // moedas
        $totais['coins']['total_five_cents'] = $_POST['5-cents'] * 0.05;
        $totais['coins']['total_ten_cents'] = $_POST['10-cents'] * 0.10;
        $totais['coins']['total_twenty_five_cents'] = $_POST['25-cents'] * 0.25;
        $totais['coins']['total_fifty_cents'] = $_POST['50-cents'] * 0.50;
        $totais['coins']['total_one_real'] = $_POST['1-real'] * 1.00;
        
        // notas
        $totais['bills']['total_two_real'] = $_POST['2-real'] * 2.00;
        $totais['bills']['total_five_real'] = $_POST['5-real'] * 5.00;
        $totais['bills']['total_ten_real'] = $_POST['10-real'] * 10.00;
        $totais['bills']['total_twenty_real'] = $_POST['20-real'] * 20.00;
        $totais['bills']['total_fifty_real'] = $_POST['50-real'] * 50.00;
        $totais['bills']['total_one_hundred_real'] = $_POST['100-real'] * 100.00;
        
        // total de moedas e notas
        foreach ($totais as $key => $value) {
            if ($key === 'coins') {
                $totais['total_coins'] = array_sum($value);
            } else {
                $totais['total_bills'] = array_sum($value);
            }
        }        
        
        // total geral
        $totais['total_money'] = $totais['total_coins'] + $totais['total_bills'];
                
    }
    
?>

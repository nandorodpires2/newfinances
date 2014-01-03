<?php include 'calc-money.php'; ?>
<html lang="pt-br">
    <head>       
        <title>Calc Money</title>
        <meta charset="UTF-8">
        <style>
            
            body { background: url(img/bg_gray_noise.png) repeat; }
            
            * { font-family: Helvetica; }
            input[type='text'] { width: 50px; height: 30px; text-align: center; }
            input[type='submit'], button {                
                color: #fff;
                background-color: #0068B4;
                box-shadow: 1px 1px 1px #999;
                border-radius: 4px;        
                padding: 5px 20px;
                text-decoration: none;
                box-sizing: border-box;
                font-family: Helvetica, Arial, sans-serif;
                font-size: 12px;
                border: 0px;    
            }            
            img.moedas { width: 40px; }
            img.notas { width: 70px; }
            
            table { width: 96%; margin: 7px 2%; background: #FFFAFA; border: 1px solid #000; box-shadow: 10px 10px 5px #888888; }
            table th.head { font-size: 20px; color: #FFF; padding: 4px 0; background: #27408B; }
            table th.type-money { background: #4682B4;  color: #FFFFF0; }
            table th.value-maney { border: 1px solid #DCDCDC; }
            table td.qtde-money { border: 1px solid #DCDCDC; }
            table th.total-qtde-money { border: 1px solid #DCDCDC; }
            table th.total-coins { font-size: 20px; font-family: Time New Roman; color: #FFFFF0; background: #4682B4; padding: 5px 0; }
            table th.total-bills { font-size: 20px; font-family: Time New Roman; color: #FFFFF0; background: #4682B4; padding: 5px 0; }
            table th.total-money { font-size: 23px; font-family: Time New Roman; background: #27408B; color: #FFFFF0; padding: 7px 0; }
            
            table tr.submit-bar { background: #DCDCDC; }
            table td.submit-bar { border-top: 1px solid #000; }
        </style>
        <script type="text/javascript" language="javascript">
            window.onload  = function() {
                document.getElementById("5-cents").focus();
            }            
        </script>
    </head>
    <body>
        <form method="post">
            <table>
                <tr>
                    <th class="head" colspan="11">CALCULADOR DE DINHEIRO</th>
                </tr>
                <tr>
                    <th class="type-money" colspan="5">MOEDAS</th>
                    <th class="type-money" colspan="6">NOTAS</th>
                </tr>
                <tr>
                    <th class="value-maney"><img class="moedas" src="img/5-cents.png" /></th>
                    <th class="value-maney"><img class="moedas" src="img/10-cents.png" /></th>
                    <th class="value-maney"><img class="moedas" src="img/25-cents.png" /></th>
                    <th class="value-maney"><img class="moedas" src="img/50-cents.png" /></th>
                    <th class="value-maney"><img class="moedas" src="img/1-real.png" /></th>
                    <th class="value-maney"><img class="notas" src="img/2-real.png" /></th>
                    <th class="value-maney"><img class="notas" src="img/5-real.png" /></th>
                    <th class="value-maney"><img class="notas" src="img/10-real.png" /></th>
                    <th class="value-maney"><img class="notas" src="img/20-real.png" /></th>
                    <th class="value-maney"><img class="notas" src="img/50-real.png" /></th>
                    <th class="value-maney"><img class="notas" src="img/100-real.png" /></th>
                </tr>
                <tr align="center">
                    <td class="qtde-money"><input type="text" name="5-cents" id="5-cents" value="<?php echo isset($_POST['5-cents']) ? $_POST['5-cents'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="10-cents" value="<?php echo isset($_POST['10-cents']) ? $_POST['10-cents'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="25-cents" value="<?php echo isset($_POST['25-cents']) ? $_POST['25-cents'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="50-cents" value="<?php echo isset($_POST['50-cents']) ? $_POST['50-cents'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="1-real" value="<?php echo isset($_POST['1-real']) ? $_POST['1-real'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="2-real" value="<?php echo isset($_POST['2-real']) ? $_POST['2-real'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="5-real" value="<?php echo isset($_POST['5-real']) ? $_POST['5-real'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="10-real" value="<?php echo isset($_POST['10-real']) ? $_POST['10-real'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="20-real" value="<?php echo isset($_POST['20-real']) ? $_POST['20-real'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="50-real" value="<?php echo isset($_POST['50-real']) ? $_POST['50-real'] : ''; ?>" /></td>
                    <td class="qtde-money"><input type="text" name="100-real" value="<?php echo isset($_POST['100-real']) ? $_POST['100-real'] : ''; ?>" /></td>
                </tr>
                <?php if (isset ($_POST['submit'])) : ?>
                <tr>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['coins']['total_five_cents'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['coins']['total_ten_cents'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['coins']['total_twenty_five_cents'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['coins']['total_fifty_cents'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['coins']['total_one_real'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['bills']['total_two_real'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['bills']['total_five_real'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['bills']['total_ten_real'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['bills']['total_twenty_real'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['bills']['total_fifty_real'], 2, ',', '.'); ?></th>
                    <th class="total-qtde-money">R$<?php echo number_format($totais['bills']['total_one_hundred_real'], 2, ',', '.'); ?></th>
                </tr>
                <tr align="left">
                    <th class="total-coins" colspan="5">TOTAL MOEDAS: R$<?php echo number_format($totais['total_coins'], 2, ',', '.'); ?></th>
                    <th class="total-bills" colspan="6">TOTAL NOTAS: R$<?php echo number_format($totais['total_bills'], 2, ',', '.'); ?></th>
                </tr>
                <tr align="left">
                    <th class="total-money" colspan="11">TOTAL DINHEIRO: R$<?php echo number_format($totais['total_money'], 2, ',', '.'); ?></th>                    
                </tr>
                <?php endif; ?>
                <tr class="submit-bar">
                    <td class="submit-bar" colspan="11" align="right">
                        <button type="button" id="limpar" onclick="javascript:location.href='index.php'">Limpar</button>
                        <input type="submit" name="submit" value="Calcular" />
                    </td>
                </tr>
            </table>
        </form>        
    </body>
</html>
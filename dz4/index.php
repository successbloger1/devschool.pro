<?php
/*
 * Следующие задания требуется воспринимать как ТЗ (Техническое задание)
 * p.s. Разработчик, помни! 
 * Лучше уточнить ТЗ перед выполнением у заказчика, если ты что-то не понял, чем сделать, переделать, потерять время, деньги, нервы, репутацию.
 * Не забывай о навыках коммуникации :)
 * 
 * Задание 1
 * - Вы проектируете интернет магазин. Посетитель на вашем сайте создал следующий заказ (цена, количество в заказе и остаток на складе генерируются автоматически):
 */
$ini_string='
[игрушка мягкая мишка белый]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[одежда детская куртка синяя синтепон]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';
    
[игрушка детская велосипед]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';

';
$bd = parse_ini_string($ini_string, true);

/*
 * 
 * - Вам нужно вывести корзину для покупателя, где указать: 
 * 1) Перечень заказанных товаров, их цену, кол-во и остаток на складе
 * 2) В секции ИТОГО должно быть указано: сколько всего наименовний было заказано, каково общее количество товара, какова общая сумма заказа
 * - Вам нужно сделать секцию "Уведомления", где необходимо извещать покупателя о том, что нужного количества товара не оказалось на складе
 * - Вам нужно сделать секцию "Скидки", где известить покупателя о том, что если он заказал "игрушка детская велосипед" в количестве >=3 штук, 
 * то на эту позицию ему автоматически дается скидка 30% (соответственно цены в корзине пересчитываются тоже автоматически)
 * 3) у каждого товара есть автоматически генерируемый скидочный купон diskont, используйте переменную функцию, чтобы делать скидку на итоговую 
 * цену в корзине diskont0 = скидок нет, diskont1 = 10%, diskont2 = 20%
 * 
 * В коде должно быть использовано:
 * - не менее одной функции
 * - не менее одного параметра для функции
 * операторы if, else, switch
 * статические и глобальные переменные в теле функции
 * 

 */

$info = array(
        'sklad_ok' => '',
        'discount_flag' => 0,
        'kolichestvo' => 0,
        'itogo' => 0,
        'i' => 0
);

function sklad_info($key, $value, &$total_out = 0) {
    global $info;
    static $total = 0;
    
    if ($value['осталось на складе'] == 0){
        $info['sklad_ok'].='К сожалению, товар <i>"'.$key.'"</i> закончился на складе.<br>'; 
    } elseif ($value['осталось на складе'] < $value['количество заказано']) {
        $info['sklad_ok'].='Товара <i>"'.$key.'"</i> на складе меньше, чем Вы заказали на '
                 .($value['количество заказано'] - $value['осталось на складе'])
                 .' шт. <br>';
        $info['kolichestvo'] += $value['осталось на складе'];
        $total += ($value['осталось на складе'] * $value['цена']) - (($value['осталось на складе'] * $value['цена']) * $info['discount_flag']);
        $total_out = $total;
    } else {
        $info['kolichestvo'] += $value['количество заказано'];
        $total += (($value['количество заказано'] * $value['цена']) - (($value['количество заказано'] * $value['цена']) * $info['discount_flag']));
        $total_out = $total;
    }
}

function discount_article($key, $value) {
    global $info;
    
    if (($key=='игрушка детская велосипед')&&($value['количество заказано']>=3)
        &&($value['количество заказано']<=$value['осталось на складе']))
    {
        $info['discount_flag'] = 0.3;
        return $info['discount_flag']*100;
    } else {
        switch ($value['diskont']) {
            case 'diskont0':
                $info['discount_flag'] = 0;
                return $info['discount_flag']*100;
                break;
            case 'diskont1':
                $info['discount_flag'] = 0.1;
                return $info['discount_flag']*100;
                break;
            case 'diskont2':
                $info['discount_flag'] = 0.2;
                return $info['discount_flag']*100;
                break;
            default :
                break;
        }
    }
}

?>

<table border="1">
    <caption>ВАШ ЗАКАЗ: </caption>
    <tr>
        <th style="background-color:grey;" align="center">№</th>
        <th style="background-color:grey;" align="center">Наименование</th>
        <th style="background-color:grey;" align="center">Цена, $</th>
        <th style="background-color:grey;" align="center">Количество, шт.</th>
        <th style="background-color:grey;" align="center">На складе, шт.</th>
        <th style="background-color:grey;" align="center">Скидка, %</th>
    </tr>
    
<?php

foreach ($bd as $key => $value) {
    
    ++$info['i'];
    
?>
    
    <tr>
        <td align="center"><?=$info['i']; ?></td>
        <td align="left"><?=$key; ?></td>
        <td align="center"><?=$value['цена']; ?></td>
        <td align="center"><?=$value['количество заказано']; ?></td>
        <td align="center"><?=$value['осталось на складе']; ?></td>
        <td align="center"><?=discount_article($key, $value); ?></td>
    </tr>  
  
<?php    

sklad_info($key, $value, $info['itogo']);
    
}

?>   
</table>

<?php

echo '<h3>ИТОГО:</h3>';
echo 'Заказано '.$info['i'].' товара в общем количестве '.$info['kolichestvo'].' шт. В сумме к оплате <b>'.$info['itogo']. ' $</b>'; 

if ($info['sklad_ok']){
    echo '<h3>Уведомления:</h3>';
    echo $info['sklad_ok'];
}

?>
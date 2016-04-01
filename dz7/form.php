<!DOCTYPE html>    
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <style type="text/css">

.field {clear:both; text-align:right; line-height:25px;}
label {
    float:left; 
    padding-right:0px;
}
.main {float:left}

textarea {
width: 200px;
height: 100px;
}
        .formLabelAuto {
        width: auto;
        float: none;
        position: static;
        }

        #mr {
        margin-left: 40px;
        }
        #dispatch {
        margin-left: 0px;
        }
        span {
        color: red;
        }

        </style>    
    </head>

<body> 

    <div class="main">
        <form  method="post" align="center">
            <div class="field">
                <?php
                if (isset($mas['private']) && ($mas['private'] == 1)) {
                ?>
                    <input type="radio" value="0" name="private" id="mr"><label class="formLabelAuto">Частное лицо</label>
                    <input type="radio" value="1" name="private" id="mr" checked=""><label class="formLabelAuto">Компания</label>
            </div>
                <?php
                } else {
                ?>
                    <input type="radio" value="0" name="private" id="mr" checked=""><label class="formLabelAuto">Частное лицо</label>
                    <input type="radio" value="1" name="private" id="mr"><label class="formLabelAuto">Компания</label>
            </div>
                <?php
            }
            ?>
            <div class="field">
                <label>Ваше имя </label><span>*</span>
                <input type="text" value="<?= isset($mas['seller_name']) ? $mas['seller_name'] : ''; ?>" name="seller_name" maxlength="40">
            </div>
            <div class="field">
                <label>Электронная почта</label>
                <input type="text" value="<?= isset($mas['email']) ? $mas['email'] : ''; ?>" name="email">
            </div>
            <div class="field"> 
                <?php
                if (!empty($mas['allow_mails'])) {
                    ?>
                    <input type="checkbox" checked="" name="allow_mails" id="dispatch" />
                    <?php
                } else {
                    ?>
                    <input type="checkbox" name="allow_mails" id="dispatch" />
                    <?php
                }
                ?>
                <label class="formLabelAuto">Я не хочу получать вопросы по объявлению по e-mail</label>
            </div>
            <div class="field">
                <label>Номер телефона</label> 
                <input type="text" value="<?= isset($mas['phone']) ? $mas['phone'] : ''; ?>" name="phone">
            </div>
            <div class="field">
                <label>Город</label>
                <select title="Выберите Ваш город" name="location_id"> 
                    <option value="">-- Выберите город --</option>
                    <option disabled="disabled">-- Города --</option>
                    <?php
                    $citys = array('641780' => 'Новосибирск', '641490' => 'Барабинск', '641510' => 'Бердск', '641600' => 'Искитим',
                        '641630' => 'Колывань', '641680' => 'Краснообск', '641710' => 'Куйбышев', '641760' => 'Мошково');
                    foreach ($citys as $number => $city) {
                        $location_id = isset($mas['location_id']) ? $mas['location_id'] : '';
                        $check = ($number == $location_id) ? 'selected=""' : '';
                        echo '<option value="' . $number . '" ' . $check . '>' . $city . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="field">
                <label>Категория</label> 
                <select title="Выберите категорию объявления" name="category_id"> 
                    <option value="">-- Выберите категорию --</option>
                    <?php
                    $category = array('Транспорт', 'Недвижимость', 'Работа', 'Услуги', 'Личные вещи', 'Для дома и дачи', 'Бытовая электроника', 'Прочее');
                    foreach ($category as $key => $volume) {
                        $category_id = isset($mas['category_id']) ? $mas['category_id'] : '';
                        $check = (($category_id != null) && ($key == $category_id)) ? 'selected=""' : '';
                        echo '<option value="' . $key . '" ' . $check . '>' . $volume . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="field">
                <label>Название объявления</label><span>*</span>
                <input type="text" value="<?= isset($mas['title']) ? $mas['title'] : ''; ?>" name="title" maxlength="50">
            </div>
            <div class="field">
                <label>Описание объявления</label>
                <textarea name="description" maxlength="3000"><?= isset($mas['description']) ? $mas['description'] : ''; ?></textarea>
            </div>
            <div class="field">
                <label>Цена</label><span>*</span>
                <input type="text" value="<?= isset($mas['price']) ? $mas['price'] : ''; ?>" name="price" maxlength="9">
            </div>
            <div class="field">
                <?php if(!empty($mas) && isset($_GET['id'])) {
                    
                ?>  
                    <input type="submit" value="Создать новое" name="new">
                    <input type="submit" value="Сохранить" name="save">
                <?php
                } else {
                ?>    
                    <input type="submit" value="Создать" name="create">
                <?php 
                }
                ?> 
            </div>
            <div class="field">
                <?php 
                if(!empty($err)) {
                    echo '<span>'.$err.'</span>';
                }
                ?>
            </div>
        </form>
    </div>
    
    </body>   
    </html>
<?php /* Smarty version 2.6.25-dev, created on 2016-05-18 10:03:06
         compiled from index.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div class="col-md-5">
    
    <div id="container" class="alert alert-danger alert-dismissible" style="display: none" role="alert">
        <button type="button" style="float: right;" onclick="$('#container').hide();return false;" class="btn btn-warning btn-sm">
            <span aria-hidden="true">&times;</span></button>
        <div id="container_info"></div>
    </div>
    
    <h2><center>Форма объявления</center></h2>
    <form role="form" class="form-horizontal" method="post" action="javascript:void(null);">
        <div class="form-group col-sm-5"></div>
        <div class="form-group col-sm-7">
            <label class="radio-inline"><input type="radio" value="0" name="private" id="radio1" <?php if ($this->_tpl_vars['ad']->private == 0): ?> checked="" <?php endif; ?>> Частное лицо</label>
            <label class="radio-inline"><input type="radio" value="1" name="private" id="radio2" <?php if ($this->_tpl_vars['ad']->private == 1): ?> checked="" <?php endif; ?>> Компания</label>
        </div>
        <div class="form-group">
            <label for="seller_name" class="col-sm-4 control-label">Ваше имя</label>
            <div class="col-sm-8">
                <input type="text" name="seller_name" value="<?php echo $this->_tpl_vars['ad']->seller_name; ?>
" class="form-control" id="seller_name" placeholder="Введите имя">
                <p class="help-block">Поле обязательно для заполнения</p>
            </div> 
        </div> 
        <div class="form-group">
            <label for="email" class="col-sm-4 control-label">Электронная почта</label>
            <div class="col-sm-8">
                <input type="text" name="email" value="<?php echo $this->_tpl_vars['ad']->email; ?>
" class="form-control" id="email" placeholder="Введите email">
            </div>
            <div class="col-sm-offset-4 col-sm-8">
                <div class="checkbox">
                    <label class="formLabelAuto">
                        <?php if (! empty ( $this->_tpl_vars['ad']->allow_mails )): ?>
                        <input type="checkbox" checked="" name="allow_mails" id="dispatch" />
                        <?php else: ?>
                        <input type="checkbox" name="allow_mails" id="dispatch" />
                        <?php endif; ?>
                        Не получать вопросы по объявлению по e-mail
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group" >
            <label for="phone" class="col-sm-4 control-label">Номер телефона</label>
            <div class="col-sm-8">
                <input type="text" name="phone" class="form-control" id="phone" value="<?php echo $this->_tpl_vars['ad']->phone; ?>
" placeholder="Введите номер телефона">
            </div>
        </div>
        <div class="form-group">
            <label for="citys" class="col-sm-4 control-label">Город</label>
            <div class="col-sm-8">
                <select id="location_id" name="location_id" class="form-control">
                    <?php $_from = $this->_tpl_vars['citys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['number'] => $this->_tpl_vars['city']):
?> 
                    <option value="<?php echo $this->_tpl_vars['number']; ?>
" <?php if ($this->_tpl_vars['number'] == $this->_tpl_vars['ad']->location_id): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['city']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="category" class="col-sm-4 control-label">Категория</label> 
            <div class="col-sm-8" >
                <select id="category_id" name="category_id" class="form-control">
                    <?php $_from = $this->_tpl_vars['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['number'] => $this->_tpl_vars['categ']):
?> 
                    <option value="<?php echo $this->_tpl_vars['number']; ?>
" <?php if ($this->_tpl_vars['number'] == $this->_tpl_vars['ad']->category_id): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['categ']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-sm-4 control-label">Название объявления</label>
            <div class="col-sm-8">
                <input type="text" value="<?php echo $this->_tpl_vars['ad']->title; ?>
" name="title" class="form-control" id="title" maxlength="50" placeholder="Введите название объявления">
                <p class="help-block">Поле обязательно для заполнения</p>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-4 control-label">Описание объявления</label>
            <div class="col-sm-8">
                <textarea id="description" name="description" maxlength="3000" rows="3" class="form-control"><?php echo $this->_tpl_vars['ad']->description; ?>
</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="price" class="col-sm-4 control-label">Цена</label>
            <div class="col-sm-8">
                <input type="text" value="<?php echo $this->_tpl_vars['ad']->price; ?>
" name="price" id="price" maxlength="9" placeholder="Введите цену" class="form-control">
                <p class="help-block">Поле обязательно для заполнения</p>
            </div>
        </div>
        <div class="form-group">
            <div class="buttons col-sm-offset-6 col-sm-6" align="right">
                <?php if (! empty ( $this->_tpl_vars['ad'] ) && isset ( $_GET['id'] )): ?>
                <button name="save" id="save">Сохранить</button>
                <input type="submit" value="Создать новое" formaction="index.php"  name="new" id="new">
                <?php else: ?> 
                <button name="create" id="create">Создать</button>
                <?php endif; ?>  
            </div>      
        </div>
    </form>
</div>
<div class="col-md-7">
    <h2><center>Объявления</center></h2><br>
    <div class="tablediv col-sm-offset-1 col-sm-10">
        <?php if (is_array ( $this->_tpl_vars['ads_list'] ) && ! empty ( $this->_tpl_vars['ads_list'] )): ?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><center>#</center></th>
                    <th><a href="?sort=title">Название</a></th>
                    <th><a href="?sort=price">Цена</a></th>
                    <th>Имя</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php $_from = $this->_tpl_vars['ads_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['print'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['print']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['ad_l']):
        $this->_foreach['print']['iteration']++;
?>
            <tr>
                <td align="center"><?php echo $this->_foreach['print']['iteration']; ?>
</td>
                <td><?php echo $this->_tpl_vars['ad_l']->title; ?>
</td>
                <td><?php echo $this->_tpl_vars['ad_l']->price; ?>
 руб.</td>
                <td><?php echo $this->_tpl_vars['ad_l']->seller_name; ?>
</td>
                <td>
                    <button type="button" id="<?php echo $this->_tpl_vars['ad_l']->id; ?>
" class="delete btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
                    <button type="button" id="<?php echo $this->_tpl_vars['ad_l']->id; ?>
" class="edit btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></button>
                </td>
            </tr>    
            <?php endforeach; endif; unset($_from); ?> 
            </tbody>
            <tr>
                <td align="center" colspan="5">
                    <button type="button" id="0" class="deleteall btn btn-default btn-sm">
                        <span class="glyphicon glyphicon-trash"></span> Удалить все объявления
                    </button>   
                </td>
            </tr>
        </table>

        <div class="deleteall col-md-12"></div>
        <?php else: ?>
        <div class="deleteall col-md-12">
            <center><p><br>Объявлений нет</p></center>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

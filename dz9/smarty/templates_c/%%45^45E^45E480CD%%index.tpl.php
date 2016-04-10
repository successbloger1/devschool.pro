<?php /* Smarty version 2.6.25-dev, created on 2016-04-10 08:15:37
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'index.tpl', 31, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

   <DIV class="main"> 
        <form  method="post" align="center"> 
            <div class="field">
                <input type="radio" value="0" name="private" id="mr" <?php if ($this->_tpl_vars['mas']['private'] == 0): ?> checked="" <?php endif; ?>><label class="formLabelAuto">Частное лицо</label>
                <input type="radio" value="1" name="private" id="mr" <?php if ($this->_tpl_vars['mas']['private'] == 1): ?> checked="" <?php endif; ?>><label class="formLabelAuto">Компания</label>
            </div>
            <div class="field">
                <label>Ваше имя </label><span>*</span>
                <input type="text" value="<?php echo $this->_tpl_vars['mas']['seller_name']; ?>
" name="seller_name" maxlength="40">
            </div> 
            <div class="field">
                <label>Электронная почта</label>
                <input type="text" value="<?php echo $this->_tpl_vars['mas']['email']; ?>
" name="email">
            </div>
            <div class="field"> 
                <?php if (! empty ( $this->_tpl_vars['mas']['allow_mails'] )): ?>
                    <input type="checkbox" checked="" name="allow_mails" id="dispatch" />
                <?php else: ?>
                    <input type="checkbox" name="allow_mails" id="dispatch" />
                <?php endif; ?>
                <label class="formLabelAuto">Я не хочу получать вопросы по объявлению по e-mail</label>
            </div>
            <div class="field">
                <label>Номер телефона</label> 
                <input type="text" value="<?php echo $this->_tpl_vars['mas']['phone']; ?>
" name="phone">
            </div>
            <div class="field">
                <label>Город</label>
                <?php echo smarty_function_html_options(array('name' => 'location_id','options' => $this->_tpl_vars['citys'],'selected' => $this->_tpl_vars['mas']['location_id']), $this);?>

            </div>
            <div class="field">
                <label>Категория</label> 
                <?php echo smarty_function_html_options(array('name' => 'category_id','options' => $this->_tpl_vars['category'],'selected' => $this->_tpl_vars['mas']['category_id']), $this);?>

            </div>
            <div class="field">
                <label>Название объявления</label><span>*</span>
                <input type="text" value="<?php echo $this->_tpl_vars['mas']['title']; ?>
" name="title" maxlength="50">
            </div>
            <div class="field">
                <label>Описание объявления</label>
                <textarea name="description" maxlength="3000"><?php echo $this->_tpl_vars['mas']['description']; ?>
</textarea>
            </div>
            <div class="field">
                <label>Цена</label><span>*</span>
                <input type="text" value="<?php echo $this->_tpl_vars['mas']['price']; ?>
" name="price" maxlength="9">
            </div>
            <div class="field">
                <?php if (! empty ( $this->_tpl_vars['mas'] ) && isset ( $_GET['id'] )): ?>
                    <input type="submit" value="Сохранить" name="save">
                    <input type="submit" value="Создать новое" name="new">
                <?php else: ?> 
                    <input  type="submit" value="Создать" name="create">
                <?php endif; ?>    
            </div>
            <div class="field">
                <?php if (isset ( $this->_tpl_vars['mas']['err'] ) && ! empty ( $this->_tpl_vars['mas']['err'] )): ?>
                    <span><?php echo $this->_tpl_vars['mas']['err']; ?>
</span>
                <?php endif; ?>    
            </div> 
        </form>
    </DIV>

    <h2><center>Объявления</center></h2><br>
    
    <?php if (is_array ( $this->_tpl_vars['print_ads'] )): ?>
        <center>
        <table>
            <tr>
                <th width="40px">#</th>
                <th><a href="?sort=title">Название</a></th>
                <th><a href="?sort=price">Цена</a></th>
                <th>Имя</th>
                <th></th>
            </tr>
        <?php $_from = $this->_tpl_vars['print_ads']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['print'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['print']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['ad']):
        $this->_foreach['print']['iteration']++;
?>
            <tr>
                <td align="center"><?php echo $this->_foreach['print']['iteration']; ?>
</td>
                <td><a href="?id=<?php echo $this->_tpl_vars['ad']['id']; ?>
"><?php echo $this->_tpl_vars['ad']['title']; ?>
</a></td>
                <td><?php echo $this->_tpl_vars['ad']['price']; ?>
 руб.</td>
                <td><?php echo $this->_tpl_vars['ad']['seller_name']; ?>
</td>
                <td><a href="?delete=<?php echo $this->_tpl_vars['ad']['id']; ?>
">Удалить</a></td>
            </tr>    
        <?php endforeach; endif; unset($_from); ?> 
        </table>
        </center>
            <br>
            <center>
                <a href="?delete=0"><br>Удалить все объявления</a>
            </center>
            <br>
    <?php else: ?>
        <center>Объявлений нет</center>
    <?php endif; ?>
    
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

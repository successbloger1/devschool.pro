<?php /* Smarty version 2.6.25-dev, created on 2016-05-04 17:47:32
         compiled from install.tpl */ ?>
<html>
    <head>
        <style>
            <?php echo '
            .field {clear:both; text-align:right; line-height:25px;}
            label {float:left; padding-right:10px;}
            .main {float:left}
            span {color: red}
            #success {color: blue}
            '; ?>

        </style>
    </head>
    <body>
        <form method="POST">
            <div class="main">
                <div class="field">
                    <label for="n">ID приложения:</label>
                    <input type="text" name="app_id" value="<?php echo $this->_tpl_vars['app_id']; ?>
">
                </div>
                <div class="field">
                    <label for="ln">Защищенный ключ:</label>
                    <input type="text" name="secret" value="<?php echo $this->_tpl_vars['secret']; ?>
">
                </div>
                <div class="field">
                    <label for="a">ID группы:</label>
                    <input type="text" name="group_id" value="<?php echo $this->_tpl_vars['group_id']; ?>
">
                </div>
                <div class="field">
                    <label for="a">Ссылка на файл XML:</label>
                    <input type="text" name="xml" value="<?php echo $this->_tpl_vars['xml']; ?>
">
                </div>
                <div class="field">
                    <?php if (( $this->_tpl_vars['load_available'] == 1 )): ?>
                    <input type="checkbox" checked="" name="load_available"/>
                    <?php else: ?>
                    <input type="checkbox" name="load_available"/>
                    <?php endif; ?>
                    <label>Загружать товары со значением "available = false"</label>
                </div>
                <div class="field">
                    <?php if (( $this->_tpl_vars['resize_photo'] == 1 )): ?>
                    <input type="checkbox" checked="" name="resize_photo"/>
                    <?php else: ?>
                    <input type="checkbox" name="resize_photo"/>
                    <?php endif; ?>
                    <label>Увеличивать фото, которые меньше 400x400px </label>
                </div>
                <div class="field">
                    <?php if (( $this->_tpl_vars['loging'] == 1 )): ?>
                    <input type="checkbox" checked="" name="loging"/>
                    <?php else: ?>
                    <input type="checkbox" name="loging"/>
                    <?php endif; ?>
                    <label>Включить логирование процесса синхронизации </label>
                </div>
                <div class="field">
                    <input type="submit" value="Install" name="install">
                </div>
                <div class="field">
                    <label for="n"><?php echo $this->_tpl_vars['link']; ?>
</label>
                </div>
                <div class="field">
                    <?php if (isset ( $this->_tpl_vars['err'] ) && ! empty ( $this->_tpl_vars['err'] )): ?>
                            <span><?php echo $this->_tpl_vars['err']; ?>
</span>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </body>
</html>
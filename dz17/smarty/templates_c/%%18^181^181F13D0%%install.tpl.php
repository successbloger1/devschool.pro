<?php /* Smarty version 2.6.25-dev, created on 2016-04-07 15:08:35
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
                    <label for="n">Server name:</label>
                    <input type="text" name="server_name" value="<?php echo $this->_tpl_vars['server_name']; ?>
">
                </div>
                <div class="field">
                    <label for="ln">User name:</label>
                    <input type="text" name="user_name" value="<?php echo $this->_tpl_vars['user_name']; ?>
">
                </div>
                <div class="field">
                    <label for="a">Password:</label>
                    <input type="text" name="password" value="<?php echo $this->_tpl_vars['password']; ?>
">
                </div>
                <div class="field">
                    <label for="a">Database:</label>
                    <input type="text" name="database" value="<?php echo $this->_tpl_vars['database']; ?>
">
                </div>
                <div class="field">
                    <input type="submit" value="Install" name="install">
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
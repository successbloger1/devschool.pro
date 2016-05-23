<?php /* Smarty version 2.6.25-dev, created on 2016-05-23 13:28:26
         compiled from table_row.tpl.html */ ?>
            <tr>
                <td align="center"><?php echo $this->_tpl_vars['n']; ?>
</td>
                <td><?php echo $this->_tpl_vars['ad']->title; ?>
</td>
                <td><?php echo $this->_tpl_vars['ad']->price; ?>
 руб.</td>
                <td><?php echo $this->_tpl_vars['ad']->seller_name; ?>
</td>
                <td>
                    <button type="button" id="<?php echo $this->_tpl_vars['ad']->id; ?>
" class="delete btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
                    <button type="button" id="<?php echo $this->_tpl_vars['ad']->id; ?>
" class="edit btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></button>
                </td>
            </tr>  
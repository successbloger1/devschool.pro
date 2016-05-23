<?php /* Smarty version 2.6.25-dev, created on 2016-05-23 14:04:54
         compiled from table.tpl.html */ ?>
<table id="ads" class="table table-striped table-hover">
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
        <?php echo $this->_tpl_vars['ads_rows']; ?>
 
    </tbody>
</table>
<table id="del" class="table table-striped table-hover">
    <tr>
        <td align="center" colspan="5">
            <button type="button" id="0" class="deleteall btn btn-default btn-sm">
                <span class="glyphicon glyphicon-trash"></span> Удалить все объявления
            </button>   
        </td>
    </tr>
</table>
<div class="deleteall col-md-12"></div>

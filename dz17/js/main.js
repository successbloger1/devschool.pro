onload = function () {
    $('form :checked').each(function () {
        this.selected = false;
        this.checked = false;
    });
    $('form').trigger('reset');
    $('input[id = radio1]').prop('checked', true);  
}

$(document).ready(function () {
    
    var options = {
        target: '#container_info', // target element(s) to be updated with server response 
        success: showResponse, // post-submit callback 
        url: 'index.php', // override for form's 'action' attribute
        dataType: 'json', // 'xml', 'script', or 'json' (expected server response type) 
    };

    function showResponse(response) {
        
        if (response.status == 'error') {
            $('#container').removeClass('alert-warning').addClass('alert-danger');
            $('#container_info').html(response.message);
            $('#container').fadeIn('slow');
        } else {
            $('#container').hide();
            $('form :checked').each(function () {
                this.selected = false;
                this.checked = false;
            });
            $('form').trigger('reset');
            $('input[id = radio1]').prop('checked', true);  
            
            if ($('tr:has(button[id=' + response.id + '])').length > 0) {
                var num = $('tr:has(button[id=' + response.id + ']) td:first').html();
                $('tr:has(button[id=' + response.id + '])').html('<td align="center">' + num + '</td><td>'
                        + response.title + '</td><td>' + response.price + ' руб.</td><td>'
                        + response.seller_name + '</td><td><button type="button" id="'
                        + response.id + '" class="delete btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></button> '
                        + ' <button type="button" id="' + response.id + '" class="edit btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></button></td>');

                $('.buttons').html('<button name="create" id="' + response.id + '">Создать</button>');

                console.log('edited ad with id = ' + response.id);
            } else {

                if ($('table').length == 0) {
                    $('.tablediv').html('<table class="table table-striped table-hover"><thead><tr><th>'
                            + '<center>#</center></th><th><a href="?sort=title">Название</a></th>'
                            + '<th><a href="?sort=price">Цена</a></th><th>Имя</th><th>Действия</th>'
                            + '</tr></thead><tbody></tbody><tr><td align="center" colspan="5">'
                            + '<button type="button" id="0" class="deleteall btn btn-default btn-sm">'
                            + '<span class="glyphicon glyphicon-trash"></span> Удалить все объявления'
                            + '</button></td></tr></table><div class="deleteall col-md-12"></div>');
                }
                var num = parseInt($('tbody tr:eq(-2) td:first').html() != undefined ? $('tbody tr:eq(-2) td:first').html() : 0) + 1;
                $('tbody tr:last').before('<tr><td align="center">' + num + '</td><td>'
                        + response.title + '</td><td>' + response.price + ' руб.</td><td>'
                        + response.seller_name + '</td><td><button type="button" id="'
                        + response.id + '" class="delete btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></button> '
                        + ' <button type="button" id="' + response.id + '" class="edit btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></button></td></tr>');
                console.log('added ad with id = ' + response.id);
            }
        }
    };

    // bind form using 'ajaxForm' 
    $('#ajax-form').ajaxForm(options);

    // функция удаление таблицы
    function delTable() {
        $('table').fadeOut('fast', function () {
            $(this).remove();
        });

        $('div.deleteall').html("<center><p><br>Объявлений нет</p></center>");
    }
    
    // Выбор объявления для редактирования
    $('body').on('click', '.edit', function () {
        var tr = $(this).closest('tr');
        var id = {'id': $(this).attr('id')};

        $.getJSON('index.php',
                id,
                function (response) { // success
                    $('#id').val(response.id);
                    if (response.private == 0) {
                        $('input[id = radio1]').prop('checked', true);
                        $('#radio2').removeAttr('checked');
                    } else if (response.private == 1) {
                        $('input[id = radio2]').prop('checked', true);
                        $('#radio1').removeAttr('checked');
                    }
                    $('#seller_name').val(response.seller_name);
                    $('#email').val(response.email);
                    if (response.allow_mails != '') {
                        $('#dispatch').prop('checked', true);
                    }
                    $('#phone').val(response.phone);
                    $('#location_id option[value =' + response.location_id + ']').prop('selected', true);
                    $('#category_id option[value =' + response.category_id + ']').prop('selected', true);
                    $('#title').val(response.title);
                    $('#description').val(response.description);
                    $('#price').val(response.price);

                    $('.buttons').html('<button name="' + response.id + '" id="save">Сохранить</button>'
                            + '<button name="new" id="new" onclick="window.location.reload();return false;">Создать новое</button>');
                });
    });
    
    
    
    // Удаление выбранного объявления
    $('body').on('click', 'button.delete', function () {
        var tr = $(this).closest('tr');
        var id = {'delete': $(this).attr('id')};

        $.getJSON('index.php',
                id,
                function (response) { // success
                    console.log(response.status);
                    tr.fadeOut('slow', function () {
                        $(this).remove();
                    });
                });

        if ($('table tr').length == 3)
        {
            delTable();
        }
    });

    //Удаление всех объявлений
    $('body').on('click', 'button.deleteall', function () {
        var id = {'delete': $(this).attr('id')};

        $.getJSON('index.php',
                id,
                function (response) {
                    console.log(response.status);
                    delTable();
                });
    });

});


onload = function () {
    $(':input', '#form')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
    $("select option:first-child").prop('selected', true);
    $('input[id = radio1]').prop('checked', true);
}

$(document).ready(function () {

    // функция удаление таблицы
    function delTable() {
        $('table').fadeOut('fast', function () {
            $(this).remove();
        });

        $('div.deleteall').html("<center><p><br>Объявлений нет</p></center>");
    }

    // Редактирование объявления
    $('body').on('click', '.edit', function () {
        var tr = $(this).closest('tr');
        var id = {'id': $(this).attr('id')};

        $.getJSON('index.php',
                id,
                function (response) { // success
                    console.log(response.status);
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
                            + '<input type="submit" value="Создать новое" formaction="index.php"  name="new" id="new">');
                });
    });

    // Сохранение объявления
    $('body').on('click', '#save', function () {
        var form = $('#form').serialize();
        $.post('index.php?id=' + $(this).attr('name'),
                form,
                function (response) {
                    if (response.status == 'error') {
                        $('#container').removeClass('alert-warning').addClass('alert-danger');
                        $('#container_info').html(response.message);
                        $('#container').fadeIn('slow');
                    } else {
                        $('#container').hide();
                        $(':input', '#form')
                                .not(':button, :submit, :reset, :hidden')
                                .val('')
                                .removeAttr('checked')
                                .removeAttr('selected');
                        $("select option:first-child").prop('selected', true);
                        $('input[id = radio1]').prop('checked', true);

                        var num = $('tr:has(button[id=' + response.id + ']) td:first').html();
                        $('tr:has(button[id=' + response.id + '])').html('<td align="center">' + num + '</td><td>'
                                + response.title + '</td><td>' + response.price + ' руб.</td><td>'
                                + response.seller_name + '</td><td><button type="button" id="'
                                + response.id + '" class="delete btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></button> '
                                + ' <button type="button" id="' + response.id + '" class="edit btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></button></td>');

                        $('.buttons').html('<button name="create" id="create">Создать</button>');

                        console.log('edited ad with id = ' + response.id);
                    }
                },
                'json');
    });

    // Создание нового объявления
    $('body').on('click', '#create', function () {
        var form = $('form').serialize();
        $.post('index.php',
                form,
                function (response) {
                    if (response.status == 'error') {
                        $('#container').removeClass('alert-warning').addClass('alert-danger');
                        $('#container_info').html(response.message);
                        $('#container').fadeIn('slow');
                    } else {
                        $('#container').hide();
                        $(':input', '#form')
                                .not(':button, :submit, :reset, :hidden')
                                .val('')
                                .removeAttr('checked')
                                .removeAttr('selected');
                        $("select option:first-child").prop('selected', true);
                        $('input[id = radio1]').prop('checked', true);
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
                },
                'json');
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
                        
                        $(':input', '#form')
                                .not(':button, :submit, :reset, :hidden')
                                .val('')
                                .removeAttr('checked')
                                .removeAttr('selected');
                        $("select option:first-child").prop('selected', true);
                        $('input[id = radio1]').prop('checked', true);
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


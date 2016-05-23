function clean() {
    $(':input', '#form')
            .not(':button, :submit, :reset, #dispatch, input[type = radio]')
            .val('')
            .removeAttr('selected');
    $("select option:first-child").prop('selected', true);
    $('input[id = radio1]').prop('checked', true).attr('checked');
    $('#radio2').removeAttr('checked');
    $('#dispatch').removeAttr('checked');
    
    $('.buttons').html('<button name="create" id="create">Создать</button>');
}

onload = function () {
    clean();
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
        var id = {'action': 'edit', 'id': $(this).attr('id')};

        $.getJSON('ajax_controller.php',
                id,
                function (response) { // success
                    console.log(response.message);
                    if (response.data.private == 0) {
                        $('input[id = radio1]').prop('checked', true).attr('checked');
                        $('#radio2').removeAttr('checked');
                    } else if (response.data.private == 1) {
                        $('input[id = radio2]').prop('checked', true).attr('checked');
                        $('#radio1').removeAttr('checked');
                    }
                    $('#seller_name').val(response.data.seller_name);
                    $('#email').val(response.data.email);
                    if (response.data.allow_mails != '') {
                        $('#dispatch').prop('checked', true).attr('checked');
                    }
                    $('#phone').val(response.data.phone);
                    $('#location_id option[value =' + response.data.location_id + ']').prop('selected', true);
                    $('#category_id option[value =' + response.data.category_id + ']').prop('selected', true);
                    $('#title').val(response.data.title);
                    $('#description').val(response.data.description);
                    $('#price').val(response.data.price);

                    $('.buttons').html('<button name="' + response.data.id + '" id="save">Сохранить</button>'
                            + '<input type="submit" value="Создать новое" formaction="index.php"  name="new" id="new">');
                });
    });

    // Сохранение объявления
    $('body').on('click', '#save', function () {
        var form = $('#form').serialize();
        $.post('ajax_controller.php?action=save&id=' + $(this).attr('name'),
                form,
                function (response) {
                    if (response.status == 'error') {
                        $('#container').removeClass('alert-warning').addClass('alert-danger');
                        $('#container_info').html(response.message);
                        $('#container').fadeIn('slow');
                    } else {
                        $('#container').hide();
                        clean();

                        var num = $('tr:has(button[id=' + response.data.id + ']) td:first').html();
                        $('tr:has(button[id=' + response.data.id + '])').replaceWith(response.row);
                        $('tr:has(button[id=' + response.data.id + ']) td:first').html(num);

                        console.log(response.message);
                    }
                },
                'json');
    });

    // Создание нового объявления
    $('body').on('click', '#create', function () {
        var form = $('form').serialize();
        $.post('ajax_controller.php?action=create',
                form,
                function (response) {
                    if (response.status == 'error') {
                        $('#container').removeClass('alert-warning').addClass('alert-danger');
                        $('#container_info').html(response.message);
                        $('#container').fadeIn('slow');
                    } else {
                        $('#container').hide();
                        clean();
                        
                        if ($('table').length == 0) {
                            $('.tablediv').html(response.table);
                        }
                        var num = parseInt($('tbody tr:eq(-2) td:first').html() != undefined ? $('tbody tr:eq(-2) td:first').html() : 0) + 1;
                        $('#ads>tbody').append(response.row);
                        $('tbody tr:eq(-2) td:first').html(num);
                        console.log(response.message);
                    }
                },
                'json');
    });

    // Удаление выбранного объявления
    $('body').on('click', 'button.delete', function () {
        var tr = $(this).closest('tr');
        var id = {'action' : 'delete', 'id' : $(this).attr('id')};

        $.getJSON('ajax_controller.php',
                id,
                function (response) { // success
                    console.log(response.message);
                    tr.fadeOut('slow', function () {
                        $(this).remove();
                        
                        clean();
                    });
                });

        if ($('table tr').length == 3)
        {
            delTable();
        }
    });

    //Удаление всех объявлений
    $('body').on('click', 'button.deleteall', function () {
        var id = {'action' : 'delete', 'id' : $(this).attr('id')};

        $.getJSON('ajax_controller.php',
                id,
                function (response) {
                    console.log(response.message);
                    delTable();
                });
    });

});


$(document).ready(function () {
    
    $('body').on('click', 'button.delete',function () {
        var tr = $(this).closest('tr');
        var id = $(this).attr('id');

        $.get('index.php?delete=' + id, function () { // success
            tr.fadeOut('slow', function () {
                $(this).remove();
                
                $(':input', '#form')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .removeAttr('checked')
                        .removeAttr('selected');
                $("select option:first-child").prop('selected', true);
                $('input[id = radio1]').prop('checked', true);
                
                $('.buttons').html('<button name="create" id="undefined">Создать</button>');
            });
        });
        
        if ($('table tr').length == 3)
        {
            delTable();
        };
    });
    
    function delTable() { // success
            $('table').fadeOut('fast', function () {
                $(this).remove();
            });

            $('div.deleteall').html("<center><p><br>Объявлений нет</p></center>");
        }

    $('button.deleteall').on('click', function () {
        var id = $(this).attr('id');

        $.get('index.php?delete=' + id, delTable);
    });

});


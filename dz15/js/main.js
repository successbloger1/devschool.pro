$(document).ready(function () {
    
    
    $('button.delete').on('click', function () {
        var tr = $(this).closest('tr');
        var id = $(this).attr('id');

        $.get('index.php?delete=' + id, function () { // success
            tr.fadeOut('slow', function () {
                $(this).remove();
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


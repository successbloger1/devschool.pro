<html>
    <head>
        <style>
            {literal}
            .field {clear:both; text-align:right; line-height:25px;}
            label {float:left; padding-right:10px;}
            .main {float:left}
            span {color: red}
            #success {color: blue}
            {/literal}
        </style>
    </head>
    <body>
        <form method="POST">
            <div class="main">
                <div class="field">
                    <label for="n">ID приложения:</label>
                    <input type="text" name="app_id" value="{$app_id}">
                </div>
                <div class="field">
                    <label for="ln">Защищенный ключ:</label>
                    <input type="text" name="secret" value="{$secret}">
                </div>
                <div class="field">
                    <label for="a">ID группы:</label>
                    <input type="text" name="group_id" value="{$group_id}">
                </div>
                <div class="field">
                    <label for="a">Ссылка на файл XML:</label>
                    <input type="text" name="xml" value="{$xml}">
                </div>
                <div class="field">
                    {if ($load_available == 1)}
                    <input type="checkbox" checked="" name="load_available"/>
                    {else}
                    <input type="checkbox" name="load_available"/>
                    {/if}
                    <label>Загружать товары со значением "available = false"</label>
                </div>
                <div class="field">
                    {if ($resize_photo == 1)}
                    <input type="checkbox" checked="" name="resize_photo"/>
                    {else}
                    <input type="checkbox" name="resize_photo"/>
                    {/if}
                    <label>Увеличивать фото, которые меньше 400x400px </label>
                </div>
                <div class="field">
                    {if ($loging == 1)}
                    <input type="checkbox" checked="" name="loging"/>
                    {else}
                    <input type="checkbox" name="loging"/>
                    {/if}
                    <label>Включить логирование процесса синхронизации </label>
                </div>
                <div class="field">
                    <input type="submit" value="Install" name="install">
                </div>
                <div class="field">
                    <label for="n">{$link}</label>
                </div>
                <div class="field">
                    {if isset($err) && !empty($err)}
                            <span>{$err}</span>
                    {/if}
                </div>
            </div>
        </form>
    </body>
</html>
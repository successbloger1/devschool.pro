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
                    <label for="n">Server name:</label>
                    <input type="text" name="server_name" value="{$server_name}">
                </div>
                <div class="field">
                    <label for="ln">User name:</label>
                    <input type="text" name="user_name" value="{$user_name}">
                </div>
                <div class="field">
                    <label for="a">Password:</label>
                    <input type="text" name="password" value="{$password}">
                </div>
                <div class="field">
                    <label for="a">Database:</label>
                    <input type="text" name="database" value="{$database}">
                </div>
                <div class="field">
                    <input type="submit" value="Install" name="install">
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
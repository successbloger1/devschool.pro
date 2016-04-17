{include file='header.tpl'}

   <DIV class="main"> 
        <form  method="post" align="center" name="form"> 
            <div class="field">
                <input type="radio" value="0" name="private" id="mr" {if $mas.private == 0} checked="" {/if}><label class="formLabelAuto">Частное лицо</label>
                <input type="radio" value="1" name="private" id="mr" {if $mas.private == 1} checked="" {/if}><label class="formLabelAuto">Компания</label>
            </div>
            <div class="field">
                <label>Ваше имя </label><span>*</span>
                <input type="text" value="{$mas.seller_name}" name="seller_name" maxlength="40">
            </div> 
            <div class="field">
                <label>Электронная почта</label>
                <input type="text" value="{$mas.email}" name="email">
            </div>
            <div class="field"> 
                {if !empty($mas.allow_mails)}
                    <input type="checkbox" checked="" name="allow_mails" id="dispatch" />
                {else}
                    <input type="checkbox" name="allow_mails" id="dispatch" />
                {/if}
                <label class="formLabelAuto">Я не хочу получать вопросы по объявлению по e-mail</label>
            </div>
            <div class="field">
                <label>Номер телефона</label> 
                <input type="text" value="{$mas.phone}" name="phone">
            </div>
            <div class="field">
                <label>Город</label>
                {html_options name=location_id options=$citys selected=$mas.location_id}
            </div>
            <div class="field">
                <label>Категория</label> 
                {html_options name=category_id options=$category selected=$mas.category_id}
            </div>
            <div class="field">
                <label>Название объявления</label><span>*</span>
                <input type="text" value="{$mas.title}" name="title" maxlength="50">
            </div>
            <div class="field">
                <label>Описание объявления</label>
                <textarea name="description" maxlength="3000">{$mas.description}</textarea>
            </div>
            <div class="field">
                <label>Цена</label><span>*</span>
                <input type="text" value="{$mas.price}" name="price" maxlength="9">
            </div>
            <div class="field">
                {if !empty($mas) && isset($smarty.get.id)}
                    <input type="submit" value="Сохранить" name="save">
                    <input type="submit" value="Создать новое" formaction="index.php"  name="new">
                {else} 
                    <input  type="submit" value="Создать" name="create">
                {/if}    
            </div>
            <div class="field">
                {if isset($mas.err) && !empty($mas.err)}
                    <span>{$mas.err}</span>
                {/if}    
            </div> 
        </form>
    </DIV>

    <h2><center>Объявления</center></h2><br>
    
    {if is_array($print_ads)}
        <center>
        <table>
            <tr>
                <th width="40px">#</th>
                <th><a href="?sort=title">Название</a></th>
                <th><a href="?sort=price">Цена</a></th>
                <th>Имя</th>
                <th></th>
            </tr>
        {foreach from=$print_ads item=ad name=print}
            <tr>
                <td align="center">{$smarty.foreach.print.iteration}</td>
                <td><a href="?id={$ad.id}">{$ad.title}</a></td>
                <td>{$ad.price} руб.</td>
                <td>{$ad.seller_name}</td>
                <td><a href="?delete={$ad.id}">Удалить</a></td>
            </tr>    
        {/foreach} 
        </table>
        </center>
            <br>
            <center>
                <a href="?delete=0"><br>Удалить все объявления</a>
            </center>
            <br>
    {else}
        <center>Объявлений нет</center>
    {/if}
    
{include file='footer.tpl'}


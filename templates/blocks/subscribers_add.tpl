<form method="post" action="<{$xoops_url}>/modules/subscribers/subscribe.php">
    <div style="text-align:center;">
        <div style="padding:5px;">
            <h3><{$smarty.const._MB_SUBSCRIBERS_SUBSCRIBE}></h3>
        </div>
        <div style="padding:5px;">
            <strong><{$smarty.const._MB_SUBSCRIBERS_ENTER_NAME}></strong><br>
            <input name="user_name" type="text" maxlength="100" size="15">
        </div>
        <div style="padding:5px;">
            <strong><{$smarty.const._MB_SUBSCRIBERS_ENTER_EMAIL}></strong><br>
            <input name="user_email" type="text" maxlength="100" size="15">
        </div>
        <div style="padding:5px;">
            <strong><{$smarty.const._MB_SUBSCRIBERS_ENTER_PHONE}></strong><br>
            <input name="user_phone" type="text" maxlength="100" size="15">
        </div>
        <div style="padding:5px;">
            <strong><{$smarty.const._MB_SUBSCRIBERS_ENTER_COUNTRY}></strong><br>
            <select name="user_country" size="1" width="100" style="width:100px;">
                <{foreach item=country key=key from=$block.countries}>
                    <option value="<{$key}>"<{if $key == $block.selected}> selected="selected"<{/if}>><{$country}></option>
                <{/foreach}>
            </select>
        </div>
        <{if $block.captcha}>
            <div style="padding:5px;">
                <strong><{$smarty.const._MB_SUBSCRIBERS_ENTER_SECURITY}></strong>
                <input type="text" name="xoopscaptcha" id="xoopscaptcha" size="4" maxlength="4" value="">
                <img id="xoopscaptcha" src="<{$xoops_url}>/class/captcha/image/scripts/image.php"
                     onclick='this.src="<{$xoops_url}>/class/captcha/image/scripts/image.php?refresh="+Math.random()'
                     style="cursor: pointer; vertical-align: middle;" alt="">
            </div>
        <{/if}>
        <div style="padding:5px;">
            <input type="submit" name="submit" value="<{$smarty.const._MB_SUBSCRIBERS_SUBMIT}>">
        </div>
    </div>
</form>

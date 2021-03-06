<form method="post" action="<{$xoops_url}>/modules/subscribers/subscribe.php">
    <div style="text-align:center;">
        <div style="padding:5px;">
            <h2><{$smarty.const._MD_SUBSCRIBERS_SUBSCRIBE}></h2>
        </div>
        <div style="padding:5px;">
            <strong><{$smarty.const._MD_SUBSCRIBERS_ENTER_NAME}></strong>
            <input name="user_name" type="text" maxlength="100" size="30">
        </div>
        <div style="padding:5px;">
            <strong><{$smarty.const._MD_SUBSCRIBERS_ENTER_EMAIL}></strong>
            <input name="user_email" type="text" maxlength="100" size="30">
        </div>
        <div style="padding:5px;">
            <strong><{$smarty.const._MD_SUBSCRIBERS_ENTER_PHONE}></strong>
            <input name="user_phone" type="text" maxlength="100" size="30">
        </div>
        <div style="padding:5px;">
            <strong><{$smarty.const._MD_SUBSCRIBERS_ENTER_COUNTRY}></strong>
            <select name="user_country" size="1" width="190" style="width:190px;">
                <{foreach item=country key=key from=$countries}>
                    <option value="<{$key}>"<{if $key == $selected}> selected="selected"<{/if}>><{$country}></option>
                <{/foreach}>
            </select>
        </div>
        <{if $captcha}>
            <div style="padding:5px;">
                <strong><{$smarty.const._MD_SUBSCRIBERS_ENTER_SECURITY}></strong>
                <input type="text" name="xoopscaptcha" id="xoopscaptcha" size="4" maxlength="4" value="">
                <img id="xoopscaptcha" src="<{$xoops_url}>/class/captcha/image/scripts/image.php"
                     onclick='this.src="<{$xoops_url}>/class/captcha/image/scripts/image.php?refresh="+Math.random()'
                     style="cursor: pointer; vertical-align: middle;" alt="">
            </div>
        <{/if}>
        <div style="padding:5px;">
            <input type="submit" name="submit" value="<{$smarty.const._MD_SUBSCRIBERS_SUBMIT}>">
        </div>
    </div>
</form>

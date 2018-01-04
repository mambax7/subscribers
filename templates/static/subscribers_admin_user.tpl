<script type="text/javascript">
    function subscribers_showDiv(type, id) {
        divs = document.getElementsByTagName('div');
        for (i = 0; i < divs.length; i++) {
            if (/opt_divs/.test(divs[i].className)) {
                divs[i].style.display = 'none';
            }
        }
        if (!id)id = '';
        document.getElementById(type + id).style.display = 'block';
        document.anchors.item(type + id).scrollIntoView();
    }
</script>

<div style="margin-top:0; float: left;">
    <{$smarty.const._AM_SUBSCRIBERS_RESULTS_FOUND}><{$count}><br>
    <{$smarty.const._AM_SUBSCRIBERS_USERS_FOUND}><{$users_count}><br>
    <{$smarty.const._AM_SUBSCRIBERS_TOTAL_FOUND}><{$total_count}>
</div>

<div style="margin-top:0; float: right; width:400px;" align="right">
    <form action="admin_user.php" method="POST">
        <input type="text" name="query" id="query" size="30" value="<{$query}>">
        <input type="submit" name="btn" value="<{$smarty.const._SEARCH}>">
        <input type="submit" name="btn1" value="<{$smarty.const._CANCEL}>"
               onclick="document.getElementById('query').value='';">
    </form>
</div>

<br>

<table width="100%" cellspacing="1" cellpadding="0" class="outer">
    <tr align="center">
        <th><{$smarty.const._AM_SUBSCRIBERS_COUNTRY}></th>
        <th><{$smarty.const._AM_SUBSCRIBERS_NAME}></th>
        <th><{$smarty.const._AM_SUBSCRIBERS_EMAIL}></th>
        <th><{$smarty.const._AM_SUBSCRIBERS_PHONE}></th>
        <th width="15%"><{$smarty.const._OPTIONS}></th>
    </tr>
    <{if $count > 0}>
        <{foreach item=obj key=key from=$objs}>
            <tr class="<{cycle values="even,odd"}>" align="center">
                <td align="left"><{$obj.user_country}></td>
                <td align="left"><{$obj.user_name}></td>
                <td align="left"><{$obj.user_email}></td>
                <td align="left"><{$obj.user_phone}></td>
                <td>
                    <a href="admin_user.php?op=edit&amp;id=<{$obj.user_id}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                  title="<{$smarty.const._EDIT}>"
                                                                                  alt="<{$smarty.const._EDIT}>"></a>
                    <a href="admin_user.php?op=del&amp;id=<{$obj.user_id}>"><img src="<{xoModuleIcons16 delete.png}>"
                                                                                 title="<{$smarty.const._DELETE}>"
                                                                                 alt="<{$smarty.const._DELETE}>"></a>
                </td>
            </tr>
        <{/foreach}>
    <{else}>
        <tr>
            <td class="head" colspan="5" align="center"><{$smarty.const._AM_SUBSCRIBERS_NOTFOUND}></td>
        </tr>
    <{/if}>
    <tr>
        <td class="head" colspan="5" align="right">
            <{$pag}>
            <input type="button" onclick="subscribers_showDiv('add_form','','hiddendiv'); return false;"
                   value="<{$smarty.const._ADD}>">
        </td>
    </tr>
</table>
<br>
<a name="add_form_anchor"></a>
<div id="add_form" class="hiddendiv" style="display:none;"><{$add_form}></div>

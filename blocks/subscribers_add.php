<?php

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

function subscribers_add_show($options)
{
    global $xoopsUser;
    require_once XOOPS_ROOT_PATH . '/modules/subscribers/include/functions.php';
    subscribers_sendEmails();

    $config =& subscribers_getModuleConfig();
    $block  = [];
    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    $block['countries'] = XoopsLists::getCountryList();
    $block['selected']  = $config['country'];
    array_shift($block['countries']);
    $sub_captcha = $config['captcha'];
    if (is_object($xoopsUser)) {
        $block['captcha'] = 2 == $sub_captcha ? 0 : 1;
    } else {
        $block['captcha'] = 3 == $sub_captcha ? 0 : 1;
    }

    return $block;
}

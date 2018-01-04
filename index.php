<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

require_once __DIR__ . '/../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/subscribers/include/functions.php';
subscribers_sendEmails();

$GLOBALS['xoopsOption']['template_main'] = 'subscribers_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$config   =& subscribers_getModuleConfig();
$selected = $config['country'];

$sub_captcha = $config['captcha'];
if (is_object($xoopsUser)) {
    $captcha = 2 == $sub_captcha ? 0 : 1;
} else {
    $captcha = 3 == $sub_captcha ? 0 : 1;
}

require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
$countries = XoopsLists::getCountryList();
array_shift($countries);

$xoopsTpl->assign('countries', $countries);
$xoopsTpl->assign('selected', $selected);
$xoopsTpl->assign('captcha', $captcha);

require_once XOOPS_ROOT_PATH . '/footer.php';

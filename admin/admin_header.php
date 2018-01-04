<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

require_once __DIR__ . '/../../../include/cp_header.php';
//require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

//require_once __DIR__ . '/../class/Utility.php';
//require_once __DIR__ . '/../include/common.php';

$moduleDirName = basename(dirname(__DIR__));
$helper = \Xmf\Module\Helper::getHelper($moduleDirName);
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}

if ($xoopsUser) {
    $modulepermHandler = xoops_getHandler('groupperm');
    if (!$modulepermHandler->checkRight('module_admin', $xoopsModule->getVar('mid'), $xoopsUser->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

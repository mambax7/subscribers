<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

use XoopsModules\Subscribers;

// require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = Subscribers\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');


// Index
$adminmenu[] = [
    'title' => _MI_SUBSCRIBERS_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',

    // User
];

$adminmenu[] = [
    'title' => _MI_SUBSCRIBERS_ADMENU_USER,
    'link'  => 'admin/admin_user.php',
    'icon'  => $pathIcon32 . '/users.png',
];

// Send
$adminmenu[] = [
    'title' => _MI_SUBSCRIBERS_ADMENU_SEND,
    'link'  => 'admin/admin_send.php',
    'icon'  => $pathIcon32 . '/mail_foward.png',
];

// Waiting
$adminmenu[] = [
    'title' => _MI_SUBSCRIBERS_ADMENU_WAITING,
    'link'  => 'admin/admin_waiting.php',
    'icon'  => $pathIcon32 . '/exec.png',
];

// Export
$adminmenu[] = [
    'title' => _MI_SUBSCRIBERS_EXPORT,
    'link'  => 'admin/admin_export.php',
    'icon'  => $pathIcon32 . '/export.png',
];

$adminmenu[] = [
    'title' => _MI_SUBSCRIBERS_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];

//$subscribers_adminmenu = $adminObject;

//if (isset($xoopsModule) && $xoopsModule->getVar('dirname') == basename(dirname(__DIR__))) {
//    $subscribers_url = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname');
//
//    $i = 0;
//    $subscribers_headermenu[$i]['title'] = _AM_SUBSCRIBERS_GOMOD;
//    $subscribers_headermenu[$i]['link'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname');
//
//    ++$i;
//    $subscribers_headermenu[$i]['title'] = _AM_SUBSCRIBERS_BLOCKS;
//    $subscribers_headermenu[$i]['link'] = '../../system/admin.php?fct=blocksadmin&amp;selvis=-1&amp;selmod=-2&amp;selgrp=-1&amp;selgen=' . $xoopsModule->getVar('mid');
//
//    ++$i;
//    $subscribers_headermenu[$i]['title'] = _PREFERENCES;
//    $subscribers_headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&op=showmod&amp;mod='. $xoopsModule->getVar('mid');
//
//    ++$i;
//    $subscribers_headermenu[$i]['title'] = _AM_SUBSCRIBERS_UPDATE_MODULE;
//    $subscribers_headermenu[$i]['link'] = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module=' . $xoopsModule->getVar('dirname');
//
//    ++$i;
//    $subscribers_headermenu[$i]['title'] = _AM_SUBSCRIBERS_EXPORT;
//    $subscribers_headermenu[$i]['link'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/admin_export.php';
//
//}
//

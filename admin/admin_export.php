<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

use XoopsModules\Subscribers;

require_once __DIR__ . '/admin_header.php';
error_reporting(0);
$xoopsLogger->activated = false;

require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
$countries = \XoopsLists::getCountryList();

$header   = [
    'user_email'   => _AM_SUBSCRIBERS_EMAIL,
    'user_name'    => _AM_SUBSCRIBERS_NAME,
    'user_country' => _AM_SUBSCRIBERS_COUNTRY,
    'user_phone'   => _AM_SUBSCRIBERS_PHONE,//"user_created" => _AM_SUBSCRIBERS_CREATED,
];
$items    = [];
$keys     = array_keys($header);
$uHandler = new Subscribers\UserHandler();
//$objs = $uHandler->getObjects();
$objs = $uHandler->getAll(null, $keys);
foreach ($objs as $key => $obj) {
    $objs[$key]->setVar('user_country', $countries[$obj->getVar('user_country')]);
}

foreach ($objs as $obj) {
    foreach ($keys as $key) {
        $item[$key] = $obj->getVar($key, 'e');
    }
    $items[] = $item;
    unset($item);
}
//windows-1256

if (false === xoops_load('PHPExcel', 'framework')) {
    redirect_header('admin_user.php', 2, _AM_SUBSCRIBERS_MISSING_PHPEXCEL);
}
if (_CHARSET === 'windows-1256') {
    include PHPEXCEL_ROOT . '/xoopsPHPExcelArabic.php';
    $exporter                 = new \XoopsPHPExcelArabic();
    $exporter->convertCharset = true;
} else {
    include PHPEXCEL_ROOT . '/xoopsPHPExcelAbstract.php';
    $exporter = new \XoopsPHPExcelAbstract();
}

//$exporter->debug = true;
$exporter->render($header, $items);

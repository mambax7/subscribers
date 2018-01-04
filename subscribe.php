<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

require_once __DIR__ . '/../../mainfile.php';

$redirect = $_SERVER['HTTP_REFERER'];

xoops_load('captcha');
$xoopsCaptcha = XoopsCaptcha::getInstance();
if (!$xoopsCaptcha->verify()) {
    redirect_header($redirect, 2, $xoopsCaptcha->getMessage());
}

$myts    = \MyTextSanitizer::getInstance();
$country = isset($_POST['user_country']) ? $myts->stripSlashesGPC($_POST['user_country']) : '';
$email   = isset($_POST['user_email']) ? trim($myts->stripSlashesGPC($_POST['user_email'])) : '';
$name    = isset($_POST['user_name']) ? trim($myts->stripSlashesGPC($_POST['user_name'])) : $GLOBALS['xoopsConfig']['anonymous'];
$phone   = isset($_POST['user_phone']) ? trim($myts->stripSlashesGPC($_POST['user_phone'])) : '';

$stop = false;

if (!checkEmail($email)) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_ERROR_BADEMAIL);
}
if (strrpos($email, ' ') > 0) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_ERROR_BADEMAIL);
}

require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
$countries = XoopsLists::getCountryList();
if (!in_array($country, array_keys($countries))) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_NO_THANKS);
}

$userHandler = xoops_getModuleHandler('user');
$criteria    = new Criteria('user_email', $myts->addSlashes($email));
$count       = $userHandler->getCount($criteria);
unset($criteria);

if ($count > 0) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_ERROR_ALREADY);
}

if ($stop) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_NO_THANKS);
}

$user = $userHandler->create();
$user->setVar('user_email', $email);
$user->setVar('user_name', $name);
$user->setVar('user_country', $country);
$user->setVar('user_phone', $phone);
$user->setVar('user_created', time());

if (false === $userHandler->insert($user)) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_NO_THANKS);
}

redirect_header($redirect, 2, _MD_SUBSCRIBERS_THANKS);

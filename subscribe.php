<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

use Xmf\Request;
use XoopsModules\Subscribers;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

$redirect = Request::getString('HTTP_REFERER', '', 'SERVER');

xoops_load('captcha');
$xoopsCaptcha = XoopsCaptcha::getInstance();
if (!$xoopsCaptcha->verify()) {
    redirect_header($redirect, 2, $xoopsCaptcha->getMessage());
}

$myts    = \MyTextSanitizer::getInstance();
$country = \Xmf\Request::getString('user_country', '', 'POST');
$email   = \Xmf\Request::getString('user_email', '', 'POST');
$name    = \Xmf\Request::getString('user_name', $GLOBALS['xoopsConfig']['anonymous'], 'POST');
$phone   = \Xmf\Request::getString('user_phone', '', 'POST');

$stop = false;

if (!checkEmail($email)) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_ERROR_BADEMAIL);
}
if (strrpos($email, ' ') > 0) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_ERROR_BADEMAIL);
}

require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
$countries = \XoopsLists::getCountryList();
if (!array_key_exists($country, $countries)) {
    redirect_header($redirect, 2, _MD_SUBSCRIBERS_NO_THANKS);
}

$userHandler = new Subscribers\UserHandler();
$criteria    = new \Criteria('user_email', $myts->addSlashes($email));
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

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Subscribers
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 */

require_once  dirname(dirname(__DIR__)) . '/mainfile.php';

if (!isset($_GET['email']) || !isset($_GET['key'])) {
    redirect_header(XOOPS_URL, 2, _MD_SUBSCRIBERS_U_NO_THANKS);
}

$email = $_GET['email'];
$key   = $_GET['key'];

$truekey = md5($email . XOOPS_ROOT_PATH);

if ($truekey != $key) {
    redirect_header(XOOPS_URL, 2, _MD_SUBSCRIBERS_U_NO_THANKS);
}

$userHandler = xoops_getModuleHandler('user');
$criteria    = new \Criteria('user_email', $email);
$users       = $userHandler->getObjects($criteria);

unset($criteria);
if (0 == count($users)) {
    redirect_header(XOOPS_URL, 2, _MD_SUBSCRIBERS_U_NO_THANKS);
}

//delete user from subscribers
$user = $users[0];
$userHandler->delete($user, true);

//delete all wating emails related to this user
$wtHandler = xoops_getModuleHandler('waiting');
$criteria  = new \Criteria('wt_toemail', $email);
$wtHandler->deleteAll($criteria);
unset($criteria);

redirect_header(XOOPS_URL, 5, _MD_SUBSCRIBERS_U_THANKS);

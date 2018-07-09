<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

use XoopsModules\Subscribers;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * @param int    $currentoption
 * @param string $breadcrumb
 */
function subscribers_adminMenu($currentoption = 0, $breadcrumb = '')
{
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    require_once XOOPS_ROOT_PATH . '/modules/subscribers/admin/menu.php';

    xoops_loadLanguage('admin', 'subscribers');
    xoops_loadLanguage('modinfo', 'subscribers');

    $tpl = new \XoopsTpl();
    $tpl->assign([
                     'modurl'          => XOOPS_URL . '/modules/subscribers',
                     'headermenu'      => $subscribers_headermenu,
                     'adminmenu'       => $subscribers_adminmenu,
                     'current'         => $currentoption,
                     'breadcrumb'      => $breadcrumb,
                     'headermenucount' => count($subscribers_headermenu)
                 ]);
    $tpl->display(XOOPS_ROOT_PATH . '/modules/subscribers/templates/static/subscribers_admin_menu.tpl');
}

/**
 * @return FALSE|mixed|\XoopsModule
 */
function &subscribers_getModuleHandler()
{
    static $handler;

    if (null === $handler) {
        global $xoopsModule;
        if (null !== $xoopsModule && is_object($xoopsModule) && 'subscribers' === $xoopsModule->getVar('dirname')) {
            $handler = $xoopsModule;
        } else {
            $hModule = xoops_getHandler('module');
            $handler = $hModule->getByDirname('subscribers');
        }
    }

    return $handler;
}

/**
 * @return mixed
 */
function &subscribers_getModuleConfig()
{
    static $config;

    if (!$config) {
        global $xoopsModule;
        if (null !== $xoopsModule && is_object($xoopsModule) && 'subscribers' === $xoopsModule->getVar('dirname')) {
            $config = $GLOBALS['xoopsModuleConfig'];
        } else {
            $handler    =& subscribers_getModuleHandler();
            $hModConfig = xoops_getHandler('config');
            $config     = $hModConfig->getConfigsByCat(0, $handler->getVar('mid'));
        }
    }

    return $config;
}

/**
 * @return bool
 */
function subscribers_sendEmails()
{
    global $xoopsConfig;
    $thisConfigs   =& subscribers_getModuleConfig();
    $emailsperpack = (int)$thisConfigs['emailsperpack'];
    $timebpacks    = (int)$thisConfigs['timebpacks'];

    $fromname  = trim($thisConfigs['fromname']);
    $fromemail = trim($thisConfigs['fromemail']);
    $fromname  = '' != $fromname ? $fromname : $xoopsConfig['sitename'];
    $fromemail = '' != $fromemail ? $fromemail : $xoopsConfig['adminmail'];

    $now  = time();
    $last = subscribers_getLastTime();

    if (($now - $last) <= $timebpacks) {
        return false;
    }

    $thisHandler = new Subscribers\WaitingHandler();

    $criteria = new \CriteriaCompo();
    $criteria->setSort('wt_priority DESC, wt_created');
    $criteria->setOrder('ASC');
    $criteria->setLimit($emailsperpack);
    $objs  = $thisHandler->getObjects($criteria);
    $count = count($objs);
    unset($criteria);

    if (0 == $count) {
        return false;
    }

    require_once XOOPS_ROOT_PATH . '/kernel/user.php';

    $obj_delete = [];
    foreach ($objs as $obj) {
        $xoopsMailer                           = xoops_getMailer();
        $xoopsMailer->multimailer->ContentType = 'text/html';
        $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/subscribers/language/' . $xoopsConfig['language'] . '/mail_template/');
        $xoopsMailer->setTemplate('content.tpl');
        $xoopsMailer->setFromName($fromname);
        $xoopsMailer->setFromEmail($fromemail);
        $xoopsMailer->useMail();
        $xoopsMailer->setToEmails([$obj->getVar('wt_toemail', 'n')]);
        $xoopsMailer->setSubject($obj->getVar('wt_subject'), 'n');
        $xoopsMailer->assign('CONTENT', $obj->getVar('wt_body'));

        $key = md5($obj->getVar('wt_toemail', 'n') . XOOPS_ROOT_PATH);
        $xoopsMailer->assign('UNSUBSCRIBE_URL', XOOPS_URL . '/modules/subscribers/unsubscribe.php?email=' . $obj->getVar('wt_toemail', 'n') . '&key=' . $key);

        $xoopsMailer->send(false);
        unset($xoopsMailer);

        $obj_delete[] = $obj->getVar('wt_id');
    }

    $criteria = new \Criteria('wt_id', '(' . implode(',', $obj_delete) . ')', 'IN');
    $thisHandler->deleteAll($criteria, true);

    subscribers_setLastTime($now);

    return true;
}

/**
 * @return int
 */
function subscribers_getLastTime()
{
    $fileName = XOOPS_UPLOAD_PATH . '/subscribers_lasttime.txt';

    if (!file_exists($fileName)) {
        $time = time();
        $ret  = subscribers_setLastTime($time);

        return $ret;
    }

    $ret = (int)file_get_contents($fileName);

    return $ret;
}

/**
 * @param int $time
 * @return int
 */
function subscribers_setLastTime($time = 0)
{
    $ret      = 0;
    $fileName = XOOPS_UPLOAD_PATH . '/subscribers_lasttime.txt';
    @unlink($fileName);
    $fileHandler = fopen($fileName, 'wb');
    if (!$fileHandler) {
        return $ret;
    }
    fwrite($fileHandler, $time);
    fclose($fileHandler);

    return $time;
}

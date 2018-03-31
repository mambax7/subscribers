<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

use XoopsModules\Subscribers;
/** @var Subscribers\Helper $helper */
$helper = Subscribers\Helper::getInstance();

require_once __DIR__ . '/admin_header.php';

if (!empty($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
if (!empty($_GET)) {
    foreach ($_GET as $k => $v) {
        ${$k} = $v;
    }
}

$op = isset($_GET['op']) ? trim($_GET['op']) : (isset($_POST['op']) ? trim($_POST['op']) : 'list');
$id = \Xmf\Request::getInt('id', (isset($_POST['id']) ? (int)$_POST['id'] : null), 'GET');

$limit = \Xmf\Request::getInt('limit', (isset($_POST['limit']) ? (int)$_POST['limit'] : 15), 'GET');
$start = \Xmf\Request::getInt('start', (isset($_POST['start']) ? (int)$_POST['start'] : 0), 'GET');
$redir = isset($_GET['redir']) ? $_GET['redir'] : (isset($_POST['redir']) ? $_POST['redir'] : null);

switch ($op) {
    case 'list':
        xoops_cp_header();
        //        subscribers_adminMenu(0, _MI_SUBSCRIBERS_ADMENU_USER);
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        echo user_index($start);
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'add':
        user_add($id);
        break;
    case 'edit':
        xoops_cp_header();
        //        subscribers_adminMenu(0, _MI_SUBSCRIBERS_ADMENU_USER);
        echo user_form($id);
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'editok':
        user_edit($id);
        break;
    case 'del':
        user_confirmdel($id, $redir);
        break;
    case 'delok':
        user_del($id, $redir);
        break;
}

function user_index($start = 0)
{
    global $xoopsTpl, $xoopsUser, $xoopsConfig, $limit;
    $myts = \MyTextSanitizer::getInstance();

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $thisHandler   = xoops_getModuleHandler('user', 'subscribers');
    $moduleHandler = xoops_getHandler('module');

    $query = isset($_POST['query']) ? $_POST['query'] : null;
    $xoopsTpl->assign('query', $query);

    $criteria = null;
    if (!is_null($query)) {
        $criteria = new \Criteria('user_email', $myts->addSlashes($query) . '%', 'LIKE');
    }

    $count = $thisHandler->getCount($criteria);
    $xoopsTpl->assign('count', $count);

    $mHandler    = xoops_getHandler('member');
    $users_count = $mHandler->getUserCount(new \Criteria('level', 0, '>'));
    $xoopsTpl->assign('users_count', $users_count);
    $xoopsTpl->assign('total_count', $users_count + $count);

    $criteria = new \CriteriaCompo($criteria);
    $criteria->setSort('user_id');
    $criteria->setOrder('DESC');
    $criteria->setStart($start);
    $criteria->setLimit($limit);
    $objs = $thisHandler->getObjects($criteria);
    unset($criteria);

    if ($count > 0) {
        if ($count > $limit) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $nav = new \XoopsPageNav($count, $limit, $start, 'start', 'op=list');
            $xoopsTpl->assign('pag', '<div style="float:left; padding-top:2px;" align="center">' . $nav->renderNav() . '</div>');
        } else {
            $xoopsTpl->assign('pag', '');
        }
    } else {
        $xoopsTpl->assign('pag', '');
    }

    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    $countries = XoopsLists::getCountryList();

    foreach ($objs as $obj) {
        $objArray                 = $obj->toArray();
        $objArray['user_country'] = $countries[$objArray['user_country']];
        $xoopsTpl->append('objs', $objArray);
        unset($objArray);
    }
    $xoopsTpl->assign('add_form', user_form());

    return $xoopsTpl->fetch(XOOPS_ROOT_PATH . '/modules/subscribers/templates/static/subscribers_admin_user.tpl');
}

function user_add($id)
{
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin_user.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    $thisHandler = xoops_getModuleHandler('user', 'subscribers');

    $criteria = new \Criteria('user_id', $id);
    $count    = $thisHandler->getCount($criteria);
    if ($count > 0) {
        $obj = $thisHandler->get($id);
    } else {
        $obj = $thisHandler->create();
    }
    $obj->setVars($_POST);
    $obj->setVar('user_created', time());

    if (!$thisHandler->insert($obj)) {
        $msg = _AM_SUBSCRIBERS_ERROR;
    } else {
        $msg = _AM_SUBSCRIBERS_SUCCESS;
    }

    redirect_header('admin_user.php', 2, $msg);
}

function user_edit($id)
{
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin_user.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    $thisHandler = xoops_getModuleHandler('user', 'subscribers');
    $obj         = $thisHandler->get($id);

    $obj->setVars($_POST);
    $obj->setVar('user_created', time());

    if (!$thisHandler->insert($obj)) {
        $msg = _AM_SUBSCRIBERS_ERROR;
    } else {
        $msg = _AM_SUBSCRIBERS_SUCCESS;
    }

    redirect_header('admin_user.php', 2, $msg);
}

function user_del($id, $redir = null)
{
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin_user.php', 1, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    if ($id <= 0) {
        redirect_header('admin_user.php', 1);
    }

    $thisHandler = xoops_getModuleHandler('user', 'subscribers');
    $obj         = $thisHandler->get($id);
    if (!is_object($obj)) {
        redirect_header('admin_user.php', 1);
    }

    if (!$thisHandler->delete($obj)) {
        xoops_cp_header();
        xoops_error(_AM_SUBSCRIBERS_ERROR, $obj->getVar('id'));
        xoops_cp_footer();
        exit();
    }

    redirect_header(!is_null($redir) ? base64_decode($redir) : 'admin_user.php', 2, _AM_SUBSCRIBERS_SUCCESS);
}

function user_confirmdel($id, $redir = null)
{
    global $xoopsConfig;

    $thisHandler = xoops_getModuleHandler('user', 'subscribers');
    $obj         = $thisHandler->get($id);

    xoops_cp_header();

    $arr       = [];
    $arr['op'] = 'delok';
    $arr['id'] = $id;
    if (!is_null($redir)) {
        $arr['redir'] = $redir;
    }

    xoops_confirm($arr, 'admin_user.php', _AM_SUBSCRIBERS_AYS);

    xoops_cp_footer();
}

function user_form($id = null)
{
    global $xoopsUser, $xoopsConfig ;
    /** @var Subscribers\Helper $helper */
    $helper = Subscribers\Helper::getInstance();

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $thisHandler = xoops_getModuleHandler('user', 'subscribers');

    if (isset($id)) {
        $obj = $thisHandler->get($id);
    }

    if (@is_object($obj)) {
        $title   = _EDIT;
        $name    = $obj->getVar('user_name', 'e');
        $email   = $obj->getVar('user_email', 'e');
        $country = $obj->getVar('user_country', 'e');
        $phone   = $obj->getVar('user_phone', 'e');
    } else {
        $title   = _ADD;
        $name    = '';
        $email   = '';
        $phone   = '';
        $country = $helper->getConfig('country');
    }

    $form = new \XoopsThemeForm($title, 'user_form', 'admin_user.php', 'post', true);

    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    $countries = XoopsLists::getCountryList();
    unset($countries['']);

    $element = new \XoopsFormSelect(_AM_SUBSCRIBERS_COUNTRY, 'user_country', $country);
    $element->addOptionArray($countries);
    $form->addElement($element);
    unset($element);

    $form->addElement(new \XoopsFormText(_AM_SUBSCRIBERS_NAME, 'user_name', 50, 50, $name));
    $form->addElement(new \XoopsFormText(_AM_SUBSCRIBERS_EMAIL, 'user_email', 50, 50, $email));
    $form->addElement(new \XoopsFormText(_AM_SUBSCRIBERS_PHONE, 'user_phone', 50, 50, $phone));

    $tray = new \XoopsFormElementTray('', '');
    $tray->addElement(new \XoopsFormButton('', 'submit_button', _SUBMIT, 'submit'));

    $btn = new \XoopsFormButton('', 'reset', _CANCEL, 'button');

    if (@is_object($obj)) {
        $btn->setExtra('onclick="document.location.href=\'admin_user.php?op=list\'"');
    } else {
        $btn->setExtra('onclick="document.getElementById(\'user_form\').style.display = \'none\'; return false;"');
    }
    $tray->addElement($btn);
    $form->addElement($tray);

    if (@is_object($obj)) {
        $form->addElement(new \XoopsFormHidden('op', 'editok'));
        $form->addElement(new \XoopsFormHidden('id', $id));
    } else {
        $form->addElement(new \XoopsFormHidden('op', 'add'));
    }

    return $form->render();
}

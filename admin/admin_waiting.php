<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

require_once __DIR__ . '/admin_header.php';

$op = isset($_GET['op']) ? trim($_GET['op']) : (isset($_POST['op']) ? trim($_POST['op']) : 'list');
$op = isset($_POST['delall']) ? 'delall' : $op;

$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : null);

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : (isset($_POST['limit']) ? (int)$_POST['limit'] : 15);
$start = isset($_GET['start']) ? (int)$_GET['start'] : (isset($_POST['start']) ? (int)$_POST['start'] : 0);
$redir = isset($_GET['redir']) ? $_GET['redir'] : (isset($_POST['redir']) ? $_POST['redir'] : null);

switch ($op) {
    case 'list':
        xoops_cp_header();
        //        subscribers_adminMenu(2, _MI_SUBSCRIBERS_ADMENU_WAITING);
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        echo waiting_index($start);
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'del':
        waiting_confirmdel($id, $redir);
        break;
    case 'delok':
        waiting_del($id, $redir);
        break;
    case 'delall':
        waiting_confirmdel(null, $redir, 'delallok');
        break;
    case 'delallok':
        waiting_delall($redir);
        break;
}

function waiting_index($start = 0)
{
    global $xoopsTpl, $xoopsUser, $xoopsConfig, $limit;

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/modules/subscribers/include/functions.php';

    subscribers_sendEmails();

    $thisHandler = xoops_getModuleHandler('waiting', 'subscribers');

    $count = $thisHandler->getCount();
    $xoopsTpl->assign('count', $count);

    $criteria = new \CriteriaCompo();
    $criteria->setSort('wt_priority DESC, wt_created');
    $criteria->setOrder('ASC');
    $criteria->setStart($start);
    $criteria->setLimit($limit);
    $objs = $thisHandler->getObjects($criteria);

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
        $objArray               = $obj->toArray();
        $objArray['wt_created'] = formatTimestamp($objArray['wt_created']);
        $xoopsTpl->append('objs', $objArray);
        unset($objArray);
    }

    return $xoopsTpl->fetch(XOOPS_ROOT_PATH . '/modules/subscribers/templates/static/subscribers_admin_waiting.tpl');
}

function waiting_del($id, $redir = null)
{
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin_waiting.php', 1, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    if ($id <= 0) {
        redirect_header('admin_waiting.php', 1);
    }

    $thisHandler = xoops_getModuleHandler('waiting', 'subscribers');
    $obj         = $thisHandler->get($id);
    if (!is_object($obj)) {
        redirect_header('admin_waiting.php', 1);
    }

    if (!$thisHandler->delete($obj)) {
        xoops_cp_header();
        xoops_error(_AM_SUBSCRIBERS_ERROR, $obj->getVar('id'));
        xoops_cp_footer();
        exit();
    }

    redirect_header(!is_null($redir) ? base64_decode($redir) : 'admin_waiting.php', 2, _AM_SUBSCRIBERS_SUCCESS);
}

function waiting_delall($redir = null)
{
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin_waiting.php', 1, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    $thisHandler = xoops_getModuleHandler('waiting', 'subscribers');

    if (!$thisHandler->deleteAll()) {
        redirect_header(!is_null($redir) ? base64_decode($redir) : 'admin_waiting.php', 2, _AM_SUBSCRIBERS_ERROR);
    }

    redirect_header(!is_null($redir) ? base64_decode($redir) : 'admin_waiting.php', 2, _AM_SUBSCRIBERS_SUCCESS);
}

function waiting_confirmdel($id, $redir = null, $op = 'delok')
{
    global $xoopsConfig;

    $thisHandler = xoops_getModuleHandler('waiting', 'subscribers');
    $obj         = $thisHandler->get($id);

    xoops_cp_header();

    $arr       = [];
    $arr['op'] = $op;
    $arr['id'] = $id;
    if (!is_null($redir)) {
        $arr['redir'] = $redir;
    }

    xoops_confirm($arr, 'admin_waiting.php', _AM_SUBSCRIBERS_AYS);

    xoops_cp_footer();
}

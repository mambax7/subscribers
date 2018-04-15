<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

use XoopsModules\Subscribers;

require_once __DIR__ . '/admin_header.php';

/** @var Subscribers\Helper $helper */
$helper = Subscribers\Helper::getInstance();

$op = isset($_GET['op']) ? trim($_GET['op']) : (isset($_POST['op']) ? trim($_POST['op']) : 'form');

switch ($op) {
    case 'email':
        send_email();
        break;
    case 'form':
    default:
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        //        subscribers_adminMenu(1, _MI_SUBSCRIBERS_ADMENU_SEND);
        echo send_form();
        require_once __DIR__ . '/admin_footer.php';
        break;
}

function send_form()
{
    /** @var Subscribers\Helper $helper */
    $helper = Subscribers\Helper::getInstance();

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $thisHandler = xoops_getModuleHandler('user', 'subscribers');

    $form = new \XoopsThemeForm(_AM_SUBSCRIBERS_SEND, 'send_form', 'admin_send.php', 'post', true);

    $element = new \XoopsFormLabel(_MI_SUBSCRIBERS_CONF_FROMNAME, $helper->getConfig('fromname'));
    $form->addElement($element);
    unset($element);

    $element = new \XoopsFormLabel(_MI_SUBSCRIBERS_CONF_FROMEMAIL, $helper->getConfig('fromemail'));
    $form->addElement($element);
    unset($element);

    // Country
    require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
    $countries  = ['ALL' => _AM_SUBSCRIBERS_ALL_COUNTRIES];
    $countries2 = \XoopsLists::getCountryList();
    array_shift($countries2);
    $countries += $countries2;

    $element = new \XoopsFormSelect(_AM_SUBSCRIBERS_COUNTRY, 'country', 'ALL');
    $element->addOptionArray($countries);
    $form->addElement($element);
    unset($element, $countries);

    // Subject
    $form->addElement(new \XoopsFormText(_AM_SUBSCRIBERS_EMAIL_SUBJECT, 'subject', 75, 150, ''), true);

    // Body
    $editor_configs           = [];
    $editor_configs['rows']   = 35;
    $editor_configs['cols']   = 60;
    $editor_configs['width']  = '100%';
    $editor_configs['height'] = '400px';
    $editor_configs['name']   = 'body';
    $editor_configs['value']  = '';
    $element                  = new \XoopsFormEditor(_AM_SUBSCRIBERS_EMAIL_BODY, $helper->getConfig('editor'), $editor_configs, $nohtml = false, $onfailure = null);
    $element->setDescription(_AM_SUBSCRIBERS_EMAIL_BODY_DSC);
    $form->addElement($element);
    unset($element);

    // Priority
    $priorities = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    unset($priorities[0]);
    $element = new \XoopsFormSelect(_AM_SUBSCRIBERS_EMAIL_PRIORITY, 'priority', 5);
    $element->setDescription(_AM_SUBSCRIBERS_EMAIL_PRIORITY_DSC);
    $element->addOptionArray($priorities);
    $form->addElement($element);
    unset($element, $priorities);

    // Groups
    $groups  = [_AM_SUBSCRIBERS_SUBSCRIBERS, _AM_SUBSCRIBERS_USERS, _AM_SUBSCRIBERS_BOTH];
    $element = new \XoopsFormSelect(_AM_SUBSCRIBERS_EMAIL_GROUPS, 'groups', 0);
    $element->setDescription(_AM_SUBSCRIBERS_EMAIL_GROUPS_DSC);
    $element->addOptionArray($groups);
    $form->addElement($element);
    unset($element, $groups);

    // Buttons
    $tray = new \XoopsFormElementTray('', '');
    $tray->addElement(new \XoopsFormButton('', 'submit_button', _SUBMIT, 'submit'));

    $btn = new \XoopsFormButton('', 'reset', _CANCEL, 'button');
    $btn->setExtra('onclick="document.location.href=\'admin_send.php\'"');

    $tray->addElement($btn);
    $form->addElement($tray);

    $form->addElement(new \XoopsFormHidden('op', 'email'));

    return $form->render();
}

function send_email()
{
    $vars                = [];
    $vars['wt_priority'] = \Xmf\Request::getInt('priority', 5, 'POST');
    $vars['wt_created']  = time();

    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $body    = isset($_POST['body']) ? trim($_POST['body']) : '';
    $country = isset($_POST['country']) ? $_POST['country'] : 'ALL';
    $groups  = \Xmf\Request::getInt('groups', 0, POST);

    $userHandler = xoops_getModuleHandler('user', 'subscribers');
    $wtHandler   = xoops_getModuleHandler('waiting', 'subscribers');

    $error = false;

    if (0 == $groups || 2 == $groups) {
        $criteria = null;
        if ('ALL' !== $country) {
            $criteria = new \Criteria('user_country', $country);
        }
        $objs = $userHandler->getObjects($criteria);
        unset($criteria);

        foreach ($objs as $obj) {
            $waiting            = $wtHandler->create();
            $vars['wt_toname']  = $obj->getVar('user_name', 'n');
            $vars['wt_toemail'] = $obj->getVar('user_email', 'n');

            $vars['wt_subject'] = str_replace('{NAME}', $vars['wt_toname'], $subject);
            $vars['wt_subject'] = str_replace('{EMAIL}', $vars['wt_toemail'], $vars['wt_subject']);

            $vars['wt_body'] = str_replace('{NAME}', $vars['wt_toname'], $body);
            $vars['wt_body'] = str_replace('{EMAIL}', $vars['wt_toemail'], $vars['wt_body']);

            $waiting->setVars($vars);
            if (!$wtHandler->insert($waiting)) {
                true === $error;
            }
            unset($waiting);
        }
        unset($objs);
    }

    if (1 == $groups || 2 == $groups) {
        require_once XOOPS_ROOT_PATH . '/kernel/user.php';
        $memberHandler = new \XoopsUserHandler($GLOBALS['xoopsDB']);
        $criteria      = new \Criteria('level', 0, '>');
        $members       = $memberHandler->getAll($criteria, ['uname', 'email'], false, false); //Using this to not exaust server resources
        unset($criteria);

        foreach ($members as $member) {
            $waiting            = $wtHandler->create();
            $vars['wt_toname']  = $member['uname'];
            $vars['wt_toemail'] = $member['email'];

            $vars['wt_subject'] = str_replace('{NAME}', $vars['wt_toname'], $subject);
            $vars['wt_subject'] = str_replace('{EMAIL}', $vars['wt_toemail'], $vars['wt_subject']);

            $vars['wt_body'] = str_replace('{NAME}', $vars['wt_toname'], $body);
            $vars['wt_body'] = str_replace('{EMAIL}', $vars['wt_toemail'], $vars['wt_body']);

            $waiting->setVars($vars);
            if (!$wtHandler->insert($waiting)) {
                true === $error;
            }
            unset($waiting);
        }
        unset($members);
    }

    if ($error) {
        redirect_header('admin_send.php', 2, _AM_SUBSCRIBERS_SOME_ERROR);
    }

    redirect_header('admin_waiting.php', 2, _AM_SUBSCRIBERS_SUCCESS);
}

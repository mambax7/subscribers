<?php
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once __DIR__ . '/preloads/autoloader.php';

$modversion['version']       = 1.2;
$modversion['module_status'] = 'Beta 2';
$modversion['release_date']  = '2017/07/23';
$modversion['name']          = _MI_SUBSCRIBERS_MD_NAME;
$modversion['description']   = _MI_SUBSCRIBERS_MD_DSC;
$modversion['author']        = 'Trabis - www.xuups.com, credit: www.arabxoops.com';
$modversion['credits']       = 'Mowaffaq & Mariane - www.arabxoops.com';
$modversion['help']          = 'page=help';
$modversion['license']       = 'GNU GPL 2.0';
$modversion['license_url']   = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']      = 0; //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
$modversion['dirname']       = basename(__DIR__);
$modversion['image']         = 'assets/images/logoModule.png';
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';
$modversion['release_file']        = XOOPS_URL . '/modules/' . $modversion['dirname'] . '/docs/changelog.txt';
$modversion['demo_site_url']       = '';
$modversion['demo_site_name']      = '';
$modversion['module_website_url']  = 'https://xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['author_website_url']  = 'http://www.myweb.ne.jp';
$modversion['author_website_name'] = 'Kazumi Ono';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'subscribers_user';
$modversion['tables'][1] = 'subscribers_waiting';

// Search
$modversion['hasSearch'] = 0;
// Menu
$modversion['hasMain']     = 1;
$modversion['system_menu'] = 1;

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_SUBSCRIBERS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_SUBSCRIBERS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_SUBSCRIBERS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_SUBSCRIBERS_SUPPORT, 'link' => 'page=support'],
];

// Templates
$i = 0;
++$i;
$modversion['templates'][$i]['file']        = 'subscribers_index.tpl';
$modversion['templates'][$i]['description'] = _MI_SUBSCRIBERS_PAGE_INDEX;

// Blocks
$i = 0;
++$i;
$modversion['blocks'][$i]['file']        = 'subscribers_add.php';
$modversion['blocks'][$i]['name']        = _MI_SUBSCRIBERS_BLK_ADD;
$modversion['blocks'][$i]['description'] = 'Subscription block';
$modversion['blocks'][$i]['show_func']   = 'subscribers_add_show';
$modversion['blocks'][$i]['template']    = 'subscribers_add.tpl';

// Configs
$i = 0;

//default value for Xoops editor
xoops_load('XoopsEditorHandler');
$editorHandler = XoopsEditorHandler::getInstance();

++$i;
$modversion['config'][$i]['name']        = 'editor';
$modversion['config'][$i]['title']       = '_MI_SUBSCRIBERS_CONF_EDITOR';
$modversion['config'][$i]['description'] = '_MI_SUBSCRIBERS_CONF_EDITOR_DSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['options']     = array_flip($editorHandler->getList());
$modversion['config'][$i]['default']     = 'dhtmltextarea';

//default value for country
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
$countries = XoopsLists::getCountryList();
unset($countries['']);

++$i;
$modversion['config'][$i]['name']        = 'country';
$modversion['config'][$i]['title']       = '_MI_SUBSCRIBERS_CONF_COUNTRY';
$modversion['config'][$i]['description'] = '_MI_SUBSCRIBERS_CONF_COUNTRY_DSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['options']     = array_flip($countries);
$modversion['config'][$i]['default']     = 'PT';

++$i;
$modversion['config'][$i]['name']        = 'fromname';
$modversion['config'][$i]['title']       = '_MI_SUBSCRIBERS_CONF_FROMNAME';
$modversion['config'][$i]['description'] = '_MI_SUBSCRIBERS_CONF_FROMNAME_DSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';

++$i;
$modversion['config'][$i]['name']        = 'fromemail';
$modversion['config'][$i]['title']       = '_MI_SUBSCRIBERS_CONF_FROMEMAIL';
$modversion['config'][$i]['description'] = '_MI_SUBSCRIBERS_CONF_FROMEMAIL_DSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';

++$i;
$modversion['config'][$i]['name']        = 'emailsperpack';
$modversion['config'][$i]['title']       = '_MI_SUBSCRIBERS_CONF_EMAILSPERPACK';
$modversion['config'][$i]['description'] = '_MI_SUBSCRIBERS_CONF_EMAILSPERPACK_DSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;

++$i;
$modversion['config'][$i]['name']        = 'timebpacks';
$modversion['config'][$i]['title']       = '_MI_SUBSCRIBERS_CONF_TIMEBPACKS';
$modversion['config'][$i]['description'] = '_MI_SUBSCRIBERS_CONF_TIMEBPACKS_DSC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 3600;

++$i;
$modversion['config'][$i]['name']        = 'captcha';
$modversion['config'][$i]['title']       = '_MI_SUBSCRIBERS_CONF_CAPTCHA';
$modversion['config'][$i]['description'] = '_MI_SUBSCRIBERS_CONF_CAPTCHA';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 2;
$modversion['config'][$i]['options']     = [
    _MI_SUBSCRIBERS_CONF_CAPTCHA_CHOICE1 => 1,
    _MI_SUBSCRIBERS_CONF_CAPTCHA_CHOICE2 => 2,
    _MI_SUBSCRIBERS_CONF_CAPTCHA_CHOICE3 => 3
];
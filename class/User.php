<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

/**
 * Class User
 */
class User extends \XoopsObject
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->initVar("user_id", XOBJ_DTYPE_INT, null, false);
        $this->initVar("user_email", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("user_name", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("user_country", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("user_phone", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("user_created", XOBJ_DTYPE_INT, null, false);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (array_keys($vars) as $i) {
            $ret[$i] = $this->getVar($i);
        }
        return $ret;
    }
}

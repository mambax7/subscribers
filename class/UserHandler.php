<?php namespace XoopsModules\Subscribers;

//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserHandler
 * @package XoopsModules\Subscribers
 */
class UserHandler extends \XoopsPersistableObjectHandler
{
    /**
     * UserHandler constructor.
     * @param \XoopsDatabase|null $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'subscribers_user', User::class, 'user_id', 'user_email');
    }
}

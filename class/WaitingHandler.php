<?php namespace XoopsModules\Subscribers;

//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class WaitingHandler
 * @package XoopsModules\Subscribers
 */
class WaitingHandler extends \XoopsPersistableObjectHandler
{
    /**
     * WaitingHandler constructor.
     * @param \XoopsDatabase|null $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'subscribers_waiting', Waiting::class, 'wt_id', 'wt_subject');
    }
}

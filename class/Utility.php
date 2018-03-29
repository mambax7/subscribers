<?php namespace XoopsModules\Subscribers;

use Xmf\Request;
use XoopsModules\Subscribers;
use XoopsModules\Subscribers\Common;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------
}

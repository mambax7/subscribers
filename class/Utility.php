<?php namespace XoopsModules\Subscribers;

use Xmf\Request;
use XoopsModules\Subscribers;
use XoopsModules\Subscribers\Common;

/**
 * Class Utility
 */
class Utility
{
    use common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use common\ServerStats; // getServerStats Trait

    use common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------

}

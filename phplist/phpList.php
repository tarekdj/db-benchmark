<?php
/**
 * User: SaWey
 * Date: 5/12/13
 */

namespace phpList;

use phpList\helper\IDatabase;
use phpList\helper\MySQLi;
use phpList\UserConfig;
use phpList\helper\DefaultConfig;
use phpList\Config;



class phpList
{
    /**
     * @return IDatabase
     * @throws \Exception
     */
    public static function DB()
    {
        switch (Config::DATABASE_MODULE) {
            case 'mysqli':
                return MySQLi::instance();
                break;
            default:
                throw new \Exception("DB Module not available");
        }
    }

    /**
     * @throws \Exception
     */
    public static function initialise()
    {
        include_once(__DIR__ .'/Config.php');
        Config::initialise();
		//Timer::start('pagestats');
    }
}
function s($a){
    return $a;
}
phpList::initialise();

<?php
/**
 * Pearified_Role_Web_setup_postinstall
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   pear
 * @package    Role_Web
 * @author     Clay Loveless <clay@killersoft.com>
 * @copyright  2005 Clay Loveless
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    SVN: $Id: setup.php 10 2005-08-06 04:58:43Z clay $
 */

/**
 * @category   pear
 * @package    Role_Web
 * @author     Clay Loveless <clay@killersoft.com>
 * @copyright  2005 Clay Loveless
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 */

class Pearified_Role_Web_setup_postinstall
{
    var $_pkg;
    var $_ui;
    var $_config;
    var $_lastverion;
    var $_registry;
    
    function init(&$config, &$pkg, $lastversion)
    {
        $this->_config      =& $config;
        $this->_registry    =& $config->getRegistry();
        $this->_ui          =& PEAR_Frontend::singleton();
        $this->_pkg         =& $pkg;
        $this->_lastversion =  $lastversion;
        
        return true;
    }
    
    function run($answers, $phrase)
    {
        $outputPrefix = $this->_ui->bold('Role_Web Setup: ');
        switch ($phrase) {
            case 'webSetup':
                if ($answers['webdirpath'] != '') {
                    if (is_dir($answers['webdirpath'])) {
                        $doingit = "setting web_dir to {$answers['webdirpath']} ...";
                        $this->_ui->outputData($outputPrefix.$doingit);
                        $this->_config->set('web_dir', $answers['webdirpath']);
                        $e = $this->_config->store();
                        if (PEAR::isError($e)) {
                            $this->_ui->outputData($outputPrefix.$e->getMessage());
                            return false;
                        }
                        return true;
                    } else {
                        $this->_ui->outputData($outputPrefix.'Please choose a path that exists! Setup failed.');
                        return false;
                    }
                }
                return false;
                break;
        }
    }
}
?>
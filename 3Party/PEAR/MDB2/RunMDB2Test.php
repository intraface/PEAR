<?php
if (!defined('PHPUNIT_MAIN_METHOD')) {
    define('PHPUNIT_MAIN_METHOD', 'RunMDB2Test::main');
}

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'MDB2TestCase.php';

class RunMDB2Test {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('MDB2_Tests');

        $suite->addTestSuite('MDB2TestCase');
        return $suite;
    }
}

if (PHPUNIT_MAIN_METHOD == 'RunMDB2Test::main') {
    RunMDB2Test::main();
}
?>
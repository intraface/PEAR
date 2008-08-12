<?php
require_once 'test_setup.php';

$GLOBALS = $mysql;

if (!defined('PHPUNIT_MAIN_METHOD')) {
	define('PHPUNIT_MAIN_METHOD', 'RunTestSchema::main');
}

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'TestSchema.php';

class RunTestSchema {
	public static function main() {
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('MDB2_Schema_Tests');

		$suite->addTestSuite('MDB2_Schema_TestCase');
		return $suite;
	}
}

if (PHPUNIT_MAIN_METHOD == 'RunTestSchema::main') {
	RunTestSchema::main();
}
?>
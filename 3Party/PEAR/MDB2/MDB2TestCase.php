<?php
/**
 * MYSQL SPECIFC
 */

require_once 'PHPUnit/Framework.php';
require_once 'MDB2/Schema.php';

define('TEST_DATABASE_DSN', 'mysql://root:@localhost/test');

class MDB2TestCase extends PHPUnit_FrameWork_TestCase {
    var $dsn;
    var $table;
    var $database;
    var $definition = array('text' => array('type' => 'text'));

    function setUp() {
        $this->dsn = TEST_DATABASE_DSN;
        $this->table = 'test';
        $this->database = 'test';
        $this->file = 'test.xml';
    }

    function tearDown() {
        $db = MDB2::factory($this->dsn);
        $db->loadModule('Manager');
        $db->dropTable($this->table);
        if (file_exists($this->file)) unlink($this->file);
    }

    function testMDB2ManagerUsesDatatypeText() {
        $db = MDB2::factory($this->dsn);
        $db->loadModule('Manager');
        $result = $db->createTable($this->table, $this->definition);
        $this->assertFalse(PEAR::isError($result));

        $this->assertEquals($this->getFieldType(), 'text');

    }

    function testMDB2SchemaUsesDatatypeText() {
        $options = array(
            'debug' => true,
            'force_defaults' => false
        );

        $schema = MDB2_Schema::factory($this->dsn, $options);

        $schema_array['name'] = $this->database;
        $schema_array['create'] = 1;
        $schema_array['overwrite'] = 1;
        $schema_array['tables'] = array($this->table => array('fields' => $this->definition));

        $schema->dumpDatabase(
            $schema_array,
            array(
                'output_mode' => 'file',
                'output' => $this->file
            ),
            MDB2_SCHEMA_DUMP_STRUCTURE
        );
        $result = $schema->updateDatabase($this->file);

        $this->assertFalse(PEAR::isError($result));

        $this->assertEquals($this->getFieldType(), 'text', 'got ' . $this->getFieldType());

    }

    function testMDB2SchemaCanUpdateADatabase() {
        $schema = MDB2_Schema::factory($this->dsn);

        // initial creation
        $schema_array['name'] = $this->database;
        $schema_array['create'] = 1;
        $schema_array['overwrite'] = 0;

        $schema_array['tables'] = array($this->table => array('fields' => $this->definition));

        $schema->dumpDatabase(
            $schema_array,
            array(
                'output_mode' => 'file',
                'output' => $this->file,
                'force_defaults' => false
            ),
            MDB2_SCHEMA_DUMP_STRUCTURE
        );

        $result = $schema->updateDatabase($this->file, $schema->getDefinitionFromDatabase());

        print_r($schema->getDefinitionFromDatabase());

        if (PEAR::isError($result)) $result->getMessage();

        // prepare to update
        $schema = MDB2_Schema::factory($this->dsn);

        $this->definition['myint'] = array('type' => 'integer', 'default' => 10);

        $schema_array['name'] = $this->database;
        $schema_array['create'] = 1;
        $schema_array['overwrite'] = 0;
        $schema_array['tables'] = array($this->table => array('fields' => $this->definition));

        $schema->dumpDatabase(
            $schema_array,
            array(
                'output_mode' => 'file',
                'output' => $this->file,
                'force_defaults' => false
            ),
            MDB2_SCHEMA_DUMP_STRUCTURE
        );

        $result = $schema->updateDatabase($this->file, $schema->getDefinitionFromDatabase());

        $this->assertFalse(PEAR::isError($result));

        print_r($schema->getDefinitionFromDatabase());
    }

    function getFieldType() {
        $db = MDB2::factory($this->dsn);
        $result = $db->query('SHOW FIELDS FROM ' . $this->table);
        $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
        return $row['type'];
    }
}
?>
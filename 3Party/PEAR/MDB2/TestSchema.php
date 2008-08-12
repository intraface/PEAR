<?php
require_once 'PHPUnit/Framework.php';
require_once 'MDB2/Schema.php';

class MDB2_Schema_TestCase extends PHPUnit_FrameWork_TestCase {
    //contains the dsn of the database we are testing
    var $dsn;
    //contains the options that should be used during testing
    var $options;
    //contains the name of the database we are testing
    var $database;
    //contains the MDB2_Schema object of the db once we have connected
    var $schema;
    //contains the name of the driver_test schema
    var $driver_input_file = 'driver_test.schema';
    //contains the name of the lob_test schema
    var $lob_input_file = 'lob_test.schema';
    //contains the name of the extension to use for backup schemas
    var $backup_extension = '.before';

    function setUp() {
        $this->dsn = $GLOBALS['dsn'];
        $this->options = $GLOBALS['options'];
        $this->database = 'test';
        $backup_file = $this->driver_input_file.$this->backup_extension;
        if (file_exists($backup_file)) {
            unlink($backup_file);
        }
        $backup_file = $this->lob_input_file.$this->backup_extension;
        if (file_exists($backup_file)) {
            unlink($backup_file);
        }
        $this->schema = MDB2_Schema::factory($this->dsn, $this->options);
        if (PEAR::isError($this->schema)) {
            $this->assertTrue(false, 'Could not connect to manager in setUp');
            exit;
        }
    }

    function tearDown() {
        unset($this->dsn);
        if (!PEAR::isError($this->schema)) {
            $this->schema->disconnect();
        }
        unset($this->schema);
    }

    function methodExists($class, $name) {
        if (is_object($class)
            && array_key_exists(strtolower($name), array_change_key_case(array_flip(get_class_methods($class)), CASE_LOWER))
        ) {
            return true;
        }
        $this->assertTrue(false, 'method '. $name.' not implemented in '.get_class($class));
        return false;
    }

    function testUpdateDatabase() {
        if (!$this->methodExists($this->schema, 'updateDatabase')) {
            return;
        }

        $current_definition = $this->schema->getDefinitionFromDatabase();
        if (PEAR::isError($current_definition)) {
            die('current: ' . $current_definition->getMessage() . $current_definition->getUserInfo());
        }

        $new_definition = $this->schema->parseDatabaseDefinitionFile($this->driver_input_file, array('name' => $this->database, 'create' => 0));

        if (PEAR::isError($new_definition)) {
            die('new: ' . $new_definition->getMessage(). $new_definition->getUserInfo());
        }

        $compared_definition = $this->schema->compareDefinitions($new_definition, $current_definition);

        if (PEAR::isError($compared_definition)) {
            die($compared_definition->getMessage());
        }


        $result = $this->schema->updateDatabase(
            $this->driver_input_file,
            $current_definition,
            array('create' =>'0', 'name' =>$this->database)
        );
        if (PEAR::isError($result)) {
            die($result->getUserInfo);
        }
        $this->assertFalse(PEAR::isError($result), 'Error updating database');
    }
}

?>
--TEST--
See how downgrading will work
--FILE--
<?php
set_include_path('c:/Users/Lars Olesen/workspace/pear/MDB2_Schema/'. PATH_SEPARATOR . get_include_path());

require 'MDB2/Schema.php';

$dsn = 'mysql://root:@localhost/test';
$schema = MDB2_Schema::factory($dsn);
if (PEAR::isError($schema)) {
    echo $schema->getUserInfo();
}

//update
$result = $schema->updateDatabase('schema.xml', null);
if (PEAR::isError($result)) {
    echo $result->getUserInfo();
}


// downgrade
$result = $schema->updateDatabase('schema_downgrade.xml', $schema->dumpDatabase('schema.xml', array()));
if (PEAR::isError($result)) {
    echo $result->getUserInfo();
}
?>
--EXPECT--

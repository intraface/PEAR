<?php
require 'MDB2/Schema.php';

$definition = array(
    'name' => 'test',
    'create' => 1,
    'overwrite' => 0,
    'tables' => array(
        'test' => array(
            'fields' => array(
                'id'   => array(
                    'type'     => 'integer',
                    'notnull'  => 1,
                    'length'   => 6,
                    'unsigned' => 1,
                    'default'  => 0
                ),
                'name' => array(
                    'type'     => 'text',
                    'length'   => 255,
                    'default'  => 'None'
                ),
                'comment' => array(
                    'type'     => 'text'
                )
            )
        )
    )
);

$dsn = 'mysql://root:@localhost/test';
$options = array(
    'output_mode' => 'file',
    'output' => 'test.xml'
);
$schema = MDB2_Schema::factory($dsn);
$schema->dumpDatabase($definition, $options, MDB2_SCHEMA_DUMP_STRUCTURE);
?>
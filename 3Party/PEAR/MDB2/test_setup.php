<?php

// uncomment this line if you want to run both MDB2 and MDB2_Schema from a CVS checkout
#ini_set('include_path', '../../MDB2/'.PATH_SEPARATOR.'..'.PATH_SEPARATOR.ini_get('include_path'));

$testcases = array(
    'MDB2_Schema_testcase',
);

// use a user that has full permissions on a database named "driver_test"
$mysql = array(
    'dsn' => array(
        'phptype' => 'mysql',
        'username' => 'root',
        'password' => '',
        'hostspec' => 'localhost',
        'database' => 'test'
    ),
    'options' => array(
        'use_transactions' => true
    )
);

$pgsql = array(
    'dsn' => array(
        'phptype' => 'pgsql',
        'username' => 'username',
        'password' => 'password',
        'hostspec' => 'hostname',
    )
);

$oci8 = array(
    'dsn' => array(
        'phptype' => 'oci8',
        'username' => '',
        'password' => 'password',
        'hostspec' => 'hostname',
    ),
    'options' => array(
        'DBA_username' => 'username',
        'DBA_password' => 'password'
    )
);

$sqlite = array(
    'dsn' => array(
        'phptype' => 'sqlite',
        'username' => '',
        'password' => 'password',
        'hostspec' => 'hostname',
    ),
    'options' => array(
        'database_path' => '',
        'database_extension' => '',
    )
);

// must be a user with system administrator privileges
$mssql = array(
    'dsn' => array(
        'phptype' => 'mssql',
        'username' => 'username',
        'password' => 'password',
        'hostspec' => 'hostname',
    )
);

$fbsql = array(
    'dsn' => array(
        'phptype' => 'fbsql',
        'username' => 'username',
        'password' => 'password',
        'hostspec' => 'hostname',
    )
);


$ibase = array(
    'dsn' => array(
        'phptype' => 'ibase',
        'username' => 'username',
        'password' => 'password',
        'hostspec' => 'hostname',
    )
);

$dbarray = array();
#$dbarray[] = $mysql;
#$dbarray[] = $pgsql;
#$dbarray[] = $oci8;
#$dbarray[] = $sqlite;
#$dbarray[] = $mssql;
#$dbarray[] = $fbsql;
#$dbarray[] = $ibase;

// you may need to uncomment the line and modify the multiplier as you see fit
#set_time_limit(60*count($dbarray));
?>
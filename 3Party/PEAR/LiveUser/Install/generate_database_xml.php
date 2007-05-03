<?php

require 'Install.php';
// error handler
function handleError($err)
{
   var_dump($err);
   return PEAR_ERRORSTACK_PUSH;
}

PEAR_ErrorStack::setDefaultCallback('handleError');

echo '<pre>';

// customize DSN as needed
$dsn = 'mysql://root:@localhost/test';

// customize config array as needed
$conf = array(
    'authContainers' => array(
        array(
            'type'         => 'MDB2',
            'expireTime'   => 3600,
            'idleTime'     => 1800,
            'storage' => array(
                'dsn' => $dsn,
                'force_seq' => false,
                'alias' => array(
                    'auth_user_id' => 'authUserId',
                    'lastlogin' => 'lastLogin',
                    'is_active' => 'isActive',
                    'owner_user_id' => 'owner_user_id',
                    'owner_group_id' => 'owner_group_id',
                ),
                'fields' => array(
                    'auth_user_id' => 'integer',
                    'lastlogin' => 'timestamp',
                    'is_active' => 'boolean',
                    'owner_user_id' => 'integer',
                    'owner_group_id' => 'integer',
                ),
                'tables' => array(
                    'users' => array(
                        'fields' => array(
                            'lastlogin' => null,
                            'is_active' => null,
                            'owner_user_id' => null,
                            'owner_group_id' => null,
                        ),
                    ),
                ),
            ),
        ),
    ),
    'permContainer'  => array(
        'type'  => 'Complex',
        'storage' => array(
            'MDB2' => array(
                'dsn' => $dsn,
                'prefix' => 'liveuser_',
                'force_seq' => false,
                'fields' => array(
                    'auth_user_id' => 'integer',
                ),
            )
        )
    )
);

@unlink('dump.sql');
function dump_to_file(&$db, $scope, $message, $is_manip)
{
    if ($is_manip) {
        $fp = fopen('dump.sql', 'a');
        fwrite($fp, $message."\n");
        fclose($fp);
    }
}

// customize MDB2_SCHEMA configuration options as needed
$options = array(
    'debug' => true,
    'log_line_break' => '<br>',
// to dump the SQL to a file uncommented the following line
// and set the disable_query parameter in the installSchema calls
#    'debug_handler' => 'dump_to_file',
);

// field name - value pairs of lengths to use in the schema
$lengths = array('description' => 255);

// field name - value pairs of defaults to use in the schema
$defaults = array('right_level' => LIVEUSER_MAX_LEVEL);

// create instance of the auth container
$auth =& LiveUser::authFactory($conf['authContainers'][0], 'foo');
// generate xml schema file for auth container
$result = LiveUser_Misc_Schema_Install::generateSchema(
    $auth,
    'auth_schema.xml',
    $lengths,
    $defaults
);
var_dump($result);

// create instance of the perm container
$perm =& LiveUser::storageFactory($conf['permContainer']['storage']);
// generate xml schema file for perm container
$result = LiveUser_Misc_Schema_Install::generateSchema(
    $perm,
    'perm_schema.xml',
    $lengths,
    $defaults
);
var_dump($result);

?>
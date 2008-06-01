--TEST--
Empty array should not trigger notice
--FILE--
<?php
require 'XML/RPC2/Backend/Php/Value.php';
class Empty_Array_Value_Test extends XML_RPC2_Backend_Php_Value
{
}

{
    function errorHandler ($errno, $errstr, $errfile, $errline, $errcontext)
    {
        echo $errno;
    }

    error_reporting(E_ALL);
    set_error_handler('errorHandler');


    $array_class = new Empty_Array_Value_Test();
    $array_class->createFromNative(array());
}

?>
--EXPECT--

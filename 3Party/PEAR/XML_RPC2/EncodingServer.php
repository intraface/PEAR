<?php
require_once 'XML/RPC2/Server.php';

class EncodingServer {

    /**
     * returns an internationalized string
     *
     * @return string An international string
     */
    public static function getInternationalString() {
        return 'זרו';
    }

}

$options = array(
    'prefix' => 'test.',
    'encoding' => 'ISO-8859-1'
);

// Let's build the server object with the name of the Echo class
$server = XML_RPC2_Server::create('EncodingServer', $options);
$server->handleCall();
?>
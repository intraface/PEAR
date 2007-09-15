<?php
require 'XML\RPC2\Client.php';
$options = array(
    'prefix' => 'test.',
    'encoding' => 'ISO-8859-1'
);

$client = XML_RPC2_Client::create('http://localhost/intrafacelibraries/PEAR/XML_RPC2/EncodingServer.php', $options);

try {
    $result = $client->getInternationalString();
    print_r($result);
} catch (XML_RPC2_FaultException $e) {
    die('Exception #' . $result->getFaultCode() . ' : ' . $e->getFaultString());
} catch (Exception $e) {
    die('Exception : ' . $e->getMessage());
}
?>

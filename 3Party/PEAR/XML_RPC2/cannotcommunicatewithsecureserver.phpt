--TEST--
Should be able to communicate with ssl server
--FILE--
<?php
require 'XML/RPC2/Client.php';

$client = XML_RPC2_Client::create('https://api.staging.revver.com/xml/1.0?login=revtester&#038;passwd=testacct', array('prefix' => 'video'));

$query = array('owners' => array('longjohn'), 'search' => array('parrot'));

$results = $client->count($query);

echo '&lt;pre>';
var_dump($results);
echo '&lt;/textarea&gt;';
?>
--EXPECT--
<pre>int(0)
</pre>
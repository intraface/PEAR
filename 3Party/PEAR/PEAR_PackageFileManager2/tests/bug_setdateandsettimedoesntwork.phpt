--TEST--
BUG: Cuts of first letter of the root directories
--FILE--
<?php
include 'bug_setdateandsettimedoesntwork/makepackage.php';
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<package packagerversion="1.6.2" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
    http://pear.php.net/dtd/tasks-1.0.xsd
    http://pear.php.net/dtd/package-2.0
    http://pear.php.net/dtd/package-2.0.xsd">
 <name>PEAR_PackageFileManager2_Test</name>
 <uri>http://localhost/</uri>
 <summary>Testing</summary>
 <description>Testing</description>
 <lead>
  <name>Lars Olesen</name>
  <user>lsolesen</user>
  <email>lars@legestue.net</email>
  <active>yes</active>
 </lead>
 <date>2007-10-10</date>
 <time>10:10:10</time>
 <version>
  <release>0.1.0</release>
  <api>0.1.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>beta</api>
 </stability>
 <license uri="http://www.gnu.org/licenses/lgpl.html">LGPL License</license>
 <notes>* this script fails on WAMP 1.7.3 on Windows Vista</notes>
 <contents>
  <dir baseinstalldir="/" name="/">
   <file name="makepackage.php" role="php" />
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>5.2.0</min>
   </php>
   <pearinstaller>
    <min>1.5.4</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease />
 <changelog>
  <release>
   <version>
    <release>0.1.0</release>
    <api>0.1.0</api>
   </version>
   <stability>
    <release>stable</release>
    <api>beta</api>
   </stability>
   <date>2007-11-09</date>
   <license uri="http://www.gnu.org/licenses/lgpl.html">LGPL License</license>
   <notes>* this script fails on WAMP 1.7.3 on Windows Vista</notes>
  </release>
 </changelog>
</package>
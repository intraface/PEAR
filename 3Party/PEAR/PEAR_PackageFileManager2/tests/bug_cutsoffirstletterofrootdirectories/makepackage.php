<?php
require_once 'PEAR/PackageFileManager2.php';

$version = '0.1.0';
$notes = '* this script fails on WAMP 1.7.3 on Windows Vista';

PEAR::setErrorHandling(PEAR_ERROR_DIE);
$pfm = new PEAR_PackageFileManager2();
$pfm->setDate('2007-10-10');
$pfm->setTime('10:10:10');
$pfm->setOptions(
    array(
        'baseinstalldir'    => '/',
        'filelistgenerator' => 'file',
        'packagedirectory'  => dirname(__FILE__) . '/src/',
        'packagefile'       => 'package.xml',
        'simpleoutput'      => true,
        'addhiddenfiles' => true
    )
);

$pfm->setPackage('PEAR_PackageFileManager2_Test');
$pfm->setSummary('Testing');
$pfm->setDescription('Testing');
$pfm->setUri('http://localhost/');
$pfm->setLicense('LGPL License', 'http://www.gnu.org/licenses/lgpl.html');
$pfm->addMaintainer('lead', 'lsolesen', 'Lars Olesen', 'lars@legestue.net');

$pfm->setPackageType('php');

$pfm->setAPIVersion($version);
$pfm->setReleaseVersion($version);
$pfm->setAPIStability('beta');
$pfm->setReleaseStability('stable');
$pfm->setNotes($notes);
$pfm->addRelease();

$pfm->clearDeps();
$pfm->setPhpDep('5.2.0');
$pfm->setPearinstallerDep('1.5.4');


$pfm->generateContents();

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    if ($pfm->writePackageFile()) {
        exit('package file written');
    }
} else {
    $pfm->debugPackageFile();
}
?>
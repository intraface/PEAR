<?php

require_once 'PEAR/Config.php';
PEAR_Config::singleton('C:/WINDOWS/pear.ini', 'C:/WINDOWS/pear.ini');

require_once 'PEAR/PackageFileManager2.php';
require_once 'PEAR/PackageFileManager/File.php';
require_once 'PEAR/Task/Postinstallscript/rw.php';
require_once 'PEAR/Task/Chiara/Managedb.php';
require_once 'PEAR/Task/Chiara/Managedb/rw.php';
require_once 'PEAR/Config.php';
require_once 'PEAR/Frontend.php';

error_reporting(E_ALL);
PEAR::setErrorHandling(PEAR_ERROR_DIE);
$pfm = new PEAR_PackageFileManager2();
$pfm->setOptions(array(
    'packagedirectory' => dirname(__FILE__),
    'baseinstalldir' => 'LiveUser/Install',
    'filelistgenerator' => 'file',
    'ignore' => array(
        '*.tgz',
        'generate_package_xml.php',
        'generate_database_xml.php',
        'Install.php'),
    'simpleoutput' => true,
    'exceptions' => array(
        'auth_schema.xml' => 'chiaramdb2schema',
        'perm_schema.xml' => 'chiaramdb2schema'
    )
));

// managedb why is startSession() started under package creation

$pfm->setPackage('LiveUser_Schema');
$pfm->setPackageType('php'); // this is a PEAR-style php script package
$pfm->setSummary('A database schema for PEAR LiveUser');
$pfm->setDescription('This package contains a complex database schema for use with the PEAR LiveUser package.');
$pfm->setUri('http://localhost/');
$pfm->setAPIStability('alpha');
$pfm->setReleaseStability('alpha');
$pfm->setAPIVersion('0.0.1');
$pfm->setReleaseVersion('0.0.1');
$pfm->setNotes("* First release.");

$pfm->addUsesRole('chiaramdb2schema', 'PEAR_Installer_Role_Chiaramdb2schema', 'pear.chiaraquartet.net');
$pfm->addUsesTask('chiara_managedb', 'PEAR_Task_Chiara_Managedb', 'pear.chiaraquartet.net');

$pfm->addMaintainer('lead','lsolesen','Lars Olesen','lars@legestue.net');
$pfm->setLicense('BSD', 'http://www.opensource.org/licenses/bsd-license.php');
$pfm->clearDeps();
$pfm->setPhpDep('5.0.0');
$pfm->setPearinstallerDep('1.4.3');
$pfm->addPackageDepWithChannel('required', 'PEAR_Installer_Role_Chiaramdb2schema', 'pear.chiaraquartet.net', '0.2.0');
$pfm->addPackageDepWithChannel('required', 'PEAR_Task_Chiara_Managedb', 'pear.chiaraquartet.net', '0.1.1');


$config = PEAR_Config::singleton();
$log = PEAR_Frontend::singleton();
$auth_task = new PEAR_Task_Chiara_Managedb_rw($pfm, $config, $log,
    array('name' => 'auth_schema.xml', 'role' => 'chiaramdb2schema'));

$perm_task = new PEAR_Task_Chiara_Managedb_rw($pfm, $config, $log,
    array('name' => 'perm_schema.xml', 'role' => 'chiaramdb2schema'));

$pfm->addTaskToFile('auth_schema.xml', $auth_task);
$pfm->addTaskToFile('perm_schema.xml', $perm_task);

//$pfm->addPostinstallTask($auth_task, 'auth_schema.xml');
//$pfm->addPostinstallTask($perm_task, 'perm_schema.xml');

$pfm->generateContents();

if (isset($_GET['make']) OR isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make') {
    if ($pfm->writePackageFile()) {
        exit('package file written');
    }
} else {
    $pfm->debugPackageFile();
}
?>
<?php
ini_set('display_errors',true);
require_once 'PEAR/PackageFileManager2.php';
require_once 'PEAR/PackageFileManager/File.php';
require_once 'PEAR/Task/Postinstallscript/rw.php';
require_once 'PEAR/Task/Chiara/Managedb/rw.php';
require_once 'PEAR/Config.php';
require_once 'PEAR/Frontend.php';

/**
 * @var PEAR_PackageFileManager
 */
PEAR::setErrorHandling(PEAR_ERROR_DIE);
chdir(dirname(__FILE__));
//$pfm = PEAR_PackageFileManager2::importOptions('package.xml', array(
$pfm = new PEAR_PackageFileManager2();
$pfm->setOptions(array(
    'packagedirectory' => dirname(__FILE__),
    'baseinstalldir' => 'LiveUser/Install',
    'filelistgenerator' => 'file',
    'ignore' => array('package.xml',
                      '.project',
                      '*.tgz',
                      'generate_package_xml.php'),
    'simpleoutput' => true,
    'roles'=>array('php'=>'php',
                   'database.xml'=>'chiaramdb2schema'),
    'exceptions'=>array()
));
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

$pfm->addMaintainer('lead','lsolesen','Lars Olesen','lars@legestue.net');
$pfm->setLicense('BSD', 'http://www.opensource.org/licenses/bsd-license.php');
$pfm->clearDeps();
$pfm->setPhpDep('5.0.0');
$pfm->setPearinstallerDep('1.4.3');
$pfm->addPackageDepWithChannel('required', 'PEAR_Installer_Role_Chiaramdb2schema', 'pear.chiaraquartet.net', '0.2.0');
$pfm->addPackageDepWithChannel('required', 'PEAR_Task_Chiara_Managedb', 'pear.chiaraquartet.net', '0.1.0');

$pfm->addUsestask('chiara_managedb', 'PEAR_Task_Chiara_Managedb', 'pear.chiaraquartet.net');
$pfm->addUsesRole('chiaramdb2schema', 'PEAR_Installer_Role_Chiaramdb2schema', 'pear.chiaraquartet.net');

$config = PEAR_Config::singleton();
$log = PEAR_Frontend::singleton();
$auth_task = new PEAR_Task_Chiara_Managedb_rw($pfm, $config, $log,
    array('name' => 'auth_schema.xml', 'role' => 'chiaramdb2schema'));

$perm_task = new PEAR_Task_Chiara_Managedb_rw($pfm, $config, $log,
    array('name' => 'perm_schema.xml', 'role' => 'chiaramdb2schema'));

$pfm->addPostinstallTask($auth_task, 'auth_schema.xml');
$pfm->addPostinstallTask($perm_task, 'perm_schema.xml');

$pfm->generateContents();

if (isset($_SERVER['argv']) && $_SERVER['argv'][1] == 'make') {
    $pfm->writePackageFile();
} else {
    $pfm->debugPackageFile();
}
?>
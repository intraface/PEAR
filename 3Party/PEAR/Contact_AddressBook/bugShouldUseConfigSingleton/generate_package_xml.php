<?php
/**
 * package.xml generation script
 *
 * @package Contact_AddressBook
 * @author  Sune Jensen <sj@sunet.dk>
 * @since   0.1.0
 * @version @package-version@
 */

$version = '0.0.1';
$stability = 'alpha';
$notes = '* Now uses Config::singleton to make i possible to use local config';

require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);
$pfm = new PEAR_PackageFileManager2();
$pfm->setOptions(
    array(
        'baseinstalldir'    => '/',
        'filelistgenerator' => 'file',
        'packagedirectory'  => dirname(__FILE__).'/src',
        'packagefile'       => 'package.xml',
        'ignore'            => array(
            'generate_package_xml.php',
            '*.tgz'
            ),
        'dir_roles' => array(
        ),
        'exceptions'        => array(
        ),
        'simpleoutput'      => true,
    )
);

$pfm->setPackage('Contact_AddressBook_bugfix_use_config_singleton');
$pfm->setSummary('Fixes bug in Contact_AddressBook');
$pfm->setDescription('Fixes bug in Contact_AddressBook');
$pfm->setUri('http://localhost/');
$pfm->setLicense('LGPL License', 'http://www.gnu.org/licenses/lgpl.html');
$pfm->addMaintainer('lead', 'sune', 'Sune Jensen', 'sj@sunet.dk');

$pfm->setPackageType('php');

$pfm->setAPIVersion($version);
$pfm->setReleaseVersion($version);
$pfm->setAPIStability('beta');
$pfm->setReleaseStability('beta');
$pfm->setNotes($notes);
$pfm->addRelease();

$pfm->addGlobalReplacement('package-info', '@package-version@', 'version');

$pfm->clearDeps();
$pfm->setPhpDep('5.1.0');
$pfm->setPearinstallerDep('1.5.0');

$pfm->generateContents();

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    if ($pfm->writePackageFile()) {
        echo "package file written\n";
        exit();
    }
} else {
    $pfm->debugPackageFile();
}
?>
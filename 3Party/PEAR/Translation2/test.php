<?php
    require_once 'Translation2/Admin.php';
    
    $file = dirname(__FILE__) . "/i18n.xml";
    @unlink($file);
    touch($file);

    $driver = "XML";
    $options = array(
        "filename"         => $file
    );
    $admin = Translation2_Admin::factory($driver, $options);
    if (PEAR::isError($admin)) {
        exit($admin->getMessage());
    }
    $lang = 'da';
    
    // set some info about the new lang
    $newLang = array(
        'lang_id'    => $lang,
        'table_name' => 'i18n',
        'name'       => 'danish',
        'meta'       => 'some meta info',
        'error_text' => 'not available',
        'encoding'   => 'iso-8859-1',
    );
    
    $admin->addLang($newLang);
    
    $identifier = 'some identifier';
    
    $admin->add($identifier, null, array($lang => 'ÆØÅ'));
    
    $translation = Translation2::factory($driver, $options);
    if (PEAR::isError($translation)) {
        exit($translation->getMessage());
    }


    echo $translation->setLang($lang);    
    echo $translation->get($identifier);
<?php
require 'HTML/QuickForm.php';
require '../advgroup.php';

$form = new HTML_QuickForm;


$areaCode = &HTML_QuickForm::createElement('text', '', null, array('size' => 4, 'maxlength' => 3));
$phoneNo1 = &HTML_QuickForm::createElement('text', '', null, array('size' => 4, 'maxlength' => 3));
$phoneNo2 = &HTML_QuickForm::createElement('text', '', null, array('size' => 5, 'maxlength' => 4));
$form->addGroup(array($areaCode, $phoneNo1, $phoneNo2), 'phoneNo', 'Telephone:', '-');


$form->addElement('advgroup', 'Label', $group);
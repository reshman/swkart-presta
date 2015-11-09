<?php
include_once('../../config/config.inc.php');
include_once('../../init.php');
$postButton = $_POST['type'];
if ($postButton == 'upload_btntype') {
    $filename = $_FILES[$_POST['data']];
    $filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

    move_uploaded_file($filename['tmp_name'], 'custom/' . $filename['name']);

    echo  __PS_BASE_URI__ . 'modules/tdpsthemeoptionpanel/custom/' . $filename['name'];
}elseif ($postButton == 'reset_btntype') {
    Configuration::deleteByName($_POST['data']);
}
die();
        



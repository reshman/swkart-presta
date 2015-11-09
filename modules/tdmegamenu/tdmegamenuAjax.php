<?php
include_once('../../config/config.inc.php');
include_once('../../init.php');
$getupdate=$_POST['tdmenulinkssorted'];
$i = 0;
foreach ($_POST['tdmenulinkssorted'] as $k => $submenulinks) :
    $parentmenulinks = str_replace("mlist_","", $submenulinks['id']);
    if (isset($getupdate[$k]['children'])) :
        submenuSaveFunction($getupdate[$k]['children'], $parentmenulinks, $i);
    else :
        Db::getInstance()->Execute('UPDATE `' . _DB_PREFIX_ . 'tdmegamenu` SET `parent` = 0 , `order` = ' . $i . ' WHERE `id_tdmegamenu` = ' . $parentmenulinks);
    endif;
    $i++;
endforeach;

function submenuSaveFunction($getupdate, $parentmenulinks, $i) {
    foreach ($getupdate as $postresult) :
        $tdmegaMid = (int) str_replace("mlist_","", $postresult['id']);
        if (isset($postresult['children'])):
            submenuSaveFunction($postresult['children'], $tdmegaMid, $i);
        else :
            Db::getInstance()->Execute($updatesql = 'UPDATE `' . _DB_PREFIX_ . 'tdmegamenu` SET `parent` = ' . $parentmenulinks . ', `order` = ' . $i . ' WHERE `id_tdmegamenu` = ' . $tdmegaMid);
            $i++;
        endif;
    endforeach;
    return true;
}


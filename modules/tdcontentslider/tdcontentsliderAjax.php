<?php
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('tdcontentslider.php');
$tdconslider = new TDcontentSlider();

$slides = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $tdconslider->secure_key || !Tools::getValue('action'))
	die(1);

echo Tools::getValue('secure_key');

if (Tools::getValue('action') == 'updateSlidesPosition' && Tools::getValue('slides'))
{

	$slides = Tools::getValue('slides');

	foreach ($slides as $position => $id_slide)
	{
        
			Db::getInstance()->Execute('
			UPDATE `'._DB_PREFIX_.'tdcontentslider` 
			SET `position` = '.(int)$position.' 
			WHERE `id_tdcontentslider` = '.(int)$id_slide);
		}
	
	$tdconslider->clearCache();
}


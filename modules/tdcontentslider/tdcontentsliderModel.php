<?php

class tdcontentsliderModel extends ObjectModel {

    public static function createTables() {
        return (
                tdcontentsliderModel::createtdhomeadvertisingTable() &&
                tdcontentsliderModel::createtdhomeadvertisingLangTable()
                && tdcontentsliderModel::createtdDefaultData()
                );
    }

    public static function dropTables() {
        return Db::getInstance()->execute('DROP TABLE
			`' . _DB_PREFIX_ . 'tdcontentslider`,
			`' . _DB_PREFIX_ . 'tdcontentslider_lang`');
    }

    public static function createtdDefaultData() {
    $context = Context::getContext();
    $id_shop = $context->shop->id;
    $tdmodurl='modules/tdcontentslider/banner/';
        $sql= Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'tdcontentslider`(`slider_link`,`active`, `position`,`id_shop`) VALUES("#",1,0,'.$id_shop.')');

        $sql .= Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'tdcontentslider`(`slider_link`,`active`, `position`,`id_shop`) VALUES("#",1,1,'.$id_shop.')');

       $sql .= Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'tdcontentslider`(`slider_link`,`active`, `position`,`id_shop`) VALUES("#",1,2,'.$id_shop.')');

       $sql .= Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'tdcontentslider`(`slider_link`,`active`, `position`,`id_shop`) VALUES("#",1,3,'.$id_shop.')');

    
        
        $languages = Language::getLanguages(false);
        for ($i = 1; $i <= 4; $i++) {
            if ($i == 1):
                $title = 'Beauty Collection';
                $content='Fusce sagittis, eros ut feugiat tristique turpis dolor placerat risus malesuada';
            elseif ($i == 2):
                $title =  'Aromatherapy';
                $content='Proin malesuada facilisis nisl a iaculis. Donec dapibus a magna sit amet accumsan.';
                 elseif ($i == 3):
                $title =  'HairCare &amp; MakeUp';
                $content='Pellentesque ut nunc eu lectus rutrum condimentum non a diam. Nunc ultricies aliquet nibh ac cursus.';
                 elseif ($i == 4):
                $title =  'The New Fragrance';
                $content='Donec iaculis, turpis vitae congue cursus, elit leo pretium lorem, in mollis felis dolor ac sapien. In sit amet rutrum diam. Aliquam eu diam eros.';
            endif;
            
            
            
            foreach ($languages as $language) {
                $sql .=Db::getInstance()->Execute('
                        INSERT INTO `' . _DB_PREFIX_ . 'tdcontentslider_lang`(`id_tdcontentslider`, `id_lang`, `image_title`, `slider_content`,`image_url`) 
                        VALUES(' . $i . ', ' . (int) $language['id_lang'] . ', 
                        "' . htmlspecialchars($title) . '"," '.htmlspecialchars($content).' ","'.$tdmodurl.'banner' . $i . '.jpg")');
            }
        }
        return $sql;
    }

    public static function createtdhomeadvertisingTable() {
        return (Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tdcontentslider` (
	        `id_tdcontentslider` int(10) unsigned NOT NULL auto_increment,
                `slider_link` varchar(255) NOT NULL,
                `active` int(11) unsigned NOT NULL,
                `position` int(11) unsigned NOT NULL default \'0\',
                `id_shop` int(10) unsigned NOT NULL,
		PRIMARY KEY (`id_tdcontentslider`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'));
    }

    public static function createtdhomeadvertisingLangTable() {
        return (Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tdcontentslider_lang` (
		`id_tdcontentslider` int(10) unsigned NOT NULL,
		`id_lang` int(10) unsigned NOT NULL,
                `image_title` varchar(255) NOT NULL,
                `slider_content` text NOT NULL,
                `image_url` varchar(255) NOT NULL,
		PRIMARY KEY (`id_tdcontentslider`, `id_lang`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'));
    }

    public static function getAllSlider() {
        global $cookie;
           $context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;
        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdcontentslider`,td.`slider_link`, td.`active`, td.`position`, td1.`image_url`, td1.id_lang, td1.image_title, td1.slider_content
            FROM `' . _DB_PREFIX_ . 'tdcontentslider` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdcontentslider_lang` td1 ON (td.`id_tdcontentslider` = td1.`id_tdcontentslider`)
            WHERE td.`id_shop`= '.(int)$id_shop.' AND td1.`id_lang` = ' . (int) $cookie->id_lang . '
            ORDER BY td.`position`');
    }

    public static function getSliderByID($id_tdcontentslider) {
$context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;
        $getslider = Db::getInstance()->ExecuteS('
            SELECT td.`id_tdcontentslider`, td.`slider_link`, td.`active`, td.`position`, td1.`image_url`, td1.id_lang, td1.image_title, td1.`slider_content`
            FROM `' . _DB_PREFIX_ . 'tdcontentslider` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdcontentslider_lang` td1 ON (td.`id_tdcontentslider` = td1.`id_tdcontentslider`)
            WHERE td.`id_tdcontentslider` = ' . (int) $id_tdcontentslider);


        $store_display_update = array(0, $size = count($getslider));
        foreach ($getslider AS $sliderbyid) {
            $getslider['image_title'][(int) $sliderbyid['id_lang']] = $sliderbyid['image_title'];
            if ($store_display_update['0'] < $store_display_update['1'])
                ++$store_display_update['0'];
        }
        foreach ($getslider AS $imagecaption) {
            $getslider['slider_content'][(int) $imagecaption['id_lang']] = $imagecaption['slider_content'];
            if ($store_display_update['0'] < $store_display_update['1'])
                ++$store_display_update['0'];
        }
        foreach ($getslider AS $sliderimage) {
            $getslider['image_url'][(int) $sliderimage['id_lang']] = $sliderimage['image_url'];
            if ($store_display_update['0'] < $store_display_update['1'])
                ++$store_display_update['0'];
        }
        return $getslider;
    }

}











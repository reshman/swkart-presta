<?php

class TdMegaMenuModel extends ObjectModel {

    public static function createTables() {
        return (
                TdMegamenuModel::createtdmegamenuTable() &&
                TdMegamenuModel::createtdmegamenuLangTable()
                 && TdMegamenuModel::createtdDefaultData()
                );
    }

    public static function dropTables() {
        return Db::getInstance()->execute('DROP TABLE
			`' . _DB_PREFIX_ . 'tdmegamenu`,
			`' . _DB_PREFIX_ . 'tdmegal`');
    }

  
    public static function createtdmegamenuTable() {
        return (Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'. _DB_PREFIX_ .'tdmegamenu`(
	        `id_tdmegamenu`int(10) unsigned NOT NULL,
                `menu_type` varchar(255) NOT NULL,
                `order` int(11) unsigned NOT NULL default \'0\',
                `parent` int(11) unsigned NOT NULL,
                `custome_type` varchar(255) NOT NULL,
                `id_shop` INT UNSIGNED NOT NULL,
		PRIMARY KEY (`id_tdmegamenu`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'));
    }

    public static function createtdmegamenuLangTable() {
        return (Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'. _DB_PREFIX_ .'tdmegal`(
		`id_tdmegamenu` int(10) unsigned NOT NULL,
		`id_lang` int(10) unsigned NOT NULL,
                `menu_title` varchar(255) NOT NULL,
                `menu_link` varchar(255) NOT NULL,
                `description` varchar(255) NOT NULL)
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'));
    }
    
    
        public static function createtdDefaultData() {
      $context = Context::getContext();
        $id_shop = $context->shop->id;
         $psbaseurl=_PS_BASE_URL_.__PS_BASE_URI__;
        $modlink=_PS_BASE_URL_.__PS_BASE_URI__.'blog';
    $sql =Db::getInstance()->Execute('
            INSERT INTO `' . _DB_PREFIX_ . 'tdmegamenu`(`id_tdmegamenu`,`menu_type`,`order`,`parent`,`custome_type`,`id_shop`) 
            VALUES(1,"LNK1",1,0,"cus_url",' . $id_shop . ')');
  $sql =Db::getInstance()->Execute('
            INSERT INTO `' . _DB_PREFIX_ . 'tdmegamenu`(`id_tdmegamenu`,`menu_type`,`order`,`parent`,`custome_type`,`id_shop`) 
            VALUES(2,"LNK2",2,0,"cus_url",' . $id_shop . ')');
  
     $languages = Language::getLanguages(false);
             for ($i = 1; $i <=2; $i++) {
            if ($i == 1):
                $title = "Home";
                $links=$psbaseurl;
         elseif ($i == 2):
                $title = "Blog";
               $links=$modlink;
            endif;
            
            foreach ($languages as $language) {
                
                $sql .=Db::getInstance()->Execute('
                INSERT INTO `' . _DB_PREFIX_ . 'tdmegal`(`id_tdmegamenu`,`id_lang`,`menu_title`,`menu_link`,`description`) 
                VALUES(' . (int) $i . ', ' . (int) $language['id_lang'] . ', "'.$title.'","'.$links.'", "")');
  
          }
             }
        return true;
     
    }

    public static function getAllMegaMenu($id_lang, $parent = 0) {
        global $cookie;
     $context = Context::getContext();
		$id_shop = $context->shop->id;
                $allmenus= array();
                
        $allselectedmenus= Db::getInstance()->ExecuteS('
            SELECT td.`id_tdmegamenu`, td.`menu_type`, td.`order`,td.`parent`,td.`custome_type`,td1.`menu_title`,td1.`menu_link`,td1.`description`
            FROM `' . _DB_PREFIX_ . 'tdmegamenu` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdmegal` td1 ON (td.`id_tdmegamenu` = td1.`id_tdmegamenu`)
            WHERE td1.`id_lang` = ' . $cookie->id_lang . ' and td.`id_shop`='.$id_shop.' and td.parent = "' . $parent . '"
            ORDER BY td.`order`');
           $i = 0;
           count($allselectedmenus);
                foreach ($allselectedmenus as $tdmenulinks) {
            $allmenus[$i] = $tdmenulinks;
            $allmenus[$i]['submenu'] = self::getAllMegaMenu($id_lang, $tdmenulinks['id_tdmegamenu']);
            $i++;
        }
         return $allmenus;
           

    }
     public static function getAllRootMegaMenu($id_lang, $parent = 0) {
        global $cookie;
           $context = Context::getContext();
		$id_shop = $context->shop->id;
                $allmenu= array();
                
        $allmenu=Db::getInstance()->ExecuteS('
            SELECT td.`id_tdmegamenu`, td.`menu_type`, td.`order`,td.`parent`,td.`custome_type`,td1.`menu_title`,td1.`menu_link`,td1.`description`
            FROM `' . _DB_PREFIX_ . 'tdmegamenu` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdmegal` td1 ON (td.`id_tdmegamenu` = td1.`id_tdmegamenu`)
            WHERE td1.`id_lang` = ' . (int) $id_lang . ' and td.`id_shop`='.$id_shop.' and td.`parent` = 0 
            ORDER BY td.`order`');
            $i = 0;
                foreach ($allmenus as $tdmenulinks) {
            $allmenu[$i] = $tdmenulinks;
            $allmenu[$i]['submenu'] = self::getAllRootMegaMenu($id_lang, $tdmenulinks['id_tdmegamenu']);
            $i++;
        }

         return $allmenu;
        
    }
    
        public static function getMenuType() {
        global $cookie;
        return Db::getInstance()->ExecuteS('
            SELECT `id_tdmegamenu`, `menu_type`
            FROM `' . _DB_PREFIX_ . 'tdmegamenu`
            ORDER BY `order`');
    }
     
  
}
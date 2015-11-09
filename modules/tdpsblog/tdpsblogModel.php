<?php

class tdpsblogModel extends ObjectModel {
public $id;
    public static function createTables() {
        return (
                tdpsblogModel::createtdpsblogTable() &&
                tdpsblogModel::createtdpsblogLangTable()
                && tdpsblogModel::createtdDefaultData()
                );
    }

    public static function dropTables() {
        return Db::getInstance()->execute('DROP TABLE
			`' . _DB_PREFIX_ . 'tdpsblog_category`,
			`' . _DB_PREFIX_ . 'tdpsblog`,`' . _DB_PREFIX_ . 'tdpsblog_comments`,
                        `' . _DB_PREFIX_ . 'tdpsblog_category_lang`,`' . _DB_PREFIX_ . 'tdpsblog_lang`,`' . _DB_PREFIX_ . 'tdpsblog_comments_lang`');
    }

    public static function createtdDefaultData() {
    $context = Context::getContext();
    $id_shop = $context->shop->id;
    $tdmodurl='modules/tdpsblog/banner/';
   
        
     $sql= Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog_category`(`category_parent`,`active`, `id_shop`) VALUES(0,1,'.$id_shop.')');
    
        $sql.= Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog`(`tdpost_category`,`active`, `position`,`allow_comment`,`comments_count`,`tdpost_view`,`id_shop`) VALUES(1,1,0,1,0,0,'.$id_shop.')');
 $sql .= Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog`(`tdpost_category`, `active`, `position`,`allow_comment`,`comments_count`,`tdpost_view`,`id_shop`) VALUES(1,1,1,1,0,0,'.$id_shop.')');

        
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            
            $sql .=Db::getInstance()->Execute('
                        INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog_category_lang`(`id_tdpsblog_category`, `id_lang`, `category_name`, `cat_rewrite`) 
                        VALUES(1, ' . (int) $language['id_lang'] . ', "Demo category", "demo-category")');
        }
        for ($i = 1; $i <= 2; $i++) {
            if ($i == 1):
                $title = 'If you are going to use a passage of Lorem Ipsum';
                $content='<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span id="more-1983"></span></p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
<p>&nbsp;</p>';
                
            elseif($i == 2):
                $title = 'It is a long established fact that a reader';
                $content='<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.<span id="more-1983"></span></p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from “de Finibus Bonorum et Malorum” by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
<p>&nbsp;</p>';
            endif;
            
        $strlower=strtolower($title);
        $link_rewrite = str_replace(' ', '-', $strlower);
  
            foreach ($languages as $language) {
                $sql .=Db::getInstance()->Execute('
                        INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog_lang`(`id_tdpsblog`, `id_lang`, `tdpost_title`, `tdpost_content`,`image_url`,`link_rewrite`) 
                        VALUES(' . $i . ', ' . (int) $language['id_lang'] . ', 
                        "' . htmlspecialchars($title) . '"," '.htmlspecialchars($content).' ","'.$tdmodurl.'banner_' . $i . '.jpg","'.$link_rewrite.'")');
            }
        }
        return $sql;
    }

    public static function createtdpsblogTable() {
      Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tdpsblog_category` (
	        `id_tdpsblog_category` int(10) unsigned NOT NULL auto_increment,
                `category_parent` int(10) unsigned NOT NULL,
                `active` int(11) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
		PRIMARY KEY (`id_tdpsblog_category`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
      
      Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tdpsblog` (
	        `id_tdpsblog` int(10) unsigned NOT NULL auto_increment,
                `tdpost_category` int(10) unsigned NOT NULL,
                `tdpost_dete` TIMESTAMP NOT NULL,
                `active` int(11) unsigned NOT NULL,
                `position` int(11) unsigned NOT NULL default \'0\',
                `allow_comment` int(11) unsigned NOT NULL,
                `comments_count` int(11) unsigned NOT NULL,
                `tdpost_view` int(11) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
		PRIMARY KEY (`id_tdpsblog`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
      
       Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tdpsblog_comments` (
	        `id_tdpsblog_comments` int(10) unsigned NOT NULL auto_increment,
                `id_tdpsblog` int(10) unsigned NOT NULL,
                `comment_author_name` varchar(255) NOT NULL,
                `comment_author_email` varchar(255) NOT NULL,
                `comment_date` varchar(255) NOT NULL,
                `comment_parent` int(11) unsigned NOT NULL,
                `active` int(11) unsigned NOT NULL,
		PRIMARY KEY (`id_tdpsblog_comments`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
       return true;
    }

    public static function createtdpsblogLangTable() {
        
             Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tdpsblog_category_lang` (
	        `id_tdpsblog_category` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `category_name` varchar(255) NOT NULL,
                `cat_rewrite` varchar(255) NOT NULL,
		PRIMARY KEY (`id_tdpsblog_category`, `id_lang`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
             
        Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tdpsblog_lang` (
		`id_tdpsblog` int(10) unsigned NOT NULL,
		`id_lang` int(10) unsigned NOT NULL,
                `tdpost_title` varchar(255) NOT NULL,
                `tdpost_content` text NOT NULL,
                `image_url` varchar(255) NOT NULL,
                `link_rewrite` nvarchar(1000) NULL,
		PRIMARY KEY (`id_tdpsblog`, `id_lang`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
             Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'tdpsblog_comments_lang` (
	        `id_tdpsblog_comments` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `comments_text` nvarchar(7000) NULL,
		PRIMARY KEY (`id_tdpsblog_comments`, `id_lang`))
		ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
        return true;
    }

    public static function getAllblogPost() {
      global $cookie;
           $context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;
                
             
        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog`, td.`tdpost_category`, td.`tdpost_dete`, td.`active`, td.`position`, td.`allow_comment`, td.`comments_count`,td.`tdpost_view`, td1.`tdpost_title`, td1.`id_lang`, td1.`tdpost_content`, td1.`image_url`, td1.`link_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` td1 ON (td.`id_tdpsblog` = td1.`id_tdpsblog`)
            WHERE td.`id_shop`= '.(int)$id_shop.' AND td1.`id_lang` = ' . (int) $cookie->id_lang . '
            ORDER BY td.`position`');
    }
     public static function getAllInactiveComments() {
           $context = Context::getContext();
      return Db::getInstance()->ExecuteS('
            SELECT ctd.`id_tdpsblog_comments`, ctd.`id_tdpsblog`, ctd.`comment_author_name`, ctd.`comment_author_email`, ctd.`comment_date`, ctd.`comment_parent`, ctd.`active`, ctd1.`comments_text`, ctd1.`id_lang`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_comments` ctd
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_comments_lang` ctd1 ON (ctd.`id_tdpsblog_comments` = ctd1.`id_tdpsblog_comments`)
            WHERE ctd1.`id_lang` = ' . (int) $context->language->id . ' 
            AND ctd.active = 0 
            ORDER BY ctd.id_tdpsblog_comments DESC'); 
     }
       public static function getAllActiveComments() {
           $context = Context::getContext();
      return Db::getInstance()->ExecuteS('
            SELECT ctd.`id_tdpsblog_comments`, ctd.`id_tdpsblog`, ctd.`comment_author_name`, ctd.`comment_author_email`, ctd.`comment_date`, ctd.`comment_parent`, ctd.`active`, ctd1.`comments_text`, ctd1.`id_lang`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_comments` ctd
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_comments_lang` ctd1 ON (ctd.`id_tdpsblog_comments` = ctd1.`id_tdpsblog_comments`)
            WHERE ctd1.`id_lang` = ' . (int) $context->language->id . ' 
            AND ctd.active = 1
            ORDER BY ctd.id_tdpsblog_comments DESC'); 
     }
     public static function validate($pid,$validate = '1')
	{
		
		return (Db::getInstance()->execute('
		UPDATE `'._DB_PREFIX_.'tdpsblog_comments` SET
		`active` = '.(int)$validate.'
		WHERE `id_tdpsblog_comments` = '.$pid));
	}
   
   public static function getAllblogCategory() {
      global $cookie;
           $context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;

        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog_category`, td.`active`, td1.`category_name`, td1.`id_lang`, td1.`cat_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_category` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td1 ON (td.`id_tdpsblog_category` = td1.`id_tdpsblog_category`)
            WHERE td.`id_shop`= '.(int)$id_shop.' AND td1.`id_lang` = ' . (int) $cookie->id_lang . '');
    }
       public static function getCategoryByID($id) {
      global $cookie;
           $context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;

        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog_category`, td.`active`, td1.`category_name`, td1.`id_lang`, td1.`cat_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_category` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td1 ON (td.`id_tdpsblog_category` = td1.`id_tdpsblog_category`)
            WHERE td1.`id_tdpsblog_category`= '.(int)$id.' AND td.`id_shop`= '.(int)$id_shop.' AND td1.`id_lang` = ' . (int) $cookie->id_lang . '');
    }
    public static function getBlogPostByID($id_tdpsblog) {
    
$context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;
        $getBlogPost = Db::getInstance()->ExecuteS('
           SELECT td.`id_tdpsblog`, td.`tdpost_category`, td.`tdpost_dete`, td.`active`, td.`position`, td.`allow_comment`, td.`comments_count`,td.`tdpost_view`, td1.`tdpost_title`, td1.`id_lang`, td1.`tdpost_content`, td1.`image_url`, td1.`link_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` td1 ON (td.`id_tdpsblog` = td1.`id_tdpsblog`)
            WHERE td.`id_tdpsblog` = ' . (int) $id_tdpsblog);


        $store_display_update = array(0, $size = count($getBlogPost));
       
        foreach ($getBlogPost AS $BlogPostbyid) {
             if(isset($BlogPostbyid['tdpost_title'])){
            $getBlogPost['tdpost_title'][(int) $BlogPostbyid['id_lang']] = $BlogPostbyid['tdpost_title'];
            if ($store_display_update['0'] < $store_display_update['1'])
                ++$store_display_update['0'];
        }
        }
        
        foreach ($getBlogPost AS $imagecaption) {
             if(isset($imagecaption['tdpost_content'])){
            $getBlogPost['tdpost_content'][(int) $imagecaption['id_lang']] = $imagecaption['tdpost_content'];
            if ($store_display_update['0'] < $store_display_update['1'])
                ++$store_display_update['0'];
        }
         }
        
        foreach ($getBlogPost AS $BlogPostimage) {
             if(isset($BlogPostimage['image_url'])){
            $getBlogPost['image_url'][(int) $BlogPostimage['id_lang']] = $BlogPostimage['image_url'];
            if ($store_display_update['0'] < $store_display_update['1'])
                ++$store_display_update['0'];
        }
         }
        return $getBlogPost;
    }
      public static function getComments($id_tdpost,$parent=0) {
          $context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;
    $results=Db::getInstance()->ExecuteS('
            SELECT ctd.`id_tdpsblog_comments`, ctd.`id_tdpsblog`, ctd.`comment_author_name`, ctd.`comment_author_email`, ctd.`comment_date`, ctd.`comment_parent`, ctd.`active`, ctd1.`comments_text`, ctd1.`id_lang`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_comments` ctd
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_comments_lang` ctd1 ON (ctd.`id_tdpsblog_comments` = ctd1.`id_tdpsblog_comments`)
            WHERE ctd1.`id_lang` = ' . (int) $context->language->id . ' 
            AND ctd.id_tdpsblog = '.(int)$id_tdpost.' AND ctd.active = 1 '
             . 'AND ctd.comment_parent =' . (int) $parent.' ORDER BY ctd.id_tdpsblog_comments ASC'); 
             
              foreach ($results as $result) {
                $sub_comments = tdpsblogModel::getComments($id_tdpost, $result['id_tdpsblog_comments']);
                if ($sub_comments && count($sub_comments) > 0)
                    $result['sub_comments'] = $sub_comments;
                $tdpspostcomments[] = $result;
            }

            return isset($tdpspostcomments) ? $tdpspostcomments : false;   
      }
        public static function getTotalCommentsByPost($id_tdpost) {
          $context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;
            return Db::getInstance()->ExecuteS('
            SELECT ctd.`id_tdpsblog_comments`, ctd.`id_tdpsblog`, ctd.`comment_author_name`, ctd.`comment_author_email`, ctd.`comment_date`, ctd.`comment_parent`, ctd.`active`, ctd1.`comments_text`, ctd1.`id_lang`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_comments` ctd
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_comments_lang` ctd1 ON (ctd.`id_tdpsblog_comments` = ctd1.`id_tdpsblog_comments`)
            WHERE ctd1.`id_lang` = ' . (int) $context->language->id . ' 
            AND ctd.id_tdpsblog = '.(int)$id_tdpost.' AND ctd.active = 1 ORDER BY ctd.id_tdpsblog_comments ASC'); 
      }
      
      public static function getPostCategory($parent=0) {
          $context = Context::getContext();
		$id_shop = $context->shop->id;
		$id_lang = $context->language->id;
    $results=Db::getInstance()->ExecuteS('
            SELECT ctd.`id_tdpsblog_category`, ctd.`category_parent`, ctd.`active`, ctd.`id_shop`, ctd1.`category_name`, ctd1.`id_lang`, ctd1.`cat_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_category` ctd
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` ctd1 ON (ctd.`id_tdpsblog_category` = ctd1.`id_tdpsblog_category`)
            WHERE ctd1.`id_lang` = ' . (int) $context->language->id . ' AND ctd.active = 1 '
             . 'AND ctd.category_parent =' . (int) $parent.' ORDER BY ctd.id_tdpsblog_category ASC'); 
             
              foreach ($results as $result) {
                $sub_cat = tdpsblogModel::getPostCategory( $result['id_tdpsblog_category']);
                if ($sub_cat && count($sub_cat) > 0)
                    $result['sub_category'] = $sub_cat;
                $tdpspostcategory[] = $result;
            }

            return isset($tdpspostcategory) ? $tdpspostcategory : false;   
      }
    
}
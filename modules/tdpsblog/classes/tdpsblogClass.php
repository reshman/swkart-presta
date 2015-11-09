<?php

class tdpsblogClass {

    var $items_per_page;
    var $items_total;
    var $current_page;
    var $num_pages;
    var $mid_range;
    var $low;
    var $high;
    var $limit;
    var $return;

    public function __construct() {
        $itemperpage = Configuration::get('td_blogperpage') ? Configuration::get('td_blogperpage') : 1;
        $this->current_page = 1;
        $this->mid_range = 3;
        $this->items_per_page = $itemperpage;
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
    }

    public function getBlogByCategory($tdcatid, $id_shop, $languageid, $limit) {
        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog`, td.`tdpost_category`, td.`tdpost_dete`, td.`active`, td.`position`, td.`allow_comment`, td.`comments_count`,td.`tdpost_view`, td1.`tdpost_title`, td1.`id_lang`, td1.`tdpost_content`, td1.`image_url`, td1.`link_rewrite`, td2.`category_name`, td2.`cat_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` td1 ON (td.`id_tdpsblog` = td1.`id_tdpsblog`)
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td2 ON (td.`tdpost_category` = td2.`id_tdpsblog_category`)
            WHERE td1.`id_lang`  = ' . (int) $languageid . ' 
                AND td2.`id_lang` = ' . (int) $languageid . ' 
                AND td.`tdpost_category`=' . (int) $tdcatid . '
                AND td.id_shop = ' . (int) $id_shop . '
            ORDER BY td.`position` ' . $limit . '');
    }

    public function gettdBlogPostByID($id_tdpost, $languageid, $id_shop) {
        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog`, td.`tdpost_category`, td.`tdpost_dete`, td.`active`, td.`position`, td.`allow_comment`, td.`comments_count`,td.`tdpost_view`, td1.`tdpost_title`, td1.`id_lang`, td1.`tdpost_content`, td1.`image_url`, td1.`link_rewrite`, td2.`category_name`, td2.`cat_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` td1 ON (td.`id_tdpsblog` = td1.`id_tdpsblog`)
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td2 ON (td.`tdpost_category` = td2.`id_tdpsblog_category`)
            WHERE td1.`id_lang`  = ' . (int) $languageid . ' 
                AND td2.`id_lang` = ' . (int) $languageid . ' 
                AND td.id_shop = ' . (int) $id_shop . ' AND td.`active`=1 AND td.id_tdpsblog = ' . (int) $id_tdpost . '
            ORDER BY td.`position` ');
    }

    public function getNumberOfPost($id_shop, $languageid) {
        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog`, td.`tdpost_category`, td.`tdpost_dete`, td.`active`, td.`position`, td.`allow_comment`, td.`comments_count`,td.`tdpost_view`, td1.`tdpost_title`, td1.`id_lang`, td1.`tdpost_content`, td1.`image_url`, td1.`link_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` td1 ON (td.`id_tdpsblog` = td1.`id_tdpsblog`)
            WHERE td1.`id_lang`  = ' . (int) $languageid . '
                AND td.id_shop = ' . (int) $id_shop . '
            ORDER BY td.`position` AND td.`active`=1');
    }

    public function getNumberOfPostByCat($id_shop, $languageid, $tdcatid) {
        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog`, td.`tdpost_category`, td.`tdpost_dete`, td.`active`, td.`position`, td.`allow_comment`, td.`comments_count`,td.`tdpost_view`, td1.`tdpost_title`, td1.`id_lang`, td1.`tdpost_content`, td1.`image_url`, td1.`link_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` td1 ON (td.`id_tdpsblog` = td1.`id_tdpsblog`)
            WHERE td1.`id_lang`  = ' . (int) $languageid . '
                AND td.`tdpost_category`=' . (int) $tdcatid . ' AND td.`active`=1
                AND td.id_shop = ' . (int) $id_shop . '
            ORDER BY td.`position` AND td.`active`=1');
    }

    public function getRecentpost($id_shop, $languageid, $limit) {

        return $tdBlogPost = Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog`, td.`tdpost_category`, td.`tdpost_dete`, td.`active`, td.`position`, td.`allow_comment`, td.`comments_count`,td.`tdpost_view`, td1.`tdpost_title`, td1.`id_lang`, td1.`tdpost_content`, td1.`image_url`, td1.`link_rewrite`, td2.`category_name`, td2.`cat_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` td1 ON (td.`id_tdpsblog` = td1.`id_tdpsblog`)
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td2 ON (td.`tdpost_category` = td2.`id_tdpsblog_category`)
            WHERE td1.`id_lang`  = ' . (int) $languageid . ' 
                AND td2.`id_lang` = ' . (int) $languageid . ' 
                AND td.id_shop = ' . (int) $id_shop . ' AND td.`active`=1
            ORDER BY td.`id_tdpsblog` DESC LIMIT ' . $limit . '');
    }

    /* public function getRecentComments($id_shop,$languageid,$limit){
      return Db::getInstance()->ExecuteS('
      SELECT ctd.`id_tdpsblog_comments`, ctd.`id_tdpsblog`, ctd.`comment_author_name`, ctd.`comment_author_email`, ctd.`comment_date`, ctd.`comment_parent`, ctd.`active`, ctd1.`comments_text`, ctd1.`id_lang`
      FROM `' . _DB_PREFIX_ . 'tdpsblog_comments` ctd
      INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td1 ON (td1.`id_tdpsblog_comments` = ctd1.`id_tdpsblog`)
      INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_comments_lang` ctd1 ON (ctd.`id_tdpsblog_comments` = ctd1.`id_tdpsblog_comments`)
      WHERE ctd1.`id_lang` = ' . (int) $languageid . '
      AND ctd.active = 1 ORDER BY ctd.id_tdpsblog_comments DESC LIMIT '.$limit.'');
      } */

    public function getRecentComments($id_shop, $languageid, $limit) {
        return Db::getInstance()->ExecuteS('
            SELECT ctd.`id_tdpsblog_comments`, ctd.`id_tdpsblog`, ctd.`comment_author_name`, ctd.`comment_author_email`, ctd.`comment_date`, ctd.`comment_parent`, ctd.`active`, ctd1.`comments_text`, ctd1.`id_lang`, ctd2.`link_rewrite` 
            FROM `' . _DB_PREFIX_ . 'tdpsblog_comments` ctd
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` ctd2 ON (ctd2.`id_tdpsblog` = ctd.`id_tdpsblog`)
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_comments_lang` ctd1 ON (ctd.`id_tdpsblog_comments` = ctd1.`id_tdpsblog_comments`)
            WHERE ctd1.`id_lang` = ' . (int) $languageid . ' AND ctd2.`id_lang` = ' . (int) $languageid . '
            AND ctd.active = 1 ORDER BY ctd.id_tdpsblog_comments DESC LIMIT ' . $limit . '');
    }

    public function getAllCategory($id_shop, $languageid) {
        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog_category`, td.`active`, td1.`category_name`, td1.`id_lang`, td1.`cat_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_category` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td1 ON (td.`id_tdpsblog_category` = td1.`id_tdpsblog_category`)
            WHERE td.`id_shop`= ' . (int) $id_shop . '  AND td.`active`=1 AND td1.`id_lang` = ' . $languageid . '');
    }

    public function getAllBlogPost($id_shop, $languageid, $limit) {
        //echo $limit;
        return $tdBlogPost = Db::getInstance()->ExecuteS('
            SELECT td.`id_tdpsblog`, td.`tdpost_category`, td.`tdpost_dete`, td.`active`, td.`position`, td.`allow_comment`, td.`comments_count`,td.`tdpost_view`, td1.`tdpost_title`, td1.`id_lang`, td1.`tdpost_content`, td1.`image_url`, td1.`link_rewrite`, td2.`category_name`, td2.`cat_rewrite`
            FROM `' . _DB_PREFIX_ . 'tdpsblog` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_lang` td1 ON (td.`id_tdpsblog` = td1.`id_tdpsblog`)
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td2 ON (td.`tdpost_category` = td2.`id_tdpsblog_category`)
            WHERE td1.`id_lang`  = ' . (int) $languageid . ' 
                AND td2.`id_lang` = ' . (int) $languageid . ' 
                AND td.id_shop = ' . (int) $id_shop . ' AND td.`active`=1
            ORDER BY td.`position` ' . $limit . '');
    }

    public function getCategoryNameByID($id) {

        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;

        return Db::getInstance()->ExecuteS('
            SELECT td1.`category_name`
            FROM `' . _DB_PREFIX_ . 'tdpsblog_category` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdpsblog_category_lang` td1 ON (td.`id_tdpsblog_category` = td1.`id_tdpsblog_category`)
            WHERE td1.`id_tdpsblog_category`= ' . (int) $id . ' AND td.`id_shop`= ' . (int) $id_shop . ' AND td1.`id_lang` = ' . (int) $id_lang . '');
    }

    function paginate($current_page, $pstdblogcatid = false, $rewrite = false) {

        if (!is_numeric($this->items_per_page) OR $this->items_per_page <= 0)
            $this->items_per_page = $itemperpage;
        $this->num_pages = ceil($this->items_total / $this->items_per_page);


        $this->current_page = (int) $current_page; // must be numeric > 0
        if ($this->current_page < 1 Or !is_numeric($this->current_page))
            $this->current_page = 1;
        if ($this->current_page > $this->num_pages)
            $this->current_page = $this->num_pages;
        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;

        if ($this->num_pages > 5) {
             if($pstdblogcatid){
                  $ppreviouslinks=_PS_BASE_URL_SSL_.__PS_BASE_URI__.'blog/category/'.$pstdblogcatid.'_'.$rewrite.'/page-'.$prev_page.'.html';
                }else{
                  $ppreviouslinks=_PS_BASE_URL_SSL_.__PS_BASE_URI__.'blog/page-'.$prev_page.'.html';
                }
            $this->return = ($this->current_page != 1 And $this->items_total >= 5) ? "<li><a class=\"paginate\" href=".$ppreviouslinks.">« Previous</a></li> " : "<li class=\"disable\"><span class=\"inactive\" href=\"#\">« Previous</span> ";

            $this->start_range = $this->current_page - floor($this->mid_range / 2);
            $this->end_range = $this->current_page + floor($this->mid_range / 2);

            if ($this->start_range <= 0) {
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }
            if ($this->end_range > $this->num_pages) {
                $this->start_range -= $this->end_range - $this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range, $this->end_range);

            for ($i = 1; $i <= $this->num_pages; $i++) {
                if ($this->range[0] > 2 And $i == $this->range[0])
                    $this->return .= " ... ";
                // loop through all pages. if first, last, or in range, display
               
                if ($i == 1 Or $i == $this->num_pages Or in_array($i, $this->range)) {
                 if($pstdblogcatid){
                  $numberpage=_PS_BASE_URL_SSL_.__PS_BASE_URI__.'blog/category/'.$pstdblogcatid.'_'.$rewrite.'/page-'.$i.'.html';
                }else{
                  $numberpage=_PS_BASE_URL_SSL_.__PS_BASE_URI__.'blog/page-'.$i.'.html';
                }
                    $this->return .= ($i == $this->current_page) ? "<li class=\"current\"><a title=\"Go to page $i of $this->num_pages\"  href=\"#\">$i</a></li> " : "<li><a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$numberpage\">$i</a></li> ";
                }
                if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 And $i == $this->range[$this->mid_range - 1])
                    $this->return .= " ... ";
            }
             if($pstdblogcatid){
                  $pnextlinks=_PS_BASE_URL_SSL_.__PS_BASE_URI__.'blog/category/'.$pstdblogcatid.'_'.$rewrite.'/page-'.$next_page.'.html';
                }else{
                  $pnextlinks=_PS_BASE_URL_SSL_.__PS_BASE_URI__.'blog/page-'.$next_page.'.html';
                }
            $this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 5)) ? "<li><a class=\"paginate\" href=\"$pnextlinks\">Next »</a></li>\n" : "<span class=\"inactive\" href=\"#\">» Next</span>\n";
        }
        else {
            for ($i = 1; $i <= $this->num_pages; $i++) {
                if($pstdblogcatid){
                  $palllinks=_PS_BASE_URL_SSL_.__PS_BASE_URI__.'blog/category/'.$pstdblogcatid.'_'.$rewrite.'/page-'.$i.'.html';
                }else{
                  $palllinks=_PS_BASE_URL_SSL_.__PS_BASE_URI__.'blog/page-'.$i.'.html';
                }
                $this->return .= ($i == $this->current_page) ? "<li><a class=\"current\" href=\"#\">$i</a> </li>" : "<li><a class=\"paginate\" href=".$palllinks.">$i</a></li>";
            }
        }
        if ($this->current_page == 0) {
            $this->current_page = 1;
        }
        $this->low = ($this->current_page - 1) * $this->items_per_page;
        $this->high = ($this->current_page * $this->items_per_page) - 1;
        $this->limit = " LIMIT $this->low,$this->items_per_page";
    }

    public static function postlinks($tdpost_id, $rewrite) {
        $post_links = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'blog/details/' . $tdpost_id . '-' . $rewrite . '.html';
        $links = preg_replace('/(\?)?(&amp;)?=\d+/', '', $post_links);
        return $links;
    }

    public static function catlinks($tdcat_id, $rewrite) {
        $cat_links = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'blog/category/' . $tdcat_id . '_' . $rewrite . '.html';
        $links = preg_replace('/(\?)?(&amp;)?=\d+/', '', $cat_links);
        return $links;
    }

    public static function postReplyLinks($tdpost_id, $rewrite, $replytocom) {
        $post_links = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'blog/details/' . $replytocom . '-reply/' . $tdpost_id . '-' . $rewrite . '.html';
        $links = preg_replace('/(\?)?(&amp;)?=\d+/', '', $post_links);
        return $links;
    }

//{$tdblogdetailslinks}{$alllinksalise}tdpost={$blogPost.id_tdpsblog}&replytocom={$tdblogpost.id_tdpsblog_comments}
    function display_pages() {
        return $this->return;
    }

    public function insertComments($author, $email, $comment) {
        Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog_comments`(`id_tdpsblog`,`comment_author_name`, `comment_author_email`, `comment_date`,`comment_parent`,`active`) VALUES(' . Tools::getValue('comment_post_id') . ',"' . $author . '","' . $email . '","' . date("F d, Y") . '",' . Tools::getValue('comment_parent') . ',0)');
        $languages = Language::getLanguages(false);
        $id_tdpsblog = Db::getInstance()->Insert_ID();
        foreach ($languages as $language) {
            Db::getInstance()->Execute('
                        INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog_comments_lang`(`id_tdpsblog_comments`, `id_lang`, `comments_text`) 
                        VALUES(' . $id_tdpsblog . ', ' . (int) $language['id_lang'] . ', 
                        "' . $comment . '")');
        }
        return true;
    }

    public function inputData($inputdata) {
        $inputdata = trim($inputdata);
        $inputdata = stripslashes($inputdata);
        $inputdata = htmlspecialchars($inputdata);
        return $inputdata;
    }

}

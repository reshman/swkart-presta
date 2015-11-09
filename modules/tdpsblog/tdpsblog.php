<?php
/**
 * Blog
 *
 * @author     ThemesDeveloper support@themesdeveloper.com
 * @copyright  2013-2015 ThemesDeveloper
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.0
 */
if (!defined('_CAN_LOAD_FILES_'))
    exit;

include_once(dirname(__FILE__) . '/tdpsblogModel.php');
include_once(dirname(__FILE__) . '/classes/tdpsblogClass.php');

class TDpsblog extends Module {

    private $_html;
    private $_display;
    private $_baseUrl;

    public function __construct() {
        $this->name = 'tdpsblog';
        $this->tab = 'front_office_features';
        $this->version = '1.6'; //updated for themesdev
        $this->author = 'ThemesDeveloper';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->secure_key = Tools::encrypt($this->name);
        parent::__construct();

        $this->displayName = $this->l('ThemesDeveloper Blog Options');
        $this->description = $this->l('BlogPost by ThemeDeveloper');
    }

    public function install() {
        /* Adds Module */
        if (parent::install() && $this->registerHook('moduleRoutes') && $this->registerHook('home') && $this->registerHook('leftColumn') && $this->registerHook('rightColumn') && $this->registerHook('header')) {
            /* Install tables */
            $respons = tdpsblogModel::createTables();
            return $respons;
        }
        return false;
    }

    public function uninstall() {
        /* Deletes Module */
        if (parent::uninstall()) {
            /* Deletes tables */
            $respons = tdpsblogModel::DropTables();
            return $respons;
        }
        return false;
    }

    public function getContent() {
        $this->_html = '';
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        $iso = $this->context->language->iso_code;

        if (version_compare(_PS_VERSION_, '1.6.0.12') >= 0)
                 $this->_html .= '
			<script type="text/javascript">	
				var iso = \'' . (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ? $iso : 'en') . '\' ;
				var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
				var ad = \'' . dirname($_SERVER['PHP_SELF']) . '\' ;
			</script>
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/admin/tinymce.inc.js"></script>
			<script language="javascript" type="text/javascript">
				id_language = Number(' . $id_lang_default . ');
				tinySetup();
			</script>';
        elseif (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
            $this->_html .= '
			<script type="text/javascript">	
				var iso = \'' . (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ? $iso : 'en') . '\' ;
				var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
				var ad = \'' . dirname($_SERVER['PHP_SELF']) . '\' ;
			</script>
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tiny_mce/tiny_mce.js"></script>
                        <script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tinymce.inc.js"></script>
			<script language="javascript" type="text/javascript">
				id_language = Number(' . $id_lang_default . ');
				tinySetup();
			</script>';
        else {
            $this->_html .= '
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
				tinyMCE.init({
					mode : "textareas",
					theme : "advanced",
					plugins : "safari,pagebreak,style,layer,table,advimage,advlink,inlinepopups,media,searchreplace,contextmenu,paste,directionality,fullscreen",
					theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor",
					theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,ltr,rtl,|,fullscreen",
					theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,pagebreak",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : false,
					content_css : "' . __PS_BASE_URI__ . 'themes/' . _THEME_NAME_ . '/css/global.css",
					document_base_url : "' . __PS_BASE_URI__ . '",
					width: "600",
					height: "auto",
					font_size_style_values : "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt",
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",
					elements : "nourlconvert",
					entity_encoding: "raw",
					convert_urls : false,
					language : "' . (file_exists(_PS_ROOT_DIR_ . '/js/tinymce/jscripts/tiny_mce/langs/' . $iso . '.js') ? $iso : 'en') . '"
				});
				id_language = Number(' . $id_lang_default . ');
			</script>';
        }
        if (Tools::isSubmit('TDsupmitvelue') || Tools::isSubmit('deleteBlogPost') || Tools::isSubmit('changeStatus')) {
            if ($this->_postValidation())
                $this->_insertBlogPost();
            $this->_displayblogPost();
        }
        elseif (Tools::isSubmit('addNewPost') || (Tools::isSubmit('id_tdpsblog')))
            $this->_displayForm();
        else
            $this->_displayblogPost();


        return $this->_html;
    }

    public function hookModuleRoutes($params) {
        $routes = array(
            'tdpsblog' => array(
                'controller' => 'default',
                'rule' => 'blog',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'tdpsblog'
                )
            ),
            'tdpsblog-default' => array(
                'controller' => 'default',
                'rule' => 'blog/',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'tdpsblog'
                )
            ),
            'tdpsblog-details' => array(
                'controller' => 'details',
                'rule' => 'blog/details/{tdpost}-{rewrite}.html',
                'keywords' => array(
                    'rewrite' => array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'rewrite'),
                    'tdpost' => array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'tdpost'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'tdpsblog'
                )
            ),
            'tdpsblog-default-category' => array(
                'controller' => 'default',
                'rule' => 'blog/category/{tdcatid}_{rewrite}.html',
                'keywords' => array(
                    'rewrite' =>  array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'rewrite'),
                    'tdcatid' =>  array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'tdcatid'),
                ),
                'params' =>  array(
                    'fc' => 'module',
                    'module' => 'tdpsblog'
                )
            ),
            'tdpsblog-details-reply' => array(
                'controller' => 'details',
                'rule' => 'blog/details/{replytocom}-reply/{tdpost}-{rewrite}.html',
                'keywords' =>  array(
                    'rewrite' =>  array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'rewrite'),
                    'tdpost' =>  array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'tdpost'),
                    'replytocom' =>  array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'replytocom'),
                ),
                'params' =>  array(
                    'fc' => 'module',
                    'module' => 'tdpsblog'
                )
            ),
            'tdpsblog-default-category-page' => array(
                'controller' => 'default',
                'rule' => 'blog/category/{tdcatid}_{rewrite}/page-{page}.html',
                'keywords' =>  array(
                    'tdcatid' =>  array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'tdcatid'),
                    'page' =>  array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'page'),
                    'rewrite' =>  array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'rewrite'),
                ),
                'params' =>  array(
                    'fc' => 'module',
                    'module' => 'tdpsblog'
               )
            ),
            'tdpsblog-default-page' => array(
                'controller' => 'default',
                'rule' => 'blog/page-{page}.html',
                'keywords' =>  array(
                    'page' =>array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'page'),
                ),
                'params' =>  array(
                    'fc' => 'module',
                    'module' => 'tdpsblog'
                )
            ),
        );
        return $routes;
    }

    private function _insertBlogPost() {
        global $currentIndex;
        $errors = array();
        $moduledir = _PS_MODULE_DIR_ . 'tdpsblog/banner/';
        $moduleurl = 'modules/tdpsblog/banner/';

        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;

        if (Tools::isSubmit('TDsupmitvelue')) {
            $languages = Language::getLanguages(false);

            if (Tools::isSubmit('addNewPost')) {
                $position = Db::getInstance()->getValue('
			SELECT COUNT(*) 
			FROM `' . _DB_PREFIX_ . 'tdpsblog`');

                Db::getInstance()->Execute('
            INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog` (`tdpost_category`, `active`, `position`,`allow_comment`,`id_shop`) 
            VALUES(' . (int) Tools::getValue('selcat') . ',' . (int) Tools::getValue('td_active_post') . ',' . (int) $position . ',' . (int) Tools::getValue('td_comments_blog') . ',' . (int) $id_shop . ')');

                $id_tdpsblog = Db::getInstance()->Insert_ID();
                foreach ($languages as $language) {
                    $strtolower = strtolower(Tools::getValue('td_title_' . $language['id_lang']));
                    $link_rewrite = str_replace(' ', '-', $strtolower);
                    $name = $_FILES['td_image_' . $language['id_lang']]['name'];
                    $image_url = $moduleurl . $name;

                    $path = $moduledir . $name;
                    $tmpname = $_FILES['td_image_' . $language['id_lang']]['tmp_name'];
                    move_uploaded_file($tmpname, $path);

                    Db::getInstance()->Execute('
                INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog_lang` (`id_tdpsblog`, `id_lang`, `tdpost_title`, `tdpost_content`,`image_url`,`link_rewrite`) 
                VALUES(' . (int) $id_tdpsblog . ', ' . (int) $language['id_lang'] . ', 
                "' . pSQL(Tools::getValue('td_title_' . $language['id_lang'])) . '", 
                 "' . htmlspecialchars(Tools::getValue('td_content_' . $language['id_lang'])) . '","' . $image_url . '","' . $link_rewrite . '")');
                }
            } elseif (Tools::isSubmit('updateBlogPost')) {

                $tdBlogPostid = Tools::getvalue('id_tdpsblog');

                // print_r($tdBlogPostid) ;
                Db::getInstance()->Execute('
                UPDATE `' . _DB_PREFIX_ . 'tdpsblog`
                SET `tdpost_category`= ' . (int) Tools::getValue('selcat') . ',
                `active` = ' . (int) Tools::getValue('td_active_post') . ',
                `allow_comment` = ' . (int) Tools::getValue('td_comments_blog') . ',
               `id_shop` = "' . $id_shop . '"
                WHERE `id_tdpsblog` = ' . (int) ($tdBlogPostid));
                //echo $image_url;


                $languages = Language::getLanguages(false);
                foreach ($languages as $language) {
                    if ($_FILES['td_image_' . $language['id_lang']]['name']):

                        $name = $_FILES['td_image_' . $language['id_lang']]['name'];
                        $image_url = $moduleurl . $name;

                        $path = $moduledir . $name;
                        $tmpname = $_FILES['td_image_' . $language['id_lang']]['tmp_name'];
                        move_uploaded_file($tmpname, $path);

                    else:
                        $image_url = Tools::getvalue('image_old_link_' . $language['id_lang']);
                    endif;
                    $strtolower = strtolower(Tools::getValue('td_title_' . $language['id_lang']));
                    $link_rewrite = str_replace(' ', '-', $strtolower);
                    Db::getInstance()->Execute('
                            UPDATE `' . _DB_PREFIX_ . 'tdpsblog_lang` 
                            SET `tdpost_title` = "' . pSQL(Tools::getValue('td_title_' . $language['id_lang'])) . '",                    
                            `tdpost_content` = "' . htmlspecialchars(Tools::getValue('td_content_' . $language['id_lang'])) . '",
                            `image_url` = "' . $image_url . '", `link_rewrite` = "' . $link_rewrite . '"
                            WHERE `id_tdpsblog` = ' . (int) $tdBlogPostid . '  AND `id_lang`= ' . (int) $language['id_lang']);
                }
                // unlink($image_url);
            }
        }elseif (Tools::isSubmit('changeStatus') AND Tools::getValue('id_tdpsblog')) {

            Db::getInstance()->Execute('
            UPDATE `' . _DB_PREFIX_ . 'tdpsblog`
            SET `active` = ' . (int) Tools::getValue('changeStatus') . '
            WHERE `id_tdpsblog` = ' . Tools::getValue('id_tdpsblog'));
        } elseif (Tools::isSubmit('deleteBlogPost') AND Tools::getValue('id_tdpsblog')) {
            Db::getInstance()->Execute('
                DELETE FROM `' . _DB_PREFIX_ . 'tdpsblog`
                WHERE `id_tdpsblog` = ' . (int) (Tools::getValue('id_tdpsblog')));

            Db::getInstance()->Execute('
				DELETE FROM `' . _DB_PREFIX_ . 'tdpsblog_lang` 
				WHERE `id_tdpsblog` = ' . (int) (Tools::getValue('id_tdpsblog')));
        }
        if (count($errors))
            $this->_html .= $this->displayError(implode('<br />', $errors));
        elseif (Tools::isSubmit('TDsupmitvelue') && Tools::getValue('id_tdpsblog'))
            $this->_html .= $this->displayConfirmation($this->l('Updated Successfully'));
        elseif (Tools::isSubmit('TDsupmitvelue'))
            $this->_html .= $this->displayConfirmation($this->l('Successfully Added'));
        elseif (Tools::isSubmit('deleteBlogPost'))
            $this->_html .= $this->displayConfirmation($this->l('Deletion successful'));
    }

    private function _postValidation() {
        $errors = array();
        if (Tools::isSubmit('TDsupmitvelue')) {
            $languages = Language::getLanguages(false);
        } elseif (Tools::isSubmit('deleteBlogPost') AND !Validate::isInt(Tools::getValue('id_tdpsblog')))
            $errors[] = $this->l('Invalid ID');

        if (sizeof($errors)) {
            $this->_html .= $this->displayError(implode('<br />', $errors));
            return false;
        }
        return true;
    }

    private function _displayForm() {

        global $currentIndex, $cookie;
        $updatevalue = NULL;
        if (Tools::isSubmit('updateBlogPost') AND Tools::getValue('id_tdpsblog'))
            $updatevalue = tdpsblogModel::getBlogPostByID((int) Tools::getValue('id_tdpsblog'));
        $blogcat = tdpsblogModel::getAllblogCategory();

//print_r($updatevalue);
        /* Languages preliminaries */
        $defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));
        $languages = Language::getLanguages(false);
        $iso = Language::getIsoById((int) ($cookie->id_lang));
        $divLangName = 'title¤image¤td_image¤image¤description';

        $this->_html .= '<style>
        .language_flags {
display: none;
}
.discount_name {
    background: none repeat scroll 0 0 #FFEBCC;
    padding: 2px;
    text-transform: uppercase;
}
.displayed_flag {
    float: left;
    margin: 4px 0 0 4px;
}
.language_flags {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #555555;
    display: none;
    float: left;
    margin: 4px;
    padding: 8px;
    width: 80px;
}
.pointer {
    cursor: pointer;
}
.clear{
clear:both;
}

</style>
		<fieldset>
			<legend>' . $this->l('ThemesDeveloper  Blog') . '</legend>      
<div id="fieldset_0" class="panel">

                    <div class="panel-heading">
                                                    <i class="icon-cogs"></i>                            Blog informations
                    </div>';


        $this->_html.= '<form class="defaultForm  form-horizontal" method="post" action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '" enctype="multipart/form-data">
  ';

        $this->_html.= '
<div class="form-group ">

        <label class="control-label col-lg-3 " for="active_slide">
                                Active
                        </label>';


        $this->_html .= '


<div class="col-lg-9 ">

                                                                        <div "col-lg-9"="">
                <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" ' . ((isset($updatevalue[0]['active']) && $updatevalue[0]['active'] == 0) ? '' : 'checked="checked" ') . '  value="1" id="active_post_on" name="td_active_post">
<label for="active_post_on">
                                                                        Yes
                                                        </label>
                                                <input type="radio" value="0" ' . ((isset($updatevalue[0]['active']) && $updatevalue[0]['active'] == 0) ? 'checked="checked" ' : '') . ' id="active_post_off" name="td_active_post">
<label for="active_post_off">
                                                                        No
                                                        </label>
                                                <a class="slide-button btn"></a>
                </span>
        </div>






                                                                                            </div>




</div>	<div class="form-group ">

        <label class="control-label col-lg-3 " for="active_slide">
                                ' . $this->l('Allow Comment:') . '
                        </label>';


        $this->_html .= '


<div class="col-lg-9 ">

                                                                        <div "col-lg-9"="">
                <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" ' . ((isset($updatevalue[0]['allow_comment']) && $updatevalue[0]['allow_comment'] == 0) ? '' : 'checked="checked" ') . '  value="1" id="active_slide_on" name="td_comments_blog">
<label for="active_slide_on">
                                                                        Yes
                                                        </label>
                                                <input type="radio" value="0" ' . ((isset($updatevalue[0]['allow_comment']) && $updatevalue[0]['allow_comment'] == 0) ? 'checked="checked" ' : '') . ' id="active_slide_off" name="td_comments_blog">
<label for="active_slide_off">
                                                                        No
                                                        </label>
                                                <a class="slide-button btn"></a>
                </span>
        </div>






                                                                                            </div>




</div>	';



        $this->_html .= '
<div class="form-group ">

<label class="control-label col-lg-3 " for="url_1">
               ' . $this->l('Select Category:') . '
            </label>


<div class="col-lg-9 ">
 <div class="margin-form">
					<select name="selcat">';
        foreach ($blogcat as $blogcategory) {
            if ($updatevalue[0]['tdpost_category'] == $blogcategory['id_tdpsblog_category'])
                $selcat = 'selected';
            else
                $selcat = '';
            $this->_html .='<option ' . $selcat . ' value="' . $blogcategory['id_tdpsblog_category'] . '">' . $blogcategory['category_name'] . '</option>';
        }
        $this->_html .= ' </select>
				</div>';
        $this->_html .='</div>


</div>';
        $this->_html .= '
<div class="form-group ">

<label class="control-label col-lg-3 " for="url_1">
              ' . $this->l('Title') . '
            </label>


<div class="col-lg-9 ">
	<div class="margin-form">';
        foreach ($languages as $language) {
            $this->_html.= '
            <div id="title_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
                    <input type="text" name="td_title_' . $language['id_lang'] . '" id="td_title_' . $language['id_lang'] . '" size="64"  value="' . (Tools::getValue('td_title_' . $language['id_lang']) ? Tools::getValue('td_title_' . $language['id_lang']) : (isset($updatevalue['tdpost_title'][$language['id_lang']]) ? $updatevalue['tdpost_title'][$language['id_lang']] : '')) . '"/>
            </div>';
        }
        $this->_html .=$this->displayFlags($languages, $defaultLanguage, $divLangName, 'title', true);



        $this->_html .='</div><div class="clear"></div>';
        $this->_html .='</div>


</div>';
        $this->_html .= '
<div class="form-group ">

<label class="control-label col-lg-3 " for="url_1">
             ' . $this->l('Post Content:') . '
            </label>


<div class="col-lg-9 ">


		<div class="margin-form">';
        foreach ($languages as $language) {
            $getsavevalue = stripslashes($updatevalue['tdpost_content'][$language['id_lang']]);

            $this->_html .= '<div id="description_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
				<textarea class="rte"  name="td_content_' . $language['id_lang'] . '" rows="10" cols="60">' . (Tools::getValue('td_content_' . $language['id_lang']) ? Tools::getValue('td_content_' . $language['id_lang']) : (isset($updatevalue['tdpost_content'][$language['id_lang']]) ? $getsavevalue : '')) . '</textarea>
			</div>';
        }
        $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'description', true);
        $this->_html .= '</div><div class="clear"></div><br />';
        $this->_html .='</div>


</div>';
        $this->_html .='<div class="form-group ">

<label class="control-label col-lg-3 " for="url_1">
               ' . $this->l('Upload Image') . '
            </label>
<div class="col-lg-9 ">';
        if (Tools::isSubmit('updateBlogPost') AND Tools::getValue('id_tdpsblog')) {
            $this->_html.= '<div class="margin-form">';
            foreach ($languages as $language) {
                $this->_html.= '<div id="image_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
                    <input type="hidden" name="image_old_link_' . $language['id_lang'] . '" value="' . $updatevalue['image_url'][$language['id_lang']] . '" />
                    <img src="' . __PS_BASE_URI__ . $updatevalue['image_url'][$language['id_lang']] . '" width=60 height=60></div> ';
            }
            $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'image', true);
            $this->_html.= '</div>';
        }

        $this->_html.= '<div class="clear"></div>';
        foreach ($languages as $language) {
            $this->_html .= '<div id="td_image_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
             <input type="file" name="td_image_' . $language['id_lang'] . '" value=""/>
                    </div>';
        }
        $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'td_image', true);
        $this->_html .= '<div class="clear"></div><br />';
        $this->_html.= '
</div></div>
               <div class="clear"></div>
            <div class="panel-footer">

<button class="btn btn-default pull-right" name="TDsupmitvelue" id="module_form_submit_btn" value="1" type="submit">
                                                    <i class="process-icon-save"></i> Save
                                            </button>
<a class="btn btn-default" href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '">
							<i class="process-icon-cancel"></i> Cancel
						</a>

            </div>     


      </fieldset>
    </form>
   
';
    }

    public function delete($id_product_comment_criterion) {

        $tdpsdelsql = Db::getInstance()->execute('
		DELETE FROM `' . _DB_PREFIX_ . 'tdpsblog_comments`
		WHERE `id_tdpsblog_comments` = ' . (int) ($id_product_comment_criterion));

        $tdpsdelsql .=Db::getInstance()->execute('
		DELETE FROM `' . _DB_PREFIX_ . 'tdpsblog_comments_lang`
		WHERE `id_tdpsblog_comments` = ' . (int) ($id_product_comment_criterion));
        return $tdpsdelsql;
    }

    private function _checkModerateComment() {
        $action = Tools::getValue('moderate_action');
        if (empty($action) === false) {
            $product_comments = Tools::getValue('id_product_comment');

            if (count($product_comments)) {

                switch ($action) {
                    case 'accept':
                        foreach ($product_comments as $id_product_comment) {
                            if (!$id_product_comment)
                                continue;
                            tdpsblogModel::validate($id_product_comment);
                        }
                        break;

                    case 'delete':

                        foreach ($product_comments as $id_product_comment) {
                            if (!$id_product_comment)
                                continue;

                            $this->delete($id_product_comment);
                        }
                        break;

                    default:
                }
            }
        }
    }

    private function _checkDeleteComment() {
        $action = Tools::getValue('delete_action');
        if (empty($action) === false) {
            $product_comments = Tools::getValue('delete_id_product_comment');

            if (count($product_comments)) {
                if ($action == 'delete') {
                    foreach ($product_comments as $id_product_comment) {
                        if (!$id_product_comment)
                            continue;

                        $this->delete($id_product_comment);
                    }
                }
            }
        }
    }

    private function _displayblogPost() {

        global $currentIndex, $cookie;
        $errors = array();
        $defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));
        $languages = Language::getLanguages(false);
        $iso = Language::getIsoById((int) ($cookie->id_lang));
        $divLangName = 'title¤image¤td_image¤image¤description¤tdcatname';


        $this->_checkModerateComment();
        $this->_checkDeleteComment();
        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;

        if (Tools::isSubmit('tdSubmitCategory')) {
            Db::getInstance()->Execute('
            INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog_category` (`category_parent`,`active`,`id_shop`) 
            VALUES(' . (int) Tools::getValue('parent_cat') . ',' . (int) Tools::getValue('td_activeblog') . ',' . (int) $id_shop . ')');

            $id_tdpsblogcat = Db::getInstance()->Insert_ID();
            foreach ($languages as $language) {
                $sttolower = strtolower(Tools::getValue('td_catname_' . $language['id_lang']));
                $catlinksrewrite = str_replace(' ', '-', $sttolower);

                Db::getInstance()->Execute('
                INSERT INTO `' . _DB_PREFIX_ . 'tdpsblog_category_lang` (`id_tdpsblog_category`, `id_lang`, `category_name`, `cat_rewrite`) 
                VALUES(' . (int) $id_tdpsblogcat . ', ' . (int) $language['id_lang'] . ', 
                "' . pSQL(Tools::getValue('td_catname_' . $language['id_lang'])) . '","' . $catlinksrewrite . '")');
            }
        } elseif (Tools::isSubmit('deleteBlogCat') && Tools::isSubmit('id_tdpsblog_category')) {
            Db::getInstance()->Execute('
                DELETE FROM `' . _DB_PREFIX_ . 'tdpsblog_category`
                WHERE `id_tdpsblog_category` = ' . (int) (Tools::getValue('id_tdpsblog_category')));

            Db::getInstance()->Execute('
				DELETE FROM `' . _DB_PREFIX_ . 'tdpsblog_category_lang` 
				WHERE `id_tdpsblog_category` = ' . (int) (Tools::getValue('id_tdpsblog_category')));
        }
        if (count($errors)) {
            $this->_html .= $this->displayError(implode('<br />', $errors));
        } elseif (Tools::isSubmit('tdSubmitCategory')) {
            $this->_html .= $this->displayConfirmation($this->l('Category added Successfully'));
        } elseif (Tools::isSubmit('deleteBlogCat') && Tools::isSubmit('id_tdpsblog_category')) {
            $this->_html .= $this->displayConfirmation($this->l('Deletion successful'));
        }
        $blogcat = tdpsblogModel::getAllblogCategory();
        $blogpost = tdpsblogModel::getAllblogPost();

        $getinactivecom = tdpsblogModel::getAllInactiveComments();
        $getactivecom = tdpsblogModel::getAllActiveComments();
        // print_r($blogpost) ;
        /* foreach ($blogcat as $blogcategory){
          $this->_html .='<option value="'.$blogcategory['id_tdpsblog_category'].'">'.$blogcategory['category_name'].'</option>';
          } */

        $this->context->controller->addJqueryUI('ui.sortable');
        $this->_html .= '<script type="text/javascript" src="' . $this->_path . 'js/moderate.js"></script><style>
        .language_flags {
display: none;
}
.discount_name {
    background: none repeat scroll 0 0 #FFEBCC;
    padding: 2px;
    text-transform: uppercase;
}
.displayed_flag {
    float: left;
    margin: 4px 0 0 4px;
}
.language_flags {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #555555;
    display: none;
    float: left;
    margin: 4px;
    padding: 8px;
    width: 80px;
}
.pointer {
    cursor: pointer;
}
.clear{
clear:both;
}
.pull-left > small {
    display: block;
    padding-top: 15px;
}
</style><script type="text/javascript">
                    $(function() {
                            var $mySlides = $("#slides");
                            $mySlides.sortable({
                                    opacity: 0.6,
                                    cursor: "move",
                                    update: function() {
                                            var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
                                            $.post("' . $this->context->shop->physical_uri . $this->context->shop->virtual_uri . 'modules/' . $this->name . '/' . $this->name . 'Ajax.php?secure_key=' . $this->secure_key . '", order);
                                            }
                                    });
                            $mySlides.hover(function() {
                                    $(this).css("cursor","move");
                                    },
                                    function() {
                                    $(this).css("cursor","auto");
                            });
                    });
            </script><fieldset>
        <legend>ThemesDeveloper Blog Options</legend>';



        $this->_html .= '<div class="panel"><h3><i class="icon-list-ul"></i> Post list
    <span class="panel-heading-action"><span class="panel-heading-action">
            <a href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&addNewPost" class="list-toolbar-btn" id="desc-product-new">
                    <span data-html="true" data-original-title="Add new" class="label-tooltip" data-toggle="tooltip" title="">
                            <i class="process-icon-new "></i>
                    </span>
            </a>
    </span>
    </h3> <div id="slidesContent">
            <div id="slides" class="ui-sortable" style="cursor: auto;">';
        if ($blogpost):



            foreach ($blogpost as $tdblogdata):
                $categorypost = tdpsblogModel::getCategoryByID($tdblogdata['tdpost_category']);
                if (isset($categorypost[0]) && is_array($categorypost[0])) {
                    $postcateogry = $categorypost[0]['category_name'];
                } else {
                    $postcateogry = '';
                }
                $this->_html .= '<div class="panel" id="slides_' . $tdblogdata['id_tdpsblog'] . '">
                                    <div class="row">
                                            <div class="col-lg-1">
                                                    <span><i class="icon-arrows "></i></span>
                                            </div>
                                            <div class="col-md-3">

                                                    <img  class="img-thumbnail" src="' . __PS_BASE_URI__ . $tdblogdata['image_url'] . '" width="80%">
                                            </div> <div class="col-md-8">
                                                    <h4 class="pull-left">#' . $tdblogdata['id_tdpsblog'] . ' - ' . $tdblogdata['tdpost_title'] . '<small>' . $postcateogry . '</small></h4>
                                                    <div class="btn-group-action pull-right">';
                if ($tdblogdata['active'] == 1) :
                    $this->_html .= '<a title="Enabled" href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&changeStatus=0&id_tdpsblog=' . (int) ($tdblogdata['id_tdpsblog']) . '" class="btn btn-success"><i class="icon-check"></i> Enabled</a>';
                else :
                    $this->_html .= '<a title="Disabled" href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&changeStatus=1&id_tdpsblog=' . (int) ($tdblogdata['id_tdpsblog']) . '" class="btn btn-danger"><i class="icon-remove"></i> Disabled</a>';
                endif;
                $this->_html .= '<a href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&updateBlogPost&id_tdpsblog=' . (int) ($tdblogdata['id_tdpsblog']) . '" class="btn btn-default">
                                                                    <i class="icon-edit"></i>
                                                                    Edit
                                                            </a>
                                                            <a href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&deleteBlogPost&id_tdpsblog=' . (int) ($tdblogdata['id_tdpsblog']) . '" class="btn btn-default">
                                                                    <i class="icon-trash"></i>
                                                                    Delete
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                            </div>
                       ';


            endforeach;
        endif;


        $this->_html .= '</table></div></fieldset>';

        $this->_html .= '<form id="productcomments" class="form-horizontal clearfix" name="comment_form" method="post" action="' . Tools::safeOutput($this->_baseUrl) . '">
					<div class="panel col-lg-12">
	<h3>Moderate Comments</h3>	
	<div class="table-responsive clearfix">
                                        <input type="hidden" id="id_product_comment" name="id_product_comment[]">
					<input type="hidden" id="moderate_action" name="moderate_action">
					<table class="table  productcomments">
					<thead>';
        if (count($getinactivecom) > 0) {
            $this->_html .= '<tr>
						<th style="width:50px;"><input class="noborder" type="checkbox" onclick="checkDelBoxes(this.form, \'id_product_comment[]\', this.checked)" /></th>
						<th style="width:50px;">Post-ID</th>
<th style="width:150px;">Author</th>
						<th style="width:500px;">Comment</th>
						<th style="width:30px;">Actions</th>
					</tr>
					</thead>
					<tbody>';

            foreach ($getinactivecom as $tdcomments):
                $this->_html .= ' <tr><td><input type="checkbox" name="id_product_comment[]" value="' . $tdcomments['id_tdpsblog_comments'] . '" class="noborder"></td>
					<td style="text-align:center">#' . $tdcomments['id_tdpsblog'] . '</td>	
<td>' . $tdcomments['comment_author_name'] . '</td>
						<td>' . $tdcomments['comments_text'] . '</td>
						<td>
                                                    <a onclick="acceptComment(' . $tdcomments['id_tdpsblog_comments'] . ');" href="javascript:;"><img title="Accept" alt="Accept" src="' . __PS_BASE_URI__ . 'modules/tdpsblog/img/accept.png"></a>
					            <a onclick="deleteComment(' . $tdcomments['id_tdpsblog_comments'] . ');" href="javascript:;"><img title="Delete" alt="Delete" src="' . __PS_BASE_URI__ . 'modules/tdpsblog/img/delete.png"></a></td>
						</tr>
                                                ';
            endforeach;

            $this->_html .= '<tr>
							<td style="font-weight:bold;text-align:right" colspan="4">Selection:</td>
							<td><a onclick="acceptComment(0);" href="javascript:;"><img title="Accept" alt="Accept" src="' . __PS_BASE_URI__ . 'modules/tdpsblog/img/accept.png"></a>
							<a onclick="deleteComment(0);" href="javascript:;"><img title="Delete" alt="Delete" src="' . __PS_BASE_URI__ . 'modules/tdpsblog/img/delete.png"></a></td>
						</tr>';
        }else {

            $this->_html .='<tr>
		<td colspan="8" class="list-empty">
			<div class="list-empty-msg">
				<i class="icon-warning-sign list-empty-icon"></i>
				No records found
			</div>
		</td>
	</tr>';
        }
        $this->_html .= '</tbody>
					</table>
                                        </div></div>
					</form>
                                        <form  class="form-horizontal clearfix" name="delete_comment_form" method="post" action="' . Tools::safeOutput($this->_baseUrl) . '">

					<div class="panel col-lg-12">
	<h3>Manage Comments</h3>	
	<div class="table-responsive clearfix">
					
					<input type="hidden" id="delete_id_product_comment" name="delete_id_product_comment[]">
					<input type="hidden" id="delete_action" name="delete_action">
					<table class="table  productcomments">
					<thead>';
        if (count($getactivecom) > 0) {
            $this->_html .= '<tr>
						<th style="width:50px;"><input class="noborder" type="checkbox" onclick="checkDelBoxes(this.form, \'delete_id_product_comment[]\', this.checked)" /></th>
						<th style="width:50px;">Post-ID</th>
                                                <th style="width:150px;">Author</th>
						<th style="width:500px;">Comment</th>
						<th style="width:30px;">Actions</th>
					</tr>
					</thead>
					<tbody>';
            foreach ($getactivecom as $tdactivecomments):
                $this->_html .= '<tr>
						<td><input type="checkbox" name="delete_id_product_comment[]" value="' . $tdactivecomments['id_tdpsblog_comments'] . '" class="noborder"></td>
						<td style="text-align:center">#' . $tdactivecomments['id_tdpsblog'] . '</td>
<td>' . $tdactivecomments['comment_author_name'] . '</td>
						<td>' . $tdactivecomments['comments_text'] . '</td>
						<td><a onclick="delComment(\'' . (int) ($tdactivecomments['id_tdpsblog_comments']) . '\',\'' . $this->l('Are you sure?') . '\');" href="javascript:;"><img title="Delete" alt="Delete" src="' . __PS_BASE_URI__ . 'modules/tdpsblog/img/delete.png"></a></td>
						</tr>';
            endforeach;
            $this->_html .= '<tr>
						
						<tr>
							<td style="font-weight:bold;text-align:right" colspan="4">Selection:</td>
							<td><a onclick="delComment(0,\'' . $this->l('Are you sure?') . '\'); " href="javascript:;"><img title="Delete" alt="Delete" src="' . __PS_BASE_URI__ . 'modules/tdpsblog/img/delete.png"></a></td>
						</tr>';
        }else {
            $this->_html .='<tr>
		<td colspan="8" class="list-empty">
			<div class="list-empty-msg">
				<i class="icon-warning-sign list-empty-icon"></i>
				No records found
			</div>
		</td>
	</tr>';
        }
        $this->_html .= '</tbody>
					</table>
					  </div></div>
                                          </form>
                    
                      
<form  class="form-horizontal clearfix" name="criterion_form" method="post" action="' . Tools::safeOutput($this->_baseUrl) . '">
					<div class="panel col-lg-12">
	<h3>Manage Blog Category</h3>	
	<div class="table-responsive clearfix">
        
	
			';






        $this->_html .='<div class="form-group ">

<label class="control-label col-lg-3 " for="url_1">
             ' . $this->l('Category Name') . '
            </label>
	<div class="col-lg-9 ">';
        foreach ($languages as $language) {
            $this->_html.= '
            <div id="tdcatname_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
                    <input type="text" name="td_catname_' . $language['id_lang'] . '" id="td_catname_' . $language['id_lang'] . '" size="64"  value="' . (Tools::getValue('td_catname_' . $language['id_lang']) ? Tools::getValue('td_catname_' . $language['id_lang']) : (isset($updatevalue['td_catname'][$language['id_lang']]) ? $updatevalue['td_catname'][$language['id_lang']] : '')) . '"/>
            </div>';
        }
        $this->_html .=$this->displayFlags($languages, $defaultLanguage, $divLangName, 'tdcatname', true);

        $this->_html .= '</div><div class="clear">&nbsp;</div>
 <div class="margin-form" style="display:none">
					<select name="parent_cat">
                                            <option value="0">Root</option>';
        $this->_html .= ' </select>
				</div>';


        $this->_html.= '</div>
<div class="form-group ">
';


        $this->_html .= '
        <label class="control-label col-lg-3 " for="active_slide">
                                Active
                        </label>

<div class="col-lg-9 ">

                                                                        <div "col-lg-9"="">
                <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" ' . ((isset($updatevalue[0]['active']) && $updatevalue[0]['active'] == 0) ? '' : 'checked="checked" ') . '  value="1" id="active_slide_on" name="td_activeblog">
<label for="active_slide_on">
                                                                        Yes
                                                        </label>
                                                <input type="radio" value="0" ' . ((isset($updatevalue[0]['active']) && $updatevalue[0]['active'] == 0) ? 'checked="checked" ' : '') . ' id="active_slide_off" name="td_activeblog">
<label for="active_slide_off">
                                                                        No
                                                        </label>
                                                <a class="slide-button btn"></a>
                </span>
        </div>






                                                                                            </div>




</div>';


        $this->_html.= '
               <div class="clear"></div>
               <div class="panel-footer">

<button type="submit" value="1" id="module_form_submit_btn" name="tdSubmitCategory" class="btn btn-default pull-right">
                                                    <i class="process-icon-save"></i> Add Category
                                            </button>

            </div></div><h3>Blog Category List</h3>
        ';

        $this->_html .= '
		
			<br>
						<table class="table">
						<thead>
						<tr>
							<th style="width:80px;">id</th>
							<th style="width:460px;">Category Name</th>
							<th style="width:50px;">Status</th>
							<th style="width:30px;">Actions</th>
						</tr>
						</thead>
						<tbody>
                                                ';

        foreach ($blogcat as $blogcate) {
            $this->_html .= '<tr>
                                                        <td>' . $blogcate['id_tdpsblog_category'] . '</td>
							<td>' . $blogcate['category_name'] . '</td>
							<td style="text-align:center;">';
            if ($blogcate['active'] == 1) :

                $this->_html .= '<img title="Enabled" alt="Enabled" src="../img/admin/enabled.gif">';
            else :
                $this->_html .= '<img title="Disabled" alt="Disabled" src="../img/admin/disabled.gif">';
            endif;

            $this->_html .= '</td>
							<td><a href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&deleteBlogCat&id_tdpsblog_category=' . (int) ($blogcate['id_tdpsblog_category']) . '" "><img alt="Delete" src="../img/admin/delete.gif"></a>
                                                        </td></tr>';
        }
        $this->_html .= '
                                                        <tr></tr></tbody></table></form>
</div></div>';
    }

    function hookHome($params) {
        global $smarty;
        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;
        $numofrepost = Configuration::get('td_bloghome') ? Configuration::get('td_bloghome') : 10;
        $psblog = new tdpsblogClass();
        $blogtdrecentpost = $psblog->getRecentpost($id_shop, $id_lang, $numofrepost);


        //print_r($blogtdrecentpost);
        $smarty->assign(array(
            'default_lang' => (int) $params['cookie']->id_lang,
            'id_lang' => (int) $params['cookie']->id_lang,
            'tdblogdetailslinks' => $this->context->link->getModuleLink('tdpsblog', 'details'),
            'tdblogcatpostlinks' => $this->context->link->getModuleLink('tdpsblog', 'default'),
            'blogtdrecentpost' => $blogtdrecentpost,
            'base_url' => __PS_BASE_URI__
        ));
        return $this->display(__FILE__, 'tdpsblog-home.tpl');
    }

    public function hookRightColumn($params) {
        return $this->hookLeftColumn($params);
    }

    function hookLeftColumn($params) {
        global $smarty;
        $this->context = Context::getContext();
        $psblog = new tdpsblogClass();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;
        $numofrepost = Configuration::get('td_numofrepost') ? Configuration::get('td_numofrepost') : 5;
        $numofcomments = Configuration::get('td_numofcomments') ? Configuration::get('td_numofcomments') : 5;
        $blogtdrecentpost = $psblog->getRecentpost($id_shop, $id_lang, $numofrepost);
        $allblogtdcat = $psblog->getAllCategory($id_shop, $id_lang);
        $blogtdrecentcomments = $psblog->getRecentComments($id_shop, $id_lang, $numofcomments);


        //print_r($blogtdrecentpost);
        $smarty->assign(array(
            'default_lang' => (int) $params['cookie']->id_lang,
            'id_lang' => (int) $params['cookie']->id_lang,
            'tdblogdetailslinks' => $this->context->link->getModuleLink('tdpsblog', 'details'),
            'tdblogcatpostlinks' => $this->context->link->getModuleLink('tdpsblog', 'default'),
            'base_url' => __PS_BASE_URI__,
            'blogtdrecentpost' => $blogtdrecentpost,
            'tdblogallcat' => $allblogtdcat,
            'blogtdrecentcomments' => $blogtdrecentcomments
        ));
        return $this->display(__FILE__, 'tdpsblog.tpl');
    }

}

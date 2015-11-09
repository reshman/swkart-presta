<?php
if (!defined('_CAN_LOAD_FILES_'))
    exit;

include_once(dirname(__FILE__) . '/tdcontentsliderModel.php');
class TDcontentSlider extends Module {
    private $_html;
    private $_display;
    
    public function __construct() {
        $this->name = 'tdcontentslider';
        $this->tab = 'front_office_features';
        $this->version = '1.3';//content slider for themesdev PS themes
        $this->author = 'ThemesDeveloper';
        $this->need_instance = 0;
        parent::__construct();
         $this->bootstrap = true;
        $this->displayName = $this->l('ThemesDeveloper Content Slider');
        $this->description = $this->l('Home Page Content Slider by ThemeDeveloper');
        $this->secure_key = Tools::encrypt($this->name);
  
    }

    public function install() {
        /* Adds Module*/
        if (parent::install() && $this->registerHook('displayTopColumn')) {
            /* Install tables */
            $respons = tdcontentsliderModel::createTables();
            return $respons;
        }
        return false;
    }

    public function uninstall() {
        /* Deletes Module */
        if (parent::uninstall()) {
            /* Deletes tables */
            $respons = tdcontentsliderModel::DropTables();
            return $respons;
        }
        return false;
    }

    public function getContent() {
        $this->_html = '';

                   $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
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
        if (Tools::isSubmit('TDsupmitvelue') || Tools::isSubmit('deleteSlider') || Tools::isSubmit('changeStatus')) {
            if ($this->_postValidation())
                $this->_insertSlider();
            $this->_displaySlider();
        }
        elseif (Tools::isSubmit('addNewSlider') || (Tools::isSubmit('id_tdcontentslider')))
            $this->_displayForm();
        else
            $this->_displaySlider();

        return $this->_html;
    }

    private function _insertSlider() {
        global $currentIndex;
        $errors = array();
        $moduledir=_PS_MODULE_DIR_.'tdcontentslider/banner/';
        $moduleurl='modules/tdcontentslider/banner/';
        
        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;
        
        if (Tools::isSubmit('TDsupmitvelue')) {
            $languages = Language::getLanguages(false);

            if (Tools::isSubmit('addNewSlider')) {
                $position = Db::getInstance()->getValue('
			SELECT COUNT(*) 
			FROM `' . _DB_PREFIX_ . 'tdcontentslider`');
                    
                Db::getInstance()->Execute('
            INSERT INTO `' . _DB_PREFIX_ . 'tdcontentslider` (`slider_link`,`active`,`position`,`id_shop`) 
            VALUES("' . Tools::getValue('slider_link') . '",' . (int) Tools::getValue('td_active_slide') . ',' . (int) $position . ',' . (int) $id_shop . ')');

                $id_tdcontentslider = Db::getInstance()->Insert_ID();
                foreach ($languages as $language) {
                    
                    $name = $_FILES['td_image_' . $language['id_lang']]['name'];
                    $image_url = $moduleurl . $name;

                    $path = $moduledir . $name;
                    $tmpname = $_FILES['td_image_' . $language['id_lang']]['tmp_name'];
                    move_uploaded_file($tmpname, $path);
            
                    Db::getInstance()->Execute('
                INSERT INTO `' . _DB_PREFIX_ . 'tdcontentslider_lang` (`id_tdcontentslider`, `id_lang`, `image_title`, `slider_content`,`image_url`) 
                VALUES(' . (int) $id_tdcontentslider . ', ' . (int) $language['id_lang'] . ', 
                "' . pSQL(Tools::getValue('td_title_' . $language['id_lang'])) . '", 
                 "' . htmlspecialchars(Tools::getValue('td_content_' . $language['id_lang'])) . '","' . $image_url . '")');
                }
            } elseif (Tools::isSubmit('updateSlider')) {

               $tdsliderid = Tools::getvalue('id_tdcontentslider');
               
              // print_r($tdsliderid) ;
                Db::getInstance()->Execute('
                UPDATE `' . _DB_PREFIX_ . 'tdcontentslider`
                SET `slider_link`= "' . Tools::getValue('slider_link') . '",
                `active` = ' . (int) Tools::getValue('td_active_slide') . ',
               `id_shop` = "' . $id_shop . '"
                WHERE `id_tdcontentslider` = ' . (int) ($tdsliderid));
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
                    
                    
                    Db::getInstance()->Execute('
                            UPDATE `' . _DB_PREFIX_ . 'tdcontentslider_lang` 
                            SET `image_title` = "' . pSQL(Tools::getValue('td_title_' . $language['id_lang'])) . '",                    
                            `slider_content` = "' .htmlspecialchars(Tools::getValue('td_content_' . $language['id_lang'])) . '",
                            `image_url` = "' . $image_url . '"
                            WHERE `id_tdcontentslider` = ' . (int) $tdsliderid . '  AND `id_lang`= ' . (int) $language['id_lang']);
                }
                // unlink($image_url);
            }
        } elseif (Tools::isSubmit('changeStatus') AND Tools::getValue('id_tdcontentslider')) {
            
            Db::getInstance()->Execute('
            UPDATE `' . _DB_PREFIX_ . 'tdcontentslider`
            SET `active` = ' . (int) Tools::getValue('changeStatus') . '
            WHERE `id_tdcontentslider` = ' .Tools::getValue('id_tdcontentslider'));
            
         }elseif (Tools::isSubmit('deleteSlider') AND Tools::getValue('id_tdcontentslider')) {
            Db::getInstance()->Execute('
                DELETE FROM `' . _DB_PREFIX_ . 'tdcontentslider`
                WHERE `id_tdcontentslider` = ' . (int) (Tools::getValue('id_tdcontentslider')));

            Db::getInstance()->Execute('
				DELETE FROM `' . _DB_PREFIX_ . 'tdcontentslider_lang` 
				WHERE `id_tdcontentslider` = ' . (int) (Tools::getValue('id_tdcontentslider')));
        }
        if (count($errors))
            $this->_html .= $this->displayError(implode('<br />', $errors));
        elseif (Tools::isSubmit('TDsupmitvelue') && Tools::getValue('id_tdcontentslider'))
            $this->_html .= $this->displayConfirmation($this->l('Advertise Update Successfully'));
        elseif (Tools::isSubmit('TDsupmitvelue'))
            $this->_html .= $this->displayConfirmation($this->l('Advertise added Successfully'));
        elseif (Tools::isSubmit('deleteSlider'))
            $this->_html .= $this->displayConfirmation($this->l('Deletion successful'));
    }

    private function _postValidation() {
        $errors = array();
        if (Tools::isSubmit('TDsupmitvelue')) {
            $languages = Language::getLanguages(false);
        }
        elseif (Tools::isSubmit('deleteSlider') AND !Validate::isInt(Tools::getValue('id_tdcontentslider')))
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
    if (Tools::isSubmit('updateSlider') AND Tools::getValue('id_tdcontentslider'))
        $updatevalue = tdcontentsliderModel::getSliderByID((int) Tools::getValue('id_tdcontentslider'));
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
</style><fieldset>
            <legend>' . $this->l('ThemesDeveloper Content Slider') . '</legend>
<div id="fieldset_0" class="panel">

                    <div class="panel-heading">
                                                    <i class="icon-cogs"></i>                            Slide informations
                    </div>



                    ';

    $this->_html.= '<form class="defaultForm  form-horizontal" method="post" action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '" enctype="multipart/form-data">
  ';


   

    $this->_html .='
 <div class="form-group ">

    <label class="control-label col-lg-3 " for="title_1">
                            ' . $this->l('Title') . '
                    </label>
<div class="col-lg-9 ">';

    foreach ($languages as $language) {
        $this->_html.= '
        <div id="title_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
                <input type="text" name="td_title_' . $language['id_lang'] . '" id="td_title_' . $language['id_lang'] . '" size="64"  value="' . (Tools::getValue('td_title_' . $language['id_lang']) ? Tools::getValue('td_title_' . $language['id_lang']) : (isset($updatevalue['image_title'][$language['id_lang']]) ? $updatevalue['image_title'][$language['id_lang']] : '')) . '"/>
        </div>';
    }
    $this->_html .=$this->displayFlags($languages, $defaultLanguage, $divLangName, 'title', true);
    $this->_html .='</div>
       </div>


<div class="form-group ">

<label class="control-label col-lg-3 " for="url_1">
                ' . $this->l('URL') . '
            </label>


<div class="col-lg-9 ">

<input type="text" name="slider_link" id="slider_link" size="64"  value="' . (Tools::getValue('slider_link') ? Tools::getValue('slider_link') : (isset($updatevalue[0]['slider_link']) ? $updatevalue[0]['slider_link'] : '')) . '"/>
</div>


</div>

<div class="form-group ">

<label class="control-label col-lg-3 " for="url_1">
               ' . $this->l('Description:') . '
            </label>


<div class="col-lg-9 ">';
    foreach ($languages as $language) {
        $this->_html .= '<div id="description_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
                            <textarea class="rte" name="td_content_' . $language['id_lang'] . '" rows="10" cols="60">' . (Tools::getValue('td_content_' . $language['id_lang']) ? Tools::getValue('td_content_' . $language['id_lang']) : (isset($updatevalue['slider_content'][$language['id_lang']]) ? $updatevalue['slider_content'][$language['id_lang']] : '')) . '</textarea>
                    </div>';
    }
    $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'description', true);
    $this->_html .='</div>


</div>';








    $this->_html .='<div class="form-group ">

<label class="control-label col-lg-3 " for="url_1">
               ' . $this->l('Upload Image') . '
            </label>
<div class="col-lg-9 ">';
    if (Tools::isSubmit('updateSlider') AND Tools::getValue('id_tdcontentslider')) {
        foreach ($languages as $language) {
            $this->_html.= '<div id="image_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
                <input type="hidden" name="image_old_link_' . $language['id_lang'] . '" value="' . $updatevalue['image_url'][$language['id_lang']] . '" />
                <img src="' . __PS_BASE_URI__ . $updatevalue['image_url'][$language['id_lang']] . '" width=60 height=60></div> ';
        }
        $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'image', true);
    }

    $this->_html.= '<div class="clear"></div><br/>';
    foreach ($languages as $language) {
        $this->_html .= '<div class="clear" id="td_image_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
         <input type="file" name="td_image_' . $language['id_lang'] . '" value=""/>
                </div>';
    }
    $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'td_image', true);


    $this->_html.= '
</div></div>
<div class="form-group ">

        <label class="control-label col-lg-3 " for="active_slide">
                                Active
                        </label>';


 $this->_html .= '


<div class="col-lg-9 ">

                                                                        <div "col-lg-9"="">
                <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" ' . ((isset($updatevalue[0]['active']) && $updatevalue[0]['active'] == 0) ? '' : 'checked="checked" ') . '  value="1" id="active_slide_on" name="td_active_slide">
<label for="active_slide_on">
                                                                        Yes
                                                        </label>
                                                <input type="radio" value="0" ' . ((isset($updatevalue[0]['active']) && $updatevalue[0]['active'] == 0) ? 'checked="checked" ' : '') . ' id="active_slide_off" name="td_active_slide">
<label for="active_slide_off">
                                                                        No
                                                        </label>
                                                <a class="slide-button btn"></a>
                </span>
        </div>






                                                                                            </div>




</div>			

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

private function _displaySlider() {
	if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;

    global $currentIndex;

    $slider = tdcontentsliderModel::getAllSlider();

    // print_r($slider) ;
$this->context->controller->addJqueryUI('ui.sortable');
    $this->_html .= '<script type="text/javascript">
                    $(function() {
                            var $mySlides = $("#slides");
                            $mySlides.sortable({
                                    opacity: 0.6,
                                    cursor: "move",
                                    update: function() {
                                            var order = $(this).sortable("serialize") + "&action=updateSlidesPosition";
                                            $.post("'.$this->context->shop->physical_uri.$this->context->shop->virtual_uri.'modules/'.$this->name.'/'.$this->name.'Ajax.php?secure_key='.$this->secure_key.'", order);
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
        <legend>ThemesDeveloper Content Slider</legend>';
        $this->_html.= '
             <div class="panel"><h3><i class="icon-list-ul"></i> Slides list
    <span class="panel-heading-action">
            <a href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&addNewSlider" class="list-toolbar-btn" id="desc-product-new">
                    <span data-html="true" data-original-title="Add new" class="label-tooltip" data-toggle="tooltip" title="">
                            <i class="process-icon-new "></i>
                    </span>
            </a>
    </span>
    </h3>';
       if ($slider):
      $this->_html.= '<div id="slidesContent">
            <div id="slides" class="ui-sortable" style="cursor: auto;">';

        foreach ($slider as $tdsliderdata):
            $this->_html .= '<div class="panel" id="slides_' . $tdsliderdata['id_tdcontentslider'] . '">
                                    <div class="row">
                                            <div class="col-lg-1">
                                                    <span><i class="icon-arrows "></i></span>
                                            </div>
                                            <div class="col-md-3">

                                                    <img  class="img-thumbnail" src="' . __PS_BASE_URI__ . $tdsliderdata['image_url'] . '" width="80%">
                                            </div>
                                            <div class="col-md-8">
                                                    <h4 class="pull-left">#' . $tdsliderdata['id_tdcontentslider'] . ' - ' . $tdsliderdata['image_title'] . '</h4>
                                                    <div class="btn-group-action pull-right">';
 if ($tdsliderdata['active'] == 1) :                                     
$this->_html .= '<a title="Enabled" href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&changeStatus=0&id_tdcontentslider=' . (int) ($tdsliderdata['id_tdcontentslider']) . '" class="btn btn-success"><i class="icon-check"></i> Enabled</a>';
else :
$this->_html .= '<a title="Disabled" href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&changeStatus=1&id_tdcontentslider=' . (int) ($tdsliderdata['id_tdcontentslider']) . '" class="btn btn-danger"><i class="icon-remove"></i> Disabled</a>';
   endif;
                                                            $this->_html .= '<a href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&updateSlider&id_tdcontentslider=' . (int) ($tdsliderdata['id_tdcontentslider']) . '" class="btn btn-default">
                                                                    <i class="icon-edit"></i>
                                                                    Edit
                                                            </a>
                                                            <a href="' . $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&deleteSlider&id_tdcontentslider=' . (int) ($tdsliderdata['id_tdcontentslider']) . '" class="btn btn-default">
                                                                    <i class="icon-trash"></i>
                                                                    Delete
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                            </div>';
        endforeach;



    endif;




    $this->_html .= '			
                                    </div>
    </div>
</div>
';
}
public function clearCache()
{
        $this->_clearCache('tdcontentslider.tpl');
}

    function hookDisplayTopColumn($params) {
        global $smarty;
             $this->context = Context::getContext();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;
            $tdslider = Db::getInstance()->ExecuteS('
            SELECT td.`id_tdcontentslider`, td.`slider_link`, td.`active`, td1.`image_url`, td.`position`,td1.image_title, td1.`slider_content`
            FROM `' . _DB_PREFIX_ . 'tdcontentslider` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdcontentslider_lang` td1 ON (td.`id_tdcontentslider` = td1.`id_tdcontentslider`)
            WHERE td1.`id_lang` = ' . (int) $params['cookie']->id_lang . ' 
                AND td.id_shop = '.(int)$id_shop.'
            ORDER BY td.`position`');
            $data = array();
            foreach ($tdslider as $slider):
                if ($slider['active'])
                    $data[] = $slider;
            endforeach;
            //print_r($tdslider); 
            $smarty->assign(array(
                'default_lang' => (int) $params['cookie']->id_lang,
                'id_lang' => (int) $params['cookie']->id_lang,
                'tdcontentslider' => $data,
                'base_url' => __PS_BASE_URI__
            ));
            return $this->display(__FILE__, 'tdcontentslider.tpl');

    }
}
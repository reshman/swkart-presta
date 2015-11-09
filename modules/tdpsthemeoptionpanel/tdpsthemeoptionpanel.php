<?php
/**
 * ThemeOptions
 *
 * @author     ThemesDeveloper support@themesdeveloper.com
 * @copyright  2013-2015 ThemesDeveloper
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.0
 */
if (!defined('_PS_VERSION_'))
    exit;

class TDpsThemeOptionPanel extends Module {

    public $idshop, $idshopgroup, $languages, $default_language, $_html, $pattern, $successmeg;
    public $tdoptions = array();
    public $tdthemename = "td_";

    public function __construct() {
        $this->name = 'tdpsthemeoptionpanel';
        $this->tab = 'front_office_features';
        $this->version = '2.0.1'; //2.0.1 theme options for all themesdeveloper themes
        $this->author = 'ThemesDeveloper';
        $this->secure_key = Tools::encrypt($this->name);
        $this->default_language = (int) (Configuration::get('PS_LANG_DEFAULT'));
        $this->languages = Language::getLanguages(false);
        parent::__construct();
        $this->displayName = $this->l('Barberry Theme Options Panel');
        $this->description = $this->l('Barberry Prestashop Themes Option Panel By ThemesDeveloper');
        $this->module_path = _PS_MODULE_DIR_ . $this->name . '/';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->tdpsBaseModeURL = __PS_BASE_URI__ . 'modules/tdpsthemeoptionpanel/';
        $this->backofficImage = __PS_BASE_URI__ . 'modules/tdpsthemeoptionpanel/img/';
        $this->backofficJS = __PS_BASE_URI__ . 'modules/tdpsthemeoptionpanel/js/';
        $this->backofficCSS = __PS_BASE_URI__ . 'modules/tdpsthemeoptionpanel/css/';
        $this->patternsURL = __PS_BASE_URI__ . 'modules/tdpsthemeoptionpanel/bg/';
        $this->patternsDIR = _PS_MODULE_DIR_ . 'tdpsthemeoptionpanel/bg/';
        $this->themeImageURL = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'themes/barberry/img/barberry/';
        $this->themeCSSURL = __PS_BASE_URI__ . 'themes/barberry/css/barberry/';
        $this->themeJSURL = __PS_BASE_URI__ . 'themes/barberry/js/barberry/';
        $this->themeImage = __PS_BASE_URI__ . 'themes/barberry/img/';
        $this->tdshopBaseURL = _PS_BASE_URL_ . __PS_BASE_URI__;
        $this->getBgPatern();
        $shop_name = array();
        $shop_group = array();
        $gettdshop = Shop::getShops();
        foreach ($gettdshop as $totalshopgroup) {
            $shopgroup = Shop::getGroupFromShop($totalshopgroup['id_shop'], false);
            $shop_name[$totalshopgroup['id_shop']] = $totalshopgroup['name'];
            $shop_group[$totalshopgroup['id_shop_group']] = $shopgroup['name'];
        }
        $this->idshop = $shop_name;
        $this->idshopgroup = $shop_group;
        $this->tdThemeOption();
    }

    public function install() {
        if (!parent::install() ||
                !$this->registerHook('displayHeader') ||
                !$this->registerHook('displayTop') ||
                !$this->registerHook('displayLeftColumn') ||
                !$this->registerHook('displayRightColumn') ||
                !$this->registerHook('displayHome') ||
                !$this->registerHook('displayFooter') ||
                !$this->installDefaultValue()
        )
            return false;

        return true;
    }

    public function uninstall() {
        $tdoptions = $this->tdoptions;
        if (!parent::uninstall())
            return false;

        foreach ($tdoptions as $option_result) {
            $getsavevaluevalue = isset($option_result['std']) ? $option_result['std'] : '';
            if (isset($getsavevaluevalue)) {
                if (is_array($getsavevaluevalue)) {
                    foreach ($getsavevaluevalue as $key => $output_value) {
                        Configuration::deleteByName($option_result['id'] . '_' . $key);
                    }
                } else {
                    if (isset($option_result['lang']) && $option_result['lang'] == true) {
                        foreach ($this->languages as $lang) {
                            Configuration::deleteByName($option_result['id'] . '_' . $lang['id_lang']);
                        }
                    } else {
                        if (isset($option_result['id']))
                            Configuration::deleteByName($option_result['id']);
                    }
                }
            }
        }

        return true;
    }

    public function hookdisplayTop() {
        global $smarty;
        $tdpsthemeoptionpanel = array();
        foreach ($this->tdoptions as $option_value):
            if ($option_value['type'] == 'typography') {
                foreach ($option_value as $values) {
                    if (is_array($values)) {
                        foreach ($values as $key => $typovalue) {
                            $tdpsthemeoptionpanel[$option_value['id'] . '_' . $key] = Configuration::get($option_value['id'] . '_' . $key);
                        }
                    }
                }
            }
            if (isset($option_value['id'])):
                $tdpsthemeoptionpanel[$option_value['id']] = Configuration::get($option_value['id']);
            endif;
            if (isset($option_value['lang']) && $option_value['lang'] == true):

                foreach ($this->languages as $lang) {
                    $tdpsthemeoptionpanel[$option_value['id'] . '_' . $lang['id_lang']] = Configuration::get($option_value['id'] . '_' . $lang['id_lang']);
                }
                $tdpsthemeoptionpanel[$option_value['id']] = Configuration::get($option_value['id'] . '_' . $this->context->language->id);
            endif;
        endforeach;
        $smarty->assign('themesdev', $tdpsthemeoptionpanel);
    }

    public function hookdisplayHome() {
        return $this->hookdisplayTop();
    }

    public function hookdisplayHeader() {
        $this->styleCustom();
        return $this->hookdisplayTop();
    }

    public function hookdisplayFooter() {
        return $this->hookdisplayTop();
    }

    public function hookdisplayLeftColumn() {
        return $this->hookdisplayTop();
    }

    public function hookdisplayRightColumn() {
        return $this->hookdisplayTop();
    }

    public function getContent() {
        $this->_html = '';
        require_once '../config/config.inc.php';
        require_once '../init.php';

        $this->_html .= '
<link href="' . $this->backofficCSS . 'style.css" rel="stylesheet" type="text/css" />
<link href="' . $this->backofficCSS . 'colorpicker.css" rel="stylesheet" type="text/css" />
<link href="' . $this->backofficCSS . 'bootstrap.css" rel="stylesheet" type="text/css" />
<script src="' . $this->backofficJS . 'colorpicker.js" type="text/javascript"></script>
<script src="' . $this->backofficJS . 'medialibrary-uploader.js" type="text/javascript" ></script>
<script src="' . $this->backofficJS . 'bootstrap.js" type="text/javascript" ></script>
<script src="' . $this->backofficJS . 'options-custom.js" type="text/javascript" ></script>
    <script>
    jQuery(document).ready(function($) {
    	//AJAX Upload
                        $(".uploadbtn").each(function(){
                        //e.preventDefault();
			var btnpostid = $(this).attr("id");	
                        var btnpostobject = $(this);
			new AjaxUpload(btnpostid, {
				  action: "' . $this->tdpsBaseModeURL . 'tdpsthemeoptionpanelAjax.php",
				  name: btnpostid, 
				  data: {
						action: "tdajax_post_action",
						type: "upload_btntype",
						data: btnpostid },
				  autoSubmit: true,
				  responseType: false,
				  onSubmit: function(file, ext){
                                              // Allow only images. You should add security check on the server-side.
                                                if (ext && /^(jpg|png|jpeg|gif)$/i.test(ext)) {
                                                    this.disable(); 
                                                    btnpostobject.text("Uploading");
                                                    interval = window.setInterval(function(){
                                                    var text = btnpostobject.text();
                                                    if (text.length <16){btnpostobject.text(text + "."); }
                                                    else { btnpostobject.text("Uploading"); } 
						}, 200);
                                                } else {
                                                    alert("Only JPG, PNG or GIF files are allowed");
                                                    return false;
                                                } 
				  },
				  onComplete: function(file, res) {
                                        this.enable();
					window.clearInterval(interval);
					btnpostobject.text("Upload");	
                                        var return_data = \'<img  alt="" class="td-upload-image" id="upimage_\'+btnpostid+\'" src="\'+res+\'" />\';
                                        $("#upimage_" + btnpostid).remove();	
                                        btnpostobject.parent().after(return_data);
                                        $("img#upimage_"+btnpostid).fadeIn();
                                        btnpostobject.next("span").fadeIn();
                                        btnpostobject.parent().prev("input").val(res);
                                        }
				});
			
			});
			 //AJAX Reset Options
                         $(".resetbtn").click(function(){
                                   var resetimagetitle = jQuery(this).attr("title");	
                                    var data = {
                                            action: "tdajax_post_action",
                                            type: "reset_btntype",
                                            data: resetimagetitle
                                    };
                                    var btnpostobject = $(this);
                                    $.post("' . $this->tdpsBaseModeURL . 'tdpsthemeoptionpanelAjax.php", 
                                           data, 
                                    function(response) {
                                            var resetbutton = $("#upimage_" + resetimagetitle);
                                            var resetbtnimage = $("#resimage_" + resetimagetitle);
                                            resetbutton.fadeOut(400,function(){ 
                                                btnpostobject.remove(); 
                                            });
                                            resetbtnimage.fadeOut();
                                            btnpostobject.parent().prev("input").val("");
                                    });
                                    return false; 
					
				});
              });                  
</script>';
        if (Tools::isSubmit('TDOptionvelue')) {
            $this->_insertData($_POST);
        }
        $this->_tdOptionForm();
        return $this->_html;
    }

    private function _tdOptionForm() {
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $iso = $this->context->language->iso_code;
        $tdoptionfields = $this->tdOptionPanelFields();
        //print_r($tdoptionfields);

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
        $this->_html .= $this->successmeg . '
            <div class="container custome-bg tdthemeoption">
        <form id="for_form" method="post" action="index.php?tab=AdminModules&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&tab_module=front_office_features&module_name=' . $this->name . '" enctype="multipart/form-data" >
            <div class="header_wrap">
                        <h2>' . $this->displayName . '</h2>';
        $this->_html .= 'Theme Option by <a href="http://themesdeveloper.com" target="_blank">ThemesDeveloper</a>, <a href="http://themeforest.net/user/themesdeveloper?ref=themesdeveloper" target="_blank">View Profile</a>, <a href="http://themeforest.net/user/themesdeveloper/portfolio?ref=themesdeveloper">View Portfolio</a>, <a href="mailto:support@themesdeveloper.com">For Support</a>
            </div>
            <div class="row-fluid">
                <div id="sidebar" class="tabbable">
                    <div class="span3">
                        <div class="well">
                            <ul id="lefttablinks" class="nav nav-pills nav-stacked">
                                 ' . $tdoptionfields[0] . ' 
                            </ul>
                        </div><!-- .well -->
                    </div><!-- .span3 -->
                    <div class="span9">				
                        <div class="tab-content content-gbcolr">
 ' . $tdoptionfields[1] . ' 
                        </div><!-- .tab-content -->
                    </div><!-- .span7 -->
                </div><!-- .tabbable -->
            </div>
              <div class="page-footer">
                     <button type="submit" name="TDOptionvelue" class="savebutton btn-button save-button">Change Update</button>
               </div>
            	</form>
	<div style="clear:both;"></div>
        </div>';

        return $this->_html;
    }

    public function getOptionConfigure($id) {
        //$id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        //public static function get($key, $id_lang = null, $id_shop_group = null, $id_shop = null)
        return Configuration::get($id);
    }

    public function tdOptionPanelFields() {
        $tdoptions = $this->tdoptions;
        $leftlinks = '';
        $tdfieldsoutput = '';

        $count = 0;
        foreach ($tdoptions as $tdfields) {
            $langfields = '';
            $count++;
            if ($tdfields['type'] != "heading") {
                $headingclass = '';
                if (isset($tdfields['class'])) {
                    $headingclass = $tdfields['class'];
                }
                $tdfieldsoutput .= '<div class="sectionupload  section-' . $tdfields['type'] . ' ' . $headingclass . '">';

                if ($tdfields['type'] != "innerheading") {
                    if (isset($tdfields['name']))
                        $tdfieldsoutput .= '<h3 class="heading">' . $tdfields['name'] . '</h3>';
                }else {
                    $tdfieldsoutput .= '<h3 class="innerheading">' . $tdfields['name'] . '</h3>';
                }
                if (isset($tdfields['sub_name'])) {
                    $tdfieldsoutput .= '<h5>' . $tdfields['sub_name'] . '</h5>';
                }

                if (isset($tdfields['type']) && ($tdfields['type'] == 'textarea')) {
                    if (isset($tdfields['tiny_mce']) && $tdfields['tiny_mce'] == true) {
                        $tdfieldsoutput .= '<div class="option tdtextareabox">';
                    } else {
                        $tdfieldsoutput .= '<div class="option tdtextareabox">';
                    }
                } else {
                    $tdfieldsoutput .= '<div class="option">';
                }
                if (!isset($tdfields['desc'])):
                    $tdfieldsoutput .= '<div class="manage managefull">';
                else:
                    $tdfieldsoutput .= '<div class="manage">';
                endif;
            }
            switch ($tdfields['type']) {
                // Basic block_text input
                case 'block_text':
                    $getsavevalue = $this->getOptionConfigure($tdfields['id']);
                    $tdfieldsoutput .= '<input class="td-input" name="' . $tdfields['id'] . '" id="' . $tdfields['id'] . '" type="' . $tdfields['type'] . '" value="' . $getsavevalue . '" />';
                    break;
                // Basic text input
                case 'text':
                    $getsavevalue = '';
                    $langfields.= $tdfields['id'];
                    if (isset($tdfields['lang']) && $tdfields['lang'] == true)
                        $getsavevalue = $this->getOptionConfigure($tdfields['id'] . '_' . $this->default_language);
                    else
                        $getsavevalue = $this->getOptionConfigure($tdfields['id']);

                    if ($getsavevalue != "") {
                        $getsavevalue = stripslashes($getsavevalue);
                    }
                    if (isset($tdfields['lang']) && $tdfields['lang'] == true):
                        foreach ($this->languages as $lang) {
                            $getsavevalue = $this->getOptionConfigure($tdfields['id'] . '_' . $lang['id_lang']);
                            if ($getsavevalue != "") {
                                $getsavevalue = stripslashes($getsavevalue);
                            }
                            $tdfieldsoutput .='<div id="' . $tdfields['id'] . '_' . $lang['id_lang'] . '" style="display: ' . ($lang['id_lang'] == $this->default_language ? 'block' : 'none') . ';float: left;">';
                            $tdfieldsoutput .= '<input class="td-input" name="' . $tdfields['id'] . '_' . $lang['id_lang'] . '" id="' . $tdfields['id'] . '_' . $lang['id_lang'] . '" type="' . $tdfields['type'] . '" value="' . $getsavevalue . '" />';
                            $tdfieldsoutput .= '</div>';
                        }
                        $tdfieldsoutput .=$this->displayFlags($this->languages, $this->default_language, $langfields, $tdfields['id'], true);
                    else:
                        $tdfieldsoutput .= '<input class="td-input" name="' . $tdfields['id'] . '" id="' . $tdfields['id'] . '" type="' . $tdfields['type'] . '" value="' . $getsavevalue . '" />';
                    endif;
                    break;
                // Select Box
                case 'select':
                    $tdfieldsoutput .= '<div class="td-select-box">';
                    $tdfieldsoutput .= '<select class="' . $tdfields['type'] . '" name="' . $tdfields['id'] . '">';
                    $selected_value = $this->getOptionConfigure($tdfields['id']);
                    foreach ($tdfields['options'] as $select_key => $option_val) {
                        $selectedoption = '';
                        if (isset($selected_value)) {
                            if ($selected_value == $select_key) {
                                $selectedoption = ' selected="selected"';
                            }
                        }
                        $tdfieldsoutput .= '<option id="' . $select_key . '" value="' . $select_key . '" ' . $selectedoption . ' />' . $option_val . '</option>';
                    }
                    $tdfieldsoutput .= '</select></div>';
                    break;
                // Select Box
                case 'typographyfsize':
                    $tdfieldsoutput .= '<div class="td-select-box">';
                    $tdfieldsoutput .= '<select class="select" name="' . $tdfields['id'] . '">';
                    $selected_value = $this->getOptionConfigure($tdfields['id']);
                    for ($i = 8; $i <= 68; $i++) {
                        $fontsize = $i . 'px';
                        $selectedoption = '';
                        if (isset($selected_value)) {
                            if ($selected_value == $fontsize) {
                                $selectedoption = ' selected="selected"';
                            }
                        }
                        $tdfieldsoutput .= '<option id="' . $fontsize . '" value="' . $fontsize . '" ' . $selectedoption . ' />' . $fontsize . '</option>';
                    }
                    $tdfieldsoutput .= '</select></div>';
                    break;
                // Basic textarea lang with mce
                case 'textarea':
                    $getsavevalue = '';
                    $langfields.= $tdfields['id'];

                    if (isset($tdfields['lang']) && $tdfields['lang'] == true)
                        $getsavevalue = $this->getOptionConfigure($tdfields['id'] . '_' . $this->default_language);
                    else
                        $getsavevalue = $this->getOptionConfigure($tdfields['id']);

                    if (isset($tdfields['tiny_mce']) && $tdfields['tiny_mce'] == true):
                        $tiny_mce = "td-input rte";
                    else:
                        $tiny_mce = 'td-input';
                    endif;

                    if ($getsavevalue != "") {
                        $getsavevalue = stripslashes($getsavevalue);
                    }
                    if (isset($tdfields['lang']) && $tdfields['lang'] == true):
                        foreach ($this->languages as $lang) {
                            $getsavevalue = $this->getOptionConfigure($tdfields['id'] . '_' . $lang['id_lang']);
                            if ($getsavevalue != "") {
                                $getsavevalue = stripslashes($getsavevalue);
                            }
                            $tdfieldsoutput .='<div id="' . $tdfields['id'] . '_' . $lang['id_lang'] . '" style="display: ' . ($lang['id_lang'] == $this->default_language ? 'block' : 'none') . ';float: left;">';
                            $tdfieldsoutput .= '<textarea class="' . $tiny_mce . '" name="' . $tdfields['id'] . '_' . $lang['id_lang'] . '" cols="10" rows="8">' . $getsavevalue . '</textarea>';
                            $tdfieldsoutput .= '</div>';
                        }
                        $tdfieldsoutput .=$this->displayFlags($this->languages, $this->default_language, $langfields, $tdfields['id'], true);
                    else:
                        $tdfieldsoutput .= '<textarea class="' . $tiny_mce . '" name="' . $tdfields['id'] . '" cols="10" rows="8" >' . $getsavevalue . '</textarea>';
                    endif;
                    break;
                // Basic image upload
                case 'upload':
                    $tduploadedimage = $this->getOptionConfigure($tdfields['id']);
                    $values = '';
                    if (isset($tduploadedimage)) {
                        $values = $tduploadedimage;
                    }
                    $tdfieldsoutput .= '<input class="upload td-input" name="' . $tdfields['id'] . '" id="' . $tdfields['id'] . '_upload" value="' . $values . '" />';
                    $tdfieldsoutput .= '<div class="uploadbtn_area"><span class="button uploadbtn" id="' . $tdfields['id'] . '">Upload</span>';
                    $tdfieldsoutput .= '<span class="button resetbtn" id="resimage_' . $tdfields['id'] . '" title="' . $tdfields['id'] . '">Remove</span></div><div class="clear"></div>';
                    if (!empty($tduploadedimage)) {
                        $tdfieldsoutput .= '<div class="up-priview"><a class="td-uploaded-image" href="' . $tduploadedimage . '">';
                        $tdfieldsoutput .= '<img alt="" class="td-upload-image" id="upimage_' . $tdfields['id'] . '" src="' . $tduploadedimage . '" />';
                        $tdfieldsoutput .= '</a></div><div class="clear"></div>';
                    }
                    break;
                case 'color':
                    $getsavevalue = '';
                    $getsavevalue = $this->getOptionConfigure($tdfields['id']);
                    if (isset($getsavevalue)) {
                        $getsavevalue = stripslashes($getsavevalue);
                    }
                    $tdfieldsoutput .= '<div id="' . $tdfields['id'] . '_picker" class="colorSelector"><div style="background-color: ' . $getsavevalue . '"></div></div>';
                    $tdfieldsoutput .= '<input class="td-color" name="' . $tdfields['id'] . '" id="' . $tdfields['id'] . '" type="text" value="' . $getsavevalue . '" />';
                    break;

                case 'typography':
                    $typography = $tdfields['std'];
                    if (isset($typography['face'])) {
                        $getfface = $this->getOptionConfigure($tdfields['id'] . '_face');
                        $tdfieldsoutput .= '<div id="' . $tdfields['id'] . '" ><h3>A quick brown fox jumps over the lazy dog.</h3></div>';
                        $fontfieldid = $tdfields['id'];
                        $tdfieldsoutput .='<script>
                        jQuery(document).ready(function($) {
                        
                    $("#' . $fontfieldid . '_face").change(function(){ 
                    var ' . $fontfieldid . 'fonts = $("option:selected", this).val();
                    var ' . $fontfieldid . 'fontid = ' . $fontfieldid . 'fonts.split(":");
                    if ($("head").find("link#' . $fontfieldid . 'fontlink").length < 1){
                        $("head").append(\'<link id="' . $fontfieldid . 'fontlink" href="" type="text/css" rel="stylesheet"/>\');
                    }
                    $("link#' . $fontfieldid . 'fontlink").attr({href:\'http://fonts.googleapis.com/css?family=\' + ' . $fontfieldid . 'fontid}); 
                    $("style#' . $fontfieldid . 'fontstyle").remove();
                    $("head").append(\'<style id="' . $fontfieldid . 'fontstyle" type="text/css">#' . $fontfieldid . ' h3{ font-family:\' + ' . $fontfieldid . 'fonts + \' !important; }</style>\'); 
                }); 
                
                 var ' . $fontfieldid . 'font=$("#' . $fontfieldid . '_face").val();
                 var ' . $fontfieldid . 'fontid = ' . $fontfieldid . 'font.split(":");
               
                 if ($("head").find(\'link#' . $fontfieldid . 'fontlink\').length < 1){
                        $("head").append(\'<link id="' . $fontfieldid . 'fontlink" href="" type="text/css" rel="stylesheet"/>\');
                    }
                    $("link#' . $fontfieldid . 'fontlink").attr({href:\'http://fonts.googleapis.com/css?family=\' + ' . $fontfieldid . 'fontid}); 
                    $("style#' . $fontfieldid . 'style").remove();
                    $("head").append(\'<style id="' . $fontfieldid . 'style" type="text/css">#' . $fontfieldid . ' h3{ font-family:\' + ' . $fontfieldid . 'font + \'; }</style>\');
 });
                         </script>';
                        $tdfieldsoutput .= '<div class="for-body-selected typography-face" original-title="Font family">';

                        $tdfieldsoutput .= '<select class="of-typography of-typography-face select" name="' . $tdfields['id'] . '[face]" id="' . $tdfields['id'] . '_face">';

                        $system_web_font = array(
                            'arial' => 'Arial',
                            'Verdana' => 'Verdana, Geneva',
                            'trebuchet' => 'Trebuchet',
                            'georgia' => 'Georgia',
                            'Helvetica%20Neue' => 'Helvetica Neue',
                            'times' => 'Times New Roman',
                            'tahoma' => 'Tahoma, Geneva',
                            'Telex' => 'Telex',
                            'Droid Sans' => 'Droid Sans',
                            'Convergence' => 'Convergence',
                            'Oswald' => 'Oswald',
                            'News Cycle' => 'News Cycle',
                            'Yanone Kaffeesatz:300' => 'Yanone Kaffeesatz Light',
                            'Yanone Kaffeesatz:200' => 'Yanone Kaffeesatz ExtraLight',
                            'Yanone Kaffeesatz:400' => 'Yanone Kaffeesatz Regular',
                            'Duru Sans' => 'Duru Sans',
                            'Open Sans' => 'Open Sans',
                            'PT Sans Narrow' => 'PT Sans Narrow',
                            'Macondo Swash Caps' => 'Macondo Swash Caps',
                            'Telex' => 'Telex',
                            'Sirin Stencil' => 'Sirin Stencil',
                            'Actor' => 'Actor',
                            'Iceberg' => 'Iceberg',
                            'Ropa Sans' => 'Ropa Sans',
                            'Amethysta' => 'Amethysta',
                            'Alegreya' => 'Alegreya',
                            'Germania One' => 'Germania One',
                            'Gudea' => 'Gudea',
                            'Trochut' => 'Trochut',
                            'Ruluko' => 'Ruluko',
                            'Alegreya' => 'Alegreya',
                            'Questrial' => 'Questrial',
                            'Armata' => 'Armata',
                            'PT Sans' => 'PT Sans'
                        );
                        $json = file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyAToyXLB8uHcAgbwUkYIc94FX26pN-7R3M', true);
                        $decode = json_decode($json, true);
                        $google_web_fonts = array();
                        //print_r($json);
                        foreach ($decode['items'] as $key => $value) {
                            $fontsfamily = $decode['items'][$key]['family'];
                            $fontsvariants = $decode['items'][$key]['variants'];
                            $fontfamily_replace = str_replace(' ', '+', $fontsfamily);
                            $google_web_fonts[$fontfamily_replace] = $fontsfamily;
                        }
                        if (isset($tdfields['section']) && $tdfields['section'] == 'gfonts') {
                            $webfont = $google_web_fonts;
                        } elseif (isset($tdfields['section']) && $tdfields['section'] == 'sfonts') {
                            $webfont = $system_web_font;
                        }
                        $tdfieldsoutput .= '<option value="">DEFAULT</option>';
                        foreach ($webfont as $key => $fontfamily) {

                            $selected_gval = '';
                            $selected_value = '';
                            $idfont = trim($key);
                            if (!empty($getfface)) {
                                if (isset($tdfields['section']) && $tdfields['section'] == 'gfonts') {
                                    if (trim($getfface) == $fontfamily) {
                                        $selected_gval = 'selected="selected"';
                                    }
                                } elseif (isset($tdfields['section']) && $tdfields['section'] == 'sfonts') {
                                    if (trim($getfface) == $idfont) {
                                        $selected_value = 'selected="selected"';
                                    }
                                }
                            } else {
                                if (isset($tdfields['section']) && $tdfields['section'] == 'gfonts') {
                                    if (trim($typography['face']) == $fontfamily) {
                                        $selected_gval = 'selected="selected"';
                                    }
                                } elseif (isset($tdfields['section']) && $tdfields['section'] == 'sfonts') {
                                    if (trim($typography['face']) == $idfont) {
                                        $selected_value = 'selected="selected"';
                                    }
                                }
                            }



                            if (isset($tdfields['section']) && $tdfields['section'] == 'gfonts') {

                                $tdfieldsoutput .= '<option value="' . $fontfamily . '" ' . $selected_gval . '>' . $fontfamily . '</option>';
                            } elseif (isset($tdfields['section']) && $tdfields['section'] == 'sfonts') {
                                $tdfieldsoutput .= '<option value="' . $key . '" ' . $selected_value . '>' . $fontfamily . '</option>';
                            }
                        }
                        $tdfieldsoutput .= '</select>';
                        $tdfieldsoutput .= '</div>';
                    }
                    if (isset($typography['color'])) {
                        $selected_value = '';
                        $getfcolor = $this->getOptionConfigure($tdfields['id'] . '_color');
                        if (isset($getfcolor)) {
                            $selected_value = $getfcolor;
                        } else {
                            $selected_value = $typography['color'];
                        }
                        $tdfieldsoutput .= '<div id="' . $tdfields['id'] . '_color_picker" class="colorSelector"><div style="background-color: ' . $selected_value . '"></div></div>';
                        $tdfieldsoutput .= '<input class="td-color of-typography of-typography-color"  name="' . $tdfields['id'] . '[color]" id="' . $tdfields['id'] . '_color" type="text" value="' . $selected_value . '" />';
                    }
                    if (isset($typography['size'])) {
                        $getfsize = $this->getOptionConfigure($tdfields['id'] . '_size');
                        //  print_r($getfsize);
                        $tdfieldsoutput .= '<select class="of-typography of-typography-size" id="' . $tdfields['id'] . '_size" name="' . $tdfields['id'] . '_size">';
                        for ($i = 8; $i <= 40; $i++) {
                            $size = $i . 'px';
                            if ($getfsize != '') {
                                if ($getfsize == $size) {
                                    $selected_value = 'selected="selected"';
                                } else {
                                    $selected_value = '';
                                }
                            } else {
                                if ($tdfields['std'] == $size) {
                                    $selected_value = ' selected="selected"';
                                } else {
                                    $selected_value = '';
                                }
                            }


                            $tdfieldsoutput .='<option value="' . $size . '" ' . $selected_value . ' >' . $size . '</option>';
                        }

                        $tdfieldsoutput .= '</select>';
                    }
                    break;
                case 'images':
                    $i = 0;
                    $get_std = $this->getOptionConfigure($tdfields['id']);
                    foreach ($tdfields['options'] as $key => $option_val) {
                        $i++;
                        $selected_value = '';
                        if (!empty($get_std)) {
                            if ($get_std == $key) {
                                $selected_value = 'add-radio-image';
                            } else {
                                $selected_value = '';
                            }
                        }
                        $tdfieldsoutput .= '<span><input type="radio" id="image-radio-box-' . $tdfields['id'] . $i . '" class="checkbox td-radio-box-image-radio" value="' . $key . '" name="' . $tdfields['id'] . '" ' . $selected_value . ' />';
                        $tdfieldsoutput .= '<div class="td-radio-image-label">' . $key . '</div>';
                        $tdfieldsoutput .= '<img src="' . $option_val . '" alt="" class="td-radio-box-image ' . $selected_value . '" onClick="document.getElementById(\'image-radio-box-' . $tdfields['id'] . $i . '\').checked = true;" /></span>';
                    }

                    break;
                case 'heading':
                    if ($count >= 2) {
                        $tdfieldsoutput .= '</div>';
                    }
                    $leftlink = str_replace(' ', '', strtolower($tdfields['name']));
                    $leftlinkid = "td-taboption-" . $leftlink;
                    $leftlinks .= '<li class="' . $leftlink . ' "><a  data-toggle="tab" title="' . $tdfields['name'] . '" href="#' . $leftlinkid . '">' . $tdfields['name'] . '</a></li>';
                    $tdfieldsoutput .= '<div class="td-tab-panel" id="' . $leftlinkid . '"><h4>' . $tdfields['name'] . '</h4>';
                    break;
                case 'iphonebutton':
                    $get_std = $this->getOptionConfigure($tdfields['id']);
                    $checked = '';

                    if ($get_std != '') {

                        if ($get_std == 'enable') {
                            $checked = ' checked';
                        }
                    } else {

                        if ($tdfields['std'] == 'enable') {
                            $checked = ' checked';
                        }
                    }
                    $anaselect = ($get_std == "enable") ? "selected" : ' ';
                    $disselet = ($get_std == "disable") ? "selected" : ' ';

                    $tdfieldsoutput .='<p class="field switch">
                            <input type="radio" id="radio' . $tdfields['button_id'][0] . '" name="' . $tdfields['id'] . '" value="enable" ' . $checked . ' />
                            <input type="radio" id="radio' . $tdfields['button_id'][1] . '" name="' . $tdfields['id'] . '" value="disable" />
                            
                            <label for="radio' . $tdfields['button_id'][0] . '" class="cb-enable ' . $anaselect . '"><span>' . $tdfields['options'][0] . '</span></label>
                            <label for="radio' . $tdfields['button_id'][1] . '" class="cb-disable ' . $disselet . '"><span>' . $tdfields['options'][1] . '</span></label>
                        </p>';
                    break;
                case 'tiles':
                    $i = 0;
                    $get_std = $this->getOptionConfigure($tdfields['id']);
                    foreach ($tdfields['options'] as $key => $option_val) {
                        $i++;

                        $selected_value = '';
                        if (isset($get_std)) {
                            if ($get_std == $option_val) {
                                $selected_value = 'td-radio-tile-selected';
                            }
                        }
                        $tdfieldsoutput .= '<span><input type="radio" id="td-radio-tile-' . $tdfields['id'] . $i . '" class="checkbox td-radio-tile-radio" value="' . $option_val . '" name="' . $tdfields['id'] . '" />';
                        $tdfieldsoutput .= '<div class="td-radio-tile-img ' . $selected_value . '" onClick="document.getElementById(\'td-radio-tile-' . $tdfields['id'] . $i . '\').checked = true;"><img width="50" height="50" alt="" src="' . $option_val . '" ></div></span>';
                    }

                    break;
            }
            if ($tdfields['type'] != 'heading') {
                if (!isset($tdfields['desc'])) {
                    $explain_value = '';
                } else {
                    $explain_value = '<div class="explain">' . $tdfields['desc'] . '</div>';
                }
                $tdfieldsoutput .= '</div>' . $explain_value;
                $tdfieldsoutput .= '<div class="clear"> </div></div></div>';
            }
        }
        $tdfieldsoutput .= '</div>';

        return array($leftlinks, $tdfieldsoutput);
    }

    public function getBgPatern() {
        $getBFpattarn = array();
        if (is_dir($this->patternsDIR)) {
            if ($bfpaterndir = opendir($this->patternsDIR)) {
                while (( $bgpatern_name = readdir($bfpaterndir)) !== false) {
                    if (stristr($bgpatern_name, ".png") !== false || stristr($bgpatern_name, ".gif") !== false || stristr($bgpatern_name, ".jpg") !== false) {
                        $getBFpattarn[] = $this->patternsURL . $bgpatern_name;
                    }
                }
            }
        }
        $this->pattern = $getBFpattarn;
    }

    public function installDefaultValue() {
        $tdoptions = $this->tdoptions;
        foreach ($tdoptions as $option_result):
            $getsavevaluevalue = isset($option_result['std']) ? $option_result['std'] : '';
            if (isset($getsavevaluevalue)) {
                if (is_array($getsavevaluevalue)) {
                    foreach ($getsavevaluevalue as $key => $output_value) {
                        Configuration::updateValue($option_result['id'] . "_" . $key, htmlspecialchars($output_value));
                    }
                } else {

                    if (isset($option_result['lang']) && $option_result['lang'] == true) {
                        foreach ($this->languages as $lang) {
                            Configuration::updateValue($option_result['id'] . '_' . $lang['id_lang'], htmlspecialchars($option_result['std']));
                        }
                    } else {
                        if (isset($option_result['id']) && isset($option_result['std']))
                            Configuration::updateValue($option_result['id'], htmlspecialchars($option_result['std']));
                    }
                }
            }

        endforeach;
        return true;
    }

    private function _insertData($insertdata) {
        if (isset($insertdata['td_shopid'])) {
            $shopid = $insertdata['td_shopid'];
        } else {
            $shopid = '';
        }
        if (isset($insertdata['td_shopgroup'])) {
            $shopgroup = $insertdata['td_shopgroup'];
        } else {
            $shopgroup = '';
        }
        //echo $insertdata['td_slider_type'];
        //public static function updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null)
        foreach ($insertdata as $id => $data) {
            if (is_array($data)) {
                foreach ($data as $key => $data_value) {
                    Configuration::updateValue($id . "_" . $key, $data_value, true, $shopgroup, $shopid);
                }
            } else {
                Configuration::updateValue($id, htmlspecialchars($data), true, $shopgroup, $shopid);
            }
        }
        $this->successmeg = '<div class="bootstrap">
		<div class="module_confirmation conf confirm alert alert-success">
			The settings have been updated.
		</div>
		</div>';
        return TRUE;
    }

    public function hex_to_rgba($color) {
        $color = str_replace("#", "", $color);
        if (strlen($color) == 3) {
            $r = hexdec(substr($color, 0, 1) . substr($color, 0, 1));
            $g = hexdec(substr($color, 1, 1) . substr($color, 1, 1));
            $b = hexdec(substr($color, 2, 1) . substr($color, 2, 1));
        } else {
            $r = hexdec(substr($color, 0, 2));
            $g = hexdec(substr($color, 2, 2));
            $b = hexdec(substr($color, 4, 2));
        }
        $getrgb = array($r, $g, $b);
        return $getrgb;
    }

    public function adjust_brightness($hex, $adjust) {
        $adjust = max(-255, min(255, $adjust));

        $rgba = $this->hex_to_rgba($hex);

        $r = $rgba[0];
        $g = $rgba[1];
        $b = $rgba[2];

        $r = max(0, min(255, $r + $adjust));
        $g = max(0, min(255, $g + $adjust));
        $b = max(0, min(255, $b + $adjust));

        $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
        $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
        $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

        return '#' . $r_hex . $g_hex . $b_hex;
    }

     public function tdThemeOption() {

        $td_options = array();

        $td_options[] = array("name" => "General Settings",
            "type" => "heading");

        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1) {
            $count = count($this->idshop);
            if ($count > 1) {
                $td_options[] = array("name" => "Shop Group",
                    "desc" => "Select Shop Group",
                    "id" => $this->tdthemename . "shopgroup",
                    "std" => '',
                    "options" => $this->idshopgroup,
                    "type" => "select");
                $td_options[] = array("name" => "Shop Name",
                    "desc" => "Select Shop Name for save data use to multishop. ",
                    "id" => $this->tdthemename . "shopid",
                    "std" => '',
                    "options" => $this->idshop,
                    "type" => "select");
            }
        }
  $td_options[] = array("name" => "Responsive Layout",
            "desc" => "Select Responsive Layout Lype",
            "id" => $this->tdthemename . "responsive",
            "std" => "responsive",
            "options" => array('responsive' => 'Responsive - Max Width 1170px', 'responsive940' => 'Responsive - Max Width 940px', 'nonresponsive' => 'Select Responsive Layout Type'),
            "type" => "select");
  
    $td_options[] = array("name" => "Content Color",
            "desc" => "Select Content Color",
            "id" => $this->tdthemename . "contentcol",
            "std" => "dark",
            "options" => array('dark' => 'Dark', 'light' => 'Light'),
            "type" => "select");
    
    $td_options[] = array("name" => "Logo Dimensions (Width) Option",
            "desc" => "The default logo canvas is 250 px",
            "id" => $this->tdthemename . 'logowidth',
            "std" => '250',
            "type" => "text");
     $td_options[] = array("name" => "Logo Dimensions (Height) Option",
            "desc" => "The default logo canvas is 100 px",
            "id" => $this->tdthemename . 'logoheight',
            "std" => '100',
            "type" => "text");


        $td_options[] = array("name" => 'Back To Top Button',
            "desc" => 'Back To Top button ',
            "id" => $this->tdthemename . 'backto_top',
            "std" => "enable",
            "button_id" => array('btt1', 'btt2'),
            "options" => array('Yes', 'No'),
            "type" => "iphonebutton");
        
      $td_options[] = array("name" => "Products Style",
            "type" => "innerheading");
          $td_options[] = array("name" => "Products Per Row",
            "desc" => "Select How many products per row in product list page.",
            "id" => $this->tdthemename . "perrow",
            "std" => "three_side",
            "options" => array('three_side' => '3 columns (Sidebar)', 'four_side' => '4 columns (Sidebar or Full-width)', 'five_full' => '5 columns (Full-width)'),
            "type" => "select");
   $td_options[] = array("name" => "Home products Per Row",
            "desc" => "Select How many products per row in home page.",
            "id" => $this->tdthemename . "perrowhome",
            "std" => "four_side",
            "options" => array('four_side' => '4 Items Per Page', 'five_full' => '5 Items Per Page'),
            "type" => "select");
      
        /*

          $td_options[] = array("name" => "Product Default View Mode",
          "desc" => "If you want to change default product view style on product list page.",
          "id" => $this->tdthemename . "proviewstyle",
          "std" => "gridview",
          "type" => "images",
          "options" => array(
          'gridview' => $this->backofficImage . 'grid.png',
          'listview' => $this->backofficImage . 'list.png'
          )
          );


          $td_options[] = array("name" => 'Product Per Row',
          "desc" => 'Per Row Product View on list of the page..',
          "id" => $this->tdthemename . 'pro_rows',
          "std" => "3",
          "type" => "images",
          "options" => array(
          '3' => $this->backofficImage . 'grid.png',
          '4' => $this->backofficImage . '4-col.png'
          )
          );
         */


        $td_options[] = array("name" => "Header",
            "type" => "heading");
        
        
          $td_options[] = array("name" => 'Header Sticky Menu',
            "desc" => 'Back To Top button ',
            "id" => $this->tdthemename . 'stickymenu',
            "std" => "enable",
            "button_id" => array('stym1', 'stym2'),
            "options" => array('Yes', 'No'),
            "type" => "iphonebutton");
        
          $td_options[] = array("name" => "Header Style",
            "desc" => "Set to header desing style",
            "id" => $this->tdthemename . "headerstyle",
            "std" => "header1",
            "options" => array('header1' => 'Header-1', 'header2' => 'Header-1', 'header3' => 'Header-3', 'header4' => 'Header-4'),
            "type" => "select");
          
        $td_options[] = array("name" => 'Top Links',
            "desc" => "Top Links block for header top section.",
            "id" => $this->tdthemename . "htoplinks",
            "lang" => true,
             "tiny_mce" => true,
            "std" => '<ul class="" id="menu-top-bar-navigation">
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1282" id="menu-item-1282"><a href="#">Services</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1281" id="menu-item-1281" style="opacity: 1;"><a href="#">FAQ</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1812" id="menu-item-1812" style="opacity: 1;"><a href="#">Wishlist</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1280" id="menu-item-1280" style="opacity: 1;"><a href="#">Contact Us</a></li>
</ul>',
            "type" => "textarea");
        
        $td_options[] = array("name" => 'Custominfo',
            "desc" => "Custominfo for header of the page",
            "id" => $this->tdthemename . "cus_info",
            "lang" => true,
            "tiny_mce" => true,
            "std" => '<div class="custominfo">
    78 2ND HOUSE RD MONTAUK, NY, 11954 
    <a href="mailto:contact@barberry.com">contact@barberry.com</a>
</div>',
            "type" => "textarea");






        $td_options[] = array("name" => "Blog Options",
            "type" => "heading");
        $td_options[] = array("name" => "Number Of Post",
            "desc" => "Number of post show in Home.",
            "id" => $this->tdthemename . 'bloghome',
            "std" => '8',
            "type" => "text");
        $td_options[] = array("name" => "Blog Per Page",
            "desc" => "Number of post show in blog per page.",
            "id" => $this->tdthemename . 'blogperpage',
            "std" => '5',
            "type" => "text");


        $td_options[] = array("name" => "Number Of Recent Post",
            "desc" => "Number of recent post show in blog sidebar.",
            "id" => $this->tdthemename . 'numofrepost',
            "std" => '5',
            "type" => "text");

        $td_options[] = array("name" => "Number OF Comments",
            "desc" => "Number of comments show in blog sidebar.",
            "id" => $this->tdthemename . 'numofcomments',
            "std" => '5',
            "type" => "text");

        $td_options[] = array("name" => "Home Page",
            "type" => "heading");
        
            $td_options[] = array("name" => "Slider",
            "desc" => "Select Home Slider.",
            "id" => $this->tdthemename . "hslider",
            "std" => "ravslider",
            "options" => array('contentslider' => 'Content Slider', 'ravslider' => 'Revulution Slider'),
            "type" => "select");
            
        $td_options[] = array("name" => "Slider Type",
            "desc" => "Home Page Slider Type",
            "id" => $this->tdthemename . "slider_type",
            "std" => "fullslider",
            "options" => array('fullslider' => 'Full Silder', 'standardslider' => 'Standard Slider'),
            "type" => "select");
        
        
        /*$td_options[] = array("name" => "Home Page Highlights Block",
            "desc" => "Custom HTML BLOCK ON TOP OF THE HOME PAGE. ",
            "id" => $this->tdthemename . 'hhigh_light',
            "lang" => true,
            "tiny_mce" => true,
            "std" => '100% Original Products &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Free Delivery on orders above $50 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Free Gift With Every Order 30-Day Returns',
            "type" => "textarea");*/

        $td_options[] = array("name" => "Home Custom HTML Block",
            "desc" => "Custom HTML BLOCK ON THE HOME PAGE. ",
            "id" => $this->tdthemename . 'sc_banner',
            "lang" => true,
            "tiny_mce" => true,
            "std" => '<div class="col one_half">

		<div class="shortcode_banner_simple dark link borders" onclick="location.href=\'#\';" ><div class="shortcode_banner_simple_inside center" style="background-color:;padding: ;">
				<div><h3>GET 25% OFF WHEN YOU SPEND $100</h3></div>
				
				<div><h4>LIMITED TIME ONLY | USE CODE: BARBERRY</h4></div>
			</div>
                </div>
</div>
    <div class="col one_half last">

		<div class="shortcode_banner_simple dark link borders" onclick="location.href=\'#\';" ><div class="shortcode_banner_simple_inside center" style="background-color:;padding: ;">
				<div><h3>FREE SHIPPING ON ALL ORDERS</h3></div>
				
				<div><h4>FREE OVER $125 FOR INTERNATIONAL ORDERS</h4></div>
			</div>
                </div>
</div>',
            "type" => "textarea");

        $td_options[] = array("name" => "Home Page Custom HTML",
            "desc" => "Custom HTML block for home page. ",
            "id" => $this->tdthemename . 'hfooter_content',
            "lang" => true,
            "tiny_mce" => true,
            "std" => '
<div class="col one_fourth"> 
		<div style="background-color:; background-image:url(' . $this->themeImageURL . 'banner/homebanner1.jpg)" onclick="location.href=\'#\';" class="shortcode_banner_simple dark link borders"><div style="background-color:;padding:50px 20px;" class="shortcode_banner_simple_inside center">
				<div><h3>BEAUTY </h3></div>
				<div class="shortcode_banner_simple_sep"></div><div class="clearfix"></div>
				<div><h4>SHOP NOW</h4></div>
			</div></div> 
</div>
<div class="col one_fourth"> 
		<div style="background-color:; background-image:url(' . $this->themeImageURL . 'banner/homebanner2.jpg)" onclick="location.href=\'#\';" class="shortcode_banner_simple dark link borders"><div style="background-color:;padding:50px 20px;" class="shortcode_banner_simple_inside center">
				<div><h3>BEAUTY </h3></div>
				<div class="shortcode_banner_simple_sep"></div><div class="clearfix"></div>
				<div><h4>SHOP NOW</h4></div>
			</div></div> 
</div>
<div class="col one_fourth"> 
		<div style="background-color:; background-image:url(' . $this->themeImageURL . 'banner/homebanner3.jpg)" onclick="location.href=\'#\';" class="shortcode_banner_simple dark link borders"><div style="background-color:;padding:50px 20px;" class="shortcode_banner_simple_inside center">
				<div><h3>BEAUTY </h3></div>
				<div class="shortcode_banner_simple_sep"></div><div class="clearfix"></div>
				<div><h4>SHOP NOW</h4></div>
			</div></div> 
</div>
<div class="col one_fourth last">
		<div style="background-color:; background-image:url(' . $this->themeImageURL . 'banner/homebanner4.jpg)" onclick="location.href=\'#\';" class="shortcode_banner_simple dark link borders"><div style="background-color:;padding:50px 20px;" class="shortcode_banner_simple_inside center">
				<div><h3>FRAGRANCE</h3></div>
				<div class="shortcode_banner_simple_sep"></div><div class="clearfix"></div>
				<div><h4>SHOP NOW</h4></div>
			</div></div> 
</div>',
            "type" => "textarea");


 /*
        $td_options[] = array("name" => "Product Page",
            "type" => "heading");

       $td_options[] = array("name" => "Custom HTML BLOCK",
            "desc" => "Product page Custom HTML BLOCK",
            "id" => $this->tdthemename . 'product_content',
            "lang" => true,
            "tiny_mce" => true,
            "std" => '<div class="block1"><img src="' . $this->themeImageURL . 'static-block.png" alt="" /></div>'
            . '<div class="block2"><img src="' . $this->themeImageURL . 'static-block1.png" alt="" /></div>',
            "type" => "textarea");

        $td_options[] = array("name" => 'Custom Tab',
            "desc" => 'Custom Tab Option for product page.',
            "id" => $this->tdthemename . 'custom_tab',
            "std" => "enable",
            "button_id" => array('ctab1', 'ctab2'),
            "options" => array('Yes', 'No'),
            "type" => "iphonebutton");

        $td_options[] = array("name" => "Custom Tab Title",
            "desc" => " Custom Tab Title for Product Page.",
            "id" => $this->tdthemename . 'protab_title',
            "std" => 'Custom Tab For All Products',
            "lang" => true,
            "type" => "text");
        $td_options[] = array("name" => "Custom Tab Content",
            "desc" => "Enter custom tab content you would like output to the product custom tab (for all products):",
            "id" => $this->tdthemename . 'protab_content',
            "lang" => true,
            "tiny_mce" => true,
            "std" => '<h2 class="what"><span>What is Lorem Ipsum?</span></h2><p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',
            "type" => "textarea");
*/
        $td_options[] = array("name" => "Styling Options",
            "type" => "heading");

       /* $td_options[] = array("name" => "Active Color",
            "desc" => "Set the theme color",
            "id" => $this->tdthemename . "themecolor",
            "std" => "#EA5959",
            "type" => "color");
        $td_options[] = array("name" => "Second Color",
            "desc" => "Set the theme second color",
            "id" => $this->tdthemename . "scolor",
            "std" => "#EA5959",
            "type" => "color");
        $td_options[] = array("name" => "Active Font Color",
            "desc" => "Set the font color for your theme.",
            "id" => $this->tdthemename . "fontcolor",
            "std" => "",
            "type" => "color");


        $td_options[] = array("name" => "Button Background Color",
            "desc" => "Set the button background color for your theme.",
            "id" => $this->tdthemename . "btnbgcolor",
            "std" => "#333333",
            "type" => "color");

        $td_options[] = array("name" => "Button Background Hover Color",
            "desc" => "Set the button background hover color for your theme.",
            "id" => $this->tdthemename . "btnbghcol",
            "std" => "#333333",
            "type" => "color");

        $td_options[] = array("name" => "Button Font Color",
            "desc" => "Set the button font color for your theme.",
            "id" => $this->tdthemename . "btnfcolor",
            "std" => "#FFFFFF",
            "type" => "color");



        $td_options[] = array("name" => "New Badge Background Color",
            "desc" => "Set the New background color for your theme.",
            "id" => $this->tdthemename . "nbadgebgc",
            "std" => "#63BA3A",
            "type" => "color");

        $td_options[] = array("name" => "New Badge Font Color",
            "desc" => "Set the New Badge font color for your theme.",
            "id" => $this->tdthemename . "nbadgebgfc",
            "std" => "#FFFFFF",
            "type" => "color");

        $td_options[] = array("name" => "Sale Badge Background Color",
            "desc" => "Set the Sale Badge background color for your theme.",
            "id" => $this->tdthemename . "sbadgebgc",
            "std" => "#37BEF2",
            "type" => "color");

        $td_options[] = array("name" => "Sale Badge Font Color",
            "desc" => "Set the Sale Badge font color for your theme.",
            "id" => $this->tdthemename . "sbadgebgfc",
            "std" => "#FFFFFF",
            "type" => "color");
*/
   $td_options[] = array("name" => "Background Color",
            "desc" => "Set the Background color for your theme.",
            "id" => $this->tdthemename . "mainbgcol",
            "std" => "#ffffff",
            "type" => "color");
        $td_options[] = array("name" => "Body background",
            "type" => "innerheading");

        $td_options[] = array("name" => "Body Background Color",
            "desc" => "Set the Background color for your theme.",
            "id" => $this->tdthemename . "body_bg_color",
            "std" => "#ffffff",
            "type" => "color");

        $td_options[] = array("name" => 'Body Background Pattern',
            "desc" => 'If you want to use Backgrond pattern',
            "id" => $this->tdthemename . 'enabody_bg',
            "std" => "disable",
            "button_id" => array(17, 18),
            "options" => array('Enable', 'Disable'),
            "type" => "iphonebutton");

        $td_options[] = array("name" => "Background Pattern",
            "id" => $this->tdthemename . "body_bg",
            "std" => $this->patternsURL . "pattern3.png",
            "type" => "tiles",
            "options" => $this->pattern,
        );


        $td_options[] = array("name" => "Custom Background",
            "desc" => "Upload a custom background image for your theme. This will override the option above. This is only for the main background pattern.",
            "id" => $this->tdthemename . "body_bg_custom",
            "std" => "",
            "mod" => "min",
            "type" => "upload");

        $td_options[] = array("name" => "background-attachment",
            "desc" => "You can define additional shorthand properties for the background. ",
            "id" => $this->tdthemename . "bgattachment",
            "std" => "scroll",
            "options" => array('scroll' => 'scroll', 'fixed' => 'fixed', 'inherit' => 'inherit'),
            "type" => "select");
        $td_options[] = array("name" => "background-repeat",
            "desc" => "You can define additional shorthand properties for the background.",
            "id" => $this->tdthemename . "bgrepeat",
            "std" => "repeat",
            "options" => array('repeat' => 'repeat', 'repeat-x' => 'repeat-x', 'repeat-y' => 'repeat-y', 'no-repeat' => 'no-repeat', 'inherit' => 'inherit'),
            "type" => "select");

        $td_options[] = array("name" => "background-position",
            "desc" => "You can define additional shorthand properties for the background.",
            "id" => $this->tdthemename . "bgposition",
            "std" => "0 0",
            "type" => "text");


        $td_options[] = array("name" => "Custom Style",
            "desc" => "Put your custom style here.",
            "id" => $this->tdthemename . "custom_style",
            "std" => "",
            "type" => "textarea");
        $td_options[] = array("name" => "Custom JS",
            "desc" => "Put your custom Script here.",
            "id" => $this->tdthemename . "custom_js",
            "std" => "",
            "type" => "textarea");


        $td_options[] = array("name" => "Typography",
            "type" => "heading");



        $td_options[] = array("name" => "Main Font",
            "desc" => "Pick the main font for your website.",
            "id" => $this->tdthemename . "heading_font",
            "std" => array('face' => 'Oswald'),
            "section" => "gfonts",
            "type" => "typography");

        $td_options[] = array("name" => "Secondary Font",
            "desc" => "Pick the secondary font for your website.",
            "id" => $this->tdthemename . "body_font",
            "std" => array('face' => 'PT Sans', 'color' => ''),
            "section" => "sfonts",
            "type" => "typography");





        $td_options[] = array("name" => "Social Options",
            "type" => "heading");
        $td_options[] = array("name" => 'Footer Options',
            "type" => "head_block");
        /*$td_options[] = array("sub_name" => 'Twitter ID',
            "desc" => "Enter your Twitter id.",
            "id" => $this->tdthemename . "twitter_id",
            "std" => "prestashop",
            "type" => "text");
        $td_options[] = array("sub_name" => 'Number of Count',
            "desc" => "Number of Count to Twitter.",
            "id" => $this->tdthemename . "num_tweets",
            "std" => "2",
            "type" => "block_text");
        $td_options[] = array("sub_name" => 'Consumer key',
            "desc" => "Add your twitter ID Consumer key code. <br/>Get From <a href='https://dev.twitter.com/apps' target='_blank'>https://dev.twitter.com/apps</a> for show tweets.",
            "id" => $this->tdthemename . "consumer_key",
            "std" => "",
            "type" => "block_text");
        $td_options[] = array("sub_name" => 'Consumer secret',
            "desc" => "Add your twitter ID Consumer secret code.",
            "id" => $this->tdthemename . "consumer_secret",
            "std" => "",
            "type" => "block_text");

        $td_options[] = array("sub_name" => 'Access token',
            "desc" => "Add your twitter ID Access token code.",
            "id" => $this->tdthemename . "consumer_token",
            "std" => "",
            "type" => "block_text");

        $td_options[] = array("sub_name" => 'Access token secret',
            "desc" => "Add your twitter ID Access token secret code.",
            "id" => $this->tdthemename . "consumer_tsecret",
            "std" => "",
            "type" => "block_text");

*/
        $td_options[] = array("name" => 'Social Footer Link',
            "type" => "head_block");
  $td_options[] = array("sub_name" => 'Facebook URL',
            "desc" => "Enter your facebook url.",
            "id" => $this->tdthemename . "facebook_url",
            "std" => "#",
            "type" => "block_text");
        $td_options[] = array("sub_name" => 'Twitter URL',
            "desc" => "Enter your Twitter url.",
            "id" => $this->tdthemename . "twitter_url",
            "std" => "#",
            "type" => "block_text");

        $td_options[] = array("sub_name" => 'google URL',
            "desc" => " Enter your google plus url.",
            "id" => $this->tdthemename . "google_url",
            "std" => "#",
            "type" => "block_text");

        $td_options[] = array("sub_name" => 'pinteres URL',
            "desc" => " Enter your pinteres url.",
            "id" => $this->tdthemename . "pinteres_url",
            "std" => "#",
            "type" => "block_text");
          $td_options[] = array("sub_name" => 'Youtube URL',
            "desc" => " Enter your Youtube url.",
            "id" => $this->tdthemename . "youtube_url",
            "std" => "#",
            "type" => "block_text");



        $td_options[] = array("name" => "Social Share Block",
            "desc" => "Social Share Block On Right Sidebar of Product Page.",
            "id" => $this->tdthemename . 'pro_shareright',
            "std" => ' <span>Share:</span>   
            <ul>    
                <li><a href="http://www.facebook.com/sharer.php?u=http://barberry.temashdesign.com/shop/adler-check-placket-cotton-polo/" onclick="javascript:window.open(this.href,
                                \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');
                        return false;"  class="product_share_facebook" target="_blank" title="Share on Facebook"></a></li>
                <li><a href="https://twitter.com/share?url=http://barberry.temashdesign.com/shop/adler-check-placket-cotton-polo/" target="_blank" onclick="javascript:window.open(this.href,
                                \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');
                        return false;"  class="product_share_twitter" title="Tweet this item"></a></li> 
                <li><a href="https://plus.google.com/share?url=http://barberry.temashdesign.com/shop/adler-check-placket-cotton-polo/" target="_blank" class="product_share_google" onclick="javascript:window.open(this.href,
                                \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');
                        return false;" title="Share on Google+"></a></li> 

                <li><a href="//pinterest.com/pin/create/button/?url=http://barberry.temashdesign.com/shop/adler-check-placket-cotton-polo/&media=http://barberry.temashdesign.com/wp-content/uploads/2013/07/product_11_img_1.jpg&description=Adler Check Placket Cotton Polo" onclick="javascript:window.open(this.href,
                                \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');
                        return false;" class="product_share_pinterest"></a></li>                           
                <li><a href="mailto:enteryour@addresshere.com?subject=Adler Check Placket Cotton Polo&body=Variegated stripes top the collar of a soft cotton polo with logo embroidery at the chest and cool micro-checks accenting the placket.

                       Partial front button closure.
                       Approx. length from shoulder: 28 1/4&#8243;.
                       Cotton; machine wash.
                       Imported.
                       Men&#039;s Designer.

                       You can buy this product from Nordstrom.
                       http://barberry.temashdesign.com/shop/adler-check-placket-cotton-polo/" class="product_share_email"  title="Email a Friend"></a></li>
            </ul>
            <div class="clearfix"></div>',
            "type" => "textarea");


        $td_options[] = array("name" => "Payment Options",
            "type" => "heading");

        $td_options[] = array("name" => "Payment Icon",
            "desc" => "Upload Your Won Payment Icon in Footer",
            "id" => $this->tdthemename . "paymenticon",
            "std" => $this->themeImageURL . 'payment_cards.png',
            "type" => "upload");

        $td_options[] = array("name" => "Upload Payment Icon Link",
            "desc" => "Pyament Icon Link",
            "id" => $this->tdthemename . "paymlink1",
            "std" => "#",
            "type" => "text");

        
        
          $td_options[] = array("name" => "Contact Us",
            "type" => "heading");
        $td_options[] = array("name" => 'Google Map',
            "desc" => 'Google Map oPTIONS',
            "id" => $this->tdthemename . 'googlemap',
            "std" => "enable",
            "button_id" => array('gm1', 'gm2'),
            "options" => array('Enable', 'Disable'),
            "type" => "iphonebutton");
        
        $td_options[] = array("name" => "GooGle Map Address",
            "desc" => "Set the GooGle Map Address",
            "id" => $this->tdthemename . "gmapaddress",
            "std" => "78 2ND HOUSE RD MONTAUK, NY, 11954",
            "type" => "text");

        $td_options[] = array("name" => "GooGle Map Description",
            "desc" => "Set the GooGle Map Description",
            "id" => $this->tdthemename . "gmapdesc",
            "std" => "Our Headquarters",
            "type" => "text");
        $td_options[] = array("name" => "GooGle Map ZOOM",
            "desc" => "Set the GooGle Map longitude",
            "id" => $this->tdthemename . "mapzoom",
            "std" => "13",
            "type" => "text");
        
     /*    $td_options[] = array("name" => "Custom Block",
            "type" => "heading");


 




$td_options[] = array("name" => 'Left Static Block',
            "desc" => "Left Static Block page ",
            "id" => $this->tdthemename . "left_box",
            "lang" => true,
            "tiny_mce" => true,
            "std" => ' <div class="block-title">
        <strong><span>Left Static Block</span></strong>
    </div>
    <div class="block-content">
            <p>This is left sidebar static block. You can edit this block from CMS &gt; Static Blocks in Admin Panel</p>
        </div>',
            "type" => "textarea");

*/
        $td_options[] = array("name" => "Footer Page",
            "type" => "heading");

       $td_options[] = array("name" => 'About us Information',
            "desc" => "This is a About us page",
            "id" => $this->tdthemename . "about_us",
            "lang" => true,
            "tiny_mce" => true,
            "std" => ' <section class="span3">
                        <div id="text-5" class="widget widget_text clearfix"><h1 class="widget-title">About Us</h1>			
                            <div class="textwidget">
                            <p>
                            <img width="65" height="65" style="float:left; margin:5px 15px 10px 0" src="' . $this->themeImageURL . 'bar_icon.jpg" alt="" />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis que porta sapien nec leo convallis.</p>
                                <p>t: 1-866-950-1638<br />
                                   f: 1-866-950-1639<br />
                                   e: contact@barberry.com</p>
                            </div>
                        </div>    </section>',
            "type" => "textarea");
      /*  $td_options[] = array("name" => "Footer Static Banner",
            "desc" => "This is a footer Static Banner.",
            "lang" => true,
            "tiny_mce" => true,
            "id" => $this->tdthemename . 'footer_image',
            "std" => '<div class="block1 span6"><img src="' . $this->themeImageURL . 'static-sample1.png" alt="" /></div>
<div class="block2 span6"><img src="' . $this->themeImageURL . 'static-sample.png" alt="" /></div>',
            "type" => "textarea");*/

        $td_options[] = array("name" => "Copyright info",
            "desc" => "Add your Copyright or some other notice.",
            "id" => $this->tdthemename . "copyright",
            "lang" => true,
            "tiny_mce" => true,
            "std" => "<p>© 2015 - Barberry Woocommerce Theme. Created by <a href='#'>TemashDesign</a></p>",
            "type" => "textarea");




        $this->tdoptions = $td_options;
    }

    public function styleCustom() {
        if (Configuration::get('td_heading_font_face')) {
            $td_heading_font_face = str_replace(" ", "+", Configuration::get('td_heading_font_face'));
            $this->context->controller->addCSS('//fonts.googleapis.com/css?family=' . $td_heading_font_face . ':400,300,700,normal', 'all');
        }
        if (Configuration::get('td_body_font_face') && (Configuration::get('td_body_font_face') != "arial") && (Configuration::get('td_body_font_face') !=  "Verdana") && (Configuration::get('td_body_font_face') != "trebuchet") && (Configuration::get('td_body_font_face') !=  "trebuchet") && (Configuration::get('td_body_font_face')!= "georgia") && (Configuration::get('td_body_font_face')!= "times") && (Configuration::get('td_body_font_face') != "tahoma")) {
            $td_body_font_face = str_replace(" ", "+", Configuration::get('td_body_font_face'));
            $this->context->controller->addCSS('//fonts.googleapis.com/css?family=' . $td_body_font_face . ':400,300,700,normal', 'all');
        }

        $page_name = Dispatcher::getInstance()->getController();

 
        foreach ($this->languages as $language) {
            if ($language['id_lang'] == $this->default_language) {
                if (($language['is_rtl']) != 1) {
                    
                }
                if (($language['is_rtl']) == 1) {
                    $this->context->controller->addCSS(($this->themeCSSURL) . 'rtl.css', 'all');
                }
            }
        }

        /*$this->context->controller->addCSS(($this->themeCSSURL) . 'woocommerce-layout.css', 'all');
        $this->context->controller->addCSS(($this->themeCSSURL) . 'woocommerce-smallscreen.css', 'only screen and (max-width: 768px)');
        $this->context->controller->addCSS(($this->themeCSSURL) . 'woocommerce.css', 'all');*/
        $this->context->controller->addCSS(($this->themeCSSURL) . 'prettyPhoto.css', 'all');
        $this->context->controller->addCSS(($this->themeCSSURL) . 'style.css', 'all');
        
        if(Configuration::get('td_responsive')=='responsive'){
        $this->context->controller->addCSS(($this->themeCSSURL) . 'bootstrap-responsive.css', 'all');
        $this->context->controller->addCSS(($this->themeCSSURL) . 'responsive.css', 'all');
    }elseif(Configuration::get('td_responsive')=='responsive940'){
             $this->context->controller->addCSS(($this->themeCSSURL).'bootstrap-responsive940.css', 'all');
            $this->context->controller->addCSS(($this->themeCSSURL).'responsive940.css', 'all');
        }else{
           $this->context->controller->addCSS(($this->themeCSSURL).'nonresponsive.css', 'all');  
        }


        //$this->context->controller->addCSS(($this->themeCSSURL) . 'colorbox.css', 'all');
        //$this->context->controller->addCSS(($this->themeCSSURL).'jckqv-styles.min.css', 'all');
        //
        //
       

        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.iosslider.min.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'js.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'bootstrap.min.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'modernizr.full.min.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'hoverIntent.min.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.prettyPhoto.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.isotope.min.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.easing-1.3.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.customSelect.min.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.fitvids.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'fresco.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'custom.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'html5.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.blockUI.min.js');


        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.dcmegamenu.1.3.3.js');
        $this->context->controller->addJS(($this->themeJSURL) . 'jquery.hoverIntent.minified.js');
        
        if ($page_name == "contact") {
            $this->context->controller->addJS(($this->themeJSURL) . 'jquery.gmap.js');
        }
        if ($page_name == "product") {
            $this->context->controller->addJS(($this->themeJSURL) . 'single-product.min.js');
        }
      

        /* Panel main color */
        $custom_styles = "
    
        ";

 
$logo_width = Configuration::get('td_logowidth'); if( empty($logo_width) ) {$logo_width = '250';}
$logo_height = Configuration::get('td_logoheight'); if( empty($logo_height) ) {$logo_height = '100';}


            $custom_styles .= '
<!-- ******************************************************************** -->
<!-- Custom CSS Styles -->
<!-- ******************************************************************** -->
	
<style>
/*========== Body ==========*/

body { background-color: ' . Configuration::get('td_mainbgcol') . '; }

/*========== Header ==========*/

.dark #menu div.children, .light #menu div.children, .dark #menu>li>ul.children, .light #menu>li>ul.children, #sticky-menu, .tdl_minicart, .header-dropdown, .product_details .category, .product_details .category a, .orderby_bg, #toggle_sidebar, .widget_price_filter .ui-slider .ui-slider-handle, .tagcloud a, .social_widget a, .go-top:hover, .minicart_cart_but:hover, .minicart_checkout_but:hover, .widget.widget_shopping_cart .buttons a:hover, .widget.widget_shopping_cart .buttons .checkout:hover, .widget_price_filter .price_slider_amount .button:hover, .product_navigation_container, .woocommerce #reviews #comments ol.commentlist li img.avatar, .woocommerce-page #reviews #comments ol.commentlist li img.avatar, #reviews a.button:hover, .items_sliders_nav, .edit-link, .edit-address, .quantity .plus,
.quantity .minus, #content .quantity .plus, #content .quantity .minus, .quantity input.qty, #content .quantity input.qty, .coupon .input-text, .coupon .button-coupon:hover, .left_column_cart .update-button:hover, .left_column_cart .checkout-button:hover, .uneditable-input, .single_add_to_cart_button:hover, .form-row .button:hover, .woocommerce table.my_account_orders  .order-actions .button:hover,
.woocommerce-page table.my_account_orders .order-actions .button:hover, .content_title.bold_title span, .toggle h3 a, .col.boxed .ins_box, .pagination ul > li > a, .pagination ul > li > span, .entry-content .moretag:hover, .tdl-button:hover, .commentlist li article img.avatar, .comment-text .reply a:hover, .form-submit input:hover, .modal-body .button:hover, .register_warp .button:hover, #change-password .button:hover, .change_password_form .button:hover, .wpcf7 input[type="submit"]:hover, .light .widget.widget_shopping_cart .buttons a:hover, .light .widget.widget_shopping_cart .buttons .checkout:hover, .light .widget_price_filter .price_slider_amount .button:hover, #menu>li>ul.children ul, .prodstyle1 .products_slider_images, .widget_calendar #calendar_wrap, .variation-select select, .variation-select:after, .chosen-container-single .chosen-single, .chosen-container .chosen-drop, .light .chosen-container-single .chosen-single, .light .chosen-container .chosen-drop
{ background:' . Configuration::get('td_mainbgcol') . ' !important;}

#jckqv .variation-select select, #jckqv .variation-select:after, #jckqv .chosen-container-single .chosen-single, #jckqv .chosen-container .chosen-drop {background: #fff !important;}


#social-icons li a, .search-trigger a:hover:before, #menu div.children h6, #header_topbar .topbar_info, #header_topbar .topbar_info a, .tdl_minicart, #sticky-menu .sticky-search-trigger a:hover:before, .header-switch:hover span.current, .header-switch:hover span.current:before, #header_topbar .topbarmenu ul li a, .tagcloud a:hover, .wig_twitbox .tweetitem:before, .social_widget a:hover, .go-top:before, .barberry_product_sort.customSelectHover, .barberry_product_sort.customSelectHover:after, .price_slider_amount button, .minicart_cart_but, .widget.widget_shopping_cart .buttons a, .widget_shopping_cart .buttons a.checkout, .minicart_checkout_but, product_navigation .product_navigation_container a.next:hover:after, .product_navigation .product_navigation_container a.prev:hover:after, .product_sliders_header .big_arrow_right:hover:after, .items_sliders_header .big_arrow_right:hover:after, .product_sliders_header .big_arrow_left:hover:after, items_sliders_header .big_arrow_left:hover:after, .product_share ul li a:hover:before, #reviews a.button, .product_navigation .product_navigation_container a.next:hover:after, .product_navigation .product_navigation_container a.prev:hover:after, .product_sliders_header .big_arrow_right:hover:after, .items_sliders_header .big_arrow_right:hover:after, .product_sliders_header .big_arrow_left:hover:after, .items_sliders_header .big_arrow_left:hover:after, .edit-link:hover a, .edit-link:hover a:after, .edit-address:hover a, .edit-address:hover a:after, .quantity .minus:hover, #content .quantity .minus:hover, .quantity .plus:hover, #content .quantity .plus:hover, .coupon .button-coupon, .left_column_cart .update-button, .left_column_cart .checkout-button, .single_add_to_cart_button, .form-row .button, .woocommerce table.my_account_orders  .order-actions .button, .woocommerce-page table.my_account_orders .order-actions .button, [class^="icon-"].style3,[class*=" icon-"].style3, [class^="icon-"].style4,[class*=" icon-"].style4, #portfolio-filter li a:hover, #portfolio-filter li.activeFilter a, .entry-content .moretag, .tdl-button, .comment-text .reply a, a.button,     button.button, input.button, #respond input#submit, #content input.button, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .portfolio_details .project_button a, #change-password .button, .change_password_form .button, .wpcf7 input[type="submit"], .tdl_small_shopbag, .tdl_small_shopbag span, .tdl_small_shopbag:hover:before, .minicart_cart_but, .widget.widget_shopping_cart .buttons a, .light .minicart_cart_but, .light .widget.widget_shopping_cart .buttons a, .widget_shopping_cart .buttons a.checkout, .minicart_checkout_but, .light .widget_shopping_cart .buttons a.checkout, .light .minicart_checkout_but, .prodstyle1 .products_slider_images, .prodstyle1 .products_slider_item .f_button, .widget_calendar tbody tr > td a, .form-row .button, .empty_bag_button, .left_column_cart .checkout-button, .shipping-calculator-form .button, .coupon .button-coupon, table.shop_table a.remove, .woocommerce table.shop_table a.remove
{color:' . Configuration::get('td_mainbgcol') . ' !important;}

#jckqv .product_share ul li a:hover:before {color: #fff !important}

#jckqv .single_add_to_cart_button, #jckqv .button.viewProduct  {color: #fff !important}
#jckqv .single_add_to_cart_button:hover, #jckqv .button.viewProduct:hover {color: #000 !important}



ul.cart_list li img, .woocommerce ul.cart_list li img, ul.product_list_widget li img, .woocommerce ul.product_list_widget li img, table.shop_table img,
.woocommerce table.shop_table img, .posts-widget .post_image, .branditems_slider ul li a img
{border-color:' . Configuration::get('td_mainbgcol') . ' !important;}

.shortcode_tabgroup.top ul.tabs li
{border-color:' . Configuration::get('td_mainbgcol') . ';}

.shortcode_tabgroup.top ul.tabs li.active {border-bottom:2px solid #000 !important;}
.light .shortcode_tabgroup.top ul.tabs li.active {border-bottom:2px solid #fff !important;}

/*========== Main font ==========*/
 
H1,H2,H3,H4,H5,H6, #menu>li>a, #menu ul.children li a, #menu div.children h6, .iosSlider .slider .item .caption, .tdl_small_shopbag span, .mobile_menu_select, .sticky-search-area input, .search-area input, .header_shopbag .overview .amount, .custominfo, .header-switch span.current, .header-dropdown ul li a, #breadcrumbs, .barberry_product_sort, .orderby_bg .perpage_cont p, .perpage_cont ul li, div.onsale, div.newbadge, div.outstock, .product_details .price, #jckqv  div.product p.price, #jckqv  div.product span.price, .product_button, .bag-items, .cart_list_product_title a, ul.cart_list li.empty, .cart_list_product_price, .minicart_total_checkout, .minicart_total_checkout span, .minicart_cart_but, .minicart_checkout_but, .widget h1.widget-title, .widget ul li a, .widget_layered_nav ul small.count, .widget.widget_shopping_cart ul li .quantity .amount, .widget.widget_shopping_cart .total strong, .widget.widget_shopping_cart .total .amount, .widget.widget_shopping_cart .buttons a, .widget.widget_shopping_cart .buttons .checkout, .widget_price_filter .price_slider_amount .button, .widget input[type=text], .footer_copyright .copytxt p, ul.product_list_widget span.amount, .woocommerce ul.product_list_widget span.amount, ul.product_list_widget span.from, .woocommerce ul.product_list_widget span.from, .woocommerce-show-products, .product-category-description p, .added-text, .nav-back, div.product .summary span.price, div.product .summary p.price, #content div.product .summary span.price, #content div.product .summary p.price, .product_main_infos span.onsale, .single_add_to_cart_button, div.product form.cart .variations .label, #content div.product form.cart .variations .label, .variations_select, div.product .woocommerce_tabs ul.tabs li a, #content div.product .woocommerce_tabs ul.tabs li a, div.product .woocommerce-tabs ul.tabs li a, #content div.product .woocommerce-tabs ul.tabs li a, #reviews #comments ol.commentlist li .comment-text p.meta, #reviews a.button, .woocommerce_message, .woocommerce_error, .woocommerce_info, .woocommerce-message, .woocommerce-error, .woocommerce-info, .form-submit input, .featured_section_title, .woocommerce table.shop_table th, .woocommerce-page table.shop_table th, table.shop_table td.product-subtotal, .woocommerce table.shop_table td.product-subtotal, table.shop_table .product-name a, .woocommerce table.shop_table .product-name a, table.shop_table .product-name .product-price, .cart_totals th, .cart_totals .amount, .left_column_cart .update-button, .left_column_cart .checkout-button, .coupon .button-coupon, .coupon .input-text, .shipping-calculator-form .button, .empty_bag_button, .form-row .button, table.shop_table td.product-name, .woocommerce table.shop_table td.product-name, #order_review table.shop_table tfoot .cart-subtotal th, #order_review table.shop_table tfoot .cart-subtotal td, #order_review table.shop_table .product-total, #order_review table.shop_table tfoot td, .cart_totals .shipping td, .woocommerce .order_details li, .woocommerce-page .order_details li, .modal-body .buttonreg, .register_warp .button, ul.my-account-nav > li a, .woocommerce    table.my_account_orders  .order-actions .button, .woocommerce-page table.my_account_orders .order-actions .button, .woocommerce  table.my_account_orders tbody td.order-number, .woocommerce-page table.my_account_orders tbody td.order-number, .woocommerce table.my_account_orders tbody td.order-amount, .woocommerce-page table.my_account_orders tbody td.order-amount, #change-password .button, .edit-link a, .edit-address a, .order-info, table.shop_table td.product-total, .woocommerce table.shop_table td.product-total, table.totals_table tr th, table.totals_table tr td, .change_password_form .button, .shortcode_tabgroup ul.tabs li a, .toggle h3 a, .prodstyle1 .products_slider_title a, .prodstyle1 .products_slider_item .f_button, .prodstyle1 .products_slider_price, .blog_list .entry_date, .entry-content .moretag, .product_share span, .comment-author, .comment-text .reply a, .widget ul li.recentcomments, .error404page, a.follow-me-posts, #portfolio-filter li a, .portfolio_details .project_button a, .portfolio_meta ul.project_details li, .wpcf7 input[type="submit"], .tdl-button, .wishlist_table td.product-price, .wishlist_table .add_to_cart, .yith-woocompare-widget .compare.button, .branditems_slider ul li a, .mfp-preloader, #jckqv .button.viewProduct, .page_archive_date, #mc_signup_submit, .variation-select select, .my-account-right .edit-address-form .button, #jckqv .price, #jckqv table.variations th, #jckqv table.variations td, #jckqv .price del, #jckqv .price ins, #jckqv .onsale
{
	font-family: "' . Configuration::get('td_heading_font_face') . '", Arial, Helvetica, sans-serif !important;
}

/*========== Secondary font ==========*/

body, table.shop_table td.product-name .variation, .posts-widget .post_meta a, .yith-woocompare-widget .products-list a.remove, .mc_input, #jckqv .woocommerce-product-rating .text-rating, #jckqv p, #jckqv .product_meta > span, #jckqv .product-actions .wishlist a {font-family: "' . Configuration::get('td_body_font_face') . '" , Arial, Helvetica, sans-serif !important;}


/*========== Logo ==========*/

.entry-header, .headerline {background:url('.$this->themeImageURL.'dark/bg1.png) top left;background-size:4px 4px;}
.light .entry-header, .light .headerline {background:url('.$this->themeImageURL.'light/bg1.png) top left;background-size:4px 4px;}
';
$heighll=$logo_width/2;
$height=$logo_height+80;
$height1=$logo_height+10;
$height2=$logo_height-39;
$height3=$logo_height-50;
$height4=$logo_height+30;

$custom_styles .= '
.logo {
margin-left:-'.$heighll.'px;	
width: '.$logo_width.'px;
    height: '.$logo_height.'px;
}
#header .header_container, .header_nb #header .header_container {height: '.$height.'px;}
.header4 #header .header_container {height:'.$height1.'px;}
.header_nb.header4 #header .header_container {height: '.$logo_height.'px;}

.header4 .mobile_navbox, .header4 .fullslider #header.dark .mobile_navbox {top:  '.$height2.'px;}
.header4.header_nb .mobile_navbox, .header4.header_nb .fullslider #header.dark .mobile_navbox {top:  '.$height3.'px;}
';



if (Configuration::get('td_responsive') == 'responsive' || Configuration::get('td_responsive') == 'responsive940') {
$custom_styles .= '
@media (min-width: 768px) and (max-width: 979px) {
#header .header_container, .header4  #header .header_container {height: '.$height4.';?>px}
.logo, .header4 .logo { left:25px; margin-left:0 }
.header4 .mobile_navbox, .header4 .fullslider #header.dark .mobile_navbox,
.header4.header_nb .mobile_navbox, .header4.header_nb .fullslider #header.dark .mobile_navbox {top: 0;}
}

@media (max-width: 767px) {
	
.logo, .header2 .logo, .header3 .logo, .header4 .logo {
    left: auto;
    margin: 10px auto;
    position: relative;
    right: auto;
    top: auto;
}

#header .header_container, .header_nb #header .header_container, .header4 #header .header_container, .header_nb.header4 #header .header_container {height: auto;}

.header4 .mobile_navbox, .header4 .fullslider #header.dark .mobile_navbox, .header4.header_nb .mobile_navbox, .header4.header_nb .fullslider #header.dark .mobile_navbox {top: auto;}

}
';
 }
 

$custom_styles .= '
    /*========== For Logo and some option in custom_options.tpl files ==========*/
    @media only screen and (-webkit-min-device-pixel-ratio: 2), 
only screen and (min-device-pixel-ratio: 2)
{
.entry-header {background:url( '.$this->themeImageURL.'dark/bg1@2x.png) top left;background-size:4px 4px;}
}

/*========== Other Styles ==========*/

.fullslider .dark #menu div.children h6  { color:#fff !important; }
.fullslider .light #menu div.children h6  { color:#000 !important;}

.fullslider .search-trigger a:hover:before  { color:#fff !important;}
.fullslider .light .search-trigger a:hover:before  { color:#000 !important;}


.dark #menu li:first-child, .dark #menu .children li, .light #menu li:first-child, .light #menu .children li, 
#header.dark #menu li:first-child, #header.dark #menu .children li, #header.light #menu li:first-child, #header.light #menu .children li, .dark #sticky-menu #menu, .light #sticky-menu #menu { background:none !important;}

.woocommerce-checkout .form-row .chzn-container-single .chzn-single div b, .chzn-container-single .chzn-single div b {
    background: none !important;
}


.login-wrap { border-right:1px solid #ccc;}

@media only screen and (-webkit-min-device-pixel-ratio: 2), 
only screen and (min-device-pixel-ratio: 2)
{

.dark #header menu li:first-child, .dark #header menu .children li, .light #menu li:first-child, .light #menu .children li, 
#header.dark #menu li:first-child, #header.dark #menu .children li, #header.light #menu li:first-child, #header.light #menu .children li{ background:none !important;}
}

 
.header4 .custominfo, .header_nb.header4 .custominfo, .header_nb.header4 .fullslider .custominfo { right:130px;}

 
.header2 .custominfo, .header3 .custominfo, .header_nb .header2 .custominfo, .header_nb .header3 .custominfo { top:15px!important;}
.header4 .custominfo, .header_nb.header4 .custominfo, .header_nb.header4 .fullslider .custominfo { right:15px; left:auto}
.header_nb.header4 .custominfo, .header_nb.header4 .fullslider .custominfo { right:0; left:auto}
@media (min-width: 768px) and (max-width: 979px) {
	}
.header2 .custominfo, .header_nb.header2 .custominfo, .header_nb.header2 .fullslider .custominfo,
.header4 .custominfo, .header_nb.header4 .custominfo, .header_nb.header4 .fullslider .custominfo { right:130px !important;}
.header3 .custominfo, .header_nb.header3 .custominfo, .header_nb.header3 .fullslider .custominfo { left:130px !important;}


/*========== Shop Product Animation ==========*/
.productanim1.product_item .image_container a, .productanim2.product_item .image_container a, .productanim5.product_item .image_container a {
	float: left;
	-webkit-perspective: 600px;
	-moz-perspective: 600px;
}
	
.productanim1.product_item .image_container, .productanim2.product_item .image_container, .productanim5.product_item .image_container  {
	position:relative;
	width:auto;
	height: auto;
}

.productanim1 .loop_products, .productanim2 .loop_products, .productanim5 .loop_products {
	position:absolute;
	top:0;
	left:0;
	z-index:-1;
}

.productanim1.product_item img, .productanim2.product_item img, .productanim5.product_item img {
	width:100%;
	height:auto;
}


.productanim1 .image_container a .front, .productanim2 .image_container a .front {
	-o-transition: all .4s ease-in-out;
	-ms-transition: all .4s ease-in-out;
	-moz-transition: all .4s ease-in-out;
	-webkit-transition: all .4s ease-in-out;
	transition: all .4s ease-in-out;
}

.productanim1 .image_container a .front {
	-webkit-transform: rotateX(0deg) rotateY(0deg);
	-webkit-transform-style: preserve-3d;
	-webkit-backface-visibility: hidden;

	-moz-transform: rotateX(0deg) rotateY(0deg);
	-moz-transform-style: preserve-3d;
	-moz-backface-visibility: hidden;
}

.productanim2 .image_container a .front {
	-webkit-transform: rotateX(0deg) rotateY(0deg);
	-webkit-transform-style: preserve-3d;
	-webkit-backface-visibility: hidden;

	-moz-transform: rotateX(0deg) rotateY(0deg);
	-moz-transform-style: preserve-3d;
	-moz-backface-visibility: hidden;
}

.productanim5 .image_container a .front {
  -webkit-opacity: 1;
  -moz-opacity: 1;
  opacity: 1;
  -webkit-transition: all .35s ease;
  -moz-transition: all .35s ease;
  -ms-transition: all .35s ease;
  -o-transition: all .35s ease;
  transition: all .35s ease;
}

.productanim1 .image_container a:hover .front {
	-webkit-transform: rotateY(180deg);
	-moz-transform: rotateY(180deg);
}

.productanim2 .image_container a:hover .front {
	-webkit-transform: rotateX(-180deg);
	-moz-transform: rotateX(-180deg);
}

.productanim5 .image_container a:hover .front {
  -webkit-opacity: 0;
  -moz-opacity: 0;
  opacity: 0;
  -webkit-transition: all .35s ease;
  -moz-transition: all .35s ease;
  -ms-transition: all .35s ease;
  -o-transition: all .35s ease;
  transition: all .35s ease;
}

.productanim1 .image_container a .back, .productanim2 .image_container a .back {
	-o-transition: all .4s ease-in-out;
	-ms-transition: all .4s ease-in-out;
	-moz-transition: all .4s ease-in-out;
	-webkit-transition: all .4s ease-in-out;
	transition: all .4s ease-in-out;
	/*z-index:10;
	position:absolute;*/
}

.productanim1 .image_container a .back {
	-webkit-transform: rotateY(-180deg);
	-webkit-transform-style: preserve-3d;
	-webkit-backface-visibility: hidden;

	-moz-transform: rotateY(-180deg);
	-moz-transform-style: preserve-3d;
	-moz-backface-visibility: hidden;
}

.productanim2 .image_container a .back {
	-webkit-transform: rotateX(180deg);
	-webkit-transform-style: preserve-3d;
	-webkit-backface-visibility: hidden;

	-moz-transform: rotateX(180deg);
	-moz-transform-style: preserve-3d;
	-moz-backface-visibility: hidden;
}

.productanim5 .image_container a .back {
  -webkit-opacity: 0;
  -moz-opacity: 0;
  opacity: 0;
  -webkit-transition: all .35s ease;
  -moz-transition: all .35s ease;
  -ms-transition: all .35s ease;
  -o-transition: all .35s ease;
  transition: all .35s ease;
}


.productanim1 .image_container a:hover .back, .productanim2 .image_container a:hover .back  {
	z-index:10;
	position:absolute;
	-webkit-transform: rotateX(0deg) rotateY(0deg);
	-moz-transform: rotateX(0deg) rotateY(0deg);
}

.productanim5 .image_container a:hover .back  {
	z-index:10;
  -webkit-opacity: 1;
  -moz-opacity: 1;
  opacity: 1;
  -webkit-transition: all .35s ease;
  -moz-transition: all .35s ease;
  -ms-transition: all .35s ease;
  -o-transition: all .35s ease;
  transition: all .35s ease;

}


/*========== For Product Animation 3 ==========*/
.productanim3.product_item  {
	list-style:none;
}

.productanim3 .image_container {
	position:relative;
	width:100%;
	overflow:hidden;
}


.productanim3 .image_container a.prodimglink {
	display: block;
	float: left;
	position: absolute;
	-webkit-transform: translate3d(0,0,0);
	-moz-transform: translate3d(0,0,0);
	-ms-transform: translate3d(0,0,0);
	-o-transform: translate3d(0,0,0);
	transform: translate3d(0,0,0);
	-webkit-transition: -webkit-transform 1s cubic-bezier(0.190,1.000,0.220,1.000);
	-webkit-transition-delay: .0s;
	-moz-transition: -moz-transform 1s cubic-bezier(0.190,1.000,0.220,1.000) 0s;
	-o-transition: -o-transform 1s cubic-bezier(0.190,1.000,0.220,1.000) 0s;
	transition: transform 1s cubic-bezier(0.190,1.000,0.220,1.000) 0s;
}


.productanim3 .image_container a.prodimglink {
	width: 100%;
	height: 200%;
	display: block;
	margin-bottom: 0;
}

.productanim3 .image_container a.prodimglink:hover {
	-webkit-transform: translate3d(0,-50%,0);
	-moz-transform: translate3d(0,-50%,0);
	-ms-transform: translate3d(0,-50%,0);
	-o-transform: translate3d(0,-50%,0);
	transform: translate3d(0,-50%,0);
	-webkit-transition: -webkit-transform 1s cubic-bezier(0.190,1.000,0.220,1.000);
	-webkit-transition-delay: 0s;
	-moz-transition: -moz-transform 1s cubic-bezier(0.190,1.000,0.220,1.000) 0s;
	-o-transition: -o-transform 1s cubic-bezier(0.190,1.000,0.220,1.000) 0s;
	transition: transform 1s cubic-bezier(0.190,1.000,0.220,1.000) 0s;
}


.productanim3 .image_container .front img, .productanim3 .image_container .back img {
	filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100);
	opacity: 1;
	padding-bottom:0;
	-webkit-transition: opacity 1.5s cubic-bezier(0.190,1.000,0.220,1.000);
	-webkit-transition-delay: 0ms;
	-moz-transition: opacity 1.5s cubic-bezier(0.190,1.000,0.220,1.000) 0ms;
	-o-transition: opacity 1.5s cubic-bezier(0.190,1.000,0.220,1.000) 0ms;
	transition: opacity 1.5s cubic-bezier(0.190,1.000,0.220,1.000) 0ms;
}';

        /* Panel Custom CSS */
        $custom_style = Configuration::get('td_custom_style');

        if (isset($custom_style)) {
            $custom_styles .= html_entity_decode(stripslashes($custom_style));
        }

        $style_file = fopen(dirname(__FILE__) . '/style_custom.css', 'w');

        /* if ($style_file === false)
          return 0; */

        $saved = fwrite($style_file, $custom_styles);
        fclose($style_file);

        $this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/tdpsthemeoptionpanel/style_custom.css', 'all');
        return true;  
    }

}

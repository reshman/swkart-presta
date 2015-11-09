<?php

if (!defined('_PS_VERSION_'))
    exit;

include_once(dirname(__FILE__) . '/tdmegamenuModel.php');

class TdMegaMenu extends Module {

    private $_menu = '';
    private $_mmenu = '';
    private $_html = '';
    private $user_groups;
    public $_tdmegamenu = '';

    /*
     * Pattern for matching config values
     */
    private $pattern = '/^([A-Z_]*)[0-9]+/';

    /*
     * Name of the controller
     * Used to set item selected or not in top menu
     */
    private $page_name = '';
    private $spacer_size = '5';

    public function __construct() {
        $this->name = 'tdmegamenu';
        $this->tab = 'front_office_features';
        $this->version = '2.4'; //for all themesdev PrestaShop Themes
        $this->author = 'ThemesDeveloper';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('ThemesDeveloper Mega Menu');
        $this->description = $this->l('ThemesDeveloper Mega Menu by ThemesDeveloper.');
        $this->module_path = __PS_BASE_URI__ . 'modules/tdmegamenu/';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->backofficJS = __PS_BASE_URI__ . 'modules/tdmegamenu/js/';
        $this->backofficCSS = __PS_BASE_URI__ . 'modules/tdmegamenu/css/';
    }

    public function install() {
        //install table
        if (parent::install() && $this->registerHook('top')) {
            $response = TdMegamenuModel::createTables();
            return $response;
        }
        return false;
    }

    public function uninstall() {
        //uninstall table
        if (parent::uninstall()) {
            $response = TdMegaMenuModel::DropTables();
            return $response;
        }
        return false;
    }

    public function getContent() {
        if (Tools::isSubmit('submitMenuLinks')) {
            if ((Tools::getValue('menu_type')) != 'selmenutype') {
                $this->insertMenuLinks();
                $this->successmeg = '<div class="bootstrap">
		<div class="module_confirmation conf confirm alert alert-success">
			Successfully Added.
		</div>
		</div>';
            } else {
                $this->_html .= $this->displayError($this->l('Please select links type.'));
            }
        } elseif (Tools::isSubmit('removemenu_links')) {

            $id_tdmegamenu = Tools::getValue('id_tdmegamenu');

            $this->deleteMenuFromList($id_tdmegamenu);
            $this->successmeg = '<div class="bootstrap">
		<div class="module_confirmation conf confirm alert alert-success">
			Successfully Deleted
		</div>
		</div>';
        }
        $this->_html .= "<link href='" . $this->backofficCSS . "style.css' rel='stylesheet' type='text/css' />
            <script src='" . $this->backofficJS . "interface-1.2.js' type='text/javascript'></script>
            <script src='" . $this->backofficJS . "custom-option.js' type='text/javascript'></script>
            <script src='" . $this->backofficJS . "inestedsortable-1.0.1.pack.js' type='text/javascript'></script>";
        $this->_html .="<script type='text/javascript'>
                $( function($) {
                $('#tdmenulinkssorted').NestedSortable(
                        {
                                accept: 'page-item1',
                                noNestingClass: 'no-nesting',
                                opacity: .8,
                                helperclass: 'helper',
                                onChange: function(serialized) {
                                        $('#serial')
                                        .val(serialized[0].hash);
                                        $.post('" . $this->module_path . "tdmegamenuAjax.php',serialized[0].hash);         
                                },
                                autoScroll: true,
                                handle: '.sort-handle'
                        }
                );

                });
                </script>";
        $this->_html .='<fieldset>
			<legend>' . $this->l('ThemesDeveloper Mega Menu') . '</legend>      
<div id="fieldset_0" class="panel"> <div class="panel-heading"> <i class="icon-cogs"></i> Add TOP MENU LINKS</div>
  ';

        $this->_html .= '<div class="col-lg-12 "><div class="col-lg-6">';

        $this->_tdAdminOptionDesign();
        $this->_html .='  </div>
                           <div class="col-lg-6 addmlink-design">
                                    <legend><img src="../img/admin/AdminTools.gif"  />' . $this->l('List Of the Menu Links') . '</legend>
                            ';
        $this->_html .= $this->adminMenuLinkList();
        $this->_html .=' </div> 
            </div>
            </fieldset>
           ';

        return $this->_html;
    }

    /*     * ***************************************** */

    private function insertMenuLinks() {
        $languages = Language::getLanguages(false);
        $context = Context::getContext();
        $id_shop = $context->shop->id;


        if (Tools::getValue('order')) {
            $order = Tools::getValue('order');
        } else {
            $order = Db::getInstance()->getValue('
			SELECT COUNT(*) 
			FROM `' . _DB_PREFIX_ . 'tdmegamenu`');
        }
        $totalid = Db::getInstance()->getValue('
			SELECT MAX(id_tdmegamenu)
			FROM `' . _DB_PREFIX_ . 'tdmegamenu`');
        $totalid +=1;

        if (Tools::getValue('cat_id') != 'sel_cat') {
            $typeofthemenu = (Tools::getValue('cat_id'));
        } elseif (Tools::getValue('cms_id') != 'selcms') {
            $typeofthemenu = (Tools::getValue('cms_id'));
        } elseif (Tools::getValue('manufac_id') != 'selmanuf') {
            $typeofthemenu = (Tools::getValue('manufac_id'));
        } elseif (Tools::getValue('sup_id') != 'selsup') {
            $typeofthemenu = (Tools::getValue('sup_id'));
        } else {
            if (Tools::getValue('menu_type')) {
                $typeofthemenu = 'LNK' . $totalid;
            }
        }
        if (Tools::getValue('custom-block-section') != 'customtype') {
            $custommenu = (Tools::getValue('custom-block-section'));
        } else {
            $custommenu = '';
        }
        Db::getInstance()->Execute('
            INSERT INTO `' . _DB_PREFIX_ . 'tdmegamenu`(`id_tdmegamenu`,`menu_type`,`order`,`parent`,`custome_type`,`id_shop`) 
            VALUES(' . $totalid . ',"' . $typeofthemenu . '",' . (int) $order . ',' . (int) Tools::getValue('parent') . ',"' . $custommenu . '",' . $id_shop . ')');



        foreach ($languages as $language) {
            Db::getInstance()->Execute('
                INSERT INTO `' . _DB_PREFIX_ . 'tdmegal`(`id_tdmegamenu`,`id_lang`,`menu_title`,`menu_link`,`description`) 
                VALUES(' . (int) $totalid . ', ' . (int) $language['id_lang'] . ', 
                "' . pSQL(Tools::getValue('title_' . $language['id_lang'])) . '", 
                "' . pSQL(Tools::getValue('link_' . $language['id_lang'])) . '",
                 "' . htmlspecialchars(Tools::getValue('description_' . $language['id_lang'])) . '")');
        }
    }

    private function _tdAdminOptionDesign() {
        global $cookie;

        $defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));
        $languages = Language::getLanguages(false);
        $iso = Language::getIsoById((int) ($cookie->id_lang));
        $id_lang = Context::getContext()->language->id;

        $divLangName = 'title¤description¤link';
        $this->_html .= '<fieldset>
      		<legend><img src="../img/admin/add.gif" alt="" title="" />' . $this->l('Add Menu Links') . '</legend>
      			<form action="index.php?tab=AdminModules&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&tab_module=front_office_features&module_name=' . $this->name . '" method="post" id="form">
                        <label>' . $this->l('Links type') . '</label>
                        <div class="from-block">
                        <select id="selected_menu_type" name="menu_type">
                            <option value="selmenutype">' . $this->l('Select Menu type') . ' </option>
                            <option value="cat">' . $this->l('Categories') . '</option>
                            <option value="cms">' . $this->l('CMS') . '</option>
                            <option value="manu">' . $this->l('Manufacturer') . '</option>
                            <option value="sup">' . $this->l('Supplier') . '</option>
                            <option value="custom">' . $this->l('Custom Options') . '</option>';
        $this->_html .= '</select>
             
                        </div>
                        <div id="category_block">
                        <label>' . $this->l('Categories') . '</label>
                        <div class="from-block">
                        <select id="cat_id" name="cat_id">
                            <option value="sel_cat">' . $this->l('Select Category Links') . '</option>';
        $this->_html .=$this->getCategoryOption();
        $this->_html .= '</select>
                        </div>
                        </div>
                         <div id="cms-block">
                        <label>' . $this->l('CMS') . '</label>
                        <div class="from-block">
                        <select id="cms_id" name="cms_id">
                        <option value="selcms"> ' . $this->l('Select CMS Links') . '</option>';
        $this->_html .=$this->getCMSOptions();
        $this->_html .= '</select>
                        </div>
                        </div>
                       <div id="menuf-block">
                        <label>' . $this->l('Manufacturer') . '</label>
                        <div class="from-block">
                        <select id="manufac_id" name="manufac_id">
                            <option value="selmanuf">' . $this->l('Select Manufacturer Links') . '</option>';
        $manufacturers = Manufacturer::getManufacturers(false, $id_lang);
        foreach ($manufacturers as $manufacturer)
            $this->_html .= '<option value="MAN' . $manufacturer['id_manufacturer'] . '">' . ' ' . $manufacturer['name'] . '</option>';
        $this->_html .= '</select>
                        </div>
                        </div>
                        <div id="supplier-block">
                        <label>' . $this->l('Supplier') . '</label>
                        <div class="from-block">
                        <select id="sup_id" name="sup_id">
                            <option value="selsup">' . $this->l('Select Supplier Links') . '</option>';
        $suppliers = Supplier::getSuppliers(false, $id_lang);
        foreach ($suppliers as $supplier)
            $this->_html .= '<option value="SUP' . $supplier['id_supplier'] . '">' . ' ' . $supplier['name'] . '</option>';
        $this->_html .= '</select>
                        </div>
                        </div>
                        <div id="custom-box">
                        <label>' . $this->l('Custom Links') . '</label>
                        <div class="from-block">
                        <select id="custom-block-section" name="custom-block-section">
                            <option value="customtype">' . $this->l('Select Custom type') . '</option>
                            <option value="cus_links">' . $this->l('Custom Link') . '</option>
                            <option value="cus_html">' . $this->l('Custom HTML Block') . '</option>';
        $this->_html .= '</select>
                        </div>
                        </div>
                    <div id="menu_links_block">
            
  			<label>' . $this->l('Title') . '</label>
        			<div class="from-block">';
        foreach ($languages as $language) {
            $this->_html .= '<div id="title_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">					
              <input type="text" name="title_' . $language['id_lang'] . '" id="title_' . $language['id_lang'] . '" size="66" /></div>';
        }
        $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'title', true);

        $this->_html .= '</div></div><p class="clear"> </p>
             <div id="custom_links_block">
  			<label>' . $this->l('Link') . '</label>
  			<div class="from-block">';
          foreach ($languages as $language) {
                          $this->_html .= '<div id="link_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">'
                                  . '<input id="link_' . $language['id_lang'] . '"  type="text" name="link_' . $language['id_lang'] . '" size="66" /></div>';
                       }  
                       $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'link', true);
  			  $this->_html .= '</div>
             </div>

<div id="description">
            <label>' . $this->l('Custom HTML Block') . '</label>
        			<div class="from-block">';
        foreach ($languages as $language) {
            $this->_html .= '
		<div id="description_' . $language['id_lang'] . '" style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;">
                <textarea  name="description_' . $language['id_lang'] . '" id="description_' . $language['id_lang'] . '" rows="5" cols="63" >';

            $this->_html .= '</textarea>
        </div>
       ';
        }
        $this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'description', true);


        $this->_html .= '</div> </div>';
        $this->_html .= '<p class="clear"> </p>
			<label>' . $this->l('Parent') . '</label>
  			<div class="from-block">
				<select name="parent">
					<option value="0">' . $this->l('Root') . '</option>';
        $this->_html .= $this->displayParentMenu();

        $this->_html .= '</select>
  			</div>';

        $this->_html .= '<label>' . $this->l('Menu Order') . '</label>
  			<div class="from-block">
                            <input type="text" name="order" />
  			</div>';
        $this->_html .= '<input type="submit" name="submitMenuLinks"  class="button"  value="' . $this->l('  Add Links  ') . '" />';
        $this->_html .= '</form>
    </fieldset><br />';
        return $this->_html;
    }

    private function makeMenuOption($item) {
        $id_lang = (int) $this->context->language->id;
        $id_shop = (int) Shop::getContextShopID();
        preg_match($this->pattern, $item, $values);
        $id = (int) substr($item, strlen($values[1]), strlen($item));
        $typeofthemenu = '';
        switch (substr($item, 0, strlen($values[1]))) {

            case 'CAT':
                $category = new Category((int) $id, (int) $id_lang);
                if (Validate::isLoadedObject($category))
                    $typeofthemenu .= $category->name . PHP_EOL;
                break;

            case 'PRD':
                $product = new Product((int) $id, true, (int) $id_lang);
                if (Validate::isLoadedObject($product))
                    $typeofthemenu .= $product->name;
                break;

            case 'CMS':
                $cms = new CMS((int) $id, (int) $id_lang);
                if (Validate::isLoadedObject($cms))
                    $typeofthemenu .= $cms->meta_title . PHP_EOL;
                break;

            case 'CMS_CAT':
                $category = new CMSCategory((int) $id, (int) $id_lang);
                if (Validate::isLoadedObject($category))
                    $typeofthemenu .= $category->name . PHP_EOL;
                break;

            case 'MAN':
                $manufacturer = new Manufacturer((int) $id, (int) $id_lang);
                if (Validate::isLoadedObject($manufacturer))
                    $typeofthemenu .= $manufacturer->name . PHP_EOL;
                break;

            case 'SUP':
                $supplier = new Supplier((int) $id, (int) $id_lang);
                if (Validate::isLoadedObject($supplier))
                    $typeofthemenu .= $supplier->name . PHP_EOL;
                break;

            case 'LNK':
                $link = TdMegaMenu::getCustomLinksByid((int) $id, (int) $id_lang, (int) $id_shop);

                if (count($link)) {
                    if (!isset($link[0]['label']) || ($link[0]['label'] == '')) {
                        //$default_language = Configuration::get('PS_LANG_DEFAULT');
                        $link = TdMegaMenu::getCustomLinksByid($link[0]['id_tdmegamenu'], (int) $id_lang, (int) Shop::getContextShopID());
                    }
                    $typeofthemenu .= $link[0]['menu_title'];
                }
                break;
            case 'SHOP':
                $shop = new Shop((int) $id);
                if (Validate::isLoadedObject($shop))
                    $typeofthemenu .=$shop->name . PHP_EOL;
                break;
        }

        return $typeofthemenu;
    }

    private function makeMenu($item) {

        $listofthisfrontmenu = '';
        $id_lang = (int) $this->context->language->id;
        $id_shop = (int) Shop::getContextShopID();

        preg_match($this->pattern, $item, $values);
        $id = (int) substr($item, strlen($values[1]), strlen($item));
        switch (substr($item, 0, strlen($values[1]))) {
            case 'CAT':

                $this->_menu .= $this->getCategory((int) $id);

                break;

            case 'PRD':
                $selected = ($this->page_name == 'product' && (Tools::getValue('id_product') == $id)) ? ' class="sfHover"' : '';
                $product = new Product((int) $id, true, (int) $id_lang);
                if (!is_null($product->id))
                    $this->_menu .= '<a href="' . $product->getLink() . '"><span class="errow"></span><span>' . $product->name . '</span></a>' . PHP_EOL;
                break;

            case 'CMS':
                $selected = ($this->page_name == 'cms' && (Tools::getValue('id_cms') == $id)) ? '' : '';
                $cms = CMS::getLinks((int) $id_lang, array($id));
                if (count($cms))
                    $this->_menu .= '<a href="' . $cms[0]['menu_link'] . '"><span class="errow"></span><span>' . $cms[0]['meta_title'] . '</span></a>' . PHP_EOL;
                break;

            case 'CMS_CAT':
                $category = new CMSCategory((int) $id, (int) $id_lang);
                if (count($category)) {
                    $this->_menu .= '<a href="' . $category->getLink() . '"><span class="errow"></span><span>' . $category->name . '</span></a>';
                    $this->getCMSMenuItems($category->id);
                }
                break;

            case 'MAN':
                $selected = ($this->page_name == 'manufacturer' && (Tools::getValue('id_manufacturer') == $id)) ? ' class="sfHover"' : '';
                $manufacturer = new Manufacturer((int) $id, (int) $id_lang);
                if (!is_null($manufacturer->id)) {
                    if (intval(Configuration::get('PS_REWRITING_SETTINGS')))
                        $manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
                    else
                        $manufacturer->link_rewrite = 0;
                    $link = new Link;
                    $this->_menu .= '<a href="' . htmlentities($link->getManufacturerLink((int) $id, $manufacturer->link_rewrite)) . '"><span class="errow"></span><span>' . $manufacturer->name . '</span></a>' . PHP_EOL;
                }
                break;
            case 'SUP':
                $selected = ($this->page_name == 'supplier' && (Tools::getValue('id_supplier') == $id)) ? ' class="sfHover"' : '';
                $supplier = new Supplier((int) $id, (int) $id_lang);
                if (!is_null($supplier->id)) {
                    $link = new Link;
                    $this->_menu .= '<a href="' . $link->getSupplierLink((int) $id, $supplier->link_rewrite) . '"><span class="errow"></span><span>' . $supplier->name . '</span></a>' . PHP_EOL;
                }
                break;
            case 'LNK':
                $link = TdMegaMenu::getCustomLinksByid((int) $id, (int) $id_lang, (int) $id_shop);

                if (count($link)) {
                    if (!isset($link[0]['label']) || ($link[0]['label'] == '')) {
                        //$default_language = Configuration::get('PS_LANG_DEFAULT');
                        $link = TdMegaMenu::getCustomLinksByid($link[0]['id_tdmegamenu'], (int) $id_lang, (int) Shop::getContextShopID());
                    }
                   
                    $listofthisfrontmenu .= '<a href="' . $link[0]['menu_link'] . '"><span class="errow"></span><span>' . $link[0]['menu_title'] . '</span></a>' . PHP_EOL;
                    if ($link[0]['custome_type'] == 'cus_html') {
                        $this->_menu .= html_entity_decode($link[0]['description']);
                    }
                }
                break;
            case 'SHOP':
                $selected = ($this->page_name == 'index' && ($this->context->shop->id == $id)) ? ' class="sfHover"' : '';
                $shop = new Shop((int) $id);
                if (Validate::isLoadedObject($shop)) {
                    $link = new Link;
                    $this->_menu .= '<a href="' . $shop->getBaseURL() . '"><span class="errow"></span><span>' . $shop->name . '</span></a>' . PHP_EOL;
                }
                break;
          
        }

        return $listofthisfrontmenu;
    }

    private function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true) {
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;
        $category = new Category((int) $id_category, (int) $id_lang, (int) $id_shop);

        if (is_null($category->id))
            return;

        if ($recursive) {
            $children = Category::getChildren((int) $id_category, (int) $id_lang, true, (int) $id_shop);
            $spacer = str_repeat('&nbsp;', $this->spacer_size * (int) $category->level_depth);
        }

        $shop = (object) Shop::getShop((int) $category->getShopID());
        $this->_html .= '<option value="CAT' . (int) $category->id . '">' . (isset($spacer) ? $spacer : '') . $category->name . ' (' . $shop->name . ')</option>';

        if (isset($children) && count($children))
            foreach ($children as $child) {
                $this->getCategoryOption((int) $child['id_category'], (int) $id_lang, (int) $child['id_shop']);
            }
    }

    private function getCategory($id_category, $id_lang = false, $id_shop = false) {
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;
        $category = new Category((int) $id_category, (int) $id_lang);

        if ($category->level_depth > 1)
            $category_link = $category->getLink();
        else
            $category_link = $this->context->link->getPageLink('index');

        if (is_null($category->id))
            return;

        $children = Category::getChildren((int) $id_category, (int) $id_lang, true, (int) $id_shop);
        $selected = ($this->page_name == 'category' && ((int) Tools::getValue('id_category') == $id_category)) ? ' class="active"' : '';
        /*
          $is_intersected = array_intersect($category->getGroups(), $this->user_groups);
          // filter the categories that the user is allowed to see and browse
          if (!empty($is_intersected))
          { */
        $this->_menu .= '<li ' . $selected . ' >';
        $this->_menu .= '<a href="' . htmlentities($category_link) . '"><span class="errow"></span><span>' . $category->name . '</span></a>';

        if (count($children)) {
            $this->_menu .= '<ul>';

            foreach ($children as $child)
                $this->getCategory((int) $child['id_category'], (int) $id_lang, (int) $child['id_shop']);

            $this->_menu .= '</ul>';
        }
        $this->_menu .= '</li>';
        //}
    }

    private function getCMSMenuItems($parent, $depth = 1, $id_lang = false) {
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;

        if ($depth > 3)
            return;

        $categories = $this->getCMSCategories(false, (int) $parent, (int) $id_lang);
        $pages = $this->getCMSPages((int) $parent);

        if (count($categories) || count($pages)) {
            $this->_menu .= '<ul>';

            foreach ($categories as $category) {
                $this->_menu .= '<li>';
                $this->_menu .= '<a href="#"><span class="errow"></span><span>' . $category['name'] . '</span></a>';
                $this->getCMSMenuItems($category['id_cms_category'], (int) $depth + 1);
                $this->_menu .= '</li>';
            }

            foreach ($pages as $page) {
                $cms = new CMS($page['id_cms'], (int) $id_lang);
                $links = $cms->getLinks((int) $id_lang, array((int) $cms->id));

                $selected = ($this->page_name == 'cms' && ((int) Tools::getValue('id_cms') == $page['id_cms'])) ? ' class="sfHoverForce"' : '';
                $this->_menu .= '<li ' . $selected . '>';
                $this->_menu .= '<a href="' . $links[0]['menu_link'] . '">' . $cms->meta_title . '</a>';
                $this->_menu .= '</li>';
            }

            $this->_menu .= '</ul>';
        }
    }
private function getCatMobileOption($id_category, $id_lang = false, $id_shop = false,$child=false) {
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;
        $category = new Category((int) $id_category, (int) $id_lang);

        if ($category->level_depth > 1)
            $category_link = $category->getLink();
        else
            $category_link = $this->context->link->getPageLink('index');

        if (is_null($category->id))
            return;

        $children = Category::getChildren((int) $id_category, (int) $id_lang, true, (int) $id_shop);
        $selected = ($this->page_name == 'category' && ((int) Tools::getValue('id_category') == $id_category)) ? ' class="active"' : '';
        /*
          $is_intersected = array_intersect($category->getGroups(), $this->user_groups);
          // filter the categories that the user is allowed to see and browse
          if (!empty($is_intersected))
          { */
        $this->_mmenu .= '<option value="' . htmlentities($category_link) . '">';
                if ($child==1) {
                    $this->_mmenu .= ' – ';
                }
                $this->_mmenu .= $category->name;
        $this->_mmenu .= '</option>';
        
        
        if (count($children)) {

            foreach ($children as $child)
                $this->getCatMobileOption((int) $child['id_category'], (int) $id_lang, (int) $child['id_shop'],$child=1);
        }
    

        //}
    }
 private function makeMobileMenu($item) {

        $listofthisfrontmenu = '';
        $id_lang = (int) $this->context->language->id;
        $id_shop = (int) Shop::getContextShopID();

        preg_match($this->pattern, $item, $values);
        $id = (int) substr($item, strlen($values[1]), strlen($item));
        switch (substr($item, 0, strlen($values[1]))) {
            case 'CAT':

              $this->_mmenu .= $this->getCatMobileOption((int) $id);

                break;

            case 'PRD':
                $selected = ($this->page_name == 'product' && (Tools::getValue('id_product') == $id)) ? ' class="sfHover"' : '';
                $product = new Product((int) $id, true, (int) $id_lang);
                if (!is_null($product->id))
                    $this->_mmenu .= '<option value="' . $product->getLink() . '">' . $product->name . '</option>' . PHP_EOL;
                break;

            case 'CMS':
                $selected = ($this->page_name == 'cms' && (Tools::getValue('id_cms') == $id)) ? '' : '';
                $cms = CMS::getLinks((int) $id_lang, array($id));
                if (count($cms))
                    $this->_mmenu .= '<option value="' . $cms[0]['link'] . '">' . $cms[0]['meta_title'] . '</option>' . PHP_EOL;
                break;

            case 'CMS_CAT':
                $category = new CMSCategory((int) $id, (int) $id_lang);
                if (count($category)) {
                    $this->_mmenu .= '<option value="' . $category->getLink() . '"><span class="errow"></span><span>' . $category->name . '</option>';
                    $this->getCMSMenuItems($category->id);
                }
                break;

            case 'MAN':
                $selected = ($this->page_name == 'manufacturer' && (Tools::getValue('id_manufacturer') == $id)) ? ' class="sfHover"' : '';
                $manufacturer = new Manufacturer((int) $id, (int) $id_lang);
                if (!is_null($manufacturer->id)) {
                    if (intval(Configuration::get('PS_REWRITING_SETTINGS')))
                        $manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
                    else
                        $manufacturer->link_rewrite = 0;
                    $link = new Link;
                    $this->_mmenu .= '<option value="' . htmlentities($link->getManufacturerLink((int) $id, $manufacturer->link_rewrite)) . '">' . $manufacturer->name . '</option>' . PHP_EOL;
                }
                break;
            case 'SUP':
                $selected = ($this->page_name == 'supplier' && (Tools::getValue('id_supplier') == $id)) ? ' class="sfHover"' : '';
                $supplier = new Supplier((int) $id, (int) $id_lang);
                if (!is_null($supplier->id)) {
                    $link = new Link;
                    $this->_mmenu .= '<option value="' . $link->getSupplierLink((int) $id, $supplier->link_rewrite) . '">' . $supplier->name . '</option>' . PHP_EOL;
                }
                break;
            case 'LNK':
                $link = TdMegaMenu::getCustomLinksByid((int) $id, (int) $id_lang, (int) $id_shop);

                if (count($link)) {
                    if (!isset($link[0]['label']) || ($link[0]['label'] == '')) {
                        $default_language = Configuration::get('PS_LANG_DEFAULT');
                        $link = TdMegaMenu::getCustomLinksByid($link[0]['id_tdmegamenu'], (int) $default_language, (int) Shop::getContextShopID());
                    }

                    $listofthisfrontmenu .= '<option value="' . $link[0]['menu_link'] . '">' . $link[0]['menu_title'] . '</option>' . PHP_EOL;
                    if ($link[0]['custome_type'] == 'cus_html') {
                        $this->_mmenu .= html_entity_decode($link[0]['description']);
                    }
                }
                break;
            case 'SHOP':
                $selected = ($this->page_name == 'index' && ($this->context->shop->id == $id)) ? ' class="sfHover"' : '';
                $shop = new Shop((int) $id);
                if (Validate::isLoadedObject($shop)) {
                    $link = new Link;
                    $this->_mmenu .= '<option value="' . $shop->getBaseURL() . '">' . $shop->name . '</option>' . PHP_EOL;
                }
                break;
          
        }

        return $listofthisfrontmenu;
    }
     private function FrontListMobileMenu($parent = 0) {
        global $cookie;
        $curent_link = str_replace(__PS_BASE_URI__, '', $_SERVER["REQUEST_URI"]);
        $td_menulinks = TdMegamenuModel::getAllMegaMenu($cookie->id_lang, $parent);
        // print_r($td_menulinks);
        foreach ($td_menulinks as $td_menulink) {
            $selected = '';


            $item = $td_menulink['menu_type'];

            preg_match($this->pattern, $item, $values);
            $id = (int) substr($item, strlen($values[1]), strlen($item));

       
            $this->_mmenu .=$this->makeMobileMenu($td_menulink['menu_type']);

            //if ($td_menulink['submenu'])
               // $this->_menu .= $this->FrontListOfChildMenu($td_menulink['id_tdmegamenu']);
       
        }
    }
    private function getCMSOptions($parent = 0, $depth = 1, $id_lang = false) {
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;

        $categories = $this->getCMSCategories(false, (int) $parent, (int) $id_lang);
        $pages = $this->getCMSPages((int) $parent, false, (int) $id_lang);

        $spacer = str_repeat('&nbsp;', $this->spacer_size * (int) $depth);

        foreach ($categories as $category) {
            $this->_html .= '<option value="CMS_CAT' . $category['id_cms_category'] . '" style="font-weight: bold;">' . $spacer . $category['name'] . '</option>';
            $this->getCMSOptions($category['id_cms_category'], (int) $depth + 1, (int) $id_lang);
        }

        foreach ($pages as $page)
            $this->_html .= '<option value="CMS' . $page['id_cms'] . '">' . $spacer . $page['meta_title'] . '</option>';
    }

    /*     * ***************************************** */

    private function adminMenuLinkList($parent = 0, $separator = '') {
        global $cookie;
        $typeofthemenu = '';
        $td_menulinks = TdMegamenuModel::getAllMegaMenu($cookie->id_lang, $parent);
        
        $typeofthemenu.='<ul  id="tdmenulinkssorted" class="menu-list">';
        foreach ($td_menulinks as $td_menulink) {

            $typeofthemenu .= '<li id="mlist_' . $td_menulink['id_tdmegamenu'] . '" class="clear-element page-item1 left">';
            $typeofthemenu .= '<div class="sort-handle"><span class="menu-link">';
            $typeofthemenu .= $this->makeMenuOption($td_menulink['menu_type']);
            $typeofthemenu .= '</span><span class="delete-menu-links"><form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                    <input type="hidden" name="id_tdmegamenu" value="' . $td_menulink['id_tdmegamenu'] . '" />
                                    <button  onclick="javascript:return confirm(\'' . $this->l('Are you sure you want to delete this link?') . '\');"  name="removemenu_links" class="button removemenu_links"><img alt="" src="../img/admin/delete.gif" /></button>
                            </form></span></div>';
            if ($td_menulink['submenu'])
                $typeofthemenu .= $this->adminMenuLinkListParent($td_menulink['id_tdmegamenu'], $separator);
            $typeofthemenu .= '</li>';
        }
        $typeofthemenu.='</ul><br/><br/><br/>' . $this->l('Note') . ': <strong>' . $this->l('For Category') . '</strong>' . $this->l(' just need to add parent Category links then showing all sub-category in frontend.') . '';
        return $typeofthemenu;
    }

    private function adminMenuLinkListParent($parent = 0, $separator = '') {
        global $cookie;
        $typeofthemenu = '';
        $td_menulinks = TdMegamenuModel::getAllMegaMenu($cookie->id_lang, $parent);

        $typeofthemenu.='<ul  class="menu-list" >';
        foreach ($td_menulinks as $td_menulink) {
            $typeofthemenu .= '<li id="mlist_' . $td_menulink['id_tdmegamenu'] . '" class="clear-element page-item1 left">';
            $typeofthemenu .= '<div class="sort-handle"><span class="menu-link">';
            $typeofthemenu .= $this->makeMenuOption($td_menulink['menu_type']);
            $typeofthemenu .='</span><span class="delete-menu-links">';
            $typeofthemenu .= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                                <input name="id_tdmegamenu" value="' . $td_menulink['id_tdmegamenu'] . '" type="hidden" />
                                    <button name="removemenu_links" class="button removemenu_links" onclick="javascript:return confirm(\'' . $this->l('Are you sure you want to delete this link?') . '\');" ><img alt="" src="../img/admin/delete.gif" /></button>
                                </form></span></div></li>';
            if ($td_menulink['submenu'])
                $typeofthemenu .= $this->adminMenuLinkListParent($td_menulink['id_tdmegamenu'], $separator);
        }
        $typeofthemenu.='</ul>';
        return $typeofthemenu;
    }

    private function displayParentMenu($idtdmegamenu = 0, $parent = 0, $separator = '') {
        global $cookie;
        $td_menulinks = TdMegamenuModel::getAllMegaMenu($cookie->id_lang, $parent);
        $formobilemenulinks = '';
        $separator .= '-';
        foreach ($td_menulinks as $td_menulink) {
            $formobilemenulinks .= '<option value="' . $td_menulink['id_tdmegamenu'] . '"';

            $formobilemenulinks .= '> ' . $separator . $this->makeMenuOption($td_menulink['menu_type']) . ' </option>';


            if ($td_menulink['submenu'])
                $formobilemenulinks .= $this->displayParentMenu($idtdmegamenu, $td_menulink['id_tdmegamenu'], $separator);
        }
        return $formobilemenulinks;
    }

    private function FrontListOfChildMenu($parent = 0) {
        global $cookie;
        $this->_menu .= '<ul>';
        $td_menulinks = TdMegamenuModel::getAllMegaMenu($cookie->id_lang, $parent);

        foreach ($td_menulinks as $td_menulink) {


            if ($td_menulink['submenu']) {
                $this->_menu .= '<li class="parent ">';
            } else {
                $this->_menu .= '<li>';
            }
            $this->_menu .= $this->makeMenu($td_menulink['menu_type']);

            if ($td_menulink['submenu'])
                $this->_menu .= $this->FrontListOfChildMenu($td_menulink['id_tdmegamenu']);
            
            $this->_menu .= '</li>';
        }

        $this->_menu .= '</ul>';
    }

    private function FrontListMenu($parent = 0) {
        global $cookie;
        $curent_link = str_replace(__PS_BASE_URI__, '', $_SERVER["REQUEST_URI"]);
        $td_menulinks = TdMegamenuModel::getAllMegaMenu($cookie->id_lang, $parent);
        // print_r($td_menulinks);
        foreach ($td_menulinks as $td_menulink) {
            $selected = '';


            $item = $td_menulink['menu_type'];

            preg_match($this->pattern, $item, $values);
            $id = (int) substr($item, strlen($values[1]), strlen($item));

            switch (substr($item, 0, strlen($values[1]))) {
                case 'CAT':
                    $selected .= ((int) Tools::getValue('id_category') == $id) ? ' active' : '';

                    break;
                case 'CMS':

                    $selected .= ((int) Tools::getValue('id_cms') == $id) ? ' active' : '';
                    break;
                case 'MAN':
                    $selected .= ((int) Tools::getValue('id_manufacturer') == $id) ? ' active' : '';
                    break;

                case 'SUP':
                    $selected .= ((int) Tools::getValue('id_supplier') == $id) ? ' active' : '';
                    break;
                case 'LNK':

                    if ($td_menulink['menu_link'] == _PS_BASE_URL_ . $_SERVER["REQUEST_URI"])
                        $selected .= 'active';
                    else
                        $selected .= ($td_menulink['menu_link'] == $curent_link) ? ' active' : '';
                    break;
            }
            if($values[1]!='CAT'){
            if ($td_menulink['submenu']) {
                $this->_menu .= '<li class="parent ' . $selected . '">';
            } else {
                $this->_menu .= '<li class="' . $selected . '">';
            }
            }
            $this->_menu .=$this->makeMenu($td_menulink['menu_type']);

            if ($td_menulink['submenu'])
                $this->_menu .= $this->FrontListOfChildMenu($td_menulink['id_tdmegamenu']);
             if($values[1]!='CAT'){
            $this->_menu .= '</li>';
             }
        }
    }

    public function hookTop($param) {
        global $smarty;
        $this->user_groups = ($this->context->customer->isLogged() ? $this->context->customer->getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));
        $this->page_name = Dispatcher::getInstance()->getController();
        $this->FrontListMenu();
         $this->FrontListMobileMenu();
        $smarty->assign('tdMENU', $this->_menu);
        $smarty->assign('tdMobleMENU', $this->_mmenu);
        
       
        return $this->display(__FILE__, 'tdmegamenu.tpl');
    }

    private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false) {
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;

        if ($recursive === false) {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `' . _DB_PREFIX_ . 'cms_category` bcp
				INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = ' . (int) $id_lang . '
				AND bcp.`id_parent` = ' . (int) $parent;

            return Db::getInstance()->executeS($sql);
        } else {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `' . _DB_PREFIX_ . 'cms_category` bcp
				INNER JOIN `' . _DB_PREFIX_ . 'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = ' . (int) $id_lang . '
				AND bcp.`id_parent` = ' . (int) $parent;

            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result) {
                $sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int) $id_lang);
                if ($sub_categories && count($sub_categories) > 0)
                    $result['sub_categories'] = $sub_categories;
                $categories[] = $result;
            }

            return isset($categories) ? $categories : false;
        }
    }

    private function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false) {
        $id_shop = ($id_shop !== false) ? (int) $id_shop : (int) Context::getContext()->shop->id;
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;

        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `' . _DB_PREFIX_ . 'cms` c
			INNER JOIN `' . _DB_PREFIX_ . 'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `' . _DB_PREFIX_ . 'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE c.`id_cms_category` = ' . (int) $id_cms_category . '
			AND cs.`id_shop` = ' . (int) $id_shop . '
			AND cl.`id_lang` = ' . (int) $id_lang . '
			AND c.`active` = 1
			ORDER BY `position`';

        return Db::getInstance()->executeS($sql);
    }

    private function getCustomLinksByid($id_td_menu, $id_lang = false, $id_shop = false) {
        global $cookie;
    
        return Db::getInstance()->ExecuteS('
            SELECT td.`id_tdmegamenu`, td.`menu_type`, td.`order`,td.`parent`,td.`custome_type`,td1.`menu_title`,td1.`menu_link`,td1.`description`
            FROM `' . _DB_PREFIX_ . 'tdmegamenu` td
            INNER JOIN `' . _DB_PREFIX_ . 'tdmegal` td1 ON (td.`id_tdmegamenu` = td1.`id_tdmegamenu`)
            WHERE td1.`id_lang` = ' . (int) $id_lang . ' and td.`id_shop`=' . $id_shop . ' and td1.`id_tdmegamenu` = ' . (int) $id_td_menu . '
            ORDER BY td.`order`');
    }

    private function deleteMenuFromList($id_td_menu) {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        //echo $id_tdmegamenu;
        Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'tdmegamenu WHERE id_tdmegamenu=' . $id_td_menu);
        Db::getInstance()->Execute('DELETE FROM ' . _DB_PREFIX_ . 'tdmegal WHERE id_tdmegamenu=' . $id_td_menu);
        return true;
    }
}

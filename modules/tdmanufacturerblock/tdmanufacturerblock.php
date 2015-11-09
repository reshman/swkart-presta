<?phpif (!defined('_PS_VERSION_'))	exit;class TDmanufacturerBlock extends Module {    function __construct() {        $this->name = 'tdmanufacturerblock';        $this->tab = 'front_office_features';        $this->version = '1.3';        $this->author = 'ThemesDeveloper';        $this->need_instance = 0;        parent::__construct();        $this->displayName = $this->l('ThemesDeveloper Manufacturers Block');        $this->description = $this->l('Displays a block listing product manufacturers and/or brands with logo in Home Page.');    }    function install() {        return parent::install() && $this->registerHook('home');    }    function hookHome($params) {        global $smarty, $cookie;        $manufacturer = Manufacturer::getManufacturers(false, $cookie->id_lang, true);        $smarty->assign(array('manufacturer' => $manufacturer ));                return $this->display(__FILE__, 'tdmanufacturerblock.tpl');    }}
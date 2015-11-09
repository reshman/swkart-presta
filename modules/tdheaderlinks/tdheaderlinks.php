<?php
if (!defined('_PS_VERSION_'))
	exit;

class TDheaderLinks extends Module
{
	public function __construct()
	{
		$this->name = 'tdheaderlinks';
		$this->tab = 'front_office_features';
		$this->version = '1.3';
		$this->author = 'ThemesDeveloper';
		$this->need_instance = 0;
		parent::__construct();
		$this->displayName = $this->l('ThemesDeveloper  Header Links Block');
		$this->description = $this->l('Show Links on header of the page.');
	}

	public function install()
	{
		return (parent::install() && $this->registerHook('displayNav'));
	}


	public function hookDisplayNav($params)
	{
		if (!$this->active)
			return;

		$this->smarty->assign(array(
			'cart' => $this->context->cart,
			'cart_qties' => $this->context->cart->nbProducts(),
			'logged' => $this->context->customer->isLogged(),
                        'wishlist_link'=>$this->context->link->getModuleLink('blockwishlist', 'mywishlist'),
			'customerName' => ($this->context->customer->logged ? $this->context->customer->firstname.' '.$this->context->customer->lastname : false),
			'firstName' => ($this->context->customer->logged ? $this->context->customer->firstname : false),
			'lastName' => ($this->context->customer->logged ? $this->context->customer->lastname : false),
			'order_process' => Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order'
		));
		return $this->display(__FILE__, 'tdheaderlinks.tpl');
	}


}
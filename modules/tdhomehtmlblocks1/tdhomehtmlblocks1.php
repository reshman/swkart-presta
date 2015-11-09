<?php
if (!defined('_PS_VERSION_'))
	exit;

class TDhomeHTMLblocks1 extends Module
{
	public function __construct()
	{
		$this->name = 'tdhomehtmlblocks1';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this->author = 'ThemesDeveloper';
		$this->need_instance = 0;
		parent::__construct();
		$this->displayName = $this->l('ThemesDeveloper Home HTML Block-01');
		$this->description = $this->l('Show Links on header of the page.');
	}

	public function install()
	{
		return (parent::install() AND $this->registerHook('displayHome'));
	}


	public function hookDisplayHome($params)
	{
		if (!$this->active)
			return;

		return $this->display(__FILE__, 'tdhomehtmlblocks1.tpl');
	}


}
<?php
class Link extends LinkCore
{
  /*
    * module: tdblockspecials
    * date: 2015-11-09 20:47:52
    * version: 0.8
    */
    public function getImageLink($name, $ids, $type = null, $overimage=null)
	{
		$not_default = false;
		$theme = ((Shop::isFeatureActive() && file_exists(_PS_PROD_IMG_DIR_.$ids.($type ? '-'.$type : '').'-'.(int)Context::getContext()->shop->id_theme.'.jpg')) ? '-'.Context::getContext()->shop->id_theme : '');
		if ((Configuration::get('PS_LEGACY_IMAGES')
			&& (file_exists(_PS_PROD_IMG_DIR_.$ids.($type ? '-'.$type : '').$theme.'.jpg')))
			|| ($not_default = strpos($ids, 'default') !== false))
		{
			if ($this->allow == 1 && !$not_default)
				$uri_path = __PS_BASE_URI__.$ids.($type ? '-'.$type : '').$theme.'/'.$name.'.jpg';
			else
				$uri_path = _THEME_PROD_DIR_.$ids.($type ? '-'.$type : '').$theme.'.jpg';
		}
		else
		{	
			$split_ids = explode('-', $ids);
			$id_image = (isset($split_ids[1]) ? $split_ids[1] : $split_ids[0]);
			$theme = ((Shop::isFeatureActive() && file_exists(_PS_PROD_IMG_DIR_.Image::getImgFolderStatic($id_image).$id_image.($type ? '-'.$type : '').'-'.(int)Context::getContext()->shop->id_theme.'.jpg')) ? '-'.Context::getContext()->shop->id_theme : '');
if(isset($overimage))
{
$sql= "SELECT id_image FROM  `"._DB_PREFIX_."image` WHERE  `id_product` =  '$overimage' AND position = 2";
$getoverimage = Db::getInstance()->getRow($sql);
if($getoverimage)
$id_image = array_shift($getoverimage);
else
return false;
}
                        if ($this->allow == 1)
				$uri_path = __PS_BASE_URI__.$id_image.($type ? '-'.$type : '').$theme.'/'.$name.'.jpg';
			else
				$uri_path = _THEME_PROD_DIR_.Image::getImgFolderStatic($id_image).$id_image.($type ? '-'.$type : '').$theme.'.jpg';
		}
		return $this->protocol_content.Tools::getMediaServer($uri_path).$uri_path;
	}
}

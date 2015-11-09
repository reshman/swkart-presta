<?php
require_once(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../init.php');

$psblogobject = new tdpsblogClass();

$context = Context::getContext();
global $smarty;

$context = Context::getContext();
$id_shop = $context->shop->id;
$id_lang = $context->language->id;

if (Tools::getValue('tdcatid')) {
      $totaltdpost=count($psblogobject->getNumberOfPostByCat($id_shop,$id_lang,Tools::getValue('tdcatid')));
}else{
      $totaltdpost=count($psblogobject->getNumberOfPost($id_shop,$id_lang));
}
$psblogobject->items_total = $totaltdpost;
$psblogobject->mid_range = 3;
if(Tools::getValue('page'))
    $currentpage=Tools::getValue('page');
else 
    $currentpage=1;

if(Tools::getValue('tdcatid')){
    //$categorybyid=tdpsblogModel::getCategoryByID(Tools::getValue('tdcatid'));
    //echo Tools::getValue('tdcatid');
    //echo Tools::getValue('rewrite');
    
    $psblogobject->paginate($currentpage, Tools::getValue('tdcatid'),Tools::getValue('rewrite'));
}else{
    $psblogobject->paginate($currentpage);
}
$limit =$psblogobject->limit;

if (Tools::getValue('tdcatid')) {
$tdblogpspost=$psblogobject->getBlogByCategory(Tools::getValue('tdcatid'),$id_shop,$id_lang,$limit);

$categoryname=$psblogobject->getCategoryNameByID(Tools::getValue('tdcatid'));

        if(count($categoryname)>0){
            $smarty->assign(array('categorynamebyid'=>$categoryname[0]));
        }else{
            $smarty->assign(array('categorynamebyid'=>array()));
        }
            
} else {
$tdblogpspost=$psblogobject->getAllBlogPost($id_shop,$id_lang,$limit);
}


$data = array();
if(isset($tdblogpspost)):
foreach ($tdblogpspost as $tdblogps):
        $data[] = $tdblogps;
endforeach;
endif;


     if (file_exists(_PS_THEME_DIR_.'modules/tdpsblog/pagination.tpl'))
      $smarty->assign('pagination', _PS_THEME_DIR_.'modules/tdpsblog/pagination.tpl');
    else
      $smarty->assign('pagination', _PS_MODULE_DIR_.'tdpsblog/views/templates/front/pagination.tpl');               
      


$smarty->assign(array(
    'default_lang' => (int) $context->language->id,
    'id_lang' => (int) $context->language->id,
    'tdblogpost' => $data,
    'tdblogdetailslinks' => $context->link->getModuleLink('tdpsblog', 'details'),
    'tdblogcatpostlinks' => $context->link->getModuleLink('tdpsblog', 'default'),
    'psblogdisplypage'=>$psblogobject->display_pages(),
    'current_page'=> $psblogobject->current_page,
    'num_pages'=>$psblogobject->num_pages,  
    'base_url' => __PS_BASE_URI__
));

